@extends('layouts.app')
@section('title','Rapor '.$classroom->name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
  <div>
    <h2 class="page-title mb-1">Rapor {{ $classroom->name }}</h2>
    <p class="text-secondary mb-0">Wali Kelas: {{ $classroom->homeroomTeacher->name ?? '-' }}</p>
  </div><button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer me-1"></i> Cetak Rapor Disetujui</button>
</div>
@foreach($students->filter(fn($s)=>$cards->has($s->id)) as $student)<section class="report-sheet mb-4">
  <div class="text-center border-bottom pb-3 mb-3">
    <h4 class="fw-bold">LAPORAN HASIL BELAJAR PESERTA DIDIK</h4>
    <div>{{ $classroom->name }}</div><div class="small">Tahun Ajaran: {{ $cards->first()?->academicYear?->name ?? '-' }} · Semester: {{ $cards->first()?->semester?->name ?? '-' }}</div>
  </div>
  <div class="row mb-3">
    <div class="col-6"><b>Nama:</b> {{ $student->name }}<br><b>NIS:</b> {{ $student->nis ?? '-' }}</div>
    <div class="col-6"><b>Kelas:</b> {{ $classroom->name }}<br><b>Tahun cetak:</b> {{ now()->translatedFormat('Y') }}</div>
  </div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No</th>
        <th>Mata Pelajaran</th>
        <th>Nilai Akhir</th>
      </tr>
    </thead>
    <tbody>@forelse($student->studentGrades as $i=>$g)<tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $g->subject->name }}</td>
        <td class="fw-bold">{{ number_format($g->final_score,2) }}</td>
      </tr>@empty<tr>
        <td colspan="3" class="text-center">Belum ada nilai yang dipublikasikan guru.</td>
      </tr>@endforelse</tbody>
  </table>
  @php($rc=$cards->get($student->id))
  @if($rc)
  <div class="mt-4 border rounded p-3"><h6 class="fw-bold">CATATAN WALI KELAS</h6><div class="row text-center mb-3"><div class="col-6"><b>Nilai Keterampilan</b><div class="fs-4 fw-bold">{{ number_format($rc->skill_score,2) }}</div></div><div class="col-6"><b>Nilai Sikap</b><div class="fs-4 fw-bold">{{ number_format($rc->attitude_score,2) }}</div></div></div><p><b>Motivasi:</b><br>{{ $rc->motivation }}</p><p class="mb-0"><b>Saran:</b><br>{{ $rc->suggestion }}</p></div>
  @else
  <div class="alert alert-warning mt-3">Pengolahan rapor wali kelas belum disetujui admin. Rapor ini belum final.</div>
  @endif
  <div class="row mt-5 text-center">
    <div class="col-6">Orang Tua / Wali<br><br><br>(................................)</div>
    <div class="col-6">Wali Kelas<br><br><br><b>{{ $classroom->homeroomTeacher->name ?? 'Belum ditetapkan' }}</b></div>
  </div>
</section>@endforeach
<style>
  @media print {
    .report-sheet {
      page-break-after: always;
      break-after: page
    }

    .report-sheet:last-child {
      page-break-after: auto;
      break-after: auto
    }
  }
</style>
@endsection