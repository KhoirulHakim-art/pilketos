<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CandidateController;
use Illuminate\Support\Facades\Storage;

// Halaman Utama (nanti kita isi untuk home/login)
Route::get('/', function () {
    return view('welcome');
});

// Route untuk Admin DPT & QR Code
Route::get('/admin/students', [StudentController::class, 'index'])->name('admin.students.index');
Route::get('/admin/students/template', [StudentController::class, 'downloadTemplate'])->name('admin.students.template');
Route::post('/admin/students/import', [StudentController::class, 'import'])->name('admin.students.import');
Route::delete('/admin/students/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
Route::delete('/admin/students/clear/all', [StudentController::class, 'clearAll'])->name('admin.students.clear');

// Route Halaman Pemilih (Siswa)
Route::get('/', [VotingController::class, 'scanner'])->name('voting.scanner');
Route::post('/voting/verify', [VotingController::class, 'verify'])->name('voting.verify');
Route::get('/voting/booth', [VotingController::class, 'booth'])->name('voting.booth');
Route::post('/voting/submit', [VotingController::class, 'submit'])->name('voting.submit');
Route::get('/voting/success', [VotingController::class, 'success'])->name('voting.success');

// Route Dashboard Admin Real-Time & Live Voice
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/voting/status', [AdminController::class, 'toggleStatus'])->name('admin.voting.status');
Route::post('/admin/voting/reset', [AdminController::class, 'resetVoting'])->name('admin.voting.reset');

// Route Kelola Candidates
Route::get('/admin/candidates', [CandidateController::class, 'index'])->name('admin.candidates.index');
Route::post('/admin/candidates', [CandidateController::class, 'store'])->name('admin.candidates.store');
Route::delete('/admin/candidates/{id}', [CandidateController::class, 'destroy'])->name('admin.candidates.destroy');

Route::get('/admin/api/unannounced-votes', [AdminController::class, 'getUnannouncedVotes']);
Route::post('/admin/api/mark-announced/{id}', [AdminController::class, 'markAnnounced']);

// Route penanganan gambar jika storage link terblokir (Anti 403)
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*');