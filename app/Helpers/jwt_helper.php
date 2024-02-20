<?php
function getJWTFromRequest($authenticationHeader):string {    
    if (is_null($authenticationHeader)) {
        throw new Exception('Missinf or invalid JWRT in request');
    }    
    return explode(' ',$authenticationHeader)[1];
}
 
function validateJWTFromRequest(string $encodedToken){
    $key =  \Config\Services::getSecretKey();
    $jwtKey = new \Firebase\JWT\Key($key, 'HS256');
    $jwt = new \Firebase\JWT\JWT;
    
    $decodedToken = $jwt::decode($encodedToken, $jwtKey);

    $userModel = new \App\Models\UsuarioModel();
    $userModel->findUserByEmailAddress($decodedToken->email);
    
}

function getSignedJWTForUser(string $email):string{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;
    $payload = [
        'email' => $email,
        'iat' => $issuedAtTime,
        'exp' => $tokenExpiration
    ];
    $key = \Config\Services::getSecretKey();
    $jwt = \Firebase\JWT\JWT::encode($payload, $key,'HS256');

    return $jwt;
}

