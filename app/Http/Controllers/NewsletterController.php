<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterConfirmation;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email:rfc,dns', 'max:255'],
        ]);

        $email = strtolower(trim($request->email));

        $subscriber = NewsletterSubscriber::firstOrNew(['email' => $email]);

        if ($subscriber->exists && $subscriber->isConfirmed()) {
            return response()->json(['status' => 'already_confirmed']);
        }

        $subscriber->confirmation_token = Str::random(64);
        $subscriber->unsubscribe_token = $subscriber->unsubscribe_token ?? Str::random(64);
        $subscriber->source = $request->input('source', 'footer');
        $subscriber->save();

        Mail::to($email)->send(new NewsletterConfirmation($subscriber));

        return response()->json(['status' => 'ok']);
    }

    public function confirm(string $token)
    {
        $subscriber = NewsletterSubscriber::where('confirmation_token', $token)->firstOrFail();

        if (! $subscriber->isConfirmed()) {
            $subscriber->confirmed_at = now();
            $subscriber->save();
        }

        return redirect()->route('home')
            ->with('newsletter_confirmed', true);
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

        if (! $subscriber->isUnsubscribed()) {
            $subscriber->unsubscribed_at = now();
            $subscriber->save();
        }

        return view('public.newsletter-unsubscribed');
    }
}
