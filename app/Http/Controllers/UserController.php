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
        $user = User::find($id);
        if (!$user) {
            return errorResponse('User not found', 404);
        }
        return successResponse($user, 'User show successfully');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return errorResponse('User not found');
        }
        $user->update($request->only(['name', 'email', 'phone', 'password', 'city', 'address', 'pin_code', 'role']));
        return successResponse($user, 'User update successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {
            $user->delete();
            return successResponse('User delete successfully');
        }
        return errorResponse('User Already Deleted');
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
            'current_password' => 'required|current_password',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
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
                'message' => 'Password changed successfully',
            ]);
        }
        return response()->json([
            'message' => 'Invalid current password',
        ], 400);
    }
}