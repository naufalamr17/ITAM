<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DocumentationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Documentation::select('id', 'no', 'judul', 'file_path');

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $editDisabled = Auth::check() && Auth::user()->location != 'Head Office';
                    $deleteDisabled = Auth::check() && Auth::user()->location != 'Head Office';

                    $editButton = $editDisabled
                        ? '<span class="text-muted">—</span>'
                        : '<a href="' . route('documentation.edit', $row->id) . '" class="btn btn-dark btn-sm mt-3">Edit</a>';

                    $deleteButton = $deleteDisabled
                        ? '<span class="text-muted">—</span>'
                        : '<form action="' . route('documentation.destroy', $row->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm mt-3">Delete</button>
                        </form>';

                    return $editButton . ' ' . $deleteButton;
                })
                ->addColumn('file_path', function ($row) {
                    // Generate the URL for the file
                    $fileUrl = asset('storage/' . $row->file_path);

                    // Return a button that opens the file in a new tab
                    return '<a href="' . $fileUrl . '" target="_blank" class="btn btn-info btn-sm mt-3">View File</a>';
                })
                ->rawColumns(['action', 'file_path'])
                ->make(true);
        }

        // Mengembalikan view untuk halaman Documentation
        return view('pages.documentation.index');
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // Limit to 10MB
        ]);

        // Generate the `no` field: 'year/month/documentation/iteration'
        $year = date('Y');
        $month = date('m');

        // Get the last record created this year, and calculate the new iteration
        $lastDocumentation = Documentation::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        if ($lastDocumentation) {
            // Extract the last 3 digits from the previous 'no' and increment
            $lastIteration = (int)substr($lastDocumentation->no, -3);
            $newIteration = str_pad($lastIteration + 1, 3, '0', STR_PAD_LEFT); // Increment and pad with 3 digits
        } else {
            // If no records for this year, start iteration at 001
            $newIteration = '001';
        }

        // Create the 'no' string: 'year/month/documentation/iteration'
        $no = $year . '/' . $month . '/documentation/' . $newIteration;

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('documentations', 'public'); // Store in the 'public/documentations' folder
        }

        // Store the new record in the database
        Documentation::create([
            'no' => $no,
            'judul' => $request->judul,
            'file_path' => $filePath,
        ]);

        // Redirect or return response after successful storage
        return redirect()->back()->with('success', 'Documentation added successfully.');
    }

    public function destroy($id)
    {
        // Find the documentation by ID
        $documentation = Documentation::findOrFail($id);

        // Delete the file from storage
        if (Storage::exists('public/' . $documentation->file_path)) {
            Storage::delete('public/' . $documentation->file_path);
        }

        // Delete the documentation record from the database
        $documentation->delete();

        // Redirect or return response after successful deletion
        return redirect()->back()->with('success', 'Documentation deleted successfully.');
    }
}
