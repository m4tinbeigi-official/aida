<?php
/**
 * Plugin Name: Aida Chatbox
 * Plugin URI: https://aidasales.ir
 * Description: Aida Chatbox Integration for WordPress. Easily add the Aida chatbot to your site with a simple admin panel.
 * Version: 1.0.4
 * Author: Rick Sanchez
 * Author URI: https://ricksanchez.ir
 * License: GPL v2 or later
 * Text Domain: aida-chatbox
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('AIDA_VERSION', '1.0.4');
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
        __('Aida Chatbox', 'aida-chatbox'),
        __('Aida Chatbox', 'aida-chatbox'),
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
    $margin_style = esc_attr( $is_rtl ? 'margin-left: 15px;' : 'margin-right: 15px;' );
    $direction_style = esc_attr( $is_rtl ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' );
    $align_style = esc_attr( $is_rtl ? 'right' : 'left' );
    ?>
    <div class="wrap" style="<?php echo $direction_style; ?>">
        <style>
            .form-table th { text-align: <?php echo $align_style; ?>; }
            .form-table td { text-align: <?php echo $align_style; ?>; }
            <?php if ($is_rtl): ?>
            body { direction: rtl; }
            <?php endif; ?>
        </style>
        <div style="display: flex; align-items: center; margin-bottom: 20px; <?php echo $direction_style; ?>">
            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php esc_attr_e('Aida Logo', 'aida-chatbox'); ?>" style="height: 50px; <?php echo $margin_style; ?>" onerror="this.style.display='none';" />
            <h1><?php esc_html_e('Aida Chatbox Settings', 'aida-chatbox'); ?></h1>
        </div>
        <p>
            <a href="<?php echo esc_url(AIDA_DASHBOARD_URL); ?>" target="_blank" class="button button-primary"><?php esc_html_e('Go to Aida Dashboard', 'aida-chatbox'); ?></a> | 
            <a href="<?php echo esc_url(AIDA_DOCS_URL); ?>" target="_blank" class="button"><?php esc_html_e('View Documentation', 'aida-chatbox'); ?></a> | 
            <a href="<?php echo esc_url(AIDA_SITE_URL); ?>" target="_blank" class="button"><?php esc_html_e('Aida Website', 'aida-chatbox'); ?></a>
        </p>
        <form method="post" action="options.php">
            <?php
            settings_fields('aida_options');
            do_settings_sections('aida_options');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Aida API Key', 'aida-chatbox'); ?></th>
                    <td>
                        <input type="text" name="aida_api_key" value="<?php echo esc_attr(get_option('aida_api_key')); ?>" class="regular-text" placeholder="<?php esc_attr_e('e.g., 20AD2PFKSB', 'aida-chatbox'); ?>" />
                        <p class="description">
                            <?php
                            // translators: %1$s: Opening link tag for dashboard, %2$s: Closing link tag.
                            printf(
                                esc_html__( 'Enter your Aida Chatbox API Key from your Aida dashboard (%1$sChannels > Website%2$s).', 'aida-chatbox' ),
                                '<a href="' . esc_url( AIDA_DASHBOARD_URL ) . '" target="_blank">',
                                '</a>'
                            );
                            ?>
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Chatbox Position', 'aida-chatbox'); ?></th>
                    <td>
                        <select name="aida_position">
                            <option value="left" <?php selected(get_option('aida_position'), 'left'); ?>><?php esc_html_e('Left', 'aida-chatbox'); ?></option>
                            <option value="right" <?php selected(get_option('aida_position'), 'right'); ?>><?php esc_html_e('Right', 'aida-chatbox'); ?></option>
                        </select>
                        <p class="description">
                            <?php
                            // translators: %1$s: Opening link tag for docs, %2$s: Closing link tag.
                            printf(
                                esc_html__( 'Position of the chatbox (default: right). See %1$sdocs%2$s for more options.', 'aida-chatbox' ),
                                '<a href="' . esc_url( AIDA_DOCS_URL ) . '" target="_blank">',
                                '</a>'
                            );
                            ?>
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Initial State', 'aida-chatbox'); ?></th>
                    <td>
                        <select name="aida_initial_state">
                            <option value="open" <?php selected(get_option('aida_initial_state'), 'open'); ?>><?php esc_html_e('Open', 'aida-chatbox'); ?></option>
                            <option value="closed" <?php selected(get_option('aida_initial_state'), 'closed'); ?>><?php esc_html_e('Closed', 'aida-chatbox'); ?></option>
                        </select>
                        <p class="description">
                            <?php
                            // translators: %1$s: Opening link tag for docs, %2$s: Closing link tag.
                            printf(
                                esc_html__( 'Initial state of the chatbox (default: closed). See %1$sdocs%2$s for more options.', 'aida-chatbox' ),
                                '<a href="' . esc_url( AIDA_DOCS_URL ) . '" target="_blank">',
                                '</a>'
                            );
                            ?>
                        </p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <?php
        // Notice if API key is missing
        if (empty(get_option('aida_api_key'))) {
            ?>
            <div class="notice notice-warning">
                <p><?php esc_html_e( 'Warning: Aida API Key is not set. The chatbox will not appear on your site. Please enter your API key above and save changes.', 'aida-chatbox' ); ?></p>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}

// Enqueue chatbox script
add_action('wp_enqueue_scripts', 'aida_enqueue_chatbox_script');
function aida_enqueue_chatbox_script() {
    $api_key = get_option('aida_api_key');
    if (empty($api_key)) {
        return; // Don't enqueue if no API key
    }

    // Use fa.js for Persian; assuming no en.js, stick to fa
    $script_src = 'https://cdn.aidasales.ir/chatbox/aida-chatbot.min.fa.js';

    wp_enqueue_script(
        'aida-chatbox-js',
        $script_src,
        array(),
        AIDA_VERSION,
        true
    );
}

// Add data attributes to the enqueued script tag
add_filter('script_loader_tag', 'aida_add_data_attrs', 10, 3);
function aida_add_data_attrs($tag, $handle, $src) {
    if ('aida-chatbox-js' !== $handle) {
        return $tag;
    }

    $api_key = get_option('aida_api_key');
    $position = get_option('aida_position', 'right');
    $initial_state = get_option('aida_initial_state', 'closed');

    $tag = str_replace(
        '<script ',
        '<script data-aida-api-key="' . esc_attr( $api_key ) . '" ' .
        'data-position-chatbox="' . esc_attr( $position ) . '" ' .
        'data-initial-state="' . esc_attr( $initial_state ) . '" ',
        $tag
    );

    return $tag;
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