<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Student;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{
    public function index()
    {
        $libraryId = Session::get('library_id');
        $students = Student::where('library_id', $libraryId)->orderBy('name')->get();
        return view('admin.students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $libraryId = Session::get('library_id');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('students')->where('library_id', $libraryId)
            ],
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['library_id'] = $libraryId;
        Student::create($data);

        return redirect()->back()->with('success', 'Student registered successfully!');
    }

    public function update(Request $request, Student $student)
    {
        $libraryId = Session::get('library_id');

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('students')->where('library_id', $libraryId)->ignore($student->id)
            ],
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
