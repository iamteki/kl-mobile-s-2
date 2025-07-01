<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function quick()
    {
        // Quick booking page - could be a modal or dedicated page
        return view('frontend.booking.quick');
    }
}