<?php
/**
 * Plugin Name: Aida
 * Plugin URI: https://aidasales.ir
 * Description: Aida Chatbox Integration for WordPress. Easily add the Aida chatbot to your site with a simple admin panel.
 * Version: 1.0.0
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

// Define plugin constants
define('AIDA_VERSION', '1.0.2');
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
        'Aida Settings',
        'Aida',
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
    ?>
    <div class="wrap">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <img src="<?php echo esc_url($logo_url); ?>" alt="Aida Logo" style="height: 50px; margin-right: 15px;" onerror="this.style.display='none';" />
            <h1>Aida Chatbox Settings</h1>
        </div>
        <p><a href="<?php echo esc_url(AIDA_DASHBOARD_URL); ?>" target="_blank" class="button button-primary">Go to Aida Dashboard</a> | <a href="<?php echo esc_url(AIDA_DOCS_URL); ?>" target="_blank" class="button">View Documentation</a> | <a href="<?php echo esc_url(AIDA_SITE_URL); ?>" target="_blank" class="button">Aida Website</a></p>
        <form method="post" action="options.php">
            <?php
            settings_fields('aida_options');
            do_settings_sections('aida_options');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Aida API Key</th>
                    <td>
                        <input type="text" name="aida_api_key" value="<?php echo esc_attr(get_option('aida_api_key')); ?>" class="regular-text" placeholder="e.g., 20AD2PFKSB" />
                        <p class="description">Enter your Aida Chatbox API Key from your Aida dashboard (<a href="<?php echo esc_url(AIDA_DASHBOARD_URL); ?>" target="_blank">Channels > Website</a>).</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Chatbox Position</th>
                    <td>
                        <select name="aida_position">
                            <option value="left" <?php selected(get_option('aida_position'), 'left'); ?>>Left</option>
                            <option value="right" <?php selected(get_option('aida_position'), 'right'); ?>>Right</option>
                        </select>
                        <p class="description">Position of the chatbox (default: right). See <a href="<?php echo esc_url(AIDA_DOCS_URL); ?>" target="_blank">docs</a> for more options.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Initial State</th>
                    <td>
                        <select name="aida_initial_state">
                            <option value="open" <?php selected(get_option('aida_initial_state'), 'open'); ?>>Open</option>
                            <option value="closed" <?php selected(get_option('aida_initial_state'), 'closed'); ?>>Closed</option>
                        </select>
                        <p class="description">Initial state of the chatbox (default: closed). See <a href="<?php echo esc_url(AIDA_DOCS_URL); ?>" target="_blank">docs</a> for more options.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>

        <h2>Documentation</h2>
        <p>For full details, visit the <a href="<?php echo esc_url(AIDA_DOCS_URL); ?>" target="_blank">official Aida Chatbox Documentation</a>.</p>
        <h3>Prerequisites</h3>
        <p>Before using the Aida Chatbox, ensure that:</p>
        <ul>
            <li>Your website URL is set in <a href="<?php echo esc_url(AIDA_DASHBOARD_URL); ?>" target="_blank">Aida Dashboard → Channels → Website</a></li>
            <li>The website channel is activated</li>
            <li>Your Chatbox ID (API Key) is copied from the Channels page (entered above)</li>
        </ul>

        <h3>Integration</h3>
        <p>This plugin automatically adds the Aida Chatbox script to your site's footer. No manual code insertion needed! Visit <a href="<?php echo esc_url(AIDA_SITE_URL); ?>" target="_blank">aidasales.ir</a> for more info.</p>

        <h3>API Usage (Advanced)</h3>
        <p>For programmatic integration (e.g., in custom apps), use:</p>
        <pre>fetch('/api/v1/conversation/chatbox/YOUR_API_KEY/message', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    message: 'Your message',
    thread_id: 'thread_id',
    username: 'username'
  })
});</pre>

        <h3>Testing</h3>
        <p>After saving settings:</p>
        <ul>
            <li>Visit your site in a new browser tab</li>
            <li>Look for the Aida Chatbox widget (usually bottom-right corner)</li>
            <li>Test sending a message to ensure it works</li>
        </ul>
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

    ?>
    <script src="https://cdn.aidasales.ir/chatbox/aida-chatbot.min.fa.js" 
            data-aida-api-key="<?php echo esc_attr($api_key); ?>" 
            data-position-chatbox="<?php echo esc_attr($position); ?>" 
            data-initial-state="<?php echo esc_attr($initial_state); ?>">
    </script>
    <?php
}

// Activation hook (optional: add welcome notice or defaults)
register_activation_hook(__FILE__, 'aida_activate');
function aida_activate() {
    add_option('aida_position', 'right');
    add_option('aida_initial_state', 'closed');
}

// Deactivation hook (cleanup if needed)
register_deactivation_hook(__FILE__, 'aida_deactivate');
function aida_deactivate() {
    // No cleanup needed
}