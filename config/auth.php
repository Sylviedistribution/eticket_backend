<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paramètres par défaut de l'authentification
    |--------------------------------------------------------------------------
    |
    | Cette option définit le "guard" d'authentification par défaut et le
    | "broker" de réinitialisation de mot de passe pour votre application.
    | Vous pouvez modifier ces valeurs selon vos besoins, mais elles
    | constituent une bonne base pour la plupart des applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Systèmes d'authentification ("guards")
    |--------------------------------------------------------------------------
    |
    | Ici, vous pouvez définir tous les "guards" d'authentification pour votre
    | application. Une configuration par défaut utilisant les sessions
    | et le provider Eloquent est déjà fournie.
    |
    | Chaque guard utilise un "provider" qui définit comment les utilisateurs
    | sont récupérés dans votre base de données ou autre système de stockage.
    |
    | Supports : "session", "token", "sanctum", "jwt"
    |
    */

    

    /*
    |--------------------------------------------------------------------------
    | Fournisseurs d'utilisateurs ("providers")
    |--------------------------------------------------------------------------
    |
    | Tous les guards d'authentification utilisent un provider pour
    | déterminer comment les utilisateurs sont récupérés.
    |
    | Si vous avez plusieurs tables ou modèles utilisateurs, vous pouvez
    | définir plusieurs providers. Ils peuvent ensuite être associés à
    | n’importe quel guard que vous avez défini.
    |
    | Supports : "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // Exemple de provider avec accès direct à la base de données
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Réinitialisation des mots de passe
    |--------------------------------------------------------------------------
    |
    | Ces options définissent le comportement du système de réinitialisation
    | de mot de passe de Laravel, notamment la table utilisée pour stocker
    | les tokens et le provider d’utilisateurs concerné.
    |
    | L’expiration est la durée en minutes pendant laquelle un token
    | est valide. Cela permet de limiter leur durée de vie pour plus
    | de sécurité. Vous pouvez l’ajuster si nécessaire.
    |
    | Le paramètre "throttle" empêche la génération abusive de tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Délai d’expiration de la confirmation du mot de passe
    |--------------------------------------------------------------------------
    |
    | Ce paramètre définit en combien de secondes expire une confirmation
    | de mot de passe avant que l’utilisateur doive le ressaisir.
    | Par défaut, ce délai est de trois heures (10800 secondes).
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
