@extends('layouts.app')
@section('title','Penugasan Guru')
@section('content')
<h2 class="page-title mb-1">Penugasan Guru Mata Pelajaran</h2>
<p class="text-secondary mb-4">Pilih satu guru, satu mata pelajaran, lalu centang kelas yang menjadi tanggung jawab guru tersebut.</p>

<div class="card content-card mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.assignments.store') }}">@csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-5"><label class="form-label">Guru</label><select name="teacher_id" class="form-select" required>
                    <option value="">Pilih guru</option>@foreach($teacherOptions as $x)<option value="{{ $x->id }}" @selected(old('teacher_id')==$x->id)>{{ $x->name }}</option>@endforeach
                </select></div>
                <div class="col-md-5"><label class="form-label">Mata Pelajaran</label><select name="subject_id" class="form-select" required>
                    <option value="">Pilih mata pelajaran</option>@foreach($subjects as $x)<option value="{{ $x->id }}" @selected(old('subject_id')==$x->id)>{{ $x->name }}</option>@endforeach
                </select></div>
                <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Simpan</button></div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                <label class="form-label mb-0">Pilih Kelas</label>
                <div class="d-flex gap-2"><button type="button" class="btn btn-sm btn-outline-primary" id="checkAllClasses">Pilih semua</button><button type="button" class="btn btn-sm btn-outline-secondary" id="uncheckAllClasses">Hapus pilihan</button></div>
            </div>
            <div class="row g-2">@foreach($classes as $x)<div class="col-6 col-md-4 col-xl-3"><label class="border rounded-3 p-3 w-100 d-flex align-items-center gap-2"><input class="form-check-input mt-0 class-check" type="checkbox" name="classroom_ids[]" value="{{ $x->id }}" @checked(collect(old('classroom_ids',[]))->contains($x->id))><span class="fw-semibold">{{ $x->name }}</span></label></div>@endforeach</div>
            @error('classroom_ids')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        </form>
    </div>
</div>

<div class="card content-card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div><h5 class="fw-bold mb-1">Daftar Penugasan Aktif</h5><small class="text-secondary">Satu guru ditampilkan satu kali, dengan seluruh mata pelajaran dan kelasnya.</small></div>
            <form method="GET" class="d-flex align-items-center gap-2">
                <label for="per_page" class="small text-secondary">Guru per halaman</label>
                <select id="per_page" name="per_page" class="form-select form-select-sm" style="width:90px" onchange="this.form.submit()">
                    @foreach([10,20,50,100] as $size)<option value="{{ $size }}" @selected($perPage===$size)>{{ $size }}</option>@endforeach
                </select>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th style="width:24%">Nama Guru</th><th>Mata Pelajaran dan Kelas</th><th class="text-end">Jumlah</th></tr></thead>
                <tbody>
                @forelse($teachers as $teacher)
                    <tr>
                        <td><div class="fw-bold">{{ $teacher->name }}</div><small class="text-secondary">{{ $teacher->username }}</small></td>
                        <td>
                            @forelse($teacher->teachingAssignments as $a)
                                <div class="border rounded-3 p-2 mb-2">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div><span class="badge text-bg-success me-1">{{ $a->subject?->name ?? 'Mapel dihapus' }}</span><span class="badge text-bg-light border text-dark">{{ $a->classroom?->name ?? 'Kelas dihapus' }}</span></div>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#edit-assignment-{{ $a->id }}"><i class="bi bi-pencil"></i> Edit</button>
                                            <form method="POST" action="{{ route('admin.assignments.destroy',$a) }}" onsubmit="return confirm('Hapus penugasan {{ $a->subject?->name }} - {{ $a->classroom?->name }}?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                                        </div>
                                    </div>
                                    <div class="collapse mt-2" id="edit-assignment-{{ $a->id }}">
                                        <form method="POST" action="{{ route('admin.assignments.update',$a) }}" class="row g-2">@csrf @method('PUT')
                                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                            <div class="col-md-5"><select name="subject_id" class="form-select form-select-sm" required>@foreach($subjects as $subject)<option value="{{ $subject->id }}" @selected($a->subject_id==$subject->id)>{{ $subject->name }}</option>@endforeach</select></div>
                                            <div class="col-md-5"><select name="classroom_id" class="form-select form-select-sm" required>@foreach($classes as $class)<option value="{{ $class->id }}" @selected($a->classroom_id==$class->id)>{{ $class->name }}</option>@endforeach</select></div>
                                            <div class="col-md-2"><button class="btn btn-sm btn-primary w-100">Simpan</button></div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <span class="text-secondary">Belum ada penugasan.</span>
                            @endforelse
                        </td>
                        <td class="text-end"><span class="badge rounded-pill text-bg-light border text-dark">{{ $teacher->teaching_assignments_count }} penugasan</span></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-secondary py-4">Belum ada data guru.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
            <small class="text-secondary">Menampilkan {{ $teachers->firstItem() ?? 0 }}–{{ $teachers->lastItem() ?? 0 }} dari {{ $teachers->total() }} guru</small>
            {{ $teachers->onEachSide(1)->links() }}
        </div>
    </div>
</div>
<script>
document.getElementById('checkAllClasses')?.addEventListener('click', () => document.querySelectorAll('.class-check').forEach(x => x.checked = true));
document.getElementById('uncheckAllClasses')?.addEventListener('click', () => document.querySelectorAll('.class-check').forEach(x => x.checked = false));
</script>
@endsection
