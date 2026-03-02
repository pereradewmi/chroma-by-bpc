<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{
    public function sendContactMail(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:5000',
        ]);

        try {
            Mail::to('chromabybpc@gmail.com')->send(new ContactUsMail($validated));

            return redirect()
                ->route('frontend.contact')
                ->with('success', 'Your message has been sent successfully.');
        } catch (\Exception $e) {
            $logReference = 'MAIL-' . now()->format('YmdHis');

            Log::error('Contact form mail send failed', [
                'reference' => $logReference,
                'error' => $e->getMessage(),
                'email' => $validated['email'],
            ]);

            return redirect()
                ->route('frontend.contact')
                ->withInput()
                ->with('error', 'Failed to send your message. Please try again.')
                ->with('error_detail', "{$logReference}: {$e->getMessage()}");
        }
    }
}
