<?php

/**
 *  PHP Mikrotik Billing (https://github.com/hotspotbilling/phpnuxbill/)
 *  by https://t.me/ibnux
 **/

$action = $routes['1'];

_log('[Callback] Received callback for action: ' . $action, 'Callback');
_log('[Callback] PAYMENTGATEWAY_PATH: ' . $PAYMENTGATEWAY_PATH, 'Callback');

$gateway_file = $PAYMENTGATEWAY_PATH . DIRECTORY_SEPARATOR . $action . '.php';
_log('[Callback] Looking for file: ' . $gateway_file, 'Callback');
_log('[Callback] File exists: ' . (file_exists($gateway_file) ? 'YES' : 'NO'), 'Callback');

if (file_exists($PAYMENTGATEWAY_PATH . DIRECTORY_SEPARATOR . $action . '.php')) {
    include $PAYMENTGATEWAY_PATH . DIRECTORY_SEPARATOR . $action . '.php';

    $function_name = $action . '_payment_notification';
    _log('[Callback] Looking for function: ' . $function_name, 'Callback');
    _log('[Callback] Function exists: ' . (function_exists($function_name) ? 'YES' : 'NO'), 'Callback');

    if (function_exists($action . '_payment_notification')) {
        run_hook('callback_payment_notification'); #HOOK
        call_user_func($action . '_payment_notification');
        die();
    } else {
        _log('[Callback] Function not found: ' . $function_name, 'Callback');
    }
}

_log('[Callback] Returning 404 - file or function not found', 'Callback');
header('HTTP/1.1 404 Not Found');
echo 'Not Found';
