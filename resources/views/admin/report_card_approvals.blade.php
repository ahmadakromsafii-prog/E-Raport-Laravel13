@extends('layouts.app')
@section('title','Validasi Olah Rapor')
@section('content')
<div class="d-flex justify-content-between flex-wrap gap-2 align-items-end mb-3">
    <div>
        <h2 class="page-title mb-1">Validasi Pengolahan Rapor</h2>
        <p class="text-secondary mb-0">Daftar seluruh hasil olah rapor dari wali kelas dan admin.</p>
    </div><span class="badge text-bg-warning px-3 py-2">{{ $pendingCount }} menunggu persetujuan</span>
</div>
<div class="card content-card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3"><label class="form-label">Kelas</label><select name="classroom_id" class="form-select">
                    <option value="">Semua kelas</option>@foreach($classes as $x)<option value="{{ $x->id }}" @selected(request('classroom_id')==$x->id)>{{ $x->name }}</option>@endforeach
                </select></div>
            <div class="col-md-3"><label class="form-label">Tahun Ajaran</label><select name="academic_year_id" class="form-select">
                    <option value="">Semua tahun</option>@foreach($years as $y)<option value="{{ $y->id }}" @selected(request('academic_year_id')==$y->id)>{{ $y->name }}</option>@endforeach
                </select></div>
            <div class="col-md-3"><label class="form-label">Semester</label><select name="semester_id" class="form-select">
                    <option value="">Semua semester</option>@foreach($semesters as $s)<option value="{{ $s->id }}" @selected(request('semester_id')==$s->id)>{{ $s->name }} · {{ $s->academicYear->name ?? '' }}</option>@endforeach
                </select></div>
            <div class="col-md-2"><label class="form-label">Status</label><select name="status" class="form-select">
                    <option value="">Semua status</option>@foreach(['submitted'=>'Menunggu validasi','revision'=>'Perlu revisi','approved'=>'Disetujui','draft'=>'Draft'] as $v=>$label)<option value="{{ $v }}" @selected(request('status')===$v)>{{ $label }}</option>@endforeach
                </select></div>
            <div class="col-md-1"><button class="btn btn-primary w-100">Cari</button></div>
        </form>
    </div>
</div>
<div class="card content-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Siswa / Kelas</th>
                        <th>Wali kelas / Pengolah</th>
                        <th>Periode</th>
                        <th>Keterampilan</th>
                        <th>Sikap</th>
                        <th>Motivasi & Saran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cards as $c)<tr>
                        <td><b>{{ $c->student->name ?? 'Siswa tidak ditemukan' }}</b><small class="d-block text-secondary">{{ $c->classroom->name ?? '-' }} · NIS {{ $c->student->nis ?? '-' }}</small></td>
                        <td>{{ $c->homeroomTeacher->name ?? '-' }}</td>
                        <td>{{ $c->academicYear->name ?? '-' }}<small class="d-block text-secondary">{{ $c->semester->name ?? '-' }}</small></td>
                        <td>{{ $c->skill_score ?? '-' }}</td>
                        <td>{{ $c->attitude_score ?? '-' }}</td>
                        <td><small><b>Motivasi:</b> {{ $c->motivation ?: '-' }}<br><b>Saran:</b> {{ $c->suggestion ?: '-' }}</small></td>
                        <td><span class="badge text-bg-{{ $c->status==='approved'?'success':($c->status==='submitted'?'warning':($c->status==='revision'?'danger':'secondary')) }}">{{ ucfirst($c->status) }}</span>@if($c->submitted_at)<small class="d-block text-secondary">{{ $c->submitted_at->format('d/m/Y H:i') }}</small>@endif</td>
                        <td>
                            @if($c->status==='submitted')<form method="POST" action="{{ route('admin.report-cards.approve',$c) }}" class="mb-1">@csrf<button class="btn btn-sm btn-success"><i class="bi bi-check2 me-1"></i>Setujui</button></form><button class="btn btn-sm btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#rc{{ $c->id }}">Revisi</button>
                            <div class="collapse mt-2" id="rc{{ $c->id }}">
                                <form method="POST" action="{{ route('admin.report-cards.revision',$c) }}">@csrf<textarea class="form-control mb-2" name="admin_note" required placeholder="Alasan revisi"></textarea><button class="btn btn-sm btn-danger">Kirim revisi</button></form>
                            </div>
                            @elseif($c->status==='draft')<span class="small text-secondary">Belum dikirim wali kelas</span>
                            @elseif($c->status==='approved')<span class="small text-success">Siap dicetak</span>
                            @else<span class="small text-danger">Menunggu perbaikan</span>@endif
                        </td>
                    </tr>@empty<tr>
                        <td colspan="8" class="text-center py-5 text-secondary">Belum ada data olah rapor untuk filter ini.</td>
                    </tr>@endforelse
                </tbody>
            </table>
        </div>{{ $cards->links() }}
    </div>
</div>
@endsection