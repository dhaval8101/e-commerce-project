<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return successResponse($user, 'User details ');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone'    => 'required',
            'address'  => 'required',
            'city'     => 'required',
            'pin_code' => 'required',
        ]);
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'phone', 'password', 'city', 'address', 'pin_code', 'role']));
        return successResponse($user, 'User update successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return successResponse($user,'User delete successfully');
    }
    //Logout user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "User successfully logged out",
        ]);
        return response()->json(['message' => 'Logged out successfully']);
    }
    //User Change Password
    public function changepassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'current_password'       => 'required|current_password',
            'password'               => 'required|min:8',
            'password_confirmation'  => 'required|same:password',
        ]);
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }
        $user = Auth::user();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'message'  => 'Password changed successfully',
            ]);
        }
        return response()->json([
            'message'     => 'Invalid current password',
        ], 400);
    }
}