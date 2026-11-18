<?php

namespace App\Http\Controllers;

use App\Models\TeacherAssignment;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->integer('per_page', 20), [10, 20, 50, 100], true)
            ? $request->integer('per_page', 20) : 20;

        // Satu baris paginator = satu guru. Seluruh mapel/kelas guru dimuat di bawah namanya.
        $teachers = User::where('role', 'guru')
            ->whereHas('teachingAssignments')
            ->with(['teachingAssignments' => function ($query) {
                $query->with(['classroom', 'subject'])->orderBy('subject_id')->orderBy('classroom_id');
            }])
            ->withCount('teachingAssignments')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $teacherOptions = User::where('role', 'guru')->orderBy('name')->get();
        $classes = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.assignments', compact('teachers', 'teacherOptions', 'classes', 'subjects', 'perPage'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => ['required', Rule::exists('users', 'id')->where('role', 'guru')],
            'classroom_ids' => ['required', 'array', 'min:1'],
            'classroom_ids.*' => ['integer', 'distinct', 'exists:classrooms,id'],
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $teacher = User::where('role','guru')->findOrFail($data['teacher_id']);
        foreach ($data['classroom_ids'] as $classId) {
            $class = Classroom::findOrFail($classId);
            abort_unless($teacher->school_level === $class->school_level, 422, 'Guru dan kelas harus berada pada jenjang yang sama.');
        }

        $created = 0;
        DB::transaction(function () use ($data, &$created) {
            foreach ($data['classroom_ids'] as $classId) {
                $assignment = TeacherAssignment::firstOrCreate([
                    'teacher_id' => $data['teacher_id'],
                    'classroom_id' => $classId,
                    'subject_id' => $data['subject_id'],
                ]);
                if ($assignment->wasRecentlyCreated) $created++;
            }
        });

        return back()->with('success', $created
            ? "$created penugasan kelas berhasil ditambahkan."
            : 'Semua kombinasi kelas dan mata pelajaran tersebut sudah pernah ditugaskan.');
    }

    public function update(Request $request, TeacherAssignment $assignment)
    {
        $data = $request->validate([
            'teacher_id' => ['required', Rule::exists('users', 'id')->where('role', 'guru')],
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $teacher = User::where('role','guru')->findOrFail($data['teacher_id']);
        $class = Classroom::findOrFail($data['classroom_id']);
        abort_unless($teacher->school_level === $class->school_level, 422, 'Guru dan kelas harus berada pada jenjang yang sama.');

        $duplicate = TeacherAssignment::where($data)
            ->where('id', '!=', $assignment->id)->exists();

        if ($duplicate) return back()->withErrors(['assignment' => 'Penugasan tersebut sudah ada.']);

        $assignment->update($data);
        return back()->with('success', 'Penugasan berhasil diperbarui.');
    }

    public function destroy(TeacherAssignment $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'Penugasan dihapus.');
    }
}
