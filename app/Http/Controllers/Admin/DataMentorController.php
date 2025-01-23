<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class DataMentorController extends Controller
{
    //get all mentors
    public function getMentor(Request $request)
    {
        $search = $request->input('search');

        $mentors = User::where('role', 'mentor')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->with('courses')
            ->get();

        return view('admin.mentor', compact('mentors', 'search'));
    }

    //add mentor
    public function addMentor(Request $request)
    {
        $request->validate([
            'nim' => 'required',
        ]);

        try {
            $user = User::where('nim', $request->nim)->firstOrFail();

            if ($user->role === 'mentor') {
                return back()->withErrors(['error' => 'User is already a mentor.']);
            }

            $user->role = 'mentor';
            $user->save();

            return redirect()->back()->with('success', 'User role updated to mentor successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'User with the specified NIM was not found.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    // Edit mentor role
    public function editMentorRole(Request $request)
    {
        $request->validate([
            'nim' => 'required|exists:users,nim',
            'role' => 'required|in:mentor,mente',
        ]);

        try {
            $user = User::where('nim', $request->nim)->firstOrFail();
            $user->role = $request->role;
            $user->save();

            return redirect()->back()->with('success', 'Role has been updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    //Delete Mentor
    public function destroyMentor(Request $request)
    {
        $request->validate([
            'nim' => 'required',
        ]);

        try {
            $user = User::where('nim', $request->nim)->firstOrFail();

            if ($user->role !== 'mentor') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The user is not a mentor.',
                ], 400);
            }

            $user->role = 'mente';
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'The mentor role has been removed successfully.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while removing the mentor role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
