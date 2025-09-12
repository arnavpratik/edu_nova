<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', ['users' => $users]);
    }

    
    public function createTeacher()
    {
        return view('admin.users.create-teacher');
    }

    
    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher', 
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'New teacher has been added successfully.');
    }

    public function destroy(User $user)
    {
        
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

       
        $user->delete();

       
        return redirect()->route('admin.users.index')
            ->with('success', 'User has been deleted successfully.');
    }
}
