<?php

function generateJWTToken($headers, $payload, $secret = 'secret') {
	$headersEncoded = encodeURL(json_encode($headers));	
	$payloadEncoded = encodeURL(json_encode($payload));	
	$signature = hash_hmac('SHA256', "$headersEncoded.$payloadEncoded", $secret, true);
	$signature_encoded = encodeURL($signature);	
	$jwt = "$headersEncoded.$payloadEncoded.$signature_encoded";	
	return $jwt;
}

function jwtValiditatonCheck($jwt, $secret = 'secret') {
	$tokenParts = explode('.', $jwt);
	$header = base64_decode($tokenParts[0]);
	$payload = base64_decode($tokenParts[1]);
	$signature_provided = $tokenParts[2];$expiration = json_decode($payload)->exp;
	$isTokenExpired = ($expiration - time()) < 0;
	$base64URLHeader = encodeURL($header);
	$base64URLPayload = encodeURL($payload);
	$signature = hash_hmac('SHA256', $base64URLHeader . "." . $base64URLPayload, $secret, true);
	$base64URLSignature = encodeURL($signature);
	$isSignatureValid = ($base64URLSignature === $signature_provided);
	if ($isTokenExpired || !$isSignatureValid) {
		return FALSE;
	} else {
		return TRUE;
	}
}

function encodeURL($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}