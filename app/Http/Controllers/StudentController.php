<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Repositories\StudentRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Storage;
use Response;

class StudentController extends AppBaseController
{
    /** @var StudentRepository $studentRepository*/
    private $studentRepository;

    public function __construct(StudentRepository $studentRepo)
    {
        $this->studentRepository = $studentRepo;
    }

    /**
     * Display a listing of the Student.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $students = $this->studentRepository->all();

        return view('students.index')
            ->with('students', $students);
    }

    /**
     * Show the form for creating a new Student.
     *
     * @return Response
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created Student in storage.
     *
     * @param CreateStudentRequest $request
     *
     * @return Response
     */
    public function store(CreateStudentRequest $request)
    {
        $input = $request->all();

        // store photo in google drive with specify folder id
        $fileName = $request->file('photo')->store('1vpX-gBIdPS5XtbycqCVG21hBqufwJUxZ', 'google');
        $input['photo'] = Storage::disk('google')->url($fileName);

        $student = $this->studentRepository->create($input);

        Flash::success('Student saved successfully.');

        return redirect(route('students.index'));
    }

    /**
     * Display the specified Student.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $student = $this->studentRepository->find($id);

        if (empty($student)) {
            Flash::error('Student not found');

            return redirect(route('students.index'));
        }

        return view('students.show')->with('student', $student);
    }

    /**
     * Show the form for editing the specified Student.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $student = $this->studentRepository->find($id);

        if (empty($student)) {
            Flash::error('Student not found');

            return redirect(route('students.index'));
        }

        return view('students.edit')->with('student', $student);
    }

    /**
     * Update the specified Student in storage.
     *
     * @param int $id
     * @param UpdateStudentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStudentRequest $request)
    {
        $student = $this->studentRepository->find($id);

        if (empty($student)) {
            Flash::error('Student not found');

            return redirect(route('students.index'));
        }

        $input = $request->all();

        // delete old photo
        $fileId = str_replace('https://drive.google.com/uc?id=', '', $student->photo);
        $fileId = str_replace('&export=media', '', $fileId);
        Storage::disk('google')->delete('1vpX-gBIdPS5XtbycqCVG21hBqufwJUxZ/' . $fileId);
        
        // store photo in google drive with specify folder id
        $fileName = $request->file('photo')->store('1vpX-gBIdPS5XtbycqCVG21hBqufwJUxZ', 'google');
        $input['photo'] = Storage::disk('google')->url($fileName);
        

        $student = $this->studentRepository->update($input, $id);

        Flash::success('Student updated successfully.');

        return redirect(route('students.index'));
    }

    /**
     * Remove the specified Student from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $student = $this->studentRepository->find($id);

        if (empty($student)) {
            Flash::error('Student not found');

            return redirect(route('students.index'));
        }

        // delete photo from google drive in folder id 1vpX-gBIdPS5XtbycqCVG21hBqufwJUxZ
        // get id of photo in google drive link
        $fileId = str_replace('https://drive.google.com/uc?id=', '', $student->photo);
        $fileId = str_replace('&export=media', '', $fileId);
        Storage::disk('google')->delete('1vpX-gBIdPS5XtbycqCVG21hBqufwJUxZ/' . $fileId);

        $this->studentRepository->delete($id);

        Flash::success('Student deleted successfully.');

        return redirect(route('students.index'));
    }
}
