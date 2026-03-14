<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('donate.index', compact('paymentMethods'));
    }
}
