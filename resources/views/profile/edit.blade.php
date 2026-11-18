@extends('layouts.app')
@section('title','Profil Saya')
@section('content')
<h2 class="page-title mb-1">Profil Saya</h2><p class="text-secondary mb-4">Perbarui informasi akun dan keamanan password Anda.</p>
<div class="row g-4">
 <div class="col-lg-7"><div class="card content-card"><div class="card-body"><h5 class="fw-bold mb-3">Informasi Profil</h5>
 <form method="POST" action="{{ route('profile.update') }}" class="row g-3" enctype="multipart/form-data">@csrf @method('PUT')
  <div class="col-12 d-flex align-items-center gap-3 flex-wrap mb-2">
   @if($user->avatar_path)
    <img id="profilePhotoPreview" class="profile-photo-preview" src="{{ \Illuminate\Support\Facades\Storage::url($user->avatar_path) }}" alt="Foto profil {{ $user->name }}">
   @else
    <div id="profilePhotoFallback" class="profile-photo-preview d-grid place-items-center" style="display:grid!important;place-items:center;background:#e7f4ef;color:#0b6657;font-size:2.4rem;font-weight:800">{{ strtoupper(substr($user->name,0,1)) }}</div>
    <img id="profilePhotoPreview" class="profile-photo-preview d-none" src="" alt="Pratinjau foto profil">
   @endif
   <div class="flex-grow-1"><label class="form-label mb-1" for="avatar">Foto Profil</label><input class="form-control" type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp"><small class="text-secondary d-block mt-1">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</small>@error('avatar')<small class="text-danger">{{ $message }}</small>@enderror</div>
  </div>
  <div class="col-md-6"><label class="form-label">Nama Lengkap</label><input class="form-control" name="name" value="{{ old('name',$user->name) }}" required></div>
  <div class="col-md-6"><label class="form-label">Username</label><input class="form-control" value="{{ $user->username }}" disabled></div>
  <div class="col-md-6"><label class="form-label">Nomor Telepon</label><input class="form-control" name="phone" value="{{ old('phone',$user->phone) }}" placeholder="08xxxxxxxxxx"></div>
  @if($user->role==='siswa')
  <div class="col-md-6"><label class="form-label">Asal Sekolah</label><input class="form-control" name="previous_school" value="{{ old('previous_school',$user->previous_school) }}"></div>
  <div class="col-md-6"><label class="form-label">Nama Orang Tua / Wali</label><input class="form-control" name="parent_name" value="{{ old('parent_name',$user->parent_name) }}"></div>
  <div class="col-12"><label class="form-label">Tempat Tinggal / Alamat</label><textarea class="form-control" name="residence" rows="3">{{ old('residence',$user->residence) }}</textarea></div>
  @elseif($user->role==='guru')
  <div class="col-md-6"><label class="form-label">NIP</label><input class="form-control" name="nip" value="{{ old('nip',$user->nip) }}"></div>
  <div class="col-12"><label class="form-label">Alamat / Tempat Tinggal</label><textarea class="form-control" name="residence" rows="3">{{ old('residence',$user->residence) }}</textarea></div>
  @else
  <div class="col-12"><label class="form-label">Alamat / Tempat Tinggal</label><textarea class="form-control" name="residence" rows="3">{{ old('residence',$user->residence) }}</textarea></div>
  @endif
  <div class="col-12"><button class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Profil</button></div>
 </form></div></div></div>
 <div class="col-lg-5"><div class="card content-card"><div class="card-body"><h5 class="fw-bold mb-3">Ubah Password</h5><form method="POST" action="{{ route('profile.password') }}" class="vstack gap-3">@csrf @method('PUT')
  <div><label class="form-label">Password Saat Ini</label><input class="form-control" type="password" name="current_password" required autocomplete="current-password"></div>
  <div><label class="form-label">Password Baru</label><input class="form-control" type="password" name="password" required minlength="8" autocomplete="new-password"></div>
  <div><label class="form-label">Konfirmasi Password Baru</label><input class="form-control" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></div>
  <button class="btn btn-outline-primary">Perbarui Password</button></form></div></div></div>
</div>
<script>
document.getElementById('avatar')?.addEventListener('change', function (event) {
    const file = event.target.files?.[0];
    if (!file) return;
    const preview = document.getElementById('profilePhotoPreview');
    const fallback = document.getElementById('profilePhotoFallback');
    preview.src = URL.createObjectURL(file);
    preview.classList.remove('d-none');
    fallback?.classList.add('d-none');
});
</script>
@endsection
