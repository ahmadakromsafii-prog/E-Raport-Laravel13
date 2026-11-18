@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
    <div>
        <h2 class="page-title mb-1">Dashboard</h2>
        @if(auth()->user()->role==='guru')<p class="text-secondary mb-0">Kelola input nilai dan pengolahan rapor kelas yang menjadi tanggung jawab Anda.</p>
        @elseif(auth()->user()->role==='siswa')<p class="text-secondary mb-0">Pantau nilai dan aktivitas terbaru dari guru mata pelajaran Anda.</p>
        @else<p class="text-secondary mb-0">Ringkasan aktivitas dan pemantauan pengolahan rapor sekolah.</p>@endif
    </div>
</div>
<div class="row g-3 mb-4">@foreach($stats as $label=>$value)<div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="text-secondary text-capitalize">{{ str_replace('_',' ',$label) }}</div>
            <div class="stat-number">{{ $value }}</div>
        </div>
    </div>@endforeach</div>
@if(auth()->user()->role==='admin')
<div class="card content-card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Validasi Olah Rapor</h5>
                <p class="text-secondary small mb-0">Pengolahan rapor dari wali kelas yang menunggu persetujuan admin.</p>
            </div><a class="btn btn-primary btn-sm" href="{{ route('admin.report-cards.index',['status'=>'submitted']) }}">Lihat Semua</a>
        </div>
        @if($pendingReports->isNotEmpty())<div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Wali Kelas</th>
                        <th>Periode</th>
                        <th>Dikirim</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>@foreach($pendingReports as $card)<tr>
                        <td class="fw-semibold">{{ $card->student->name ?? '-' }}</td>
                        <td>{{ $card->classroom->name ?? '-' }}</td>
                        <td>{{ $card->homeroomTeacher->name ?? '-' }}</td>
                        <td>{{ $card->semester->name ?? '-' }} · {{ $card->academicYear->name ?? '-' }}</td>
                        <td>{{ $card->submitted_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.report-cards.index',['status'=>'submitted','classroom_id'=>$card->classroom_id]) }}">Validasi</a></td>
                    </tr>@endforeach</tbody>
            </table>
        </div>
        @else<div class="text-center text-secondary py-4"><i class="bi bi-check-circle fs-3 d-block mb-2"></i>Tidak ada olah rapor yang menunggu validasi.</div>@endif
    </div>
</div>
@endif
@if(auth()->user()->role==='guru')
<div class="row g-3 mb-4">
    <div class="col-md-6"><a href="{{ route('grades.bulk') }}" class="btn btn-primary btn-lg w-100 py-3"><i class="bi bi-pencil-square me-2"></i>Input Nilai Siswa</a></div>
    <div class="col-md-6"><a href="{{ route('report-cards.index') }}" class="btn btn-outline-primary btn-lg w-100 py-3"><i class="bi bi-clipboard2-check me-2"></i>Olah Nilai Rapor</a></div>
</div>
@endif
<div class="card content-card">
    <div class="card-body">
        <h5 class="fw-bold mb-3">@if(auth()->user()->role==='siswa')Aktivitas Terbaru — Nilai dari Guru @elseif(auth()->user()->role==='guru')Aktivitas Input Nilai Terbaru @else Nilai Terbaru yang Dipublikasikan @endif</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>@if(auth()->user()->role!=='siswa')<th>Siswa</th>@endif<th>Mata Pelajaran</th>
                        <th>@if(auth()->user()->role==='siswa')Guru @else Guru Penginput @endif</th>
                        <th>Nilai Akhir</th>
                        <th>Periode</th>
                        <th>Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $g)<tr>@if(auth()->user()->role!=='siswa')<td>{{ $g->student->name ?? '-' }}</td>@endif<td>{{ $g->subject->name ?? '-' }}</td>
                        <td>{{ auth()->user()->role==='siswa' ? ($g->teacher->name ?? '-') : ($g->teacher->name ?? auth()->user()->name) }}</td>
                        <td class="fw-bold">{{ number_format($g->final_score,2) }}</td>
                        <td>{{ $g->semester->name ?? '-' }} · {{ $g->academicYear->name ?? '-' }}</td>
                        <td><span class="badge text-bg-success">Nilai diberikan</span>
                            <div class="small text-secondary">{{ ($g->published_at ?? $g->updated_at)?->format('d/m/Y H:i') }}</div>
                        </td>
                    </tr>
                    @empty<tr>
                        <td colspan="6" class="text-center text-secondary py-4">Belum ada nilai yang dipublikasikan.</td>
                    </tr>@endforelse</tbody>
            </table>
        </div>
    </div>
</div>
@endsection