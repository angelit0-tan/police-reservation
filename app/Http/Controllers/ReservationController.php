<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use Inertia\Inertia;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::orderBy('created_at', 'desc')->paginate(10);
        
        return Inertia::render('Index', [
            'reservations' => ReservationResource::collection($reservations)->response()->getData(true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function reservation(Request $request)
    {
        return Inertia::render('Reservation');
    }
}
