<?php

namespace App\Http\Controllers;

use App\Models\PCRMember;
use App\Models\PCRChapter;
use App\Models\MembershipType;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\PCRMail;
use App\Mail\ThankYouMail;
use Ideapay\samplefolder;
use Illuminate\Support\Facades\Storage;

class PCRMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAllPending()
    {
        $data = PCRMember::where('email', '!=', '')->where('is_declined', 0)
                        ->where('is_approved', 0)
                        ->orderBy('last_name', 'asc')
                        ->latest()
                        ->paginate(20);
        return response()->json($data);
    }

    public function getAllApproved()
    {
        $model = new PCRMember;
        $data = $model->getAllApproved();

        return response()->json($data);
    }

    public function getAllApprovedNoPagination()
    {
        $data = PCRMember::where('is_approved', 1)
            ->orderBy('last_name', 'asc')
            ->latest()->get();

        return response()->json($data);
    }

    public function approveMember(Request $request)
    {
        $data = PCRMember::find($request->id);
        if($data) {
            $data->is_approved = 1;
            $data->save();
            //email verification
            Mail::to($data->email)->send(new PCRMail($data));
            return response()->json($data);
        }
        return response()->json(['error' => 'Member not found.'], 400);
    }

    public function declineMember($id)
    {
        $data = PCRMember::find($id);
        if($data) {
            $data->is_declined = 1;
            $data->save();
            //email verification
            // Mail::to($data->email)->send(new PCRMail($data));
            return response()->json($data);
        }
        return response()->json(['error' => 'Member not found.'], 400);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:pcr_members',
            'password' => 'required',
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'prc_number' => 'required',
            'contact_number' => 'required',
            'address' => 'required'
        ]);
        $messages = [
            'required' => 'The :email :password :first_name :middle_name :last_name :contact_number field is required.',
            'unique' => 'The :email needs to be unique'
        ];
        if ($validator->fails()) {
            // return redirect('post/create')->withErrors($validator)->withInput();
            $errors = $validator->errors();
            return response()->json(['error' => $errors], 403);
        }
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email_exist = User::where('email', $request->input('email'))->exists();

        $data = PCRMember::where(function($query) use ($first_name, $last_name) {
            $query->where('first_name', 'like', "%$first_name%")->where('last_name', 'like', "%$last_name%");
        })->exists();

        if ($data) {
            $new_request = $request;
            unset($new_request->password);
            $member = PCRMember::where(function($query) use ($first_name, $last_name) {
                $query->where('first_name', 'like', "%$first_name%")->where('last_name', 'like', "%$last_name%");
            })->update([
                'email' => $request->input('email'),
                'first_name' => $first_name,
                'middle_name' => $request->input('middle_name'),
                'last_name' => $last_name,
                'prc_number' => $request->input('prc_number'),
                'contact_number' => $request->input('contact_number'),
                'address' => $request->input('address'),
                'mem_type_id' => $request->input('mem_type_id'),
                'chapter_id' => $request->input('chapter_id'),
                'is_trainee' => $request->input('is_trainee'),
                'memberships' => $request->input('memberships'),
                'training_ins' => $request->input('training_ins'),
                'is_approved' => 1
            ]);
            $pcr_member = PCRMember::where('email', $request->input('email'))->first();
            if ($file = $request->file('profile_pic')) {
                $path = $file->store('public/files');
                $name = $file->getClientOriginalName();
                $url = Storage::url($name);
                //store your file into directory and db
                if($email_exist == false) {
                    $user = $pcr_member->user()->create([
                        'email' => $request->input('email'),
                        'password' => Hash::make($request->input('password')),
                        'profile_pic' => $path,
                        'role' => 'pcr'
                    ]);
                    Mail::to($request->input('email'))->send(new PCRMail($pcr_member));
                    return response()->json([
                        'status_code' => 201,
                        'user' => $user,
                        'pcr' => $pcr_member
                    ], 201);
                }
                else {
                    $user = User::where('email', $request->email)->update([
                        'role' => 'pcr/attendee'
                    ]);
                    Mail::to($request->input('email'))->send(new PCRMail($pcr_member));
                    return response()->json([
                        'status_code' => 201,
                        'pcr' => $pcr_member
                    ], 201);
                }

            }
            else {
                if($email_exist == false) {
                    $user = $pcr_member->user()->create([
                        'email' => $request->input('email'),
                        'password' => Hash::make($request->input('password')),
                        'role' => 'pcr'
                    ]);
                    Mail::to($request->input('email'))->send(new PCRMail($pcr_member));
                    return response()->json([
                        'status_code' => 201,
                        'user' => $user,
                        'pcr' => $pcr_member
                    ], 201);
                }
                else {
                    $user = User::where('email', $request->email)->update([
                        'role' => 'pcr/attendee'
                    ]);
                    Mail::to($request->input('email'))->send(new PCRMail($pcr_member));
                    return response()->json([
                        'status_code' => 201,
                        'pcr' => $pcr_member
                    ], 201);
                }
            }
        }
        else {
            $member = PCRMember::create($request->all());
            $pcr_member = PCRMember::find($member->id);

            if ($file = $request->file('profile_pic')) {
                $path = $file->store('public/files');
                $name = $file->getClientOriginalName();
                $url = Storage::url($name);
                //store your file into directory and db
                if($email_exist == false) {
                    $user = $pcr_member->user()->create([
                        'email' => $request->input('email'),
                        'password' => Hash::make($request->input('password')),
                        'profile_pic' => $path,
                        'role' => 'pcr'
                    ]);
                    Mail::to($request->input('email'))->send(new ThankYouMail($pcr_member));
                    return response()->json([
                        'status_code' => 201,
                        'pcr' => $pcr_member
                    ], 201);
                }
                else {
                    $user = User::where('email', $request->email)->update([
                        'role' => 'pcr/attendee'
                    ]);
                    Mail::to($request->input('email'))->send(new ThankYouMail($pcr_member));
                    return response()->json([
                        'status_code' => 201,
                        'pcr' => $pcr_member
                    ], 201);
                }
            }
            else {
                if($email_exist == false) {
                    $user = $pcr_member->user()->create([
                        'email' => $request->input('email'),
                        'password' => Hash::make($request->input('password')),
                        'role' => 'pcr'
                    ]);
                    Mail::to($request->input('email'))->send(new ThankYouMail($pcr_member));
                    return response()->json([
                        'status_code' => 201,
                        'pcr' => $pcr_member
                    ], 201);
                }
                else {
                    $user = User::where('email', $request->email)->update([
                        'role' => 'pcr/attendee'
                    ]);
                    Mail::to($request->input('email'))->send(new ThankYouMail($pcr_member));
                    return response()->json([
                        'status_code' => 201,
                        'pcr' => $pcr_member
                    ], 201);
                }
            }
        }
    }

    public function tagForConvention($id) {
        $application = PCRMember::find($id);
        if ($application->registered_in_convention == false) {
            $application->registered_in_convention = true;
            $application->save();

            $registration = Registration::create([
                'first_name' => $application->first_name,
                'middle_name' => $application->middle_name,
                'last_name' => $application->last_name,
                'contact_number' => $application->contact_number,
                'prc_number' => $application->prc_number,
                'address' => $application->address,
                'reg_type_id' => 1,
                'memberships' => $application->memberships,
                'chapter_id' => $application->chapter_id,
                'prc_upload' => $application->prc_upload,
                'email' => $application->email
            ]);

            $user = User::where('userable_id', $application->id)->first();
            $user->role = 'pcr/attendee';
            $user->save();

            return response()->json([
                "success" => true,
                "message" => "Tagged and registered for convention",
                "registration" => $registration
            ], 201);
        }
        else {
            return response()->json([
                "message" => "PCR Member is already registered for convention",
            ], 400);
        }
    }

    public function getMember($id) {
        $application = PCRMember::find($id);
        if ($application->is_trainee == 1) {
            $application["chapter_id"] = "Not Applicable";
            $application["memberships"] = "Not Applicable";
        }
        $user = User::where('email', $application->email)->first();
        if ($user->userable_type == "App\Models\Registration") {
            // dd(config('app.mem_url'));
            $explode_path = explode('/', $user->profile_pic);
            unset($explode_path[0]);
            $implode_path = implode('/', $explode_path);
            $application['profile_pic'] = config('app.conv_url') ."storage/" . $implode_path;
            return response()->json($application, 200);
        }
        else {
            $explode_path = explode('/', $user->profile_pic);
            unset($explode_path[0]);
            $implode_path = implode('/', $explode_path);
            $application['profile_pic'] = url("storage/" . $implode_path);
            return response()->json($application, 200);
        }
    }

    public function getMemberViaEmail(Request $request) {
        $application = PCRMember::where('email', $request->email)->first();
        $application['conv_url'] =  config('app.conv_url');
        if ($application->is_trainee == 1) {
            $application["chapter_id"] = "Not Applicable";
            $application["memberships"] = "Not Applicable";
        }
        $user = User::where('email', $application->email)->first();
        if($user->profile_pic){
            if ($user->userable_type == "App\Models\Registration") {
                // dd(config('app.mem_url'));
                $explode_path = explode('/', $user->profile_pic);
                unset($explode_path[0]);
                $implode_path = implode('/', $explode_path);
                $application['profile_pic'] = config('app.conv_url') ."storage/" . $implode_path;
                return response()->json($application, 200);
            }
            else {
                $explode_path = explode('/', $user->profile_pic);
                unset($explode_path[0]);
                $implode_path = implode('/', $explode_path);
                $application['profile_pic'] = url("storage/" . $implode_path);
                return response()->json($application, 200);
            }
        }else{
            $application['profile_pic'] = null;
            return response()->json($application, 200);
        }

    }

    public function searchMember(Request $request) {
        try {
            $keyword = $request->keyword;
            $members = PCRMember::where(function($query) use ($keyword) {
                $query->where('first_name', 'like', "%$keyword%")
                ->orWhere('last_name', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%");
            })->get();

            return response()->json($members);
        }
        catch (exception $e) {
            return response()->json(['message' => $e]);
        }
    }

    public function updateMember(Request $request, $id) {
        try {
            $profile = PCRMember::find($id);
            $profile->update($request->all());
            return response()->json($profile, 202);
        }
        catch (exception $e) {
            return response()->json(['message' => $e]);
        }
    }

    public function uploadDepositSlip(Request $request, $id) {
        $profile = PCRMember::find($id);
        $file = $request->file('deposit_slip');
        $path = $file->store('public/payment');
        $name = $file->getClientOriginalName();
        $url = Storage::url($name);

        $profile->update([
            'deposit_slip' => $path
        ]);

        return response()->json([
            'message' => 'Upload Success'
        ], 201);
    }

}
