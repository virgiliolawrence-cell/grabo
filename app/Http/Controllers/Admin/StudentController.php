<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::query()
            ->when($request->string('q')->trim()->value(), fn ($query, $q) => $query
                ->where(fn ($sub) => $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('nis', 'like', "%{$q}%")))
            ->when($request->string('kelas')->trim()->value(), fn ($query, $k) => $query->where('class', $k))
            ->withCount('orders')
            ->orderBy('class')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.students.index', [
            'students' => $students,
            'classes' => Student::query()->distinct()->orderBy('class')->pluck('class'),
            'totalBalance' => Student::sum('balance'),
        ]);
    }

    public function create(): View
    {
        return view('admin.students.form', ['student' => new Student(['is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Student::create($this->validated($request));

        return redirect()->route('admin.students.index')->with('status', 'Siswa ditambahkan.');
    }

    public function edit(Student $student): View
    {
        return view('admin.students.form', ['student' => $student]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $student->update($this->validated($request, $student));

        return redirect()->route('admin.students.index')->with('status', "Data {$student->name} diperbarui.");
    }

    public function destroy(Student $student): RedirectResponse
    {
        $name = $student->name;
        $student->delete();

        return redirect()->route('admin.students.index')->with('status', "{$name} dihapus dari daftar.");
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?Student $student = null): array
    {
        return $request->validate([
            'nis' => ['required', 'string', 'max:20', Rule::unique('students')->ignore($student?->id)],
            'name' => ['required', 'string', 'max:80'],
            'class' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:120'],
            'balance' => ['required', 'integer', 'min:0', 'max:10000000'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
