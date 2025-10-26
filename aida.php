<?php
/**
 * Plugin Name: Aida
 * Plugin URI: https://aidasales.ir
 * Description: Aida Chatbox Integration for WordPress. Easily add the Aida chatbot to your site with a simple admin panel.
 * Version: 1.0.3
 * Author: Rick Sanchez
 * Author URI: https://ricksanchez.ir/
 * License: GPL v2 or later
 * Text Domain: aida
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load textdomain for translations
add_action('plugins_loaded', 'aida_load_textdomain');
function aida_load_textdomain() {
    load_plugin_textdomain('aida', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

// Define plugin constants
define('AIDA_VERSION', '1.0.3');
define('AIDA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AIDA_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('AIDA_DOCS_URL', 'https://app.aidasales.ir/chatbox');
define('AIDA_DASHBOARD_URL', 'https://app.aidasales.ir/dashboard');
define('AIDA_SITE_URL', 'https://aidasales.ir');

// Admin menu and settings
add_action('admin_menu', 'aida_admin_menu');
function aida_admin_menu() {
    $icon_url = AIDA_PLUGIN_URL . 'assets/logo.png';
    add_menu_page(
        __('Aida', 'aida'),
        __('Aida', 'aida'),
        'manage_options',
        'aida-settings',
        'aida_settings_page',
        $icon_url,
        80
    );
}

// Register settings
add_action('admin_init', 'aida_register_settings');
function aida_register_settings() {
    register_setting('aida_options', 'aida_api_key', 'sanitize_text_field');
    register_setting('aida_options', 'aida_position', array(
        'type' => 'string',
        'default' => 'right',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    register_setting('aida_options', 'aida_initial_state', array(
        'type' => 'string',
        'default' => 'closed',
        'sanitize_callback' => 'sanitize_text_field'
    ));
}

// Settings page
function aida_settings_page() {
    $logo_url = AIDA_PLUGIN_URL . 'assets/logo.png';
    $is_rtl = is_rtl();
    $margin_style = $is_rtl ? 'margin-left: 15px;' : 'margin-right: 15px;';
    $direction_style = $is_rtl ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;';
    ?>
    <div class="wrap" style="<?php echo $direction_style; ?>">
        <style>
            .form-table th { text-align: <?php echo $is_rtl ? 'right' : 'left'; ?>; }
            .form-table td { text-align: <?php echo $is_rtl ? 'right' : 'left'; ?>; }
            <?php if ($is_rtl): ?>
            body { direction: rtl; }
            <?php endif; ?>
        </style>
        <div style="display: flex; align-items: center; margin-bottom: 20px; <?php echo $direction_style; ?>">
            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php _e('Aida Logo', 'aida'); ?>" style="height: 50px; <?php echo $margin_style; ?>" onerror="this.style.display='none';" />
            <h1><?php _e('Aida Chatbox Settings', 'aida'); ?></h1>
        </div>
        <p>
            <a href="<?php echo esc_url(AIDA_DASHBOARD_URL); ?>" target="_blank" class="button button-primary"><?php _e('Go to Aida Dashboard', 'aida'); ?></a> | 
            <a href="<?php echo esc_url(AIDA_DOCS_URL); ?>" target="_blank" class="button"><?php _e('View Documentation', 'aida'); ?></a> | 
            <a href="<?php echo esc_url(AIDA_SITE_URL); ?>" target="_blank" class="button"><?php _e('Aida Website', 'aida'); ?></a>
        </p>
        <form method="post" action="options.php">
            <?php
            settings_fields('aida_options');
            do_settings_sections('aida_options');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Aida API Key', 'aida'); ?></th>
                    <td>
                        <input type="text" name="aida_api_key" value="<?php echo esc_attr(get_option('aida_api_key')); ?>" class="regular-text" placeholder="<?php _e('e.g., 20AD2PFKSB', 'aida'); ?>" />
                        <p class="description"><?php printf(__('Enter your Aida Chatbox API Key from your Aida dashboard (%sChannels > Website%s).', 'aida'), '<a href="' . esc_url(AIDA_DASHBOARD_URL) . '" target="_blank">', '</a>'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Chatbox Position', 'aida'); ?></th>
                    <td>
                        <select name="aida_position">
                            <option value="left" <?php selected(get_option('aida_position'), 'left'); ?>><?php _e('Left', 'aida'); ?></option>
                            <option value="right" <?php selected(get_option('aida_position'), 'right'); ?>><?php _e('Right', 'aida'); ?></option>
                        </select>
                        <p class="description"><?php printf(__('Position of the chatbox (default: right). See %sdocs%s for more options.', 'aida'), '<a href="' . esc_url(AIDA_DOCS_URL) . '" target="_blank">', '</a>'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Initial State', 'aida'); ?></th>
                    <td>
                        <select name="aida_initial_state">
                            <option value="open" <?php selected(get_option('aida_initial_state'), 'open'); ?>><?php _e('Open', 'aida'); ?></option>
                            <option value="closed" <?php selected(get_option('aida_initial_state'), 'closed'); ?>><?php _e('Closed', 'aida'); ?></option>
                        </select>
                        <p class="description"><?php printf(__('Initial state of the chatbox (default: closed). See %sdocs%s for more options.', 'aida'), '<a href="' . esc_url(AIDA_DOCS_URL) . '" target="_blank">', '</a>'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <?php
        // Notice if API key is missing
        if (empty(get_option('aida_api_key'))) {
            echo '<div class="notice notice-warning"><p>' . __('Warning: Aida API Key is not set. The chatbox will not appear on your site. Please enter your API key above and save changes.', 'aida') . '</p></div>';
        }
        ?>
    </div>
    <?php
}

// Add script to footer
add_action('wp_footer', 'aida_add_chatbox_script');
function aida_add_chatbox_script() {
    $api_key = get_option('aida_api_key');
    if (empty($api_key)) {
        return; // Don't add script if no API key
    }

    $position = get_option('aida_position', 'right');
    $initial_state = get_option('aida_initial_state', 'closed');

    // Use fa.js for Persian; assuming no en.js, stick to fa
    $script_src = 'https://cdn.aidasales.ir/chatbox/aida-chatbot.min.fa.js';

    ?>
    <script src="<?php echo esc_url($script_src); ?>" 
            data-aida-api-key="<?php echo esc_attr($api_key); ?>" 
            data-position-chatbox="<?php echo esc_attr($position); ?>" 
            data-initial-state="<?php echo esc_attr($initial_state); ?>">
    </script>
    <?php
}

// Activation hook
register_activation_hook(__FILE__, 'aida_activate');
function aida_activate() {
    add_option('aida_position', 'right');
    add_option('aida_initial_state', 'closed');
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'aida_deactivate');
function aida_deactivate() {
    // No cleanup needed
}