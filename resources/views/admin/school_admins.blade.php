@extends('layouts.app')
@section('title','Kepala Sekolah')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
 <div><h2 class="page-title mb-1">Kepala Sekolah</h2><p class="text-secondary mb-0">Ketua yayasan dapat membuat dan mengelola akun kepala sekolah SD, SMP, dan SMA.</p></div>
 <span class="badge text-bg-success px-3 py-2">{{ $items->count() }} akun</span>
</div>
<div class="card content-card mb-4"><div class="card-body">
 <h5 class="fw-bold mb-3">{{ $editItem?'Ubah Akun Kepala Sekolah':'Tambah Kepala Sekolah' }}</h5>
 <form method="POST" action="{{ $editItem?route('foundation.school-admins.update',$editItem->id):route('foundation.school-admins.store') }}" class="row g-3">
 @csrf @if($editItem) @method('PUT') @endif
 <div class="col-md-4"><label class="form-label">Nama Lengkap</label><input name="name" class="form-control" value="{{ old('name',$editItem?->name) }}" required></div>
 <div class="col-md-3"><label class="form-label">Username</label><input name="username" class="form-control" value="{{ old('username',$editItem?->username) }}" required></div>
 <div class="col-md-3"><label class="form-label">Jenjang</label><select name="school_level" class="form-select" required>@foreach(['SD','SMP','SMA'] as $level)<option value="{{ $level }}" @selected(old('school_level',$editItem?->school_level)===$level)>{{ $level }}</option>@endforeach</select></div>
 <div class="col-md-2"><label class="form-label">Password {{ $editItem?'(opsional)':'' }}</label><input name="password" type="password" minlength="8" class="form-control" {{ $editItem?'':'required' }}></div>
 <div class="col-12"><button class="btn btn-primary">{{ $editItem?'Simpan Perubahan':'Buat Akun' }}</button>@if($editItem)<a href="{{ route('foundation.school-admins.index') }}" class="btn btn-light ms-2">Batal</a>@endif</div>
 </form>
</div></div>
<div class="card content-card"><div class="card-body"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nama</th><th>Username</th><th>Jenjang</th><th>Aksi</th></tr></thead><tbody>
@forelse($items as $item)<tr><td class="fw-semibold">{{ $item->name }}</td><td>{{ $item->username }}</td><td><span class="badge text-bg-light">{{ $item->school_level }}</span></td><td><div class="d-flex gap-2"><a class="btn btn-sm btn-outline-primary" href="?edit={{ $item->id }}">Edit</a><form method="POST" action="{{ route('foundation.school-admins.destroy',$item->id) }}" onsubmit="return confirm('Hapus akun kepala sekolah ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></div></td></tr>@empty<tr><td colspan="4" class="text-center py-4">Belum ada akun kepala sekolah.</td></tr>@endforelse
</tbody></table></div></div></div>
@endsection
