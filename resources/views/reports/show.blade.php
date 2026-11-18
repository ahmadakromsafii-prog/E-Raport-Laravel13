@extends('layouts.app')
@section('title','Nilai Saya')
@section('content')
<h2 class="page-title mb-2">Nilai Saya</h2>
<p class="text-secondary mb-4">Berikut nilai yang sudah dipublikasikan oleh sekolah.</p>
<div class="card content-card">
  <div class="card-body">
    <div class="mb-3"><b>Nama:</b> {{ $student->name }} &nbsp; <b>NIS:</b> {{ $student->nis ?? '-' }} &nbsp; <b>Kelas:</b> {{ $student->classroom->name ?? '-' }}</div>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Mata Pelajaran</th>
            <th>Tahun Ajaran</th>
            <th>Semester</th>
            <th>Tugas</th>
            <th>UTS</th>
            <th>UAS</th>
            <th>Nilai Akhir</th>
          </tr>
        </thead>
        <tbody>@forelse($grades as $i=>$g)<tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $g->subject->name }}</td>
            <td>{{ $g->academicYear->name }}</td>
            <td>{{ $g->semester->name }}</td>
            <td>{{ $g->tugas_score ?? '-' }}</td>
            <td>{{ $g->uts_score ?? '-' }}</td>
            <td>{{ $g->uas_score ?? '-' }}</td>
            <td class="fw-bold">{{ number_format($g->final_score,2) }}</td>
          </tr>@empty<tr>
            <td colspan="8" class="text-center py-4">Belum ada nilai yang dipublikasikan.</td>
          </tr>@endforelse</tbody>
      </table>
    </div>
  </div>
</div>
@endsection