<?php

namespace App\Http\Controllers;

use App\Models\employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Fetch all employees from the 'approval' database connection
            $employees = DB::connection('approval')->table('employees')->get();

            // Return employee data for DataTables
            return DataTables::of($employees)
                ->addColumn('action', function ($employee) {
                    $editUrl = route('employee.edit', $employee->id);
                    $deleteUrl = route('employee.destroy', $employee->id);
                    // Mendapatkan lokasi pengguna yang sedang terautentikasi
                    $userLocation = auth()->user()->location;

                    // Mengecek apakah lokasi pengguna adalah Head Office
                    if ($userLocation !== 'Head Office') {
                        // Jika bukan Head Office, nonaktifkan tombol dan form
                        return '
                            <a href="#" class="btn btn-sm btn-secondary mt-3 disabled" aria-disabled="true">Edit</a>
                            <form action="#" method="POST" style="display:inline;">
                                <button type="button" class="btn btn-sm btn-danger mt-3 disabled" aria-disabled="true">Delete</button>
                            </form>
                        ';
                    }

                    // Jika lokasi adalah Head Office, tampilkan tombol dengan fungsi normal
                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-secondary mt-3">Edit</a>
                        <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger mt-3">Delete</button>
                        </form>
                    ';
                })
                ->make(true);
        }

        // Fetch employees for the view if not an AJAX request
        $employees = DB::connection('approval')->select('SELECT * FROM employees');
        // Extract distinct values for each field
        $organizations = collect($employees)->pluck('organization')->unique()->sort()->values();
        $job_positions = collect($employees)->pluck('job_position')->unique()->sort()->values();
        $job_levels = collect($employees)->pluck('job_level')->unique()->sort()->values();
        $branch_names = ['Head Office', 'Office Kendari', 'MLP Site Molore'];

        return view('pages.employee.index', compact('employees', 'organizations', 'job_positions', 'job_levels', 'branch_names'));
    }

    public function store(Request $request)
    {
        // dd($request);

        // Validate the request data
        $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'organization' => 'required|string',
            'job_position' => 'required|string',
            'job_level' => 'required|string',
            'branch_name' => 'required|string',
            'poh' => 'nullable|string',
        ]);

        // Process the data as needed
        // For example, save the data to the database
        DB::connection('approval')->table('employees')->insert([
            'nik' => $request->input('nik'),
            'nama' => $request->input('nama'),
            'organization' => $request->input('organization'),
            'job_position' => $request->input('job_position'),
            'job_level' => $request->input('job_level'),
            'branch_name' => $request->input('branch_name'),
            'poh' => $request->input('poh'),
        ]);

        // Redirect or return a response
        return redirect()->route('employee')->with('success', 'Data saved successfully!');
    }

    public function edit($id)
    {
        $employee = DB::connection('approval')->table('employees')->where('id', $id)->first();

        $employees = DB::connection('approval')->select('SELECT * FROM employees');
        $organizations = array_unique(array_column($employees, 'organization'));
        $job_positions = array_unique(array_column($employees, 'job_position'));
        $job_levels = array_unique(array_column($employees, 'job_level'));
        $branch_names = array_unique(array_column($employees, 'branch_name'));

        return view('pages.employee.edit', compact('employee', 'organizations', 'job_positions', 'job_levels', 'branch_names'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'organization' => 'required|string',
            'job_position' => 'required|string',
            'job_level' => 'required|string',
            'branch_name' => 'required|string',
            'poh' => 'nullable|string',
        ]);

        DB::connection('approval')->table('employees')->where('id', $id)->update([
            'nik' => $request->input('nik'),
            'nama' => $request->input('nama'),
            'organization' => $request->input('organization'),
            'job_position' => $request->input('job_position'),
            'job_level' => $request->input('job_level'),
            'branch_name' => $request->input('branch_name'),
            'poh' => $request->input('poh'),
        ]);

        return redirect()->route('employee')->with('success', 'Data updated successfully!');
    }

    public function destroy($id)
    {
        $employee = employee::findOrFail($id);
        // dd($employee);
        $employee->delete();

        return redirect()->route('employee')->with('success', 'Employee deleted successfully!');
    }
}
