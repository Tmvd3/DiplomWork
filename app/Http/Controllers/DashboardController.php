<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();


        $cars = $user->cars()->with('parts')->get();


        $totalCars = $cars->count();
        $totalMileage = $cars->sum('current_mileage');


        $partsNeedingReplacement = 0;
        $partsWarning = 0;
        $partsGood = 0;

        foreach ($cars as $car) {
            foreach ($car->parts as $part) {
                switch ($part->status) {
                    case 'danger':
                        $partsNeedingReplacement++;
                        break;
                    case 'warning':
                        $partsWarning++;
                        break;
                    case 'good':
                        $partsGood++;
                        break;
                }
            }
        }


        $recentUpdates = $cars->map(function ($car) {
            return [
                'car' => $car->brand . ' ' . $car->model,
                'mileage' => $car->current_mileage,
                'updated_at' => $car->updated_at->format('d.m.Y'),
            ];
        })->take(5);

        return view('dashboard', compact(
            'cars',
            'totalCars',
            'totalMileage',
            'partsNeedingReplacement',
            'partsWarning',
            'partsGood',
            'recentUpdates'
        ));
    }
}
