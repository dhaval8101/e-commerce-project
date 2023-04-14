<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\SearchableTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; 
use App\Notifications\ResetPasswordNotification;
use App\Models\PasswordReset;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
class Authcontroller extends Controller
{
    use SearchableTrait;
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone'     => 'required',
            'address'     => 'required',
            'city'     => 'required',
            'pin_code'     => 'required',
        ]);
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->pin_code = $request->pin_code;
        $user->password = Hash::make($request->password);
        $user->save();
        Mail::to($user->email)->send(new WelcomeMail($user));
        return successResponse($user,'User create successfully');
    }
    //login User 
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "email"    => "required",
            "password" => "required||min:8",
        ]);
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }
        $request->only('email', 'password');
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('Token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }
        return response()->json([
            'message' => 'Invalid user data'
        ], 401);
    }
    //forogt password link send mailtrap
    public function forgotPasswordLink(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }
        $user = User::where('email', $request->email)->first();
        $token = Str::random(16);
        $user->notify(new ResetPasswordNotification($token));
        PasswordReset::create([
            'token' => $token,
            'email' => $request->email
        ]);
        return response()->json([
            'message' => 'Password reset email has been sent successfully'
        ]);
        
    }
      // user forgot password
      public function forgotPassword(Request $request)
      {
        $validation = Validator::make($request->all(), [
            'token'     => 'required|exists:password_resets,token',
            'password'  => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }
          $passwordReset = PasswordReset::where('token', $request->token)->first();
          $user = User::where('email', $passwordReset->email)->first();
          $user->update([
              'password'  => Hash::make($request->password)
          ]);
          return 'Password Changed Successfully';    
      }
      //search and pagination
      public function index()
      {
          $this->ListingValidation();
          $query = User::query();
          $searchable_fields = ['name'];
          $data = $this->serching($query, $searchable_fields);
          return response()->json([
              'success' => true,
              'data'    => $data['query']->get(),
              'total'   => $data['count']
          ]);
      }
}