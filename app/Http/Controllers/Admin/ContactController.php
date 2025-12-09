<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.admin.contact', [
            'contacts' => ContactMessage::orderByDesc('created_at')->get(),
        ]);
    }

    public function markAsRead(ContactMessage $contact)
    {
        $contact->update(['status' => 'answered']);
        return back()->with('success', 'Đã đánh dấu tin nhắn là đã xem.');
    }

    public function destroy(ContactMessage $contact)
    {
        $contact->delete();
        return back()->with('success', 'Đã xóa tin nhắn liên hệ.');
    }
}
