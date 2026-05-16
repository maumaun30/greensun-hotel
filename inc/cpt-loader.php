<?php
/**
 * Auto-loads every PHP file under inc/post-types/ and inc/taxonomies/.
 * Drop a new file in either folder to register a CPT or taxonomy — no edits here.
 */

if (!defined('ABSPATH')) {
    exit;
}

$greensun_hotel_module_dirs = [
    __DIR__ . '/post-types',
    __DIR__ . '/taxonomies',
    __DIR__ . '/fields',
];

foreach ($greensun_hotel_module_dirs as $dir) {
    if (!is_dir($dir)) {
        continue;
    }
    foreach (glob($dir . '/*.php') as $file) {
        require_once $file;
    }
}
