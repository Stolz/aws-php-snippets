<?php

/** Programatically log in a Cognito user and retrieve its information */

require_once __DIR__ . '/vendor/autoload.php';

// Create Cognito provider client
$cognitoProvider = new Aws\CognitoIdentityProvider\CognitoIdentityProviderClient([
    'version' => VERSION,
    'region'  => REGION,
]);

// Log in Cognito user and get authentication tokens
$userCredentials = [
    'USERNAME' => COGNITO_USER,
    'PASSWORD' => COGNITO_PASSWORD,
];
$result = $cognitoProvider->adminInitiateAuth([
    'UserPoolId' => COGNITO_POOL_ID,
    'ClientId' => COGNITO_CLIENT_ID,
    'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
    'AuthParameters' => $userCredentials,
]);
$tokens = $result->get('AuthenticationResult');
dump('Cognito user tokens', $tokens);

// Get user info
$user = $cognitoProvider->getUser([
    'AccessToken' => $tokens['AccessToken']
]);
dump('Cognito user', $user);
