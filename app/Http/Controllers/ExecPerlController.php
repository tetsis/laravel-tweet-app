<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExecPerlController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $script_path = base_path('perl-scripts/sum.pl');
        $num1 = $request->input('num1');
        $num2 = $request->input('num2');
        $result = exec("/usr/bin/perl $script_path $num1 $num2 2>&1", $output, $exit);
        return $output[0];
    }
}
