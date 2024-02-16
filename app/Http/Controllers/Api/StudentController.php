<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Students;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $students = Students::filter($request)
        // ->orWhere('nim', 'LIKE', "%1005%")
        ->get();

        return $this->responseSuccess([
            'students' => $students->load('city:id,name')
        ]);
    }

    public function detail($id)
    {
        $student = Students::find($id);

        return $this->responseSuccess([
            'student' => $student->load('city:id,name')
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => ['required', 'unique:students,nim'],
            'name' => ['required', 'string'],
            'born_date' => ['required', 'date'],
            'gender' => ['required', 'in:P,L'],
            'city_id' => ['required', 'string', 'uuid'],
            'address' => ['required', 'max:1000'],
            'university' => ['required', 'string']
        ]);

        if ($validator->fails()) return $this->responseError($validator->errors()->first());

        $validatedData = $validator->valid();

        $student = $request->user()->students()->create($validatedData);

        if (!$student) return $this->responseError('Student create failed.');

        return $this->responseSuccess('Student successfully created.');
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nim' => ['required', 'numeric', Rule::unique('students', 'nim')->ignore($id)],
            'name' => ['required', 'string'],
            'born_date' => ['required', 'date'],
            'gender' => ['required', 'in:P,L'],
            'city_id' => ['required', 'string', 'uuid'],
            'address' => ['required', 'max:1000'],
            'university' => ['required', 'string']
        ]);

        if ($validator->fails()) return $this->responseError($validator->errors()->first());

        $validatedData = $validator->valid();

        $student = Students::find($id);

        if (!$student) return $this->responseError('Student not found.');

        $validatedData['admin_id'] = $request->user()->id;
        $update = $student->update($validatedData);

        if (!$update) return $this->responseError('Student update failed.');

        return $this->responseSuccess('Student successfully updated.');
    }

    public function delete($id)
    {

        $student = Students::find($id);

        if (!$student) return $this->responseError('Student not found.');

        $delete = $student->delete();

        if (!$delete) return $this->responseError('Student delete failed.');

        return $this->responseSuccess('Student successfully deleted.');
    }
}
