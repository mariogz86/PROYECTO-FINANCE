<?php
    session_name(APP_SESSION_NAME); 
   // session_start();
    session_start([
    'cookie_secure' => true,    // requiere HTTPS
    'cookie_httponly' => true,
    'cookie_samesite' => 'None' // necesario para ngrok y algunos navegadores móviles
]);
 