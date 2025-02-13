<?php

namespace App\Http\Controllers;

use App\Services\AppTopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppTopController extends Controller
{
    private $appTopService;

    public function __construct(AppTopService $appTopService)
    {
        $this->appTopService = $appTopService;
    }

    public function getTopCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        $date = $request->query('date');
        return $this->appTopService->getTopByDate($date);
    }
}

