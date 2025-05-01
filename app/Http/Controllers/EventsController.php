<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    /**
     * Liste tous les événements.
     */
    public function index()
    {
        $events = Event::latest()
        ->get()
        ->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'location' => $event->location,
                'event_date' => $event->event_date,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'ticket_price' => $event->ticket_price,
                'banner_url' => $event->banner_url,
                'is_active' => $event->is_active,
                'organizer_name' => $event->organizer ? $event->organizer->name : 'Inconnu', // Récupère le nom de l'organisateur
                'capacity' => $event->capacity,
                'category' => $event->category,
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Liste des événements récupérée avec succès.',
            'data' => $events
        ]);
    }

    /**
     * Affiche un événement spécifique.
     */
    public function show($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => "Événement avec l'identifiant $id introuvable.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Événement récupéré avec succès.',
            'data' => $event
        ], 200);
    }

    /**
     * Supprime un événement spécifique.
     */
    public function eventDelete($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => "Échec de la suppression : événement avec l'identifiant $id introuvable.",
            ], 404);
        }

        try {
            $event->delete();

            return response()->json([
                'success' => true,
                'message' => "Événement avec l'identifiant $id supprimé avec succès.",
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Une erreur est survenue lors de la suppression de l'événement.",
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
