<?php

/** Upload an object to S3 on behalf of a Cognito identity */

// Get Cognito identity temporary credentials for Cognito user
require_once __DIR__ . '/cognito-identity-tmp-credentials.php';

// Create S3 client with the Cognito identity temporary credentials
$s3 = new Aws\S3\S3Client([
    'version' => VERSION,
    'region' => REGION,
    'credentials' => [
        'key' => $tmpCredentials['AccessKeyId'],
        'secret' => $tmpCredentials['SecretKey'],
        'token' => $tmpCredentials['SessionToken'],
    ],
]);

// Upload file to S3 on behalf of the user
$result = $s3->putObject([
    'Bucket' => BUCKET_NAME,
    'Key' => "cognito/user/$identityId/test.txt",
    'Body' => "This file was uploaded by Cognito user with federated identity ID $identityId",
]);
dump('S3 upload result', $result);

/*
IAM Policy that should be attached to the autenticated users role of the identity pool:

    {
        "Version": "2012-10-17",
        "Statement": [
            {
                "Effect": "Allow",
                "Action": [
                    "s3:ListBucket"
                ],
                "Resource": [
                    "arn:aws:s3:::BUCKET_NAME"
                ],
                "Condition": {
                    "StringLike": {
                        "s3:prefix": [
                            "cognito/user/${cognito-identity.amazonaws.com:sub}"
                        ]
                    }
                }
            },
            {
                "Effect": "Allow",
                "Action": [
                    "s3:GetObject",
                    "s3:PutObject"
                ],
                "Resource": [
                    "arn:aws:s3:::BUCKET_NAME/cognito/user/${cognito-identity.amazonaws.com:sub}/*"
                ]
            }
        ]
    }
*/
