<?php

namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Auth\Events\Registered;


    class AuthController extends Controller
    {
        // Inscription
        public function register(Request $request)
        {
            // Validation des données de la requête
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|confirmed|min:8',
                'phone' => 'nullable|string|max:20', // Optionnel
                'avatar_url' => 'nullable|string|max:255', // Optionnel
                'role' => 'required|in:' . implode(',', User::ROLES),

            ]);

            // Création de l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone, // Ajout du numéro de téléphone
                'avatar_url' => $request->avatar_url, // Ajout de l'avatar URL
                'role'=>$request->role,
                'two_factor_secret' => null, // Pas de secret pour l'authentification à deux facteurs
                'two_factor_recovery_codes' => null, // Pas de codes de récupération pour l'authentification à deux facteurs
                'two_factor_confirmed_at' => null, // Pas de confirmation de l'authentification à deux facteurs
                'created_at' => now(), // Horodatage de la création
            ]);


            event(new Registered($user));

            return response()->json([
                'message' => 'Compte créé avec succès. Vérifiez votre adresse email pour activer votre compte.'
            ]);
        }

      // Connexion
        public function login(Request $request)
        {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Identifiants incorrects. Veuillez réessayer.',
                ], 401);
            }
    
            //$request->session()->regenerate(); //Regenere la session pour eviter du session fixation (faille de sécurité).

            $user = Auth::user();
    
            return response()->json([
                'message' => 'Connexion réussie.',
                'user' => $user,

            ]);
        }
    
        // Envoi du lien pour la récupération de mot de passe
        public function sendResetLinkEmail(Request $request)
        {
            $request->validate(['email' => 'required|email']);
    
            // Envoie du lien de réinitialisation
            \Illuminate\Support\Facades\Password::sendResetLink($request->only('email'));
    
            return response()->json(['message' => 'Password reset link sent!']);
        }
    
        // Réinitialisation du mot de passe
        public function resetPassword(Request $request)
        {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
            ]);
    
            $resetStatus = \Illuminate\Support\Facades\Password::reset(
                $request->only('email', 'password', 'token'),
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->save();
                }
            );
    
            if ($resetStatus == \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
                return response()->json(['message' => 'Password reset successfully!']);
            }
    
            return response()->json(['error' => 'Failed to reset password.'], 500);
        }

        public function logout(Request $request)
        {
            Auth::guard('api')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['message' => 'Déconnecté']);
        }
}