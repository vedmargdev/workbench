<?php

namespace Workbench\OfflineTest\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Workbench\OfflineTest\Models\OfflineTest;
use App\Exports\OfflineTestExports;
use Illuminate\Support\Facades\Http;



class OfflineTestController extends Controller
{

public function home(){
 return view('offlinetest::home');
}

    public function index(Request $request){

        // return 123;
        $response = Http::get('https://devtd17.vedmarg.com/api/get-classes');
        if ($response->successful()) {
            $apiResponse = $response->json(); 
            // dd("response is ",$apiResponse);
        } else {
            $apiResponse = []; 
        }
        $query=request()->get('trashed') ? getOfflineTestTrashed() : getOfflineTest();
        
        if(!empty($request->test_title)){
            $query=$query->filter(function($item) use ($request){
                return $item->test_name==$request->test_title;
            });
        
        }
        if(!empty($request->classes)){
            $query=$query->filter(function($item) use ($request){
                return $item->classes_id==$request->classes;
            });
        }
        if(!empty($request->from_date)){
            $query=$query->filter(function($item) use ($request){
                return $item->date>=$request->from_date;
            });
        }
        if(!empty($request->to_date)){
            $query=$query->filter(function($item) use ($request){
                return $item->date<=$request->to_date;
            });
        }
        $offlineTestDetails=$query;
        if ($request->has('download') && $request->download === 'excel') {
             $timestamp = now()->format('d-m-Y-H-i-s');
            return Excel::download(new OfflineTestExports($offlineTestDetails,$apiResponse), "offline-test-{$timestamp}.xlsx");
        }
        $trashedOfflineTest = !request()->get('trashed') ? getOfflineTestTrashed() : false;
        $total_trashed = !request()->get('trashed') ? $trashedOfflineTest->count() : $offlineTestDetails->count();
        $title = request()->get('trashed') ? 'Deleted Offline Test' : 'Offline Test';
        return view('offlinetest::index',compact('title','total_trashed','offlineTestDetails','apiResponse'));
       
    }
    //-------------------------------------------Store--------------------------------------------//
    public function store(Request $request){
        // dd("All request is ",$request->all());

        // return response()->json(['all' => $request->all()]);
        $userId = getUserId();
        $createdBy = Auth::id();
        $session = getCurrentSession();
        $validator=Validator::make($request->all(),[
           'test_name'=>['required','string'],
           'date'=>['required','date'],
           'from_time'=>['required','date_format:H:i'],
           'to_time'    => ['required', 'date_format:H:i', function ($attribute, $value, $fail) use ($request) {
                if (strtotime($value) <= strtotime($request->from_time)) {
                    $fail('The To Time must be after From Time.');
                }
            }],
           'classes_id'=>['required','string'],
           'section'=>['required','array'],
           'sections_data'=>['nullable','string'],
           'syllabus'=>['nullable','string'],
        ],
        [
            'test_name.required'=>'Test name is required',
            'test_name.string'=>'Test name must be valid string',
            'date.required'=>'Date is required',
            'date.date'=>'Date must be valid date',
            'from_time.required'      => 'From time is required',
            'from_time.date_format'   => 'From time must be a valid time (HH:MM format)',
            'to_time.required'      => 'To time is required',
            'to_time.date_format'   => 'To time must be a valid time (HH:MM format)',
            'classes_id.required'=>'Class is required',
            'classes_id.string'=>'Class must be valid string',
            'section.required'=>'Section is required',
            'section.array'=>'Section must be valid array',
            'syllabus.string'=>'Syllabus must be valid string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated(); 
        $validatedData['section']=json_encode($validatedData['section']);
        // dd("Validated data is ",$validatedData);
        if($request->uuid){
            $offlineTestDetails= getOfflineTest();
            $offlineTestDetail = $offlineTestDetails->where('uuid', $request->uuid)->first();
            if( !$offlineTestDetail ) {
                return response()->json(['status' => 'failed', 'message' => 'Offline test not found.']);
            }
            $offlineTestDetail->update($validatedData);
            $data=$offlineTestDetail;
            return response()->json([
                        'status'  => 'success',
                        'message' => 'Offline test successfully updated!.',
                        'update'    => 1,
                        'data'    => $data
                    ]);
        }
        else{
            // dd("Inside the else condition");
            $validatedData['user_id']=$userId;
            $validatedData['session']=$session;
            $validatedData['created_by']=$createdBy;
            $offlineTestDetail=OfflineTest::Create($validatedData);
            $action = '
                    <a class="btn btn-add" href="' . route('user.offline-test.views', [$offlineTestDetail->uuid]) . '" target="_blank">
                    <img src="' . asset_url('images/icons/eye-white.png') . '">
                    </a>
                    <a  href="javascript:void(0)" data-uuid="'. $offlineTestDetail->uuid .'" class="btn btn-add  edit-offline-test" title="Edit offline test" >
                        <img src="'.asset_url('images/icons/edit-white.png') .'">
                    </a>
                    <a  href="javascript:void(0)" data-uuid="'. $offlineTestDetail->uuid .'" class="btn btn-add bg-danger delete-new-room" title="Delete offline test" >
                        <img src="'.asset_url('images/icons/delete-white.png') .'">
                    </a>';
            $checkbox = '<label for="select-box'.$offlineTestDetail->id.'">
                            <input type="checkbox" class="select--flog-checkbox-" name="select--flog-checkbox-" value="'. $offlineTestDetail->uuid .'">
                        </label>';
            $sectionData=   json_decode($offlineTestDetail->sections_data);  
            $totalSection=count($sectionData);       
            $section='<strong>Total Section: </strong>'.$totalSection;
            $data=[
            'id'            => $offlineTestDetail->id,
            'checkbox'      =>$checkbox,
            'test_name'     =>$offlineTestDetail->test_name ?? '',
            'date'          =>convertDate($offlineTestDetail->date ?? ''),
            'time'          =>$offlineTestDetail->from_time ?? '',
            'class'         =>$offlineTestDetail->classes_id ?? '',
            'section'       =>$section,
            'created_by'    =>$offlineTestDetail->created_by_user->name ?? '',
            'created_date'  => convertDate($offlineTestDetail->created_at),
            'action'        =>$action,
            ]; 
            if($data){
               return response()->json(['status'=>'success','message'=>'Offline test save successfully!','data'=>$data]);
            }
           else{
            return response()->json(['status'=>'failed','message'=>'Failed to save']);
           }          
        }
    }
    //------------------------------------------Edit Pop Up view----------------------------------//
    public function edit(Request $request){
        $response = Http::get('https://devtd17.vedmarg.com/api/get-classes');
        if ($response->successful()) {
            $apiResponse = $response->json(); 
            // dd("response is ",$apiResponse);
        } else {
            $apiResponse = []; 
        }
        $offlineTestDetail = getOfflineTest();
        if( !$request->uuid ) {
            return response()->json(['status'=>'failed', 'message'=> 'Offline test not found.']);
        }
        $offlineTestDetails = $offlineTestDetail->where('uuid', $request->uuid)->first();
        if( !$offlineTestDetails ) {
            return response()->json(['status'=>'failed', 'message'=> 'Offline test not found.']);
        }
        $subjectData = json_decode($offlineTestDetails->sections_data, true);
        $sections = json_decode($offlineTestDetails->section ?? '[]', true);
        $classId=$offlineTestDetails->classes_id ?? '';

        // Fetch subjects for the given class ID & section IDs
        $sectionSubjectMap = [];
        $subjectMap = [];
        if (!empty($classId) && !empty($sections)) {
             foreach($sections as $se){
                $subjectNameResponse = Http::get("https://devtd17.vedmarg.com/api/get-subjects?classes_id={$classId}&sections={$se}");
                $subjectResponse = $subjectNameResponse->successful() ? $subjectNameResponse->json() : [];
                // dd("subjectResponse is ",$subjectResponse);
                if (!empty($subjectResponse['subjects'])) {
                    foreach ($subjectResponse['subjects'] as $subject) {
                        $sectionSubjectMap[$se][$subject['id']] = $subject['name'];
                    }
                }
                if (!empty($subjectResponse['subjects'])) {
                    foreach ($subjectResponse['subjects'] as $subject) {
                        $subjectMap[$subject['id']] = $subject['name'];
                    }
                }
            }   
        }
        // Pass the selected subjects
        $selectedSubjects = [];
        if (!empty($subjectData)) {
            foreach ($subjectData as $section) {
                foreach ($section['subjects'] as $subject) {
                    $selectedSubjects[] = $subject['subject_id'];
                }
            }
        }
        $view = view( _template('user.test.edit-popup') ,compact('offlineTestDetails','apiResponse','subjectData','sections','sectionSubjectMap','selectedSubjects','subjectMap'))->render();
        return response()->json(['status' => 'success', 'view' => $view]);
    }
    //-----------------------------------------Views-----------------------------------------------//
    public function views(Request $request,$uuid){
        $response = Http::get('https://devtd17.vedmarg.com/api/get-classes');
        if ($response->successful()) {
            $apiResponse = $response->json(); 
            // dd("response is ",$apiResponse);
        } else {
            $apiResponse = []; 
        }
        $offlineTests=getOfflineTest();
        $offlineTest=$offlineTests->where('uuid',$uuid)->first();
        if(!$offlineTest){
             return response()->json(['status'=>'failed', 'message'=> 'Offline test not found.']);
        }

        $sections = json_decode($offlineTest->section ?? '[]', true);
        $classId=$offlineTest->classes_id ?? '';
        // Fetch subjects for the given class ID & section IDs
        $sectionSubjectMap = [];
        $subjectMap = [];
        if (!empty($classId) && !empty($sections)) {
             foreach($sections as $se){
                $subjectNameResponse = Http::get("https://devtd17.vedmarg.com/api/get-subjects?classes_id={$classId}&sections={$se}");
                $subjectResponse = $subjectNameResponse->successful() ? $subjectNameResponse->json() : [];
                // dd("subjectResponse is ",$subjectResponse);
                if (!empty($subjectResponse['subjects'])) {
                    foreach ($subjectResponse['subjects'] as $subject) {
                        $sectionSubjectMap[$se][$subject['id']] = $subject['name'];
                    }
                }
                if (!empty($subjectResponse['subjects'])) {
                    foreach ($subjectResponse['subjects'] as $subject) {
                        $subjectMap[$subject['id']] = $subject['name'];
                    }
                }
            }   
        }
        $title='Offline Test Details';
       
       return view(_template('user.test.views'),compact('offlineTest','title','apiResponse','subjectMap','sectionSubjectMap'));
    }
    //------------------------------------------Bulk Marks Data Fetching----------------------------//
    public function section(Request $request,$uuid){
        $response = Http::get('https://devtd17.vedmarg.com/api/get-classes');
        if ($response->successful()) {
            $apiResponse = $response->json(); 
        } else {
            $apiResponse = []; 
        }
        $offlineTests=getOfflineTest();
        $offlineTest=$offlineTests->where('uuid',$uuid)->first();
        if(!$offlineTest){
             return response()->json(['status'=>'failed', 'message'=> 'Offline test not found.']);
        }
        $sections=json_decode($offlineTest->section);
        return view(_template('user.test.section'),compact('offlineTest','sections','apiResponse'));
    }



    public function marks(Request $request){
        $response = Http::get('https://devtd17.vedmarg.com/api/search-students?str=a');
        if ($response->successful()) {
            $apiResponse = $response->json();
            $students = $apiResponse['users'] ?? [];
        } else {
            $students = [];
        }

        if(!$request->uuid){
          return response()->json(['status'=>'failed','message'=>'Offline test not found']);
        }
        $section=$request->sections ?? '';
        $classId=$request->classes_id ?? '';

        $filteredStudents = array_filter($students, function ($student) use ($classId, $section) {
           return $student['classes_id'] == $classId && strtolower($student['section']) == strtolower($section);
        });

        $offlineTests=getOfflineTest();
        $offlineTest=$offlineTests->where('uuid',$request->uuid)->first();
        if(!$offlineTest){
            return response()->json(['status'=>'failed','message'=>'Offline test not found']);
        }
        $sectionsData = json_decode($offlineTest->sections_data, true);
        $filteredSectionsData = [];
        if ($request->filled('sections')) {
            $filteredSectionsData = array_filter($sectionsData, function ($sectionData) use ($request) {
                return $sectionData['section'] === $request->sections;
            });
        } else {
            $filteredSectionsData = $sectionsData;
        }
        
        $title='Marks Update';
        // dd("Filtered students ",$filteredStudents);
        return view(_template('user.test.marks-update'),compact('filteredSectionsData','offlineTest','filteredStudents','title',));
    }
    //-----------------------------------------Delete----------------------------------------------//
    public function destroy(Request $request){
        $request->validate(['remark'    => 'required|string|max:200', 'uuids' => 'required'],
                            ['remark.required'  => 'Remark is required *',
                                'remark.string' => 'Remark must be valid string.',
                                'remark.max'    => 'Remark can have max 200 characters.',
                                'uuids.required'    => 'Oops! something went wrong, please try again later.'
                            ]);
        $uuids = json_decode($request->uuids, true);
        $offlineTestDetails = getOfflineTest();
        $offlineTestDetail = $offlineTestDetails->whereIn('uuid', $uuids);
        if( count($offlineTestDetail) < 1 ) {
            return response()->json(['status' => 'failed', 'message' => 'Offline test could not be deleted.']);
        }
        $ids = $offlineTestDetail->pluck('id')->toArray();
        foreach ($offlineTestDetail as $e) {
            $e->deleted_reasons = strip_tags($request->remark);
            $e->deleted_by = Auth::id();
            $e->save();
            $e->delete();
        }
        return response()->json(['status' => 'success', 'ids' => $ids, 'message' => 'Offline test successfully deleted.']);
    }

    //--------------------------------------------Restore-------------------------------------------//
    public function restore(Request $request){
        if( !$request->uuids ) {
            return response()->json(['status' => 'failed', 'message' => 'Offline test not found.']);
        }
        $uuids = json_decode($request->uuids, true);
        if( !$uuids || count($uuids) < 1 ) {
            return response()->json(['status' => 'failed', 'message' => 'Offline test not found.']);
        }
        $offlineTestDetails = getOfflineTestTrashed();
        $offlineTestDetail = $offlineTestDetails->whereIn('uuid', $uuids);
        if( count($offlineTestDetail) < 1 ) {
            return response()->json(['status' => 'failed', 'message' => 'Offline test could not be deleted.']);
        }
        $ids = $offlineTestDetail->pluck('id')->toArray();
        foreach ($offlineTestDetail as $e) {
            $e->deleted_reasons = null;
            $e->deleted_by = null;
            $e->save();
            $e->restore();
        }
        return response()->json(['status'=>'success', 'ids' => $ids, 'message'=>'Offline test successfully restored!.']);
    }
    
    //---------------------------------------------Force Delete-----------------------------------------//
    public function forceDelete(Request $request){
        if( !$request->uuid ) {
            return response()->json(['status' => 'failed', 'message' => 'Offline test not found.']);
        }
        $offlineTestDetails = getOfflineTestTrashed();
        $offlineTestDetail = $offlineTestDetails->where('uuid', $request->uuid)->first();
        if( !$offlineTestDetail ) {
            return response()->json(['status' => 'failed', 'message' => 'Offline test not found.']);
        }
        $offlineTestDetail->forceDelete();
        return response()->json(['status'=>'success', 'message'=>'Offline test permanent successfully deleted.']);
    }
}
