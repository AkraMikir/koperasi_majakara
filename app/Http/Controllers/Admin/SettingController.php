<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.setting.index');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.setting.index')->with('success', 'Password berhasil diperbarui!');
    }

    public function updatePin(Request $request)
    {
        $request->validate([
            'current_pin' => 'required',
            'pin' => 'required|string|size:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_pin, $user->pin)) {
            return back()->withErrors(['current_pin' => 'PIN saat ini tidak sesuai.']);
        }

        $user->update([
            'pin' => Hash::make($request->pin)
        ]);

        return redirect()->route('admin.setting.index')->with('success', 'PIN berhasil diperbarui!');
    }
}
