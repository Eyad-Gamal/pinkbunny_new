<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EgyptController extends Controller
{
    /**
     * Return the list of areas/districts for a given governorate.
     * Used by Alpine.js fetch in checkout & profile address forms.
     */
    public function areas(Request $request)
    {
        $governorate = $request->query('governorate', '');
        $areas = config("egypt.{$governorate}", []);

        return response()->json($areas);
    }
}
