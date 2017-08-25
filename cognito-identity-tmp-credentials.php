<?php

/** Get Cognito identity temporary credentials for an authenticated Cognito user */

// Log in Cognito user and get authentication tokens
require_once __DIR__ . '/cognito-login-user.php';

// Create Cognito identity client
$cognitoIdentity = new Aws\CognitoIdentity\CognitoIdentityClient([
    'version' => VERSION,
    'region'  => REGION,
]);

// Get Cognito identity ID for the Cognito user
$providerName = sprintf('cognito-idp.%s.amazonaws.com/%s', REGION, COGNITO_POOL_ID);
$logins = [$providerName => $tokens['IdToken']];
$result = $cognitoIdentity->getId([
    'AccountId' => ACCOUNT_ID,
    'IdentityPoolId' => COGNITO_IDENTITY_POOL_ID,
    'Logins' => $logins,
]);
$identityId = $result['IdentityId'];
dump('Cognito identity', $identityId);

// Get temporary credentials for Cognito identity
$result = $cognitoIdentity->getCredentialsForIdentity([
    'IdentityId' => $identityId,
    'Logins' => $logins,
]);
$tmpCredentials = $result['Credentials'];
dump('Cognito identity temporary credentials', $tmpCredentials);
