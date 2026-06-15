<?php
/**
 * Settings Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$plugin = ADNetwork\Core\Plugin::instance();
$settings = $plugin->settings;

// Form verarbeiten
if (isset($_POST['adnetwork_settings_nonce']) && wp_verify_nonce($_POST['adnetwork_settings_nonce'], 'adnetwork_save_settings')) {
    $settings->setMultiple([
        'network_name' => sanitize_text_field($_POST['network_name'] ?? ''),
        'project_email' => sanitize_email($_POST['project_email'] ?? ''),
        'currency_main' => sanitize_text_field($_POST['currency_main'] ?? 'CP'),
        'start_balance_cp' => floatval($_POST['start_balance_cp'] ?? 0),
        'start_balance_gsc' => floatval($_POST['start_balance_gsc'] ?? 0),
        'start_balance_bp' => intval($_POST['start_balance_bp'] ?? 0),
        'start_balance_sh' => floatval($_POST['start_balance_sh'] ?? 0),
        'maintenance_mode' => isset($_POST['maintenance_mode']),
        'user_approval' => sanitize_text_field($_POST['user_approval'] ?? 'auto'),
        'seo_urls' => isset($_POST['seo_urls']),
        'cookie_consent' => isset($_POST['cookie_consent']),
    ]);
    
    echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved.', 'adnetwork') . '</p></div>';
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post">
        <?php wp_nonce_field('adnetwork_save_settings', 'adnetwork_settings_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><label for="network_name"><?php _e('Network Name', 'adnetwork'); ?></label></th>
                <td>
                    <input type="text" id="network_name" name="network_name" 
                           value="<?php echo esc_attr($settings->get('network_name')); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="project_email"><?php _e('Project Email', 'adnetwork'); ?></label></th>
                <td>
                    <input type="email" id="project_email" name="project_email" 
                           value="<?php echo esc_attr($settings->get('project_email')); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="currency_main"><?php _e('Main Currency', 'adnetwork'); ?></label></th>
                <td>
                    <select id="currency_main" name="currency_main">
                        <option value="CP" <?php selected($settings->get('currency_main'), 'CP'); ?>>CP - CashPoints</option>
                        <option value="GSC" <?php selected($settings->get('currency_main'), 'GSC'); ?>>GSC - GoldSurferCoins</option>
                        <option value="BP" <?php selected($settings->get('currency_main'), 'BP'); ?>>BP - Boost Punkte</option>
                        <option value="SH" <?php selected($settings->get('currency_main'), 'SH'); ?>>SH - Shimly</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Start Balances (New Users)', 'adnetwork'); ?></th>
                <td>
                    <p><label>CP: <input type="number" name="start_balance_cp" value="<?php echo esc_attr($settings->get('start_balance_cp')); ?>" step="0.01"></label></p>
                    <p><label>GSC: <input type="number" name="start_balance_gsc" value="<?php echo esc_attr($settings->get('start_balance_gsc')); ?>" step="0.00000001"></label></p>
                    <p><label>BP: <input type="number" name="start_balance_bp" value="<?php echo esc_attr($settings->get('start_balance_bp')); ?>" step="1"></label></p>
                    <p><label>SH: <input type="number" name="start_balance_sh" value="<?php echo esc_attr($settings->get('start_balance_sh')); ?>" step="0.01"></label></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Maintenance Mode', 'adnetwork'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="maintenance_mode" <?php checked($settings->get('maintenance_mode')); ?>>
                        <?php _e('Enable maintenance mode', 'adnetwork'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="user_approval"><?php _e('User Approval', 'adnetwork'); ?></label></th>
                <td>
                    <select id="user_approval" name="user_approval">
                        <option value="auto" <?php selected($settings->get('user_approval'), 'auto'); ?>><?php _e('Auto-approve', 'adnetwork'); ?></option>
                        <option value="email" <?php selected($settings->get('user_approval'), 'email'); ?>><?php _e('Email confirmation', 'adnetwork'); ?></option>
                        <option value="admin" <?php selected($settings->get('user_approval'), 'admin'); ?>><?php _e('Admin approval', 'adnetwork'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('SEO URLs', 'adnetwork'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="seo_urls" <?php checked($settings->get('seo_urls')); ?>>
                        <?php _e('Enable search engine friendly URLs', 'adnetwork'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Cookie Consent', 'adnetwork'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="cookie_consent" <?php checked($settings->get('cookie_consent')); ?>>
                        <?php _e('Show cookie consent bar (DSGVO)', 'adnetwork'); ?>
                    </label>
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <button type="submit" class="button button-primary">
                <?php _e('Save Changes', 'adnetwork'); ?>
            </button>
        </p>
    </form>
</div>
