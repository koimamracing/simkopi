<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = Staff::query();

        if ($request->search) {
            $query->where('full_name', 'like', '%' . $request->search . '%');
        }

        $staff = $query->latest()->paginate(5);

        $totalpekerja = Staff::count();

        $totalAdmin = Staff::where('jabatan', 'Admin')->count();

        $totalStaff = Staff::where('jabatan', 'Staff')->count();

        return view('menu.staff', compact(
            'staff',
            'totalpekerja',
            'totalAdmin',
            'totalStaff'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required|in:Admin,Staff',
            'no_telp' => 'required',
            'email' => 'required',
            'foto' => 'nullable|image'
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')
                ->store('staff', 'public');
        }

        Staff::create([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'foto' => $foto
        ]);

        return back()->with('success', 'Staff berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required|in:
            Admin,
            Staff',
            'no_telp' => 'required',
            'email' => 'email',
            'foto' => 'nullable|image'
        ]);

        $foto = $staff->foto;

        if ($request->hasFile('foto')) {

            if ($staff->foto) {
                Storage::disk('public')->delete($staff->foto);
            }

            $foto = $request->file('foto')
                ->store('staff', 'public');
        }

        $staff->update([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'foto' => $foto
        ]);

        return back()->with('success', 'Staff berhasil diupdate');
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('menu.staff_edit', compact('staff'));
    }
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);

        if ($staff->foto) {
            Storage::disk('public')->delete($staff->foto);
        }

        $staff->delete();

        return back()->with('success', 'Staff berhasil dihapus');
    }
}
