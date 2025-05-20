<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('profiles.show', compact('user', 'profile'));
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('profiles.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'academic_profile' => 'nullable|string',
            'skills' => 'nullable|string',
            'certifications' => 'nullable|string',
            'experiences' => 'nullable|string',
            'preferred_location' => 'nullable|string',
            'internship_type' => 'nullable|string',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'certificate' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $profile = Auth::user()->profile;

        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = Auth::id();
        }

        $profile->fill($validated);

        if ($request->hasFile('cv')) {
            $profile->cv_path = $request->file('cv')->store('documents/cv', 'public');
        }
        if ($request->hasFile('cover_letter')) {
            $profile->cover_letter_path = $request->file('cover_letter')->store('documents/cover_letter', 'public');
        }
        if ($request->hasFile('certificate')) {
            $profile->certificate_path = $request->file('certificate')->store('documents/certificate', 'public');
        }

        $profile->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
