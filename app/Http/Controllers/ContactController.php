<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Notifications\NewSafariInquiry;
use App\Notifications\TravelerConfirmation; // Added this
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    /**
     * Show the contact form.
     */
    public function show()
    {
        return view('contact');
    }

    /**
     * Handle the form submission.
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // 1. Save to Database
        $inquiry = ContactMessage::create($validated);

        // 2. Notify the Admin/Staff (Internal)
        $admins = User::role(['super-admin', 'safari-manager'])->get();
        
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewSafariInquiry($inquiry));
        }

        // 3. Send Auto-Responder to Traveler (External)
        // We use Notification::route because the traveler is not necessarily a registered User
        Notification::route('mail', $inquiry->email)
            ->notify(new TravelerConfirmation($inquiry));

        return back()->with('success', 'Thank you! Your safari inquiry has been sent. Check your email for a confirmation.');
    }
}