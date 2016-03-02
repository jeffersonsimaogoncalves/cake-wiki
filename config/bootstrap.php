<?php
use Cake\Core\Configure;
use Cake\Utility\Hash;

// Load and merge default with app config
$config = include 'wiki.default.php';
$config = $config['Wiki'];
if ($appWikiConfig = Configure::read('Wiki')) {
    $config = Hash::merge($config, $appWikiConfig);
}
Configure::write('Wiki', $config);
