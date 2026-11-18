@extends('layouts.app')
@section('title','Input Nilai Satu Kelas')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <div>
    <h2 class="page-title mb-1">Input Nilai</h2>
    <p class="text-secondary mb-0">Input berdasarkan kelas atau NIS. Kolom tugas dapat ditambah/dikurangi. Nilai tersimpan otomatis.</p>
  </div><span class="badge text-bg-success px-3 py-2"><i class="bi bi-cloud-check me-1"></i> Auto-save aktif</span>
</div>
<div class="card content-card mb-4">
  <div class="card-body">
    <form method="GET" action="{{ route('grades.bulk') }}" class="row g-3 align-items-end" id="gradeFilter">
      <div class="col-12"><label class="form-label d-block mb-2">Metode Input Nilai</label>
        <div class="d-flex flex-wrap gap-3"><label class="form-check"><input class="form-check-input" type="radio" name="mode" value="kelas" @checked($mode==='kelas' )> <span class="form-check-label fw-semibold">Berdasarkan Kelas</span></label><label class="form-check"><input class="form-check-input" type="radio" name="mode" value="nis" @checked($mode==='nis' )> <span class="form-check-label fw-semibold">Berdasarkan NIS</span></label></div>
      </div>
      <div class="col-md-3" id="classFilter"><label class="form-label">Kelas</label><select class="form-select" name="classroom_id">
          <option value="">Pilih kelas</option>@foreach($classrooms as $x)<option value="{{ $x->id }}" @selected($classId==$x->id)>{{ $x->name }}</option>@endforeach
        </select></div>
      <div class="col-md-3 d-none" id="nisFilter"><label class="form-label">Nomor Induk Siswa (NIS)</label><input class="form-control" name="nis" value="{{ $nis }}" placeholder="Masukkan NIS"></div>
      <div class="col-md-3"><label class="form-label">Mata Pelajaran</label><select class="form-select" name="subject_id" required>
          <option value="">Pilih mapel</option>@foreach($subjects as $x)<option value="{{ $x->id }}" @selected($subjectId==$x->id)>{{ $x->name }}</option>@endforeach
        </select></div>
      <div class="col-md-2"><label class="form-label">Tahun Ajaran</label><select class="form-select" name="academic_year_id" required>
          <option value="">Pilih tahun</option>@foreach($years as $x)<option value="{{ $x->id }}" @selected($yearId==$x->id)>{{ $x->name }}</option>@endforeach
        </select></div>
      <div class="col-md-2"><label class="form-label">Semester</label><select class="form-select" name="semester_id" required>
          <option value="">Pilih semester</option>@foreach($semesters as $x)<option value="{{ $x->id }}" @selected($semesterId==$x->id)>{{ $x->name }} · {{ $x->academicYear->name ?? '' }}</option>@endforeach
        </select></div>
      <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Tampilkan</button></div>
    </form>
  </div>
</div>
@if($mode==='nis' && $nis!=='' && $students->isEmpty())<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>NIS <strong>{{ $nis }}</strong> tidak ditemukan. Periksa kembali nomor induk siswa.</div>@endif
@if($students->isNotEmpty())
@if(in_array(auth()->user()->role,['admin','super_admin'],true))<div class="card content-card mb-4">
  <div class="card-body">
    <h6 class="fw-bold">Atur Bobot Penilaian Sekolah</h6>
    <p class="small text-secondary">Bobot awal 30% Tugas, 30% UTS, 40% UAS. Admin dapat menyesuaikan sesuai ketentuan sekolah. Total harus 100%.</p>
    <form method="POST" action="{{ route('admin.weights.save') }}" class="row g-2 align-items-end">@csrf<input type="hidden" name="academic_year_id" value="{{ $yearId }}"><input type="hidden" name="semester_id" value="{{ $semesterId }}"><input type="hidden" name="classroom_id" value="{{ $classId }}">
      <div class="col"><label class="form-label">Tugas (%)</label><input class="form-control" type="number" min="0" max="100" step=".01" name="tugas_weight" value="{{ $weights->tugas_weight }}" required></div>
      <div class="col"><label class="form-label">UTS (%)</label><input class="form-control" type="number" min="0" max="100" step=".01" name="uts_weight" value="{{ $weights->uts_weight }}" required></div>
      <div class="col"><label class="form-label">UAS (%)</label><input class="form-control" type="number" min="0" max="100" step=".01" name="uas_weight" value="{{ $weights->uas_weight }}" required></div>
      <div class="col-auto"><button class="btn btn-outline-primary">Simpan Bobot</button></div>
    </form>
  </div>
