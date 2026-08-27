<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $title = "Sistem Sekolah - Daftar Siswa";
        $students = [
            [
                'id' => 1,
                'nis' => '2024001',
                'name' => 'Budi Ariyanto',
                'class'=> 'XII AKL 1',
                'major'=> 'AKL',
            ],

             [
                'id' => 2,
                'nis' => '2024002',
                'name' => 'Andi',
                'class'=> 'XII TKJ 1',
                'major'=> 'TKJ',
            ],
        ];
        return view('students.index', [
            'title'=> $title,
            'students' => $students
        ]);
    }

    public function show(string $id)
    {
        $title = "Sistem Sekolah - Detail Siswa";
        return view('students.show', [
            'title' => $title,
        ]);
    }

    public function create()
    {
        $title = "Sistem Sekolah - Tambah Siswa";
        return view('students.create', [
            'title' => $title,
        ]);
    }


    public function edit(string $id)
    {
        $title = "Sistem Sekolah - Edit Siswa";
        return view('students.edit', [
            'title'=> $title
        ]);
    }

    public function store()
    {
        return "melakukan penambahan data siswa baru";
    }

    public function update(string $id)
    {
        return "mengubah data siswa dengan ID: {$id}";
    }

    public function destroy(string $id)
    {
        return "menghapus data siswa dengan ID: {$id}";
    }
}
