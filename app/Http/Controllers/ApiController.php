<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.api:api', ['except' => ['login', 'signup', 'getCarrots', 'resetPassword']]);
    }

    protected function guard()
    {
        return Auth::guard('api');
    }

    public function profile()
    {
        $status = true;
        $message = 'Successful.';
        $data = $this->guard()->user();
        return response()->json(compact('status', 'message', 'data'));
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'social_type' => 'required',
                'email' => ['string', 'email', 'unique:users', 'required_if:social_type,normal'],
                'password' => ['required_if:social_type,normal'],
            ],
            [
                'social_type.required' => 'Social type is required',
                'name.required' => 'Name is required',
                'email.required_if' => 'Email is required',
                'password.required_if' => 'Password is required',
                'email.unique' => 'Email already used',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'referral_code' => Str::random(12),
            'date_of_birth' => $request->date_of_birth,
            'korean_level' => $request->korean_level,
            'premium' => $request->premium,
            'social_type' => $request->social_type,
            'social_id' => $request->social_id,
            'device_type' => $request->device_type,
            'password' => Hash::make($request->password),
        ];
        $id = DB::table('users')->insertGetId($data);
        $referral_code = $request->referral_code;

        if (!empty($referral_code)) {
            $referrer = DB::table('users')->where('referral_code', $referral_code)->first();
            DB::table('users')->where('id', $referrer)->update(['carrot_points' => $referrer->carrot_points + 5]);
        }

        if ($request->profile_pic_type == 'external' && !empty($request->profile_pic)) {
            $data = [
                'profile_pic_type' => $request->profile_pic_type,
                'profile_pic' => $request->profile_pic
            ];
            DB::table('users')->where('id', $id)->update($data);
        } else {
            if ($request->hasFile('profile_pic')) {
                $fileName = $request->file('profile_pic')->store('public/profile');
                $fileName = str_replace('public/', 'storage/', $fileName);
                $data = [
                    'profile_pic' => $fileName,
                    'profile_pic_type' => 'internal',
                ];
                DB::table('users')->where('id', $id)->update($data);
            }
        }


        if ($id) {
            $status = true;
            $message = 'Successfully signup.';
            $data = DB::table('users')->where('id', $id)->first();
            return response()->json(compact('status', 'message', 'data'));
        } else {
            $status = false;
            $message = 'Something went wrong';
            return response()->json(compact('status', 'message'));
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required',
                'password' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $credentials = array('email' => $request->email, 'password' => $request->password);
        $user = DB::table('users')->where('email', $request->email)->first();
        if (!empty($user)) {
            $userData = array(
                'user_id' => encrypt($user->id),
            );
        } else {
            $userData = [];
        }
        if (!$token = auth('api')->claims($userData)->attempt($credentials)) {
            $status = false;
            $errors = 'Email and password did not matched';
            return response()->json(compact('status', 'errors'));
        }
        $status = true;
        $user = User::select('*')->where('email', $request->email)->first();
        return response()->json(compact('status', 'user', 'token'));
    }

    public function getCarrots(Request $request)
    {
        $keyword = $request->keyword;
        if (!empty($keyword)) {
            $carrots = DB::table('carrots')
                ->where('title_english', 'like', '%' . $keyword . '%')
                ->where('price', '0')
                ->get();
        } else {
            $carrots = DB::table('carrots')
                ->where('price', '0')
                ->get();
        }
        $status = true;
        return response()->json(compact('status', 'carrots'));
    }

    public function getCategories(Request $request)
    {
        $keyword = $request->keyword;
        if (!empty($keyword)) {
            $categories = DB::table('categories')
                ->where('title_english', 'like', '%' . $keyword . '%')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $categories = DB::table('categories')
                ->orderBy('id', 'desc')
                ->get();
        }
        $status = true;
        return response()->json(compact('status', 'categories'));
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'old_password' => 'required',
                'new_password' => 'required'
            ],
            [
                'old_password.required' => 'Old password is required',
                'new_password.required' => 'New password is required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }

        $user = DB::table('users')
            ->where('id', $this->guard()->user()->id)
            ->first();
        if ($user) {
            $credentials = array(
                'email' => $user->email,
                'password' => $request->old_password,
            );
            if (Auth::attempt($credentials)) {
                $status = true;
                $message = 'Password changed successfully.';
                $password = array(
                    'password' => Hash::make($request->new_password)
                );
                DB::table('users')
                    ->where('id', $this->guard()->user()->id)
                    ->update($password);
                return response()->json(compact('message', 'status'));
            } else {
                $status = false;
                $message = 'Old password is incorrect.';
                return response()->json(compact('status', 'message'));
            }
        } else {
            $status = false;
            $message = 'Old password is incorrect.';
            return response()->json(compact('status', 'message'));
        }
    }

    public function updateProfile(Request $request)
    {
        if ($request->profile_pic_type == 'external' && !empty($request->profile_pic)) {
            $data = [
                'profile_pic_type' => $request->profile_pic_type,
                'profile_pic' => $request->profile_pic,
                'name' => $request->name,
                'email' => $request->email,
                'date_of_birth' => $request->date_of_birth,
                'korean_level' => $request->korean_level,
            ];
        } else {
            if ($request->hasFile('profile_pic')) {
                $fileName = $request->file('profile_pic')->store('public/profile');
                $fileName = str_replace('public/', 'storage/', $fileName);
                $data = [
                    'profile_pic' => $fileName,
                    'profile_pic_type' => 'internal',
                    'name' => $request->name,
                    'email' => $request->email,
                    'date_of_birth' => $request->date_of_birth,
                    'korean_level' => $request->korean_level,
                ];
            } else {
                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'date_of_birth' => $request->date_of_birth,
                    'korean_level' => $request->korean_level,
                ];
            }
        }
        $saved = DB::table('users')->where('id', $this->guard()->user()->id)->update($data);
        if ($saved) {
            $status = true;
            $message = 'Updated successfully.';
            return response()->json(compact('status', 'message'));
        } else {
            $status = false;
            $message = 'Something went wrong!';
            return response()->json(compact('status', 'message'));
        }
    }

    public function getLearnCategories()
    {
        $categories = DB::table('learn_categories')->orderBy('id', 'desc')->get();
        $status = true;
        return response()->json(compact('status', 'categories'));
    }

    public function getLearnSubCategories(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'category_id' => 'required',
            ],
            [
                'category_id.required' => 'Category Id is required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $categories = array();
        $categoriesData = DB::table('learn_subcategories')->orderBy('id', 'desc')->where('category_id', $request->category_id)->get();
        foreach ($categoriesData as $cat){
            $content = DB::table('learn_contents')
                ->orderBy('id', 'desc')->where('category_id', $cat->id)
                ->get();
            $categories[] = array(
                'category' => $cat,
                'contents' => $content
            );
        }
        $status = true;
        return response()->json(compact('status', 'categories'));
    }

    public function getLevels(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'category_id' => 'required',
            ],
            [
                'category_id.required' => 'Category Id is required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $levelsData = DB::table('levels')->orderBy('id', 'desc')->where('category_id', $request->category_id)->get();
        $levels = array();
        foreach ($levelsData as $level){
            $completed = DB::table('user_levels')->where('level_id', $level->id)->where('user_id', $this->guard()->id())->first();
            if ($completed){
                $completeStatus = $completed->completed;
            }else{
                $completeStatus = 'no';
            }
            $levels[] = array(
                'image' => $level->image,
                'title_english' => $level->title_english,
                'title_japanese' => $level->title_japanese,
                'title_french' => $level->title_french,
                'title_spanish' => $level->title_spanish,
                'title_arabic' => $level->title_arabic,
                'premium' => $level->premium,
                'category_id' => $level->category_id,
                'completed' => $completeStatus,
            );
        }
        $status = true;
        return response()->json(compact('status', 'levels'));
    }

    public function getQuestions(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'level_id' => 'required',
            ],
            [
                'level_id.required' => 'Level Id is required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $questionList = DB::table('questions')
            ->where('level_id', $request->level_id)
            ->orderBy('id', 'desc')
            ->get();
        $questions = array();
        foreach ($questionList as $item) {
            $options = DB::table('options')->where('question_id', $item->id)->get();
            $item->options = $options;
            $questions[] = $item;
        }
        $status = true;
        return response()->json(compact('status', 'questions'));
    }

    public function getGifs()
    {
        $gifs = DB::table('gifs')->orderBy('id', 'desc')->get();
        $status = true;
        return response()->json(compact('status', 'gifs'));
    }

    public function updateCarrotPoint(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'point' => 'required',
            ],
            [
                'point.required' => 'Point is required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        DB::table('users')->where('id', $this->guard()->user()->id)->increment('carrot_points', $request->point);
        $totalPoint = DB::table('users')->where('id', $this->guard()->user()->id)->first()->carrot_points;
        $status = true;
        $message = 'Successfully updated';
        return response()->json(compact('status', 'message', 'totalPoint'));
    }

    public function buyCarrot(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'carrot_id' => 'required',
            ],
            [
                'carrot_id.required' => 'Carrot Id is required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $carrot = DB::table('carrots')->where('id', $request->carrot_id)->first();
        if ($carrot) {
            $previousCarrot = DB::table('user_carrots')->where('user_id', $this->guard()->user()->id)->where('carrot_id', $request->carrot_id)->first();
            if (empty($previousCarrot)) {
                $data = array(
                    'user_id' => $this->guard()->user()->id,
                    'carrot_id' => $request->carrot_id
                );
                $saved = DB::table('user_carrots')->insert($data);
                if ($saved) {
                    $status = true;
                    $message = 'Successfully purchased';
                    return response()->json(compact('status', 'message'));
                } else {
                    $status = false;
                    $message = 'Something went wrong';
                    return response()->json(compact('status', 'message'));
                }
            } else {
                $status = false;
                $message = 'Already purchased';
                return response()->json(compact('status', 'message'));
            }
        } else {
            $status = false;
            $message = 'Carrot does not exist';
            return response()->json(compact('status', 'message'));
        }

    }

    public function getUserCarrots()
    {
        $previousCarrot = DB::table('user_carrots')->orderBy('id', 'desc')->where('user_id', $this->guard()->user()->id)->get();
        $userCarrots = array();
        foreach ($previousCarrot as $userCarrot) {
            array_push($userCarrots, $userCarrot->carrot_id);
        }
        $carrots = DB::table('carrots')->where('price', 0)->orWhereIn('id', $userCarrots)->get();
        $status = true;
        return response()->json(compact('status', 'carrots'));
    }

    public function createRoom(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'members' => 'required',
            ],
            [
                'title.required' => 'Title is required',
                'members.required' => 'Title is required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $data = array(
            'creator_id' => $this->guard()->user()->id,
            'members' => $request->members
        );
        $saved = DB::table('rooms')->insert($data);
        if ($saved) {
            $status = true;
            $message = 'Successfully created';
            return response()->json(compact('status', 'message'));
        } else {
            $status = false;
            $message = 'Something went wrong';
            return response()->json(compact('status', 'message'));
        }
    }

    public function getRooms()
    {
        $roomList = DB::table('rooms')->get();
        $rooms = array();
        foreach ($roomList as $item) {
            $userData = DB::table('users')->where('id', $item->creator_id)->first();
            $item->userDetails = $userData;
            $rooms[] = $item;
        }
        $status = true;
        return response()->json(compact('status', 'rooms'));
    }

    public function upgradeMembership(Request $request)
    {

    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required',
            ],
            [
                'email.required' => 'Email is required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = Password::getRepository()->create($user);
            $user->sendPasswordResetNotification($token);
            return response()->json([
                'status' => true,
                'message' => 'Password reset link send into mail.',
                'data' => '']);
        } else {
            $status = false;
            $message = 'No user found with this email';
            return response()->json(compact('status', 'message'));
        }
    }

    public function userList()
    {
        $data = DB::table('users')->where('role', 'user')->orderBy('carrot_points', 'desc')->get();
        $status = true;
        return response()->json(compact('status', 'data'));
    }

    public function learnContents(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'category_id' => 'required',
            ],
            [
                'category_id.required' => 'Category ID is required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $data = DB::table('learn_contents')
            ->where('category_id', $request->category_id)
            ->orderBy('id', 'desc')->get();
        if ($data) {
            $status = true;
            return response()->json(compact('status', 'data'));
        } else {
            $status = false;
            $message = 'No content found';
            return response()->json(compact('status', 'message'));
        }
    }

    public function getBenefits()
    {
        $benefits = DB::table('benefits')->orderBy('id', 'desc')->get();
        $status = true;
        return response()->json(compact('status', 'benefits'));
    }

    public function getLifeJobs()
    {
        $lifejobs = DB::table('life_and_jobs')->orderBy('id', 'desc')->get();
        $status = true;
        return response()->json(compact('status', 'lifejobs'));
    }

    public function markLevelComplete(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'level_id' => 'required',
            ],
            [
                'level_id.required' => 'Level ID is required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $data = DB::table('user_levels')
            ->insert(['user_id' => $this->guard()->user()->id, 'level_id' => $request->level_id, 'completed' => 'yes']);
        if ($data) {
            $status = true;
            return response()->json(compact('status', 'data'));
        } else {
            $status = false;
            $message = 'No content found';
            return response()->json(compact('status', 'message'));
        }
    }

    public function createTicket(Request $request){
        $validator = Validator::make($request->all(),
            [
                'sender_email' => 'required',
                'receiver_email' => 'required',
                'sender_name' => 'required',
                'title' => 'required',
                'description' => 'required',
                'type' => 'required',
            ],
            [
                'sender_email.required' => 'Sender Email is required',
                'receiver_email.required' => 'Receiver Email is required',
                'sender_name.required' => 'Sender Name is required',
                'title.required' => 'Title is required',
                'description.required' => 'Description is required',
                'type.required' => 'Type is required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $status = DB::table('tickets')
            ->insert([
                'sender_email' => $request->sender_email,
                'receiver_email' => $request->receiver_email,
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'ticket_number' => Str::random(6),
            ]);
        if ($status){

        }
    }

}
