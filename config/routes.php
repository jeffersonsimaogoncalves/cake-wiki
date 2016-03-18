<?php
use Cake\Routing\Router;

Router::plugin('Scherersoftware/Wiki', ['path' => '/wiki'], function ($routes) {
    $routes->connect('/wiki-pages/:id-:slug', [
        'controller' => 'WikiPages',
        'action' => 'view',
    ], [
        'id' => '[0-9]+',
        'pass' => ['id'],
        '_name' => 'wikiPages'
    ]);
    $routes->fallbacks('DashedRoute');
});
