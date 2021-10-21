<?php

if (class_exists('Google_Client')) {
    // Prevent error with preloading in PHP 7.4
    // @see https://github.com/googleapis/google-api-php-client/issues/1976
    return;
}

$classMap = [
    'RexGoogle\\Client' => 'Google_Client',
    'RexGoogle\\Service' => 'Google_Service',
    'RexGoogle\\AccessToken\\Revoke' => 'Google_AccessToken_Revoke',
    'RexGoogle\\AccessToken\\Verify' => 'Google_AccessToken_Verify',
    'RexGoogle\\Model' => 'Google_Model',
    'RexGoogle\\Utils\\UriTemplate' => 'Google_Utils_UriTemplate',
    'RexGoogle\\AuthHandler\\Guzzle6AuthHandler' => 'Google_AuthHandler_Guzzle6AuthHandler',
    'RexGoogle\\AuthHandler\\Guzzle7AuthHandler' => 'Google_AuthHandler_Guzzle7AuthHandler',
    'RexGoogle\\AuthHandler\\Guzzle5AuthHandler' => 'Google_AuthHandler_Guzzle5AuthHandler',
    'RexGoogle\\AuthHandler\\AuthHandlerFactory' => 'Google_AuthHandler_AuthHandlerFactory',
    'RexGoogle\\Http\\Batch' => 'Google_Http_Batch',
    'RexGoogle\\Http\\MediaFileUpload' => 'Google_Http_MediaFileUpload',
    'RexGoogle\\Http\\REST' => 'Google_Http_REST',
    'RexGoogle\\Task\\Retryable' => 'Google_Task_Retryable',
    'RexGoogle\\Task\\Exception' => 'Google_Task_Exception',
    'RexGoogle\\Task\\Runner' => 'Google_Task_Runner',
    'RexGoogle\\Collection' => 'Google_Collection',
    'RexGoogle\\Service\\Exception' => 'Google_Service_Exception',
    'RexGoogle\\Service\\Resource' => 'Google_Service_Resource',
    'RexGoogle\\Exception' => 'Google_Exception',
];

foreach ($classMap as $class => $alias) {
    class_alias($class, $alias);
}

/**
 * This class needs to be defined explicitly as scripts must be recognized by
 * the autoloader.
 */
class Google_Task_Composer extends \RexGoogle\Task\Composer
{
}