<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TicketCategory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'location',
        'event_date',
        'start_time',
        'end_time',
        'banner_url',
        'is_active',
        'capacity',
        'category',
    ];

    // Relation : Un événement appartient à un organisateur
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    // Relation : Un événement peut avoir plusieurs catégories de tickets
    public function ticketCategories()
    {
        return $this->hasMany(TicketCategory::class);
    }


}
