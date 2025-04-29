<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionsController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'items' => 'required|array',
            'items.*.prix' => 'required|numeric|min:100',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.robeId' => 'required|integer',
            'currency' => 'nullable|string|max:3',
        ]);

        $client = auth('sanctum')->user();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client non authentifié',
            ], 401);
        }

        $items = $request->input('items');

        $total = 
        $currency = $request->input('currency', 'XOF');
        $id = Str::uuid();

        // Requête à l'API PayTech
        try {
            $response = Http::withHeaders([
                'API_KEY' => 'd8f8ad0c6081458d7100abc07db3400ac1fe27a391531e3bc33fba2003e8e53a',
                'API_SECRET' => '9578673a7a0e17c2d8ef55be7d34aa20fe287f9c957358d0c7a40ca9df329a50',
            ])->post('https://paytech.sn/api/payment/request-payment', [
                'item_name' => "Commande client",
                'item_price' => $total,
                'command_name' => "Paiement via PayTech",
                'currency' => $currency,
                'custom_field' => json_encode([
                    'item_id' => $id,
                    'time_command' => time(),
                    'ip_user' => $request->ip(),
                    'lang' => $request->header('Accept-Language'),
                ]),
                'env' => 'test',
                'ipn_url' => "https://domaine.com/ipn",
                'success_url' => "https://paytech.sn/mobile/success",
                'cancel_url' => "https://paytech.sn/mobile/cancel",
                'ref_command' => $id,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur PayTech', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion avec PayTech.',
            ], 500);
        }

        if ($response->successful()) {
            $paymentUrl = $response->json('redirect_url');
            if (!$paymentUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'URL de paiement non générée.',
                ], 500);
            }

            // Création de la commande
            $commande = Commandes::create([
                'date' => Carbon::now(),
                'total' => $total,
                'statut' => 'EN_ATTENTE',
                'clientId' => $client->id,
            ]);

            foreach ($items as $item){
                $commandeArticle = CommandeArticles::create([
                    'robeId' => $item['robeId'],
                    'quantite' => $item['quantite'],
                    'prixUnitaire' => $item['prix'],
                    'commandeId' => $commande->id,
                ]);
            }

            return response()->json([
                'payment_url' => $response->json('redirect_url'),
            ]);
        }
        else {
            // Erreurs lors de la requête
            $statusCode = $response->status();
            $responseBody = $response->json();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du paiement.',
                'status_code' => $statusCode,
                'response_body' => $responseBody,
            ], 500);
        }
    }


}
