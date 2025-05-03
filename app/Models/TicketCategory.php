<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TicketCategory extends Model
{
    use HasFactory;

    public function event(){
        return $this->belongsTo(Event::class, 'eventId');
    }
}
