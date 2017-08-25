<?php

/** Confirm a Cognito user (update user status from FORCE_CHANGE_PASSWORD to CONFIRMED) */

require_once __DIR__ . '/vendor/autoload.php';

// Create Cognito provider client
$cognitoProvider = new Aws\CognitoIdentityProvider\CognitoIdentityProviderClient([
    'version' => VERSION,
    'region'  => REGION,
]);

// Log in Cognito user with the temporary credentials
$credentials = [
    'USERNAME' => COGNITO_USER,
    'PASSWORD' => COGNITO_TMP_PASSWORD,
];
$result = $cognitoProvider->adminInitiateAuth([
    'UserPoolId' => COGNITO_POOL_ID,
    'ClientId' => COGNITO_CLIENT_ID,
    'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
    'AuthParameters' => $credentials,
]);

// Log in will fail and instead a NEW_PASSWORD_REQUIRED challenge will be returned

// Respond to the NEW_PASSWORD_REQUIRED to set the new credentials
$newCredentials = [
    'USERNAME' => COGNITO_USER,
    'NEW_PASSWORD' => COGNITO_PASSWORD,
];
$result = $cognitoProvider->respondToAuthChallenge([
    'ChallengeName' => 'NEW_PASSWORD_REQUIRED',
    'ClientId' => COGNITO_CLIENT_ID,
    'Session' => $result['Session'],
    'ChallengeResponses' => $newCredentials,
]);

dump('Cognito user tokens', $result['AuthenticationResult']);
