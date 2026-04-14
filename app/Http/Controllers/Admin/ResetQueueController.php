<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetQueueController extends Controller
{
    public function index()
    {
        return view('admin.reset_queues.index');
    }
}
