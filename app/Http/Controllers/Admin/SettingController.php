<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
  public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        // Logic to save settings to a table or a JSON file
        // For now, we'll just redirect back with success
        return back()->with('success', 'Settings updated successfully!');
    }
}
