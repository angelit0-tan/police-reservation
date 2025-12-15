<?php

namespace App\Http\Controllers;

use App\Models\Pizza;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\PizzaResource;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pizzas = Pizza::all();
       
        return Inertia::render('Home', [
            'pizzas' => PizzaResource::collection($pizzas)->response()->getData(true)
        ]);
    }

}
