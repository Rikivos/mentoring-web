<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // get announcement
    public function index()
    {
        $announcements = Announcement::all();
        return view('admin.announcement', compact('announcements'));
    }

    //Add Announcement
    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'announcement' => 'required|mimes:pdf|max:10240',
        ]);

        $file = $request->file('announcement');
        $originalFileName = $file->getClientOriginalName();

        $storagePath = storage_path('app/public/announcements');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        $file->move($storagePath, $originalFileName);

        Announcement::create([
            'title' => $request->input('title'),
            'file_path' => $originalFileName,
        ]);

        return redirect()->back()->with('success', 'Announcement uploaded successfully!');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);

        $filePath = storage_path('app/public/announcements/' . $announcement->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $announcement->delete();

        return redirect()->back()->with('success', 'Announcement deleted successfully!');
    }

    // Method untuk update data announcement
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $title = $request->input('title');
        if ($title) {
            $announcement->title = $title;
        }

        if ($request->hasFile('announcement')) {
            if ($announcement->file_path && file_exists(storage_path('app/announcements/' . $announcement->file_path))) {
                unlink(storage_path('app/announcements/' . $announcement->file_path));  // Menghapus file lama
            }

            $file = $request->file('announcement');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('announcements', $fileName);

            $announcement->file_path = $fileName;
        }

        if (!$title && !$request->hasFile('announcement')) {
            return redirect()->back()->with('warning', 'No changes detected.');
        }

        $announcement->save();

        return redirect()->back()->with('success', 'Announcement updated successfully!');
    }

    public function download($fileName)
    {
        $filePath = storage_path('app/public/announcements/' . $fileName);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return response()->json(['message' => 'File not found.'], 404);
    }
}
