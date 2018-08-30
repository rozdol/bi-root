<?php
use \Firebase\JWT\JWT;

//

$token =
[
    'sub' => $GLOBALS['uid'],
    'unm' => $GLOBALS['username'],
    'exp' => time() + 604800
];

/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */
$jwt = JWT::encode($token, $_ENV[APP_SALT]);
echo $this->html->pre_display($jwt, "jwt");
$decoded = JWT::decode($jwt, $_ENV[APP_SALT], ['HS256']);

echo $this->html->pre_display($decoded, "decoded");
//print_r($decoded);

/*
 NOTE: This will now be an object instead of an associative array. To get
 an associative array, you will need to cast it as such:
*/

$decoded_array = (array) $decoded;
echo $this->html->pre_display($decoded_array, "decoded_array");

/**
 * You can add a leeway to account for when there is a clock skew times between
 * the signing and verifying servers. It is recommended that this leeway should
 * not be bigger than a few minutes.
 *
 * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
 */
JWT::$leeway = 60; // $leeway in seconds
$decoded = JWT::decode($jwt, $_ENV[APP_SALT], ['HS256']);
echo $this->html->pre_display($decoded, "decoded");
echo $this->html->pre_display($_SERVER, "SERVER");
