<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::route('/*', function () {

    if (
        strpos(Flight::request()->url, "/auth/login") === 0 ||
        strpos(Flight::request()->url, "/auth/register") === 0
    ) {
        return TRUE;
    } else {
        try {
            $token = Flight::request()->getHeader("Authentication");
            if (!$token)
                Flight::halt(401, "Missing authentication header");

            $decoded_token = JWT::decode($token, new Key(JWT_SECRET, "HS256"));
            Flight::set("User", $decoded_token->user);
            Flight::set("jwt_token", $token);
            return TRUE;

        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }

});