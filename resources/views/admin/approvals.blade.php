@extends('layouts.app')
@section('title','Validasi Nilai')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4">
  <div>
    <h2 class="page-title mb-1">Validasi Nilai</h2>
    <p class="text-secondary mb-0">Pilih tahun ajaran, semester, dan status untuk menampilkan nilai yang perlu diperiksa.</p>
  </div><span class="badge text-bg-success px-3 py-2">{{ $grades->total() }} data</span>
</div>
<div class="card content-card mb-4">
  <div class="card-body">
    <form method="GET" action="{{ route('admin.approvals.index') }}" class="row g-3 align-items-end" id="approvalFilter">
      <div class="col-md-3"><label class="form-label">Tahun Ajaran</label><select name="academic_year_id" class="form-select">
          <option value="">Semua Tahun Ajaran</option>@foreach($years as $year)<option value="{{ $year->id }}" @selected((string)$selectedYear===(string)$year->id)>{{ $year->name }}</option>@endforeach
        </select></div>
      <div class="col-md-3"><label class="form-label">Semester</label><select name="semester_id" class="form-select">
          <option value="">Semua Semester</option>@foreach($semesters as $semester)<option value="{{ $semester->id }}" @selected((string)$selectedSemester===(string)$semester->id)>{{ $semester->name }} · {{ $semester->academicYear->name ?? '' }}</option>@endforeach
        </select></div>
      <div class="col-md-2"><label class="form-label">Kelas</label><select name="classroom_id" class="form-select"><option value="">Semua Kelas</option>@foreach($classes as $class)<option value="{{ $class->id }}" @selected((string)request('classroom_id')===(string)$class->id)>{{ $class->name }}</option>@endforeach</select></div><div class="col-md-3"><label class="form-label">Status Validasi</label><select name="status" class="form-select">
          <option value="">Semua Status</option>
          <option value="submitted" @selected(request('status')==='submitted' )>Menunggu Validasi</option>
          <option value="approved" @selected(request('status')==='approved' )>Disetujui</option>
          <option value="revision" @selected(request('status')==='revision' )>Perlu Revisi</option>
        </select></div>
      <div class="col-md-1"><button class="btn btn-primary w-100">Cari</button></div>
    </form>
  </div>
</div>
<div class="card content-card mb-4">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Publikasikan Nilai</h5>
    <p class="small text-secondary">Pilih periode dari daftar. Hanya nilai yang sudah disetujui yang akan dipublikasikan kepada siswa.</p>
    <form method="POST" action="{{ route('admin.approvals.publish') }}" class="row g-3 align-items-end">@csrf
      <div class="col-md-5"><label class="form-label">Tahun Ajaran</label><select class="form-select" name="academic_year_id" required>
          <option value="">Pilih tahun ajaran</option>@foreach($years as $year)<option value="{{ $year->id }}" @selected((string)$selectedYear===(string)$year->id)>{{ $year->name }}</option>@endforeach
        </select></div>
      <div class="col-md-5"><label class="form-label">Semester</label><select class="form-select" name="semester_id" required>
          <option value="">Pilih semester</option>@foreach($semesters as $semester)<option value="{{ $semester->id }}" @selected((string)$selectedSemester===(string)$semester->id)>{{ $semester->name }} · {{ $semester->academicYear->name ?? '' }}</option>@endforeach
        </select></div>
      <div class="col-md-2"><button class="btn btn-success w-100"><i class="bi bi-megaphone me-1"></i> Publikasikan</button></div>
    </form>
  </div>
