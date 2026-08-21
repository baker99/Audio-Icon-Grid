<?php

if (!defined('ABSPATH')) exit;

function aig_settings_page() {

    $global    = get_option('aig_global', []);
    $main_grid = get_option('aig_main_grid', []);
    $sub_grids = get_option('aig_sub_grids', []);

    $sub_count = isset($global['subgrid_count']) ? (int)$global['subgrid_count'] : 0;
    ?>
    <div class="wrap">
        <h1>Audio Icon Grid</h1>

        <form method="post" action="options.php">
            <?php settings_fields('aig_settings'); ?>

            <!-- GLOBAL SETTINGS -->
            <h2>Global Appearance & Behaviour</h2>

            <table class="form-table">
                <tr><th>Font Family</th>
                    <td><input type="text" name="aig_global[font_family]" value="<?php echo esc_attr($global['font_family'] ?? 'Arial'); ?>"></td>
                </tr>

                <tr><th>Font Size (px)</th>
                    <td><input type="number" name="aig_global[font_size]" value="<?php echo esc_attr($global['font_size'] ?? 16); ?>"></td>
                </tr>

                <tr><th>Font Weight</th>
                    <td>
                        <?php $fw = $global['font_weight'] ?? 'normal'; ?>
                        <select name="aig_global[font_weight]">
                            <option value="normal" <?php selected($fw, 'normal'); ?>>Normal</option>
                            <option value="bold" <?php selected($fw, 'bold'); ?>>Bold</option>
                        </select>
                    </td>
                </tr>

                <tr><th>Page Background</th>
                    <td><input type="text" name="aig_global[page_bg]" value="<?php echo esc_attr($global['page_bg'] ?? '#ffffff'); ?>"></td>
                </tr>

                <tr><th>Cell Background</th>
                    <td><input type="text" name="aig_global[cell_bg]" value="<?php echo esc_attr($global['cell_bg'] ?? '#e0e0e0'); ?>"></td>
                </tr>

                <tr><th>Cell Border</th>
                    <td><input type="text" name="aig_global[cell_border]" value="<?php echo esc_attr($global['cell_border'] ?? '#cccccc'); ?>"></td>
                </tr>

                <tr><th>Text Colour</th>
                    <td><input type="text" name="aig_global[text_color]" value="<?php echo esc_attr($global['text_color'] ?? '#000000'); ?>"></td>
                </tr>

                <tr><th>Main Grid Columns</th>
                    <td><input type="number" name="aig_global[main_cols]" value="<?php echo esc_attr($global['main_cols'] ?? 3); ?>"></td>
                </tr>

                <tr><th>Sub-Grid Columns</th>
                    <td><input type="number" name="aig_global[sub_cols]" value="<?php echo esc_attr($global['sub_cols'] ?? 3); ?>"></td>
                </tr>

                <tr><th>Transition</th>
                    <td>
                        <?php $tr = $global['transition'] ?? 'fade'; ?>
                        <select name="aig_global[transition]">
                            <option value="fade" <?php selected($tr, 'fade'); ?>>Fade</option>
                            <option value="swap" <?php selected($tr, 'swap'); ?>>Swap</option>
                            <option value="instant" <?php selected($tr, 'instant'); ?>>Instant</option>
                        </select>
                    </td>
                </tr>

                <tr><th>Number of Sub-Grids</th>
                    <td><input type="number" name="aig_global[subgrid_count]" value="<?php echo esc_attr($sub_count); ?>"></td>
                </tr>

                <tr><th>Return Icon</th>
                    <td>
                        <div class="aig-media-wrapper">
                            <img class="aig-preview"
                                 src="<?php echo !empty($global['return_icon']) ? esc_url(wp_get_attachment_url($global['return_icon'])) : ''; ?>"
                                 style="max-width:60px; <?php echo !empty($global['return_icon']) ? '' : 'display:none;'; ?> margin-bottom:6px;" />

                            <input type="hidden"
                                   name="aig_global[return_icon]"
                                   class="aig-icon-field"
                                   value="<?php echo esc_attr($global['return_icon'] ?? ''); ?>" />

                            <button class="button aig-select-icon">Select Icon</button>
                            <button class="button aig-remove-icon">Remove</button>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- MAIN GRID -->
            <h2>Main Grid</h2>

            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Text</th>
                        <th>Audio</th>
                        <th>Action</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (!is_array($main_grid)) $main_grid = [];
                $i = 0;
                foreach ($main_grid as $cell):
                ?>
                    <tr>
                        <!-- ICON -->
                        <td>
                            <div class="aig-media-wrapper">
                                <img class="aig-preview"
                                     src="<?php echo $cell['icon'] ? esc_url(wp_get_attachment_url($cell['icon'])) : ''; ?>"
                                     style="max-width:60px; <?php echo $cell['icon'] ? '' : 'display:none;'; ?> margin-bottom:6px;" />

                                <input type="hidden"
                                       name="aig_main_grid[<?php echo $i; ?>][icon]"
                                       class="aig-icon-field"
                                       value="<?php echo esc_attr($cell['icon'] ?? ''); ?>" />

                                <button class="button aig-select-icon">Select Icon</button>
                                <button class="button aig-remove-icon">Remove</button>
                            </div>
                        </td>

                        <!-- TEXT -->
                        <td>
                            <input type="text"
                                   name="aig_main_grid[<?php echo $i; ?>][text]"
                                   value="<?php echo esc_attr($cell['text'] ?? ''); ?>"
                                   class="regular-text" />
                        </td>

                        <!-- AUDIO -->
                        <td>
                            <div class="aig-media-wrapper">
                                <span class="aig-audio-preview">
                                    <?php echo $cell['audio'] ? esc_html(basename(wp_get_attachment_url($cell['audio']))) : ''; ?>
                                </span>

                                <input type="hidden"
                                       name="aig_main_grid[<?php echo $i; ?>][audio]"
                                       class="aig-audio-field"
                                       value="<?php echo esc_attr($cell['audio'] ?? ''); ?>" />

                                <button class="button aig-select-audio">Select Audio</button>
                                <button class="button aig-remove-audio">Remove</button>
                            </div>
                        </td>

                        <!-- ACTION -->
                        <td>
                            <select name="aig_main_grid[<?php echo $i; ?>][action]">
                                <?php $act = $cell['action'] ?? 'play'; ?>
                                <option value="play" <?php selected($act, 'play'); ?>>Play Audio</option>

                                <?php for ($sg = 1; $sg <= $sub_count; $sg++): ?>
                                    <?php $val = 'subgrid_' . $sg; ?>
                                    <option value="<?php echo esc_attr($val); ?>" <?php selected($act, $val); ?>>
                                        Link to Sub-Grid <?php echo $sg; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </td>

                        <!-- REMOVE ROW -->
                        <td>
                            <button class="button aig-remove-row">Delete Row</button>
                        </td>
                    </tr>
                <?php
                $i++;
                endforeach;
                ?>

                <!-- BLANK ROW -->
                <tr>
                    <td>
                        <div class="aig-media-wrapper">
                            <img class="aig-preview" style="max-width:60px; display:none; margin-bottom:6px;" />
                            <input type="hidden" name="aig_main_grid[<?php echo $i; ?>][icon]" class="aig-icon-field" value="">
                            <button class="button aig-select-icon">Select Icon</button>
                            <button class="button aig-remove-icon">Remove</button>
                        </div>
                    </td>

                    <td><input type="text" name="aig_main_grid[<?php echo $i; ?>][text]" value="" class="regular-text"></td>

                    <td>
                        <div class="aig-media-wrapper">
                            <span class="aig-audio-preview"></span>
                            <input type="hidden" name="aig_main_grid[<?php echo $i; ?>][audio]" class="aig-audio-field" value="">
                            <button class="button aig-select-audio">Select Audio</button>
                            <button class="button aig-remove-audio">Remove</button>
                        </div>
                    </td>

                    <td>
                        <select name="aig_main_grid[<?php echo $i; ?>][action]">
                            <option value="play">Play Audio</option>
                            <?php for ($sg = 1; $sg <= $sub_count; $sg++): ?>
                                <option value="<?php echo esc_attr('subgrid_' . $sg); ?>">Link to Sub-Grid <?php echo $sg; ?></option>
                            <?php endfor; ?>
                        </select>
                    </td>

                    <td>
                        <button class="button aig-remove-row">Delete Row</button>
                    </td>
                </tr>

                </tbody>
            </table>

            <!-- SUB-GRIDS -->
            <h2>Sub-Grids</h2>

            <?php
            if (!is_array($sub_grids)) $sub_grids = [];

            for ($sg = 1; $sg <= $sub_count; $sg++):
                $key  = (string)$sg;
                $grid = $sub_grids[$key] ?? [];
            ?>
                <h3>Sub-Grid <?php echo $sg; ?></h3>

                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Text</th>
                            <th>Audio</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                    $j = 0;
                    foreach ($grid as $cell):
                    ?>
                        <tr>
                            <!-- ICON -->
                            <td>
                                <div class="aig-media-wrapper">
                                    <img class="aig-preview"
                                         src="<?php echo $cell['icon'] ? esc_url(wp_get_attachment_url($cell['icon'])) : ''; ?>"
                                         style="max-width:60px; <?php echo $cell['icon'] ? '' : 'display:none;'; ?> margin-bottom:6px;" />

                                    <input type="hidden"
                                           name="aig_sub_grids[<?php echo $key; ?>][<?php echo $j; ?>][icon]"
                                           class="aig-icon-field"
                                           value="<?php echo esc_attr($cell['icon'] ?? ''); ?>" />

                                    <button class="button aig-select-icon">Select Icon</button>
                                    <button class="button aig-remove-icon">Remove</button>
                                </div>
                            </td>

                            <!-- TEXT -->
                            <td>
                                <input type="text"
                                       name="aig_sub_grids[<?php echo $key; ?>][<?php echo $j; ?>][text]"
                                       value="<?php echo esc_attr($cell['text'] ?? ''); ?>"
                                       class="regular-text" />
                            </td>

                            <!-- AUDIO -->
                            <td>
                                <div class="aig-media-wrapper">
                                    <span class="aig-audio-preview">
                                        <?php echo $cell['audio'] ? esc_html(basename(wp_get_attachment_url($cell['audio']))) : ''; ?>
                                    </span>

                                    <input type="hidden"
                                           name="aig_sub_grids[<?php echo $key; ?>][<?php echo $j; ?>][audio]"
                                           class="aig-audio-field"
                                           value="<?php echo esc_attr($cell['audio'] ?? ''); ?>" />

                                    <button class="button aig-select-audio">Select Audio</button>
                                    <button class="button aig-remove-audio">Remove</button>
                                </div>
                            </td>

                            <!-- REMOVE ROW -->
                            <td>
                                <button class="button aig-remove-row">Delete Row</button>
                            </td>
                        </tr>
                    <?php
                    $j++;
                    endforeach;
                    ?>

                    <!-- BLANK ROW -->
                    <tr>
                        <td>
                            <div class="aig-media-wrapper">
                                <img class="aig-preview" style="max-width:60px; display:none; margin-bottom:6px;" />
                                <input type="hidden" name="aig_sub_grids[<?php echo $key; ?>][<?php echo $j; ?>][icon]" class="aig-icon-field" value="">
                                <button class="button aig-select-icon">Select Icon</button>
                                <button class="button aig-remove-icon">Remove</button>
                            </div>
                        </td>

                        <td><input type="text" name="aig_sub_grids[<?php echo $key; ?>][<?php echo $j; ?>][text]" value="" class="regular-text"></td>

                        <td>
                            <div class="aig-media-wrapper">
                                <span class="aig-audio-preview"></span>
                                <input type="hidden" name="aig_sub_grids[<?php echo $key; ?>][<?php echo $j; ?>][audio]" class="aig-audio-field" value="">
                                <button class="button aig-select-audio">Select Audio</button>
                                <button class="button aig-remove-audio">Remove</button>
                            </div>
                        </td>

                        <td>
                            <button class="button aig-remove-row">Delete Row</button>
                        </td>
                    </tr>

                    </tbody>
                </table>

            <?php endfor; ?>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function aig_add_admin_menu() {
    add_menu_page(
        'Audio Icon Grid',
        'Audio Icon Grid',
        'manage_options',
        'audio-icon-grid',
        'aig_settings_page'
    );
}
add_action('admin_menu', 'aig_add_admin_menu');
