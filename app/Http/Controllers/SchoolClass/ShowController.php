<?php

namespace App\Http\Controllers\SchoolClass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $id)
    {
        $title = "Sistem Sekolah - Detail Jurusan";
        return view('classes.show', [
            'title' => $title,
        ]);
    }
}