</div>@endif
<div class="card content-card">
  <div class="card-body">
    <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
      <div>
        <h5 class="fw-bold mb-1">{{ $classrooms->firstWhere('id',$classId)?->name }} · {{ $subjects->firstWhere('id',$subjectId)?->name }}</h5>
        <div class="small text-secondary">Bobot: Tugas {{ $weights->tugas_weight }}% · UTS {{ $weights->uts_weight }}% · UAS {{ $weights->uas_weight }}%</div>
      </div>
      <div id="saveStatus" class="small text-success align-self-center">Siap menginput</div>
    </div>
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-3">
      <div><div class="fw-bold mb-1">Pengaturan Kolom Tugas</div><small class="text-secondary">Minimal satu kolom harus tersedia. Rata-rata semua nilai tugas terisi menjadi komponen Tugas.</small></div>
      <form method="POST" action="{{ route('grades.tasks.add') }}" class="d-flex gap-2 align-items-end">@csrf
        <input type="hidden" name="classroom_id" value="{{ $classId }}"><input type="hidden" name="subject_id" value="{{ $subjectId }}"><input type="hidden" name="academic_year_id" value="{{ $yearId }}"><input type="hidden" name="semester_id" value="{{ $semesterId }}">
        <div><label class="form-label small">Nama kolom baru</label><input class="form-control form-control-sm" name="name" placeholder="Tugas 2" required maxlength="80"></div><button class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Kolom</button>
      </form>
    </div>
    <div class="d-flex flex-wrap gap-2 mb-3">
      @foreach($tasks as $task)<form method="POST" action="{{ route('grades.tasks.remove',$task) }}" onsubmit="return confirm('Hapus kolom {{ $task->name }} beserta nilainya?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" @disabled($tasks->count()<=1)><i class="bi bi-dash-circle me-1"></i>Hapus {{ $task->name }}</button></form>@endforeach
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th style="width:55px">No</th>
            <th>Nama Siswa</th>
            <th>NIS</th>
            @foreach($tasks as $task)<th style="min-width:120px">{{ $task->name }}</th>@endforeach
            <th style="min-width:110px">Rata-rata Tugas</th>
            <th style="min-width:110px">UTS</th>
            <th style="min-width:110px">UAS</th>
            <th>Nilai Akhir</th>
            <th>Status simpan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($students as $i=>$student)@php($g=$existing->get($student->id))<tr data-student="{{ $student->id }}">
            <td>{{ $i+1 }}</td>
            <td class="fw-semibold">{{ $student->name }}</td>
            <td>{{ $student->nis ?? '-' }}</td>
            @foreach($tasks as $task)@php($taskScore=($taskScores->get($student->id,collect())->firstWhere('task_component_id',$task->id)?->score))<td><input class="form-control form-control-sm score-input task-score-input" type="number" min="0" max="100" step=".01" inputmode="decimal" aria-label="{{ $task->name }} {{ $student->name }}" data-task-id="{{ $task->id }}" value="{{ $taskScore }}" @if($g && !in_array($g->status,['draft','revision','published'])) disabled @endif></td>@endforeach
            <td class="fw-semibold task-average">{{ $g?->tugas_score !== null ? number_format($g->tugas_score,2) : '—' }}</td>
            @foreach(['uts_score'=>'UTS','uas_score'=>'UAS'] as $field=>$label)<td><input class="form-control form-control-sm score-input" type="number" min="0" max="100" step=".01" inputmode="decimal" aria-label="{{ $label }} {{ $student->name }}" data-field="{{ $field }}" value="{{ old($field,$g?->$field) }}" @if($g && !in_array($g->status,['draft','revision','published'])) disabled @endif></td>@endforeach
            <td class="fw-bold final-score">{{ $g && $g->final_score>0 ? number_format($g->final_score,2) : '—' }}</td>
            <td class="row-status small text-secondary">{{ $g?->status==='draft'?'Tersimpan' :($g?->status ? ucfirst($g->status):'Belum diisi') }}</td>
          </tr>@endforeach
        </tbody>
      </table>
    </div>
    <div class="small text-secondary mt-2"><i class="bi bi-info-circle me-1"></i>Nilai akhir dihitung setelah Tugas, UTS, dan UAS lengkap. Setelah nilai akhir tersimpan, nilai langsung tampil pada akun siswa. Guru dapat memperbarui nilai kapan saja.</div>
  </div>
