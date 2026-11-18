@extends('layouts.app')
@section('title', $type === 'users' ? 'Kelola ' . ucfirst($role) : 'Data Master')
@section('content')
@php
    $labels = ['classrooms'=>'Kelas','subjects'=>'Mata Pelajaran','academic-years'=>'Tahun Ajaran','semesters'=>'Semester'];
    $base = $type === 'users' ? '/pengguna/'.$role : '/'.(['classrooms'=>'kelas','subjects'=>'mata-pelajaran','academic-years'=>'tahun-ajaran','semesters'=>'semester'][$type]);
    $identityValue = ($type === 'users' && $editItem) ? ($role === 'siswa' ? $editItem->nis : $editItem->nip) : null;
@endphp
@if($type === 'users')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div><div class="text-uppercase small fw-bold text-success mb-1">Data Master / Pengguna</div><h2 class="page-title mb-1">Kelola {{ ucfirst($role) }}</h2><p class="text-secondary mb-0">Data akun {{ $role }} dikelola secara terpisah.</p></div>
        <span class="badge rounded-pill text-bg-light px-3 py-2">{{ $items->total() }} {{ ucfirst($role) }}</span>
    </div>
    <div class="user-tabs mb-4" role="tablist">
        <a href="{{ route('admin.master.index','siswa') }}" class="user-tab {{ $role==='siswa'?'active':'' }}"><i class="bi bi-mortarboard-fill"></i><span><b>Data Siswa</b><small>Kelola akun dan NIS</small></span></a>
        <a href="{{ route('admin.master.index','guru') }}" class="user-tab {{ $role==='guru'?'active':'' }}"><i class="bi bi-person-workspace"></i><span><b>Data Guru</b><small>Kelola akun dan NIP</small></span></a>
    </div>
    <div class="card content-card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-center gap-2 mb-3"><span class="form-icon"><i class="bi {{ $role==='siswa'?'bi-person-plus-fill':'bi-person-badge-fill' }}"></i></span><div><h5 class="fw-bold mb-0">{{ $editItem ? 'Edit Data '.ucfirst($role) : 'Tambah '.ucfirst($role).' Baru' }}</h5><small class="text-secondary">Lengkapi informasi akun di bawah ini.</small></div></div>
            <form method="POST" action="{{ $editItem ? $base.'/'.$editItem->id : $base }}" class="row g-3">@csrf @if($editItem)@method('PUT')@endif
                <div class="col-12 col-md-6"><label class="form-label">Nama Lengkap</label><input name="name" class="form-control" value="{{ old('name',$editItem?->name) }}" placeholder="Masukkan nama lengkap" required></div>
                <div class="col-12 col-md-6"><label class="form-label">Username</label><input name="username" class="form-control" value="{{ old('username',$editItem?->username) }}" placeholder="Username untuk login" required></div>
                <div class="col-12 col-md-6"><label class="form-label">{{ $role==='siswa'?'NIS':'NIP' }}</label><input name="{{ $role==='siswa'?'nis':'nip' }}" class="form-control" value="{{ old($role==='siswa'?'nis':'nip',$identityValue) }}" placeholder="Masukkan {{ strtoupper($role==='siswa'?'NIS':'NIP') }}"></div>
                @if(auth()->user()->role==='super_admin')
                <div class="col-12 col-md-6"><label class="form-label">Jenjang Sekolah</label><select name="school_level" class="form-select" required>@foreach(['SD','SMP','SMA'] as $level)<option value="{{ $level }}" @selected(old('school_level',$editItem?->school_level ?? 'SD')===$level)>{{ $level }}</option>@endforeach</select></div>
                @endif
                <div class="col-12 col-md-6"><label class="form-label">Kata Sandi {{ $editItem?'(opsional, isi jika ingin mengubah)':'' }}</label><input name="password" type="password" class="form-control" minlength="8" {{ $editItem?'':'required' }} placeholder="Minimal 8 karakter"></div>
                @if($role==='siswa')
                <div class="col-12 col-md-6"><label class="form-label">Kelas</label><select name="classroom_id" class="form-select"><option value="">Pilih kelas</option>@foreach($classrooms as $class)<option value="{{ $class->id }}" @selected(old('classroom_id',$editItem?->classroom_id)==$class->id)>{{ $class->name }}</option>@endforeach</select></div>
                <div class="col-12 col-md-6"><label class="form-label">Nama Orang Tua</label><input name="parent_name" class="form-control" value="{{ old('parent_name',$editItem?->parent_name) }}" placeholder="Nama orang tua/wali"></div>
                <div class="col-12 col-md-6"><label class="form-label">Asal Sekolah</label><input name="previous_school" class="form-control" value="{{ old('previous_school',$editItem?->previous_school) }}" placeholder="Asal sekolah sebelumnya"></div>
                <div class="col-12 col-md-6"><label class="form-label">Tempat Tinggal</label><input name="residence" class="form-control" value="{{ old('residence',$editItem?->residence) }}" placeholder="Alamat/tempat tinggal"></div>
                @endif
                <div class="col-12 col-md-6"><label class="form-label">Nomor Telepon</label><input name="phone" class="form-control" value="{{ old('phone',$editItem?->phone) }}" placeholder="08xxxxxxxxxx"></div>
                <div class="col-12 d-flex flex-column flex-sm-row gap-2 pt-2"><button class="btn btn-primary px-4"><i class="bi bi-check2-circle me-1"></i>{{ $editItem?'Simpan Perubahan':'Tambah '.ucfirst($role) }}</button>@if($editItem)<a class="btn btn-light px-4" href="{{ $base }}">Batal</a>@endif</div>
            </form>
        </div>
    </div>
    <div class="card content-card"><div class="card-body p-3 p-md-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"><div><h5 class="fw-bold mb-1">Daftar {{ ucfirst($role) }}</h5><small class="text-secondary">Informasi akun yang telah terdaftar.</small></div><span class="small text-secondary">{{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} data</span></div>
        <div class="table-responsive"><table class="table align-middle"><thead><tr><th>#</th><th>Nama</th><th>{{ $role==='siswa'?'NIS':'NIP' }}</th><th>Username</th>@if($role==='siswa')<th>Kelas</th>@endif<th>Telepon</th><th>Aksi</th></tr></thead><tbody>
        @forelse($items as $item)<tr><td>{{ $items->firstItem()+$loop->index }}</td><td><div class="fw-bold">{{ $item->name }}</div><small class="text-secondary">{{ ucfirst($role) }}</small></td><td>{{ ($role==='siswa' ? $item->nis : $item->nip) ?: '-' }}</td><td>{{ $item->username }}</td>@if($role==='siswa')<td><span class="badge rounded-pill text-bg-light">{{ $item->classroom->name ?? 'Belum ada kelas' }}</span></td>@endif<td>{{ $item->phone ?: '-' }}</td><td><div class="d-flex flex-nowrap gap-1"><a class="btn btn-sm btn-outline-primary" href="{{ $base.'?edit='.$item->id }}" title="Edit"><i class="bi bi-pencil-square"></i></a><form method="POST" action="{{ $base.'/'.$item->id }}" onsubmit="return confirm('Hapus data {{ $role }} ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button></form></div></td></tr>
        @empty<tr><td colspan="{{ $role==='siswa'?7:6 }}" class="text-center py-5"><i class="bi bi-inbox fs-2 d-block text-secondary mb-2"></i>Belum ada data {{ $role }}.</td></tr>@endforelse
        </tbody></table></div>{{ $items->links() }}
    </div></div>
@else
    <h2 class="page-title mb-4">Kelola {{ $labels[$type] }}</h2>
    <div class="card content-card mb-4"><div class="card-body p-3 p-md-4"><h5 class="fw-bold">{{ $editItem ? 'Edit' : 'Tambah' }} {{ $labels[$type] }}</h5>
        <form method="POST" action="{{ $editItem ? $base.'/'.$editItem->id : $base }}" class="row g-3 align-items-end">@csrf @if($editItem)@method('PUT')@endif
        @if($type==='classrooms')
            <div class="col-12 col-md-4"><label class="form-label">Nama Kelas</label><input name="name" class="form-control" value="{{ old('name',$editItem?->name) }}" required></div><div class="col-12 col-md-2"><label class="form-label">Tingkat</label><input name="level" class="form-control" value="{{ old('level',$editItem?->level) }}"></div>@if(auth()->user()->role==='super_admin')<div class="col-12 col-md-2"><label class="form-label">Jenjang</label><select name="school_level" class="form-select" required>@foreach(['SD','SMP','SMA'] as $level)<option value="{{ $level }}" @selected(old('school_level',$editItem?->school_level ?? 'SD')===$level)>{{ $level }}</option>@endforeach</select></div>@endif<div class="col-12 col-md-4"><label class="form-label">Wali Kelas</label><select name="homeroom_teacher_id" class="form-select"><option value="">Belum ditentukan</option>@foreach(\App\Models\User::where('role','guru')->orderBy('name')->get() as $teacher)<option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id',$editItem?->homeroom_teacher_id)==$teacher->id)>{{ $teacher->name }}</option>@endforeach</select></div>
        @elseif($type==='subjects')
            <div class="col-12 col-md-5"><label class="form-label">Nama Mata Pelajaran</label><input name="name" class="form-control" value="{{ old('name',$editItem?->name) }}" required></div><div class="col-12 col-md-3"><label class="form-label">Kode</label><input name="code" class="form-control" value="{{ old('code',$editItem?->code) }}"></div><div class="col-12"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="2">{{ old('description',$editItem?->description) }}</textarea></div>
        @elseif($type==='academic-years')
            <div class="col-12 col-md-5"><label class="form-label">Tahun Ajaran</label><input name="name" class="form-control" placeholder="2026/2027" value="{{ old('name',$editItem?->name) }}" required></div><div class="col-12 col-md-3 form-check ms-3"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active',$editItem?->is_active))><label class="form-check-label">Aktif</label></div>
        @else
            <div class="col-12 col-md-3"><label class="form-label">Semester</label><select name="name" class="form-select"><option value="Ganjil" @selected(old('name',$editItem?->name)==='Ganjil')>Ganjil</option><option value="Genap" @selected(old('name',$editItem?->name)==='Genap')>Genap</option></select></div><div class="col-12 col-md-3"><label class="form-label">Tahun Ajaran</label><select name="academic_year_id" class="form-select" required>@foreach(\App\Models\AcademicYear::orderByDesc('id')->get() as $year)<option value="{{ $year->id }}" @selected(old('academic_year_id',$editItem?->academic_year_id)==$year->id)>{{ $year->name }}</option>@endforeach</select></div><div class="col-12 col-md-3 form-check ms-3"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active',$editItem?->is_active))><label class="form-check-label">Aktif</label></div>
        @endif
        <div class="col-12 d-flex gap-2"><button class="btn btn-primary">{{ $editItem?'Simpan Perubahan':'Tambah Data' }}</button>@if($editItem)<a class="btn btn-light" href="{{ $base }}">Batal</a>@endif</div></form>
    </div></div>
    <div class="card content-card"><div class="card-body p-3 p-md-4"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>ID</th><th>Nama / Keterangan</th><th>Info</th><th>Aksi</th></tr></thead><tbody>
    @forelse($items as $item)<tr><td>{{ $item->id }}</td><td>{{ $item->name ?? '-' }}</td><td>@if($type==='classrooms'){{ $item->level }} · Wali: {{ $item->homeroomTeacher->name ?? 'Belum ditentukan' }}@elseif($type==='subjects'){{ $item->code }}@elseif($type==='semesters'){{ $item->academicYear->name ?? '' }}@else{{ $item->is_active ? 'Aktif' : 'Tidak aktif' }}@endif</td><td><div class="d-flex gap-2"><a class="btn btn-sm btn-outline-primary" href="{{ $base.'?edit='.$item->id }}">Edit</a><form method="POST" action="{{ $base.'/'.$item->id }}" onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></div></td></tr>@empty<tr><td colspan="4" class="text-center py-4">Belum ada data.</td></tr>@endforelse
    </tbody></table></div>{{ $items->links() }}</div></div>
@endif
<style>
.user-tabs{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.user-tab{display:flex;align-items:center;gap:13px;padding:16px 18px;border:1px solid #e2ece6;background:#fff;border-radius:16px;color:#31594d;text-decoration:none;box-shadow:0 5px 18px #17483e0a;transition:.2s}.user-tab>i{width:42px;height:42px;border-radius:13px;display:grid;place-items:center;background:#edf6f0;color:#0b6657;font-size:20px}.user-tab b,.user-tab small{display:block}.user-tab small{color:#82928a;font-size:11px;margin-top:3px}.user-tab.active{border-color:#0b806b;background:#f3faf6;box-shadow:0 6px 20px #0b665719}.user-tab.active>i,.form-icon{background:#0b6657;color:#fff}.form-icon{width:40px;height:40px;border-radius:12px;display:grid;place-items:center;font-size:18px}@media(max-width:575.98px){.user-tabs{gap:8px}.user-tab{padding:12px 10px;gap:8px}.user-tab>i{width:34px;height:34px;font-size:17px}.user-tab b{font-size:12px}.user-tab small{font-size:10px}.content-card .card-body{padding:16px!important}.table{font-size:12px;white-space:nowrap}.table .btn{min-width:34px}}
</style>
@endsection
