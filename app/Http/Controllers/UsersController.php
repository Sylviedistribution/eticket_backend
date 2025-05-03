<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactMessageNotification;


class UsersController extends Controller
{
    
    /**
     * Affiche les informations du profil utilisateur.
     */
    public function profile(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? null,
            ]
        ]);
    }

    
    public function contact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        Notification::route('mail', 'isylvestre757@gmail.com')
                ->notify(new ContactMessageNotification($data['name'], $data['email'], $data['message']));

        return response()->json(['message' => 'Email envoyé avec succès'], 200);
    }
    
        /**
     * Met à jour les informations de l'utilisateur connecté.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only(['name', 'email', 'phone']));

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès.',
            'data' => $user
        ]);
    }

    /**
     * Supprime le compte utilisateur.
     */
    public function delete(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Compte utilisateur supprimé avec succès.'
        ]);
    }
}
