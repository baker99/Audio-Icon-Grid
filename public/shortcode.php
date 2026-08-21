<?php
if (!defined('ABSPATH')) exit;

function aig_shortcode() {
    return '<div class="aig-wrapper" style="background:#ffffff"><div class="aig-grid"></div></div>';
}
add_shortcode('audio_icon_grid', 'aig_shortcode');
