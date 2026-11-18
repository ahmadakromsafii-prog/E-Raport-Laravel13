<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\ProfileController;


Route::get('/', fn() => auth()->check() ? redirect()->route('dashboard') : redirect()->route('login'));
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::get('/rapor', [ReportController::class, 'index'])->name('reports.index');
    Route::middleware('role:guru,admin,super_admin')->prefix('rapor/olah')->name('report-cards.')->group(function () {
        Route::get('/', [ReportCardController::class, 'index'])->name('index');
        Route::get('/kelas/{classroom}', [ReportCardController::class, 'classroom'])->name('classroom');
        Route::get('/siswa/{student}', [ReportCardController::class, 'edit'])->name('edit');
        Route::post('/siswa/{student}', [ReportCardController::class, 'save'])->name('save');
        Route::post('/kirim/{reportCard}', [ReportCardController::class, 'submit'])->name('submit');
    });
    Route::get('/rapor/kelas/{classroom}', [ReportController::class, 'classroom'])->name('reports.classroom');
    Route::get('/rapor/{student}', [ReportController::class, 'show'])->name('reports.show');
    Route::middleware('role:guru,admin,super_admin')->group(function () {
        Route::get('/nilai/input-kelas', [GradeController::class, 'bulk'])->name('grades.bulk');
        Route::post('/nilai/simpan-otomatis', [GradeController::class, 'autosave'])->name('grades.autosave');
        Route::post('/nilai/tugas', [GradeController::class, 'addTask'])->name('grades.tasks.add');
        Route::delete('/nilai/tugas/{taskComponent}', [GradeController::class, 'removeTask'])->name('grades.tasks.remove');
    });
    Route::middleware('role:guru')->group(function () {
        Route::get('/guru/nilai', [GradeController::class, 'index'])->name('grades.index');
        Route::get('/guru/nilai/buat', [GradeController::class, 'create'])->name('grades.create');
        Route::post('/guru/nilai', [GradeController::class, 'store'])->name('grades.store');
        Route::get('/guru/nilai/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit');
        Route::put('/guru/nilai/{grade}', [GradeController::class, 'update'])->name('grades.update');
    });
    Route::middleware('role:admin,super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/olah-rapor', [ApprovalController::class, 'reportCards'])->name('report-cards.index');
        Route::post('/olah-rapor/{reportCard}/approve', [ApprovalController::class, 'approveReportCard'])->name('report-cards.approve');
        Route::post('/olah-rapor/{reportCard}/revision', [ApprovalController::class, 'reviseReportCard'])->name('report-cards.revision');
        Route::get('/penugasan-guru', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::post('/penugasan-guru', [AssignmentController::class, 'store'])->name('assignments.store');
        Route::put('/penugasan-guru/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
        Route::delete('/penugasan-guru/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
        Route::get('/biodata', [MasterDataController::class, 'biodata'])->name('biodata');
        Route::post('/bobot-nilai', [GradeController::class, 'saveWeights'])->name('weights.save');
        // URL lama tetap diarahkan ke URL baru.
        Route::get('/master/{type}', function ($type) {
            $paths = ['users' => '/pengguna', 'classrooms' => '/kelas', 'subjects' => '/mata-pelajaran', 'academic-years' => '/tahun-ajaran', 'semesters' => '/semester'];
            abort_unless(isset($paths[$type]), 404);
            return redirect($paths[$type], 301);
        });
    });
});


// Data master memakai URL singkat berbahasa Indonesia dan tetap khusus admin.
Route::middleware(['auth', 'role:super_admin'])->prefix('yayasan')->name('foundation.')->group(function () {
    Route::get('/kepala-sekolah', [MasterDataController::class, 'schoolAdmins'])->name('school-admins.index');
    Route::post('/kepala-sekolah', [MasterDataController::class, 'storeSchoolAdmin'])->name('school-admins.store');
    Route::put('/kepala-sekolah/{id}', [MasterDataController::class, 'updateSchoolAdmin'])->name('school-admins.update');
    Route::delete('/kepala-sekolah/{id}', [MasterDataController::class, 'destroySchoolAdmin'])->name('school-admins.destroy');
});

Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/pengguna', fn() => redirect('/pengguna/siswa'))->name('admin.master.users');
    Route::get('/pengguna/{type}', [MasterDataController::class, 'users'])->whereIn('type', ['siswa', 'guru'])->name('admin.master.index');
    Route::post('/pengguna/{type}', [MasterDataController::class, 'storeUser'])->whereIn('type', ['siswa', 'guru'])->name('admin.master.store');
    Route::put('/pengguna/{type}/{id}', [MasterDataController::class, 'updateUser'])->whereIn('type', ['siswa', 'guru'])->name('admin.master.update');
    Route::delete('/pengguna/{type}/{id}', [MasterDataController::class, 'destroyUser'])->whereIn('type', ['siswa', 'guru'])->name('admin.master.destroy');

    Route::get('/kelas', fn() => app(MasterDataController::class)->index('classrooms'))->name('admin.master.classes');
    Route::post('/kelas', fn(\Illuminate\Http\Request $request) => app(MasterDataController::class)->store($request, 'classrooms'))->name('admin.master.classes.store');
    Route::put('/kelas/{id}', fn(\Illuminate\Http\Request $request, $id) => app(MasterDataController::class)->update($request, 'classrooms', (int) $id))->name('admin.master.classes.update');
    Route::delete('/kelas/{id}', fn($id) => app(MasterDataController::class)->destroy('classrooms', (int) $id))->name('admin.master.classes.destroy');

    Route::get('/mata-pelajaran', fn() => app(MasterDataController::class)->index('subjects'))->name('admin.master.subjects');
    Route::post('/mata-pelajaran', fn(\Illuminate\Http\Request $request) => app(MasterDataController::class)->store($request, 'subjects'))->name('admin.master.subjects.store');
    Route::put('/mata-pelajaran/{id}', fn(\Illuminate\Http\Request $request, $id) => app(MasterDataController::class)->update($request, 'subjects', (int) $id))->name('admin.master.subjects.update');
    Route::delete('/mata-pelajaran/{id}', fn($id) => app(MasterDataController::class)->destroy('subjects', (int) $id))->name('admin.master.subjects.destroy');

    Route::get('/tahun-ajaran', fn() => app(MasterDataController::class)->index('academic-years'))->name('admin.master.years');
    Route::post('/tahun-ajaran', fn(\Illuminate\Http\Request $request) => app(MasterDataController::class)->store($request, 'academic-years'))->name('admin.master.years.store');
    Route::put('/tahun-ajaran/{id}', fn(\Illuminate\Http\Request $request, $id) => app(MasterDataController::class)->update($request, 'academic-years', (int) $id))->name('admin.master.years.update');
    Route::delete('/tahun-ajaran/{id}', fn($id) => app(MasterDataController::class)->destroy('academic-years', (int) $id))->name('admin.master.years.destroy');

    Route::get('/semester', fn() => app(MasterDataController::class)->index('semesters'))->name('admin.master.semesters');
    Route::post('/semester', fn(\Illuminate\Http\Request $request) => app(MasterDataController::class)->store($request, 'semesters'))->name('admin.master.semesters.store');
    Route::put('/semester/{id}', fn(\Illuminate\Http\Request $request, $id) => app(MasterDataController::class)->update($request, 'semesters', (int) $id))->name('admin.master.semesters.update');
    Route::delete('/semester/{id}', fn($id) => app(MasterDataController::class)->destroy('semesters', (int) $id))->name('admin.master.semesters.destroy');
});
