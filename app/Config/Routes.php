<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('ws', ['filter' => 'secretKey'],static function($routes) {
    $routes->get('/', 'WsController::index');
    $routes->group('admin', static function($routes) {
        $routes->get('/', 'WsController::index');
        $routes->get('users', 'WsController::getUsers');
        $routes->get('clients', 'WsController::getClients');
        $routes->get('clientId/(:any)', 'WsController::getClientId/$1');
        $routes->get('saldoHarianId/(:any)', 'WsController::getSaldoHarianId/$1');
        $routes->get('devidenId/(:any)', 'WsController::getDevidenId/$1');
        $routes->get('transaksiId/(:any)', 'WsController::getTransaksiId/$1');
        $routes->get('transaksiSaham/(:any)', 'WsController::getTransaksiSaham/$1');
        $routes->get('saldoInvestasi/(:any)/(:any)', 'WsController::getSaldoInvestasi/$1/$2');
        $routes->get('dispen/(:num)', 'WsController::getDispen/$1');
        $routes->get('disda/(:num)', 'WsController::getDisda/$1');
    });
    $routes->group('global', static function($routes) {
        $routes->get('/', 'WsController::index');
    });
});
