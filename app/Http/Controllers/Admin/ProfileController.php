<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\Interfaces\AdminServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $adminService;

    public function __construct(AdminServiceInterface $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Display the admin's profile.
     */
    public function show()
    {
        $admin = Auth::user();
        return view('admin.profile.show', compact('admin'));
    }

    /**
     * Show the form for editing the admin's profile.
     */
    public function edit()
    {
        $admin = Auth::user();
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update the admin's profile.
     */
    public function update(UpdateProfileRequest $request)
    {
        try {
            $admin = Auth::user();
            $validatedData = $request->validated();
            
            $profileImage = null;
            if ($request->hasFile('profile_image')) {
                $profileImage = $request->file('profile_image');
            }

            $this->adminService->updateProfile($admin, $validatedData, $profileImage);

            return redirect()->route('admin.profile.show')
                ->with('success', __('admin.profile.updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
