<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('instructor.schedules.index');
    }
}