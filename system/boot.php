<?php

/**
 * PHP Mikrotik Billing (https://github.com/hotspotbilling/phpnuxbill/)

 **/

try {
    require_once 'init.php';
} catch (Throwable $e) {
    die($e->getMessage() . '<br><pre>' . $e->getTraceAsString() . '</pre>');
} catch (Exception $e) {
    die($e->getMessage() . '<br><pre>' . $e->getTraceAsString() . '</pre>');
}

function _notify($msg, $type = 'e')
{
    $_SESSION['ntype'] = $type;
    $_SESSION['notify'] = $msg;
}

$ui = new Smarty();

// Register classes for use in templates (PHP 8.2+ Smarty deprecation fix)
$ui->registerClass('Text', 'Text');
$ui->registerClass('Lang', 'Lang');
$ui->registerClass('App', 'App');
$ui->registerClass('Validator', 'Validator');

// Register PHP functions as modifiers (PHP 8.2+ Smarty deprecation fix)
$ui->registerPlugin('modifier', 'explode', 'explode');
$ui->registerPlugin('modifier', 'number_format', 'number_format');
$ui->registerPlugin('modifier', 'strtotime', 'strtotime');
$ui->registerPlugin('modifier', 'count', 'count');
$ui->registerPlugin('modifier', 'str_replace', 'str_replace');
$ui->registerPlugin('modifier', 'trim', 'trim');
$ui->registerPlugin('modifier', 'ucwords', 'ucwords');
$ui->registerPlugin('modifier', 'ucWords', 'ucwords');
$ui->registerPlugin('modifier', 'is_file', 'is_file');
$ui->registerPlugin('modifier', 'date', 'date');
$ui->registerPlugin('modifier', 'file_exists', 'file_exists');
$ui->registerPlugin('modifier', 'strpos', 'strpos');
$ui->registerPlugin('modifier', 'urlencode', 'urlencode');
$ui->registerPlugin('modifier', 'htmlspecialchars_decode', 'htmlspecialchars_decode');
$ui->registerPlugin('modifier', 'rand', 'rand');

$ui->assign('_kolaps', $_COOKIE['kolaps'] ?? '');
if (!empty($config['theme']) && $config['theme'] != 'default') {
    $_theme = APP_URL . '/' . $UI_PATH . '/themes/' . $config['theme'];
    $ui->setTemplateDir([
        'custom' => File::pathFixer($UI_PATH . '/ui_custom/'),
        'theme' => File::pathFixer($UI_PATH . '/themes/' . $config['theme']),
        'default' => File::pathFixer($UI_PATH . '/ui/')
    ]);
} else {
    $_theme = APP_URL . '/' . $UI_PATH . '/ui';
    $ui->setTemplateDir([
        'custom' => File::pathFixer($UI_PATH . '/ui_custom/'),
        'default' => File::pathFixer($UI_PATH . '/ui/')
    ]);
}
$ui->assign('_theme', $_theme);
$ui->addTemplateDir($PAYMENTGATEWAY_PATH . File::pathFixer('/ui/'), 'pg');
$ui->addTemplateDir($PLUGIN_PATH . File::pathFixer('/ui/'), 'plugin');
$ui->setCompileDir(File::pathFixer($UI_PATH . '/compiled/'));
$ui->setConfigDir(File::pathFixer($UI_PATH . '/conf/'));
$ui->setCacheDir(File::pathFixer($UI_PATH . '/cache/'));
$ui->assign('app_url', APP_URL);
$ui->assign('_domain', str_replace('www.', '', parse_url(APP_URL, PHP_URL_HOST)));
$ui->assign('_url', APP_URL . '/?_route=');
$ui->assign('_path', __DIR__);
$ui->assign('_c', $config);
// Assign menu hook variables for template access
$ui->assign('_MENU_AFTER_DASHBOARD', $config['_MENU_AFTER_DASHBOARD'] ?? '');
$ui->assign('_MENU_AFTER_CUSTOMERS', $config['_MENU_AFTER_CUSTOMERS'] ?? '');
$ui->assign('_MENU_AFTER_SERVICES', $config['_MENU_AFTER_SERVICES'] ?? '');
$ui->assign('_MENU_AFTER_PLANS', $config['_MENU_AFTER_PLANS'] ?? '');
$ui->assign('_MENU_AFTER_REPORTS', $config['_MENU_AFTER_REPORTS'] ?? '');
$ui->assign('_MENU_AFTER_NETWORKS', $config['_MENU_AFTER_NETWORKS'] ?? '');
$ui->assign('_MENU_AFTER_RADIUS', $config['_MENU_AFTER_RADIUS'] ?? '');
$ui->assign('_MENU_AFTER_PAGES', $config['_MENU_AFTER_PAGES'] ?? '');
$ui->assign('_MENU_AFTER_SETTINGS', $config['_MENU_AFTER_SETTINGS'] ?? '');
$ui->assign('_MENU_AFTER_LOGS', $config['_MENU_AFTER_LOGS'] ?? '');
$ui->assign('_MENU_AFTER_COMMUNITY', $config['_MENU_AFTER_COMMUNITY'] ?? '');
$ui->assign('AFTER_MESSAGE', $config['AFTER_MESSAGE'] ?? '');
$ui->assign('SETTINGS', $config['SETTINGS'] ?? '');
$ui->assign('paginator', $config['paginator'] ?? '');
$ui->assign('user_language', $_SESSION['user_language'] ?? 'english');
$ui->assign('UPLOAD_PATH', str_replace($root_path, '',  $UPLOAD_PATH));
$ui->assign('CACHE_PATH', str_replace($root_path, '',  $CACHE_PATH));
$ui->assign('PAGES_PATH', str_replace($root_path, '',  $PAGES_PATH));
$ui->assign('_system_menu', 'dashboard');

function _msglog($type, $msg)
{
    $_SESSION['ntype'] = $type;
    $_SESSION['notify'] = $msg;
}

if (isset($_SESSION['notify'])) {
    $notify = $_SESSION['notify'];
    $ntype = $_SESSION['ntype'];
    $ui->assign('notify', $notify);
    $ui->assign('notify_t', $ntype);
    unset($_SESSION['notify']);
    unset($_SESSION['ntype']);
}

if (!isset($_GET['_route'])) {
    $req = ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $len = strlen(ltrim(parse_url(APP_URL, PHP_URL_PATH), '/'));
    if ($len > 0) {
        $req = ltrim(substr($req, $len), '/');
    }
} else {
    // Routing Engine
    $req = _get('_route');
}

$routes = explode('/', $req);
// Ensure routes array has at least two elements
if (!isset($routes[0])) $routes[0] = '';
if (!isset($routes[1])) $routes[1] = '';
$ui->assign('_routes', $routes);
$handler = $routes[0];
if ($handler == '') {
    $handler = 'default';
}
try {
    if (!empty($_GET['uid'])) {
        $_COOKIE['uid'] = $_GET['uid'];
    }
    $admin = Admin::_info();
    $sys_render = $root_path . File::pathFixer('system/controllers/' . $handler . '.php');
    if (file_exists($sys_render)) {
        $menus = array();
        // "name" => $name,
        // "admin" => $admin,
        // "position" => $position,
        // "function" => $function
        $ui->assign('_system_menu', $routes[0]);
        // Initialize menu position variables
        $menu_positions = ['SERVICES', 'PLANS', 'MAPS', 'REPORTS', 'MESSAGE', 'NETWORK', 'RADIUS', 'PAGES', 'SETTINGS', 'LOGS'];
        foreach ($menu_positions as $position) {
            $ui->assign('_MENU_' . $position, '');
        }
        foreach ($menu_registered as $menu) {
            if ($menu['admin'] && _admin(false)) {
                if (count($menu['auth']) == 0 || in_array($admin['user_type'], $menu['auth'])) {
                    $menus[$menu['position']] = ($menus[$menu['position']] ?? '') . '<li' . ((isset($routes[1]) && $routes[1] == $menu['function']) ? ' class="active"' : '') . '><a href="' . getUrl('plugin/' . $menu['function']) . '">';
                    if (!empty($menu['icon'])) {
                        $menus[$menu['position']] .= '<i class="' . $menu['icon'] . '"></i>';
                    }
                    if (!empty($menu['label'])) {
                        $menus[$menu['position']] .= '<span class="pull-right-container">';
                        $menus[$menu['position']] .= '<small class="label pull-right bg-' . $menu['color'] . '">' . $menu['label'] . '</small></span>';
                    }
                    $menus[$menu['position']] .= '<span class="text">' . $menu['name'] . '</span></a></li>';
                }
            } else if (!$menu['admin'] && _auth(false)) {
                $menus[$menu['position']] .= '<li' . (($routes[1] == $menu['function']) ? ' class="active"' : '') . '><a href="' . getUrl('plugin/' . $menu['function']) . '">';
                if (!empty($menu['icon'])) {
                    $menus[$menu['position']] .= '<i class="' . $menu['icon'] . '"></i>';
                }
                if (!empty($menu['label'])) {
                    $menus[$menu['position']] .= '<span class="pull-right-container">';
                    $menus[$menu['position']] .= '<small class="label pull-right bg-' . $menu['color'] . '">' . $menu['label'] . '</small></span>';
                }
                $menus[$menu['position']] .= '<span class="text">' . $menu['name'] . '</span></a></li>';
            }
        }
        foreach ($menus as $k => $v) {
            $ui->assign('_MENU_' . $k, $v);
        }
        unset($menus, $menu_registered);
        include($sys_render);
    } else {
        if (empty($_SERVER["HTTP_SEC_FETCH_DEST"]) || $_SERVER["HTTP_SEC_FETCH_DEST"] != 'document') {
            // header 404
            header("HTTP/1.0 404 Not Found");
            header("Content-Type: text/html; charset=utf-8");
            echo "404 Not Found";
            die();
        } else {
            r2(getUrl('login'));
        }
    }
} catch (Throwable $e) {
    Message::sendTelegram(
        "Sistem Error.\n" .
            $e->getMessage() . "\n" .
            $e->getTraceAsString()
    );
    if (empty($_SESSION['aid'])) {
        // Show error details for debugging (remove in production)
        $ui->assign("error_message", $e->getMessage() . '<br><pre>' . $e->getTraceAsString() . '</pre>');
        $ui->assign("error_title", "PHPNuxBill Crash (Debug Mode)");
        $ui->display('customer/error.tpl');
        die();
    }
    $ui->assign("error_message", $e->getMessage() . '<br><pre>' . $e->getTraceAsString() . '</pre>');
    $ui->assign("error_title", "PHPNuxBill Crash");
    $ui->display('admin/error.tpl');
    die();
} catch (Exception $e) {
    Message::sendTelegram(
        "Sistem Error.\n" .
            $e->getMessage() . "\n" .
            $e->getTraceAsString()
    );
    if (empty($_SESSION['aid'])) {
        // Show error details for debugging (remove in production)
        $ui->assign("error_message", $e->getMessage() . '<br><pre>' . $e->getTraceAsString() . '</pre>');
        $ui->assign("error_title", "PHPNuxBill Crash (Debug Mode)");
        $ui->display('customer/error.tpl');
        die();
    }
    $ui->assign("error_message", $e->getMessage() . '<br><pre>' . $e->getTraceAsString() . '</pre>');
    $ui->assign("error_title", "PHPNuxBill Crash");
    $ui->display('admin/error.tpl');
    die();
}
