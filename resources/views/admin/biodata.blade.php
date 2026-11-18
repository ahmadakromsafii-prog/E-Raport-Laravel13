@extends('layouts.app')
@section('title','Biodata Guru dan Siswa')
@section('content')
<div class="mb-4">
  <h2 class="page-title mb-1">Biodata Guru dan Siswa</h2>
  <p class="text-secondary mb-0">Admin dapat menambahkan akun sekaligus melengkapi identitas guru dan siswa.</p>
</div>
<div class="row g-3 mb-4">
  <div class="col-xl-6">
    <div class="card content-card h-100">
      <div class="card-body">
        <h5 class="fw-bold mb-3"><i class="bi bi-person-workspace me-2 text-success"></i>Tambah Biodata Guru</h5>
        <form method="POST" action="{{ url('/pengguna/guru') }}" class="row g-3">@csrf<input type="hidden" name="role" value="guru">
          <div class="col-md-6"><label class="form-label">Nama Lengkap</label><input name="name" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label">NIP</label><input name="nip" class="form-control"></div>
          <div class="col-md-6"><label class="form-label">Password</label><input name="password" type="password" class="form-control" minlength="8" required></div>
          <div class="col-12"><button class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Simpan Guru</button></div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-xl-6">
    <div class="card content-card h-100">
      <div class="card-body">
        <h5 class="fw-bold mb-3"><i class="bi bi-mortarboard me-2 text-success"></i>Tambah Biodata Siswa</h5>
        <form method="POST" action="{{ url('/pengguna/siswa') }}" class="row g-3">@csrf<input type="hidden" name="role" value="siswa">
          <div class="col-md-6"><label class="form-label">Nama Lengkap</label><input name="name" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label">NIS</label><input name="nis" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label">Kelas</label><select name="classroom_id" class="form-select" required>
              <option value="">Pilih kelas</option>@foreach(\App\Models\Classroom::orderBy('name')->get() as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach
            </select></div>
          <div class="col-md-6"><label class="form-label">Password</label><input name="password" type="password" class="form-control" minlength="8" required></div>
          <div class="col-12"><button class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Simpan Siswa</button></div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="card content-card mb-4">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Data Guru</h5>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Guru</th>
            <th>Username</th>
            <th>NIP</th>
            <th>Wali Kelas</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>@forelse($teachers as $i=>$t)<tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $t->name }}</td>
            <td>{{ $t->username }}</td>
            <td>{{ $t->nip ?? '-' }}</td>
            <td>{{ $t->homeroom_classroom->name ?? 'Belum ditetapkan' }}</td>
            <td><a class="btn btn-sm btn-outline-primary" href="{{ url('/pengguna/guru?edit='.$t->id) }}"><i class="bi bi-pencil-square me-1"></i>Edit Biodata</a></td>
          </tr>@empty<tr>
            <td colspan="6" class="text-center">Belum ada data guru.</td>
          </tr>@endforelse</tbody>
      </table>
    </div>
  </div>
</div>
<div class="card content-card">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Data Siswa</h5>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Username</th>
            <th>NIS</th>
            <th>Kelas</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>@forelse($students as $i=>$s)<tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $s->name }}</td>
            <td>{{ $s->username }}</td>
            <td>{{ $s->nis ?? '-' }}</td>
            <td>{{ $s->classroom->name ?? '-' }}</td>
            <td><a class="btn btn-sm btn-outline-primary" href="{{ url('/pengguna/siswa?edit='.$s->id) }}"><i class="bi bi-pencil-square me-1"></i>Edit Biodata</a></td>
          </tr>@empty<tr>
            <td colspan="6" class="text-center">Belum ada data siswa.</td>
          </tr>@endforelse</tbody>
      </table>
    </div>
  </div>
</div>
@endsection