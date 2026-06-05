<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('tenant.reviews.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.reviews.index');
    }
}
