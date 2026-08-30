<?php

use App\Http\Controllers\SchoolClass\CreateController;
use App\Http\Controllers\SchoolClass\DestroyController;
use App\Http\Controllers\SchoolClass\UpdateController;
use App\Http\Controllers\SchoolClass\ShowController;
use App\Http\Controllers\SchoolClass\IndexController;
use App\Http\Controllers\SchoolClass\EditController;
use App\Http\Controllers\SchoolClass\StoreController;
use App\Http\Controllers\MajorsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/menu', function () {
    return view('menu');
})->name('menu');

//Student Action Controller
Route::name('students.')->prefix('students')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('index');

    Route::get('/create', [StudentController::class, 'create'])->name('create');

    Route::post('/', [StudentController::class, 'store'])->name('store');

    Route::get('/{id}', [StudentController::class, 'show'])->name('show');

    Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('edit');

    Route::put('/{id}', [StudentController::class, 'update'])->name('update');

    Route::delete('/{id}', [StudentController::class, 'destroy'])->name('destroy');
});

//Teacher Action Controller
Route::name('teachers.')->prefix('teachers')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');

    Route::get('/create', [TeacherController::class, 'create'])->name('create');

    Route::post('/', [TeacherController::class, 'store'])->name('store');

    Route::get('/{id}', [TeacherController::class, 'show'])->name('show');

    Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('edit');

    Route::put('/{id}', [TeacherController::class, 'update'])->name('update');

    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('destroy');
});

//SchoolClass Invokable
Route::name('classes.')->prefix('classes')->group(function () {
    Route::get('/', IndexController::class)->name('index');

    Route::get('/create', CreateController::class, 'create')->name('create');

        Route::post('/', StoreController::class, 'store')->name('store');

    Route::get('/{id}', ShowController::class, 'show')->name('show');

    Route::get('/{id}/edit', EditController::class, 'edit')->name('edit');

    Route::put('/{id}', UpdateController::class, 'update')->name('update');

    Route::delete('/{id}', DestroyController::class, 'destroy')->name('destroy');
});

Route::resource('majors', MajorsController::class);

