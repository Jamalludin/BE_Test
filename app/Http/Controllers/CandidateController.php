<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

use Symfony\Component\HttpFoundation\Response;

use App\Models\Candidate;
use App\Models\Organization;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maleCount = Candidate::where('gender', 'L')->count();
        $femaleCount = Candidate::where('gender', 'P')->count();
        $totalCandidate = Candidate::count('id');
        $candidateList = Candidate::select('*')->get();

        $response = [
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'totalCandidate' => $totalCandidate,
            'candidateList' => $candidateList
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'city_of_birth' => 'required',
            'identity_number' => 'required',
            'gender' => 'required',
            'identity_file' => 'required|image:jpeg,png,jpg',
            'date_of_birth' => 'required',
            'bank_id' => 'required',
            'bank_name' => 'required',
            'bank_account' => 'required',
            'religion_id' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required|numeric',
            'education_id' => 'required|numeric',
            'university_id' => 'required|numeric',
            'major' => 'required',
            'graduation_year' => 'required',
            'semester' => 'required|numeric',
            'education_id' => 'required',
            'university_id' => 'required',
            'semester' => 'required',
            'graduation_year' => 'required',
            'in_college' => 'required',
            'major' => 'required',
            'org_name' => 'required',
            'year' => 'required',
            'position' => 'required',
            'description' => 'required',
            'organization_file' => 'mimes:pdf',
            'skill' => 'required',
            'file_cv' => 'required|mimes:pdf',
            'file_photo' => 'required|image:jpeg,png,jpg'
        ]);

        if($validator -> fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{

            $file_name_ktp = $request ->name . '-' . $request->file('identity_file')->getClientOriginalName();
            $request->file('identity_file')->move(public_path('uploads/images/ktp'), $file_name_ktp);
            $file_path = 'uploads/images/ktp' . $file_name_ktp;

            $file_name_org = $request ->name . '-' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/pdf/org'), $file_name_org);
            $file_path_org = 'uploads/pdf/org' . $file_name_org;

            $file_name_cv = $request ->name . '-' . $request->file('file_cv')->getClientOriginalName();
            $request->file('file_cv')->move(public_path('uploads/pdf/cv'), $file_name_cv);
            $file_path_cv = 'uploads/pdf/cv' . $file_name_cv;
            
            $file_name_photo = $request ->name . '-' . $request->file('file_photo')->getClientOriginalName();
            $request->file('file_photo')->move(public_path('uploads/images/photo'), $file_name_photo);
            $file_path_photo = 'uploads/images/org' . $file_name_photo;

            $file_name_port = $request ->name . '-' . $request->file('file_portfolio')->getClientOriginalName();
            $request->file('file_portfolio')->move(public_path('uploads/pdf/portfolio'), $file_name_port);
            $file_path_port = 'uploads/pdf/portfolio' . $file_name_port;
            
            //$candidates = Candidate::create($request->all());
            
            $candidates = Candidate::create([
                'id' => $request['id'],
                'name' => $request['name'],
                'gender' => $request['gender'],
                'city_of_birth' => $request['city_of_birth'],
                'date_of_birth' => $request['date_of_birth'],
                'religion_id' => $request['religion_id'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'identity_number' => $request['identity_number'],
                'identity_file' => $file_path,
                'bank_id' => $request['bank_id'],
                'bank_account' => $request['bank_account'],
                'bank_name' => $request['bank_name'],
                'address' => $request['address'],
                'education_id' => $request['education_id'],
                'university_id' => $request['university_id'],
                'university_other' => $request['university_other'],
                'major' => $request['major'],
                'graduation_year' => $request['graduation_year'],
                'in_college' => $request['in_college'],
                'semester' => $request['semester'],
                'skill' => $request['skill'],
                'file_cv' => $file_path_cv,
                'file_photo' => $file_path_photo,
                'file_portfolio' => $file_path_port,
                'source_information_id' => $request['source_information_id'],
                'source_information_other' => $request['source_information_other'],
                'instagram' => $request['instagram'],
                'twitter' => $request['twitter'],
                'linkedin' => $request['linkedin'],
                'facebook' => $request['facebook']
            ]);

            $organization = Organization::create([

                'id' => $candidates['id'],
                'candidate_id' => $request['id'],
                'org_name' => $request['org_name'],
                'year' => $request['year'],
                'position' => $request['position'],
                'description' => $request['description'],
                'file' => $file_path_org,
            ]);

            $response = [
                'message' => 'Candidate Add',
                'data' => [$candidates, $organization]
            ];

            return response()->json($response, Response::HTTP_CREATED);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed ' . $e ->errorInfo
            ]);
        }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $candidates = Candidate::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'city_of_birth' => 'required',
            'identity_number' => 'required',
            'gender' => 'required',
            'identity_file' => 'required|image:jpeg,png,jpg',
            'date_of_birth' => 'required',
            'bank_id' => 'required',
            'bank_name' => 'required',
            'bank_account' => 'required',
            'religion_id' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required|numeric',
            'education_id' => 'required|numeric',
            'university_id' => 'required|numeric',
            'major' => 'required',
            'graduation_year' => 'required',
            'semester' => 'required|numeric',
            'education_id' => 'required',
            'university_id' => 'required',
            'semester' => 'required',
            'graduation_year' => 'required',
            'in_college' => 'required',
            'major' => 'required',
            'org_name' => 'required',
            'year' => 'required',
            'position' => 'required',
            'description' => 'required',
            'organization_file' => 'mimes:pdf',
            'skill' => 'required',
            'file_cv' => 'required|mimes:pdf',
            'file_photo' => 'required|image:jpeg,png,jpg'
        ]);

        if($validator -> fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{

            $file_name_ktp = $request ->name . '-' . $request->file('identity_file')->getClientOriginalName();
            $request->file('identity_file')->move(public_path('uploads/images/ktp'), $file_name_ktp);
            $file_path = 'uploads/images/ktp' . $file_name_ktp;

            $file_name_org = $request ->name . '-' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/pdf/org'), $file_name_org);
            $file_path_org = 'uploads/pdf/org' . $file_name_org;

            $file_name_cv = $request ->name . '-' . $request->file('file_cv')->getClientOriginalName();
            $request->file('file_cv')->move(public_path('uploads/pdf/cv'), $file_name_cv);
            $file_path_cv = 'uploads/pdf/cv' . $file_name_cv;
            
            $file_name_photo = $request ->name . '-' . $request->file('file_photo')->getClientOriginalName();
            $request->file('file_photo')->move(public_path('uploads/images/photo'), $file_name_photo);
            $file_path_photo = 'uploads/images/org' . $file_name_photo;

            $file_name_port = $request ->name . '-' . $request->file('file_portfolio')->getClientOriginalName();
            $request->file('file_portfolio')->move(public_path('uploads/pdf/portfolio'), $file_name_port);
            $file_path_port = 'uploads/pdf/portfolio' . $file_name_port;

            $candidates->identity_file = $file_path;
            $candidates->file_cv = $file_path_cv;
            $candidates->file_photo = $file_path_photo;
            $candidates->file_portfolio = $file_path_port;

            $candidates->update([
                'name' => $request['name'],
                'gender' => $request['gender'],
                'city_of_birth' => $request['city_of_birth'],
                'date_of_birth' => $request['date_of_birth'],
                'religion_id' => $request['religion_id'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'identity_number' => $request['identity_number'],
                'identity_file' => $file_path,
                'bank_id' => $request['bank_id'],
                'bank_account' => $request['bank_account'],
                'bank_name' => $request['bank_name'],
                'address' => $request['address'],
                'education_id' => $request['education_id'],
                'university_id' => $request['university_id'],
                'university_other' => $request['university_other'],
                'major' => $request['major'],
                'graduation_year' => $request['graduation_year'],
                'in_college' => $request['in_college'],
                'semester' => $request['semester'],
                'skill' => $request['skill'],
                'file_cv' => $file_path_cv,
                'file_photo' => $file_path_photo,
                'file_portfolio' => $file_path_port,
                'source_information_id' => $request['source_information_id'],
                'source_information_other' => $request['source_information_other'],
                'instagram' => $request['instagram'],
                'twitter' => $request['twitter'],
                'linkedin' => $request['linkedin'],
                'facebook' => $request['facebook']
            ]);

            $candidates_org = Organization::where('candidate_id', $id);
            

            if(!!$candidates_org) {
                $organization = Organization::create([
                    'id' => $candidates['id'],
                    'candidate_id' => $request['id'],
                    'org_name' => $request['org_name'],
                    'year' => $request['year'],
                    'position' => $request['position'],
                    'description' => $request['description'],
                    'file' => $file_path_org,
                ]);
            } else {
                $organization = Organization::where('candidate_id', $id)->update([
                    'org_name' => $request['org_name'],
                    'year' => $request['year'],
                    'position' => $request['position'],
                    'description' => $request['description'],
                    'file' => $file_path_org,
                ]);
            }

            $response = [
                'message' => 'Candidate Updated',
                'data' => [$candidates, $organization]
            ];

            return response()->json($response, Response::HTTP_OK);
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed ' . $e ->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