</div>
<div class="card content-card mb-4"><div class="card-body">
  <h5 class="fw-bold mb-2">Validasi dan Publikasi per Kelas</h5>
  <p class="small text-secondary">Pilih kelas dan periode. Validasi kelas akan menyetujui semua nilai berstatus Submitted pada kelas tersebut. Setelah itu, tombol publikasi akan menerbitkan seluruh nilai Approved di kelas yang sama.</p>
  <form class="row g-3 align-items-end" method="POST" action="{{ route('admin.approvals.approve-class') }}" id="classApproveForm">@csrf
    <div class="col-md-4"><label class="form-label">Kelas</label><select class="form-select" name="classroom_id" required><option value="">Pilih kelas</option>@foreach($classes as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Tahun Ajaran</label><select class="form-select" name="academic_year_id" required><option value="">Pilih tahun</option>@foreach($years as $year)<option value="{{ $year->id }}">{{ $year->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Semester</label><select class="form-select" name="semester_id" required><option value="">Pilih semester</option>@foreach($semesters as $semester)<option value="{{ $semester->id }}">{{ $semester->name }} · {{ $semester->academicYear->name ?? '' }}</option>@endforeach</select></div>
    <div class="col-md-2"><button class="btn btn-primary w-100" onclick="return confirm('Setujui seluruh nilai Submitted pada kelas dan periode ini?')"><i class="bi bi-check2-all me-1"></i>Validasi Kelas</button></div>
  </form>
  <form class="row g-3 align-items-end mt-1" method="POST" action="{{ route('admin.approvals.publish-class') }}" onsubmit="return confirm('Publikasikan seluruh nilai Approved pada kelas dan periode ini?')">@csrf
    <div class="col-md-4"><label class="form-label">Kelas</label><select class="form-select" name="classroom_id" required><option value="">Pilih kelas</option>@foreach($classes as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Tahun Ajaran</label><select class="form-select" name="academic_year_id" required><option value="">Pilih tahun</option>@foreach($years as $year)<option value="{{ $year->id }}">{{ $year->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Semester</label><select class="form-select" name="semester_id" required><option value="">Pilih semester</option>@foreach($semesters as $semester)<option value="{{ $semester->id }}">{{ $semester->name }} · {{ $semester->academicYear->name ?? '' }}</option>@endforeach</select></div>
    <div class="col-md-2"><button class="btn btn-success w-100"><i class="bi bi-megaphone me-1"></i>Publikasi Kelas</button></div>
  </form>
</div></div>
<div class="card content-card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Siswa</th>
            <th>Kelas</th>
            <th>Mapel</th>
            <th>Guru</th>
            <th>Periode</th>
            <th>Nilai Akhir</th>
            <th>Status</th>
            <th>Keputusan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($grades as $g)<tr>
            <td class="fw-semibold">{{ $g->student->name }}<small class="d-block text-secondary">NIS: {{ $g->student->nis ?? '-' }}</small></td>
            <td>{{ $g->classroom->name }}</td>
            <td>{{ $g->subject->name }}</td>
            <td>{{ $g->teacher->name }}</td>
            <td>{{ $g->academicYear->name }}<small class="d-block text-secondary">{{ $g->semester->name }}</small></td>
            <td class="fw-bold">{{ number_format($g->final_score,2) }}</td>
            <td><span class="badge {{ $g->status==='submitted'?'text-bg-warning':($g->status==='approved'?'text-bg-success':'text-bg-danger') }}">{{ $g->status==='submitted'?'Menunggu':($g->status==='approved'?'Disetujui':'Revisi') }}</span></td>
            <td>@if($g->status==='submitted')
              <form method="POST" action="{{ route('admin.approvals.approve',$g) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success"><i class="bi bi-check2 me-1"></i>Setujui</button></form>
              <button class="btn btn-sm btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#rev{{ $g->id }}">Revisi</button>
              <div class="collapse mt-2" id="rev{{ $g->id }}">
                <form method="POST" action="{{ route('admin.approvals.revision',$g) }}">@csrf<textarea name="admin_note" class="form-control mb-2" required placeholder="Alasan revisi"></textarea><button class="btn btn-sm btn-danger">Kirim Revisi</button></form>
              </div>
              @else <span class="text-secondary small">Tidak ada tindakan</span> @endif
            </td>
          </tr>
          @empty<tr>
            <td colspan="8" class="text-center py-5 text-secondary">Tidak ada data pada pilihan tersebut.</td>
          </tr>@endforelse
        </tbody>
      </table>
    </div>{{ $grades->links() }}
  </div>
</div>
<script>
  document.querySelectorAll('#approvalFilter select').forEach(select => select.addEventListener('change', () => document.getElementById('approvalFilter').submit()));
</script>
@endsection