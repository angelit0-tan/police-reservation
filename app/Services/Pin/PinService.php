<?php

namespace App\Services\Pin;

use Carbon\Carbon;
use App\Models\Reservation;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class PinService
{
    const PIN_LENGTH = 6;
    const QUEUE_MINUTES_PER_PERSON = 2;
    
    private Reservation $reservation;

    /**
     * Generate a unique PIN
     */
    public function generateUniquePin(): string
    {
        $numberLists = Arr::shuffle(range(0, 9)); // shuffle the list of array
        $randomPin = Arr::random($numberLists, self::PIN_LENGTH); // select 6 random number
        $pin = implode('', $randomPin);

        // If pin exist, generate again until it's unique
        while (Reservation::where('pin', $pin)->exists()) {
            return $this->generateUniquePin();
        }

        return $pin;
    }

    /**
     * Confirm reservation with PIN
     */
    public function confirmReservation(string $pin): array
    {
        $this->reservation = Reservation::where('pin', $pin)->firstOrFail();

        $this->extendPinForQueue()
            ->isPinValid($pin)
            ->confirmPin();

        return [
            'success' => true,
            'message' => 'Reservation confirmed'
        ];
    }

    /**
     * Check for pin validity
     */
    public function isPinValid(): self {

        $now = Carbon::now();
        $validFrom = Carbon::parse($this->reservation->pin_valid_from);
        $validUntil = Carbon::parse($this->reservation->pin_valid_until);
        $confirmedAt = Carbon::parse($this->reservation->confirmed_at);
       
        if (!empty($this->reservation->confirmed_at)) {
            abort(Response::HTTP_BAD_REQUEST, "PIN already confirmed at {$confirmedAt}");
        }

        if ($now->greaterThan($validUntil)) {
            abort(Response::HTTP_BAD_REQUEST, "PIN Expired at {$validUntil}");
        }

        if ($now->lessThan($validFrom)) {
            abort(Response::HTTP_BAD_REQUEST, "PIN not active, please wait until {$validFrom}");
        }

        return $this;
    }
    
    /*
    * Pin queue extension
    * Queue detection
    * Count confirmed pin from the reservation->pin_valid_from to now
    * Let say each confirmed pin takes 2 minutes per person, it will ads up as minutes and will be added as new valid until
    */
    public function extendPinForQueue(): self  {
        $validFrom = Carbon::parse($this->reservation->pin_valid_from);
        $validUntil = Carbon::parse($this->reservation->pin_valid_until);
        $now = Carbon::now();

        $confirmedCount = Reservation::whereNotNull('confirmed_at')
                        ->whereBetween('confirmed_at', [$validFrom, $now])
                        ->count();

        // If found any confirmed pins and pin not yet confirmed
        if ($confirmedCount && !$this->reservation->confirmed_at) {
            $newPinValidUntil = $validUntil->copy()->addMinutes($confirmedCount * self::QUEUE_MINUTES_PER_PERSON);

            // Make sure that
            // now > valid until = means expired
            // valid until < new pin valid until = old pin valid until vs new pin valid until
            // now < new pin valid until = new pin valid until is not expired
            if ($now->greaterThan($validUntil) && $validUntil->lessThan($newPinValidUntil) && $now->lessThan($newPinValidUntil)) {
                $this->reservation->pin_valid_until = $newPinValidUntil;
                $this->reservation->save();
            }
        }
        
        return $this;
    }

    /**
     * Confirm Pin
     */
    public function confirmPin() : void {
        $now = Carbon::now();
        $this->reservation->confirmed_at = $now;
        $this->reservation->save();
    }
}