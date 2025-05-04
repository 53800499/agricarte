<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterSubscription;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletter_subscriptions,email'
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette adresse email est déjà inscrite à notre newsletter.'
                ], 422);
            }
            return back()->with('error', 'Cette adresse email est déjà inscrite à notre newsletter.');
        }

        try {
            // Envoyer l'email de confirmation
            Mail::to($request->email)->send(new NewsletterSubscription());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Merci de votre inscription à notre newsletter !'
                ]);
            }

            return back()->with('success', 'Merci de votre inscription à notre newsletter !');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de l\'inscription.'
                ], 500);
            }
            return back()->with('error', 'Une erreur est survenue lors de l\'inscription.');
        }
    }
}
