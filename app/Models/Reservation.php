<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $guarded = ['id'];

    public function getPinValidFromAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function getPinValidUntilAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
