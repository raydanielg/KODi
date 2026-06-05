<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function database()
    {
        return view('super-admin.system.database');
    }

    public function logs()
    {
        return view('super-admin.system.logs');
    }

    public function queues()
    {
        return view('super-admin.system.queues');
    }

    public function maintenance()
    {
        return view('super-admin.system.maintenance');
    }
}
