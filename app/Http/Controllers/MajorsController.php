<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MajorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $majors = [
            [
                'id' => 1,
                'code' => 'AKL',
                'name' => 'Akuntansi dan Keuangan Lembaga',
                'description' => 'Program keahlian yang membekali murid dengan kompetensi pencatatan dan pelaporan keuangan.',
            ],
            [
                'id' => 2,
                'code' => 'TKJ',
                'name' => 'Teknik Komputer dan Jaringan',
                'description' => 'Program keahlian yang membekali murid dengan kompetensi instalasi, konfigurasi, dan pemeliharaan jaringan komputer.',
            ],
            [
                'id' => 3,
                'code' => 'BD',
                'name' => 'Bisnis Digital',
                'description' => 'Program keahlian yang membekali murid dengan kompetensi pemasaran dan pengelolaan bisnis berbasis digital.',
            ],
        ];

        return view('majors.index', [
            'title' => 'Sistem Sekolah - Daftar Jurusan',
            'majors' => $majors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
        public function show(string $id)
    {
        $title = "Sistem Sekolah - Detail Jurusan";
        return view('majors.show', [
            'title' => $title,
        ]);
    }

    public function create()
    {
        $title = "Sistem Sekolah - Tambah Jurusan";
        return view('majors.create', [
            'title' => $title,
        ]);
    }


    public function edit(string $id)
    {
        $title = "Sistem Sekolah - Edit Jurusan";
        return view('majors.edit', [
            'title'=> $title
        ]);
    }

    public function store()
    {
        return "melakukan penambahan data jurusan baru";
    }

    public function update(string $id)
    {
        return "mengubah data jurusan dengan ID: {$id}";
    }

    public function destroy(string $id)
    {
        return "menghapus data jurusan dengan ID: {$id}";
    }
}