<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class PembimbingController extends Controller
{
    public function index(request $request)
    {
        $role = session('role');

        if ($role !== 'petugas') {
            return redirect()->route('dashboard');
        }

        $search = $request->input('search');

        $pembimbings = User::where('role', 'pembimbing')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->with('courses')
            ->get();

        return view('admin.pembimbing', compact('pembimbings', 'search'));
    }

    public function addPembimbing(Request $request)
    {
        $request->validate([
            'nim' => 'required',
        ]);

        try {
            $user = User::where('nim', $request->nim)->firstOrFail();

            if ($user->role === 'pembimbing') {
                return back()->withErrors(['error' => 'User is already a pembina.']);
            }

            $user->role = 'pembimbing';
            $user->save();

            return redirect()->back()->with('success', 'User role updated to pembina successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'User with the specified NIK|NIP was not found.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function destroyPembimbing(Request $request)
    {
        $request->validate([
            'nim' => 'required',
        ]);

        try {
            $user = User::where('nim', $request->nim)->firstOrFail();

            if ($user->role !== 'pembimbing') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The user is not a pembina.',
                ], 400);
            }

            $user->role = 'mente';
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'The pembina role has been removed successfully.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while removing the pembina role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
