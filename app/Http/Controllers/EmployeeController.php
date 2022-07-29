<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MultipleImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::all();
        return view('Employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Employee.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        //validation
        $fields = $req->validate([
            'name' => 'required|string',
            'email' => 'string|email|unique:employees,email|nullable',

            'mobile' => 'required|regex:/^[0-9]{11,11}/|unique:employees,mobile',
            'address' => 'required|string',

            'thumbnail' => 'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        //thumbnail Image
        $thumbnail = $req->file('thumbnail');
        $thumbnailName = hexdec(uniqid()) . '.' . $thumbnail->getClientOriginalExtension();
        Image::make($thumbnail)->resize(1200, 1200)->save('upload/employee/thumbnail/' . $thumbnailName);
        $save_url = 'upload/employee/thumbnail/' . $thumbnailName;

        $EmployeeId = Employee::insertGetId([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'mobile' => $fields['mobile'],
            'address' => $fields['address'],
            'thumbnail_image' => $save_url
        ]);

        //Multiple image uploads
        if ($req->file('multi_img')) {
            $images = $req->file('multi_img');
            foreach ($images as $element) {
                $nameGenrate = hexdec(uniqid()) . '.' . $element->getClientOriginalExtension();
                Image::make($element)->resize(1200, 1200)->save('upload/employee/multiImages/' . $nameGenrate);
                $savePath = 'upload/employee/multiImages/' . $nameGenrate;

                MultipleImage::insert([
                    'employee_id' => $EmployeeId,
                    'image' => $savePath,
                    'created_at' => Carbon::now()
                ]);
            }
        }

        return redirect()->route('employee.index');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $multiImages = MultipleImage::where('employee_id', $id)->get();
        return view('Employee.edit', compact('employee', 'multiImages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        //validation
        $fields = $req->validate([
            'name' => 'required|string',
            'email' => 'string|email|nullable',

            'mobile' => 'required|regex:/^[0-9]{11,11}/',
            'address' => 'required|string',
        ]);

        $previousThumbnail = $req->previous_thumbnail;

        if ($req->file('thumbnail')) {
            unlink($previousThumbnail);

            $thumbnail = $req->file('thumbnail');
            $thumbnailName = hexdec(uniqid()) . '.' . $thumbnail->getClientOriginalExtension();
            Image::make($thumbnail)->resize(1200, 1200)->save('upload/employee/thumbnail/' . $thumbnailName);
            $save_url = 'upload/employee/thumbnail/' . $thumbnailName;

            Employee::findOrFail($id)->update([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'mobile' => $fields['mobile'],
                'address' => $fields['address'],
                'thumbnail_image' => $save_url
            ]);

            return redirect()->route('employee.index');
        } else {
            Employee::findOrFail($id)->update([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'mobile' => $fields['mobile'],
                'address' => $fields['address'],
            ]);

            return redirect()->route('employee.index');
        }
    }

    public function updateMultiImage(Request $req)
    {
        $images = $req->multi_img;
        //dd($images);

        foreach ($images as $key => $value) {
            $imageDelete = MultipleImage::findOrFail($key);
            unlink($imageDelete->image);

            $nameGenrate = hexdec(uniqid()) . '.' . $value->getClientOriginalExtension();
            Image::make($value)->resize(1200, 1200)->save('upload/employee/multiImages/' . $nameGenrate);
            $savePath = 'upload/employee/multiImages/' . $nameGenrate;

            MultipleImage::where('id', '=', $key)->update([
                'image' => $savePath,
                'updated_at' => Carbon::now()
            ]);

            return redirect()->route('employee.index');
        }
    }

    //delete multiimage
    public function deleteImage($id)
    {
        $oldImage = MultipleImage::findOrFail($id);
        unlink($oldImage->image);
        MultipleImage::findOrFail($id)->delete();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        unlink($employee->thumbnail_image);

        Employee::findOrFail($id)->delete();

        $multiImages = MultipleImage::where('employee_id', '=', $id)->get();
        foreach ($multiImages as $element) {
            unlink($element->image);
            MultipleImage::where('employee_id', '=', $id)->delete();
        }

        return redirect()->back();
    }

    //delete multiple employee
    public function deleteMultipleEmployee(Request $req){
        $employees = $req->ids;
        
        if($employees){
            foreach($employees as $employee){
                Employee::where('id', '=', $employee)->delete();
            }
            
            $res = [
                'status' => 200,
                'redirect_url' => route('employee.index'),
            ];
            return response()->json($res);
        }

        else{
            $res = [
                'status' => 401,
                'msg' => 'Select element first',
            ];
            return response()->json($res);
        }
        
    }
}
