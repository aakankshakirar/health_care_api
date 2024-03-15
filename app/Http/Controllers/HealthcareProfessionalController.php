<?php

namespace App\Http\Controllers;

use App\Models\HealthcareProfessional;
use Illuminate\Http\Request;

class HealthcareProfessionalController extends Controller
{
     /**
     * Display a listing of the healthcare professionals.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $healthcareProfessionals = HealthcareProfessional::all();
        
        return response()->json($healthcareProfessionals);
    }
}
