<?php
use Cake\Routing\Router;

Router::plugin('Scherersoftware/Wiki', function ($routes) {
    $routes->fallbacks('DashedRoute');
});
