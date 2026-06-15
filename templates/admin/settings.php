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
        'currency' => sanitize_text_field($_POST['currency'] ?? 'EUR'),
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
                <th scope="row"><label for="currency"><?php _e('Currency', 'adnetwork'); ?></label></th>
                <td>
                    <select id="currency" name="currency">
                        <option value="EUR" <?php selected($settings->get('currency'), 'EUR'); ?>>EUR (€)</option>
                        <option value="USD" <?php selected($settings->get('currency'), 'USD'); ?>>USD ($)</option>
                    </select>
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
