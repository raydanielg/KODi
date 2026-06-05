<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RentCollectionController extends Controller
{
    public function index()
    {
        return view('landlord.rent-collections.index');
    }

    public function history()
    {
        return view('landlord.rent-collections.history');
    }
}