</div>
@endif
<script>
  (() => {
    const modeInputs = document.querySelectorAll('input[name=mode]');
    const classBox = document.getElementById('classFilter'),
      nisBox = document.getElementById('nisFilter');

    function syncMode() {
      const nis = document.querySelector('input[name=mode]:checked')?.value === 'nis';
      classBox?.classList.toggle('d-none', nis);
      nisBox?.classList.toggle('d-none', !nis);
      classBox?.querySelector('select')?.toggleAttribute('required', !nis);
      nisBox?.querySelector('input')?.toggleAttribute('required', nis)
    }
    modeInputs.forEach(x => x.addEventListener('change', syncMode));
    syncMode();
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    const status = document.getElementById('saveStatus');
    const url = @json(route('grades.autosave'));
    const ctx = {
      classroom_id: @json($classId),
      subject_id: @json($subjectId),
      academic_year_id: @json($yearId),
      semester_id: @json($semesterId)
    };
    let timers = {};
    async function save(row) {
      if (!ctx.classroom_id || !ctx.subject_id || !ctx.academic_year_id || !ctx.semester_id) return;
      const payload = {
        ...ctx,
        student_id: row.dataset.student
      };
      payload.task_scores = {};
      row.querySelectorAll('.task-score-input').forEach(i => payload.task_scores[i.dataset.taskId] = i.value);
      row.querySelectorAll('.score-input:not(.task-score-input)').forEach(i => payload[i.dataset.field] = i.value);
      const state = row.querySelector('.row-status');
      state.textContent = 'Menyimpan…';
      state.className = 'row-status small text-warning';
      status.textContent = 'Menyimpan perubahan…';
      try {
        const r = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
          },
          body: JSON.stringify(payload)
        });
        const data = await r.json();
        if (!r.ok) throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Gagal menyimpan');
        row.querySelector('.final-score').textContent = data.final === null ? '—' : Number(data.final).toFixed(2);
        row.querySelector('.task-average').textContent = data.tugas === null ? '—' : Number(data.tugas).toFixed(2);
        state.textContent = data.message;
        state.className = 'row-status small text-success';
        status.textContent = 'Semua perubahan tersimpan';
      } catch (e) {
        state.textContent = e.message;
        state.className = 'row-status small text-danger';
        status.textContent = 'Ada nilai yang gagal disimpan';
      }
    }
    document.querySelectorAll('tr[data-student] .score-input').forEach(input => {
      input.addEventListener('input', () => {
        const row = input.closest('tr');
        clearTimeout(timers[row.dataset.student]);
        timers[row.dataset.student] = setTimeout(() => save(row), 650)
      });
      input.addEventListener('change', () => save(input.closest('tr')));
      input.addEventListener('blur', () => save(input.closest('tr')))
    });
  })();
</script>
@endsection