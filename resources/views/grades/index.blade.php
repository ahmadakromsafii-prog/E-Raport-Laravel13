@extends('layouts.app')
@section('title','Kelola Nilai')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="page-title mb-1">Input dan Kelola Nilai</h2>
    <p class="text-secondary mb-0">Nilai yang disimpan langsung dipublikasikan kepada siswa dan dapat diperbarui kembali.</p>
  </div><a class="btn btn-primary" href="{{ route('grades.create') }}">+ Input Nilai</a>
</div>
<div>
  <div class="card content-card">
    <div class="card-body">
      
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Siswa</th>
              <th>Kelas</th>
              <th>Mapel</th>
              <th>Tahun/Semester</th>
              <th>Nilai</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($grades as $g)<tr>
              <td>{{ $g->student->name }}</td>
              <td>{{ $g->classroom->name }}</td>
              <td>{{ $g->subject->name }}</td>
              <td>{{ $g->academicYear->name }} / {{ $g->semester->name }}</td>
              <td>{{ number_format($g->final_score,2) }}</td>
              <td><span class="badge text-bg-secondary">{{ ucfirst($g->status) }}</span>@if($g->admin_note)<div class="small text-danger">{{ $g->admin_note }}</div>@endif</td>
              <td>@if(in_array($g->status,['draft','revision','published']))<a class="btn btn-sm btn-outline-primary" href="{{ route('grades.edit',$g) }}">Edit</a>@else<span class="text-secondary small">Terkunci</span>@endif</td>
            </tr>
            @empty<tr>
              <td colspan="7" class="text-center py-4">Belum ada nilai.</td>
            </tr>@endforelse
          </tbody>
        </table>
      </div>{{ $grades->links() }}
    </div>
  </div>
</div>
@endsection