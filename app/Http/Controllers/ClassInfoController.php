<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassInfo;

class ClassInfoController extends Controller
{
    public function get() {
        if (auth()->check() && auth()->user()['school_year']) {
            $classInfoList = ClassInfo::where('school_year', auth()->user()['school_year'])->get();
        } else {
            $classInfoList = ClassInfo::all();
        }
        return response($classInfoList);
    }
}