<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Http\Controllers\Controller;
use App\Models\EvalYear;
use Illuminate\Http\Request;

class PEAppraisalsController extends Controller
{
  public function displayPEAppraisalsOverview()
  {
    $activeEvalYear = EvalYear::where('status', 'active')->first();

    return view('pe-pages.pe_appraisals_overview', compact('activeEvalYear'));
  }
}