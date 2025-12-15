<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Services\Pin\PinService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PinValidationRequest;
use App\Http\Resources\ReservationResource;

class ReservationController extends Controller
{

    protected $pinService;
    
    public function __construct(PinService $pinService)
    {
        $this->pinService = $pinService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reservations = Reservation::orderBy('created_at', 'desc')->paginate(10);
        return response()->json(ReservationResource::collection($reservations)->response()->getData(true));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request)
    {        
        $pin = $this->pinService->generateUniquePin();

        $reserveDateTime = Carbon::parse($request->reservation_time)->setTimezone(config('app.timezone'));

        // Create reservation
        Reservation::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'reservation_time' => $reserveDateTime->format('Y-m-d H:i'),
            'pin_valid_from' => $reserveDateTime->copy()->subMinutes(15)->format('Y-m-d H:i'), // Valid 15 minutes
            'pin_valid_until' => $reserveDateTime->copy()->addMinutes(5)->format('Y-m-d H:i'), // Add 5 minutes grace period
            'pin' => $pin
        ]);
        
        return response()->json([
            'success' => true,
            'pin' => $pin,
            'message' => 'Reservation created successfully. PIN will be active 15 minutes before your reservation.'
        ]);
    }

    public function confirm(PinValidationRequest $request)
    {
        $result = $this->pinService->confirmReservation($request->pin);
        
        return response()->json($result);
    }
}
