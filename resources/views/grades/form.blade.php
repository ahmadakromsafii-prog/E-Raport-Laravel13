@extends('layouts.app')
@section('title','Form Nilai')
@section('content')
<h2 class="page-title mb-4">{{ $grade->exists ? 'Edit Nilai' : 'Input Nilai Siswa' }}</h2>
<div class="card content-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ $grade->exists ? route('grades.update',$grade) : route('grades.store') }}">@csrf @if($grade->exists)@method('PUT')@endif
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Kelas</label><select name="classroom_id" class="form-select" required>@foreach($classrooms as $x)<option value="{{ $x->id }}" @selected(old('classroom_id',$grade->classroom_id)==$x->id)>{{ $x->name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Siswa</label><select name="student_id" class="form-select" required>@foreach($students as $x)<option value="{{ $x->id }}" @selected(old('student_id',$grade->student_id)==$x->id)>{{ $x->name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Mata Pelajaran</label><select name="subject_id" class="form-select" required>@foreach($subjects as $x)<option value="{{ $x->id }}" @selected(old('subject_id',$grade->subject_id)==$x->id)>{{ $x->name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Tahun Ajaran</label><select name="academic_year_id" class="form-select" required>@foreach($years as $x)<option value="{{ $x->id }}" @selected(old('academic_year_id',$grade->academic_year_id)==$x->id)>{{ $x->name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Semester</label><select name="semester_id" class="form-select" required>@foreach($semesters as $x)<option value="{{ $x->id }}" @selected(old('semester_id',$grade->semester_id)==$x->id)>{{ $x->name }}</option>@endforeach</select></div>
                <div class="col-md-3"><label class="form-label">Nilai Pengetahuan</label><input type="number" step=".01" min="0" max="100" name="knowledge_score" class="form-control" value="{{ old('knowledge_score',$grade->knowledge_score) }}" required></div>
                <div class="col-md-3"><label class="form-label">Nilai Keterampilan</label><input type="number" step=".01" min="0" max="100" name="skill_score" class="form-control" value="{{ old('skill_score',$grade->skill_score) }}" required></div>
                <div class="col-12"><label class="form-label">Deskripsi / Catatan Capaian</label><textarea name="description" class="form-control" rows="3">{{ old('description',$grade->description) }}</textarea></div>
            </div>
            <div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Simpan Draft</button><a href="{{ route('grades.index') }}" class="btn btn-light">Batal</a></div>
        </form>
    </div>
</div>
@endsection