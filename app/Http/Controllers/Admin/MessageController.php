<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Notifications\InquiryReply;
use Illuminate\Support\Facades\Notification;

class MessageController extends Controller
{
    /**
     * Display the list of inquiries (Inbox).
     */
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Show a specific message and mark it as read.
     */
    public function show(ContactMessage $message)
{
    // If it hasn't been read yet, mark it as read now
    if (!$message->is_read) {
        $message->update(['is_read' => true]);
    }

    return view('admin.messages.show', compact('message'));
}

    public function reply(Request $request, ContactMessage $message)
{
    $validated = $request->validate([
        'reply_message' => 'required|string|min:10',
    ]);

    // Update the record
    $message->update([
        'reply_message' => $validated['reply_message'],
        'replied_at' => now(),
    ]);

    // Send the email to the traveler
    Notification::route('mail', $message->email)
        ->notify(new InquiryReply($message));

    return back()->with('success', 'Your reply has been sent to ' . $message->name);
}

    /**
     * Delete an inquiry.
     */
    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.inbox')
            ->with('success', 'Message deleted.');
    }
}