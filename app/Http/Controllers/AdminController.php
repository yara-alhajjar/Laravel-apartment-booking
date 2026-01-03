<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function pendingUsers()
    {
        $users = User::where('approval_status', 'pending')->get();
        return response()->json($users);
    }


    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->approval_status = 'approved';
        $user->save();

        return response()->json([
            'message' => 'User approved successfully',
            'user' => $user,
        ]);
    }


    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->approval_status = 'rejected';
        $user->save();

        return response()->json([
            'message' => 'User rejected successfully',
            'user' => $user,
        ]);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
