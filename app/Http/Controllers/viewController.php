<?php

namespace App\Http\Controllers;

use App\Models\student;
use Illuminate\Http\Request;

class viewController extends Controller
{
    
    public function viewMarks(){
        return view('viewMarks', ['marks' => student::all()]);
    }
}
