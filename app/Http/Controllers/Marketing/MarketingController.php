<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormSubmitted;
use Illuminate\Http\Request;
use App\Models\SubscriptionTier;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MarketingController extends Controller
{
    /**
     * Show the marketing homepage
     */
    public function home()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->with(['primaryImage', 'images' => function($q) {
                $q->orderBy('sort_order')->limit(1);
            }])
            ->limit(3)
            ->get();

        return view('marketing.home', compact('featuredProducts'));
    }

    /**
     * Show the about page
     */
    public function about()
    {
        return view('marketing.about');
    }

    /**
     * Show the features page
     */
    public function features()
    {
        return view('marketing.features');
    }

    /**
     * Show the pricing page
     */
    public function pricing()
    {
        $tiers = SubscriptionTier::active()
            ->ordered()
            ->where('name', '!=', 'Enterprise')
            ->get();

        return view('marketing.pricing', compact('tiers'));
    }

    /**
     * Show the FAQ page
     */
    public function faq()
    {
        return view('marketing.faq');
    }

    /**
     * Show the contact page
     */
    public function contact()
    {
        return view('marketing.contact');
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Send contact form notification to admin
        $adminEmail = config('mail.from.address');
        Mail::to($adminEmail)->send(new ContactFormSubmitted($request->all()));

        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    /**
     * Show the AGB (Terms & Conditions) page
     */
    public function agb()
    {
        return view('marketing.legal.agb');
    }

    /**
     * Show the Impressum (Imprint) page
     */
    public function impressum()
    {
        return view('marketing.legal.impressum');
    }

    /**
     * Show the Privacy Policy page
     */
    public function privacy()
    {
        return view('marketing.legal.privacy');
    }
}
