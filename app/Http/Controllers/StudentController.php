<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('name')->get();
        return view('admin.students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:students|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        Student::create($request->all());

        return redirect()->back()->with('success', 'Student registered successfully!');
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:students,phone_number,'.$student->id,
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $student->update($request->all());

        return redirect()->back()->with('success', 'Student details updated!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->back()->with('success', 'Student profile deleted!');
    }
}
