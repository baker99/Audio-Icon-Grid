<?php
/*
 Plugin Name:       Audio Icon Grid
 Description:       Configurable audio grids with sub-grids and transitions. Shortcode: [audio_icon_grid]
 Version :          1.0.1
 Author:            Daniel Baker
 Author URI:        https://daniel-baker.photography/
 License:           GPLv3
 License URI:       https://gnu.org
 Copyright (C) 2026 Daniel Baker (https://daniel-baker.photography/)
 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 */

add_filter('the_content', function($content) {
    return do_shortcode($content);
});


if (!defined('ABSPATH')) exit;

/*
|--------------------------------------------------------------------------
| Includes
|--------------------------------------------------------------------------
*/
require_once plugin_dir_path(__FILE__) . 'admin/settings.php';
require_once plugin_dir_path(__FILE__) . 'public/shortcode.php';

/*
|--------------------------------------------------------------------------
| Register Settings
|--------------------------------------------------------------------------
*/
function aig_register_settings() {
    register_setting('aig_settings', 'aig_global', 'aig_sanitize_array');
    register_setting('aig_settings', 'aig_main_grid', 'aig_sanitize_array');
    register_setting('aig_settings', 'aig_sub_grids', 'aig_sanitize_array');
}
add_action('admin_init', 'aig_register_settings');

/*
|--------------------------------------------------------------------------
| Sanitization
|--------------------------------------------------------------------------
*/
function aig_sanitize_array($input) {
    if (!is_array($input)) return [];
    foreach ($input as $key => $value) {
        if (is_array($value)) {
            $input[$key] = aig_sanitize_array($value);
        } elseif (in_array($key, ['icon', 'audio', 'return_icon', 'font_size', 'main_cols', 'sub_cols', 'subgrid_count'], true)) {
            $input[$key] = absint($value);
        } elseif ($key === 'font_weight') {
            $input[$key] = in_array($value, ['normal', 'bold'], true) ? $value : 'normal';
        } elseif ($key === 'transition') {
            $input[$key] = in_array($value, ['fade', 'swap', 'instant'], true) ? $value : 'fade';
        } elseif ($key === 'action') {
            $input[$key] = ($value === 'play' || preg_match('/^subgrid_[1-9][0-9]*$/', (string) $value)) ? $value : 'play';
        } else {
            $input[$key] = sanitize_text_field($value);
        }
    }
    return $input;
}

/*
|--------------------------------------------------------------------------
| Admin Scripts
|--------------------------------------------------------------------------
*/
function aig_admin_scripts() {
    wp_enqueue_media();
    wp_enqueue_script(
        'aig-media-picker',
        plugin_dir_url(__FILE__) . 'admin/media-picker.js',
        ['jquery'],
        false,
        true
    );
}
add_action('admin_enqueue_scripts', 'aig_admin_scripts');



/*
|--------------------------------------------------------------------------
| Frontend Scripts + Styles
|--------------------------------------------------------------------------
*/
function aig_enqueue_frontend() {

    wp_enqueue_style(
        'aig-grid',
        plugin_dir_url(__FILE__) . 'public/grid.css'
    );

    /*
    |--------------------------------------------------------------------------
    | Load our module script (WP 7.2 style)
    |--------------------------------------------------------------------------
    */
    wp_enqueue_script(
        'aig-script',
        plugin_dir_url(__FILE__) . 'public/aig-script.js',
        [],
        '1.0.0',
        true // footer
    );

    /*
    |--------------------------------------------------------------------------
    | Prepare Data
    |--------------------------------------------------------------------------
    */
    $global = get_option('aig_global', []);
    $main   = get_option('aig_main_grid', []);
    $subs   = get_option('aig_sub_grids', []);

    // Main grid
    $main_out = [];
    if (is_array($main)) {
        foreach ($main as $cell) {
            $main_out[] = [
                'icon_url'  => wp_get_attachment_url($cell['icon'] ?? 0),
                'audio_url' => wp_get_attachment_url($cell['audio'] ?? 0),
                'text'      => $cell['text'] ?? '',
                'action'    => $cell['action'] ?? 'play'
            ];
        }
    }

    // Sub-grids
    $subs_out = [];
    if (is_array($subs)) {
        foreach ($subs as $key => $grid) {
            foreach ($grid as $cell) {
                $subs_out[$key][] = [
                    'icon_url'  => wp_get_attachment_url($cell['icon'] ?? 0),
                    'audio_url' => wp_get_attachment_url($cell['audio'] ?? 0),
                    'text'      => $cell['text'] ?? ''
                ];
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Inject AIG_DATA BEFORE the module
    |--------------------------------------------------------------------------
    */
    $inline = 'const AIG_DATA = ' . wp_json_encode(
        [
            'global' => [
                'font_family'      => $global['font_family'] ?? 'Arial',
                'font_size'        => $global['font_size'] ?? 16,
                'font_weight'      => $global['font_weight'] ?? 'normal',
                'page_bg'          => $global['page_bg'] ?? '#ffffff',
                'cell_bg'          => $global['cell_bg'] ?? '#e0e0e0',
                'cell_border'      => $global['cell_border'] ?? '#cccccc',
                'text_color'       => $global['text_color'] ?? '#000000',
                'main_cols'        => $global['main_cols'] ?? 3,
                'sub_cols'         => $global['sub_cols'] ?? 3,
                'transition'       => $global['transition'] ?? 'fade',
                'return_icon_url'  => wp_get_attachment_url($global['return_icon'] ?? 0)
            ],
            'main' => $main_out,
            'subs' => $subs_out
        ],
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS
    ) . ';';


    wp_add_inline_script(
        'aig-script',
        $inline,
        'before'
    );
}
add_action('wp_enqueue_scripts', 'aig_enqueue_frontend');
