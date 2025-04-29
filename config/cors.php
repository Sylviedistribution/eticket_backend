<?php 
return [

'paths' => ['api/*', 'login', 'logout', 'register', 'sanctum/csrf-cookie'],

'allowed_methods' => ['*'],

'allowed_origins' => ['http://localhost:8080'], // ou ['http://localhost:5173'] pour un front précis

'allowed_origins_patterns' => [],

'allowed_headers' => ['*'],

'exposed_headers' => [],

'max_age' => 0,

'supports_credentials' => true,

];

?>