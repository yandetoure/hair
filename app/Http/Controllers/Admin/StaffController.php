<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffMember;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        return view('admin.staff.index', [
            'members' => StaffMember::orderBy('position')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.staff.form', ['member' => new StaffMember(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        StaffMember::create($this->validated($request));

        return redirect()->route('admin.staff.index')->with('success', 'Membre ajouté.');
    }

    public function edit(StaffMember $staff)
    {
        return view('admin.staff.form', ['member' => $staff]);
    }

    public function update(Request $request, StaffMember $staff)
    {
        $data = $this->validated($request);

        if (! $data['photo']) {
            unset($data['photo']);
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('success', 'Membre mis à jour.');
    }

    public function destroy(StaffMember $staff)
    {
        $staff->delete();

        return back()->with('success', 'Membre retiré.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:600'],
            'photo' => ['nullable', 'image', 'max:4096'],
            'position' => ['nullable', 'integer', 'min:0'],
        ], [], ['name' => 'nom']);

        $data['photo'] = $request->hasFile('photo') ? $request->file('photo')->store('staff', 'public') : null;
        $data['is_active'] = $request->boolean('is_active');
        $data['position'] = (int) $request->input('position', 0);

        return $data;
    }
}
