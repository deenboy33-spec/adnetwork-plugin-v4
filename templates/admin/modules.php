<?php
/**
 * Module Management Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$plugin = ADNetwork\Core\Plugin::instance();
$modules = $plugin->modules;
$available = $modules->getAvailable();

// Form verarbeiten
if (isset($_POST['adnetwork_modules_nonce']) && wp_verify_nonce($_POST['adnetwork_modules_nonce'], 'adnetwork_save_modules')) {
    foreach ($available as $key => $module) {
        if ($module['required']) {
            continue;
        }
        
        $active = isset($_POST['module_' . $key]);
        
        if ($active) {
            $modules->activate($key);
        } else {
            $modules->deactivate($key);
        }
    }
    
    echo '<div class="notice notice-success is-dismissible"><p>' . __('Modules saved.', 'adnetwork') . '</p></div>';
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post">
        <?php wp_nonce_field('adnetwork_save_modules', 'adnetwork_modules_nonce'); ?>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th class="column-cb"><input type="checkbox" disabled checked></th>
                    <th><?php _e('Module', 'adnetwork'); ?></th>
                    <th><?php _e('Description', 'adnetwork'); ?></th>
                    <th><?php _e('Version', 'adnetwork'); ?></th>
                    <th><?php _e('Status', 'adnetwork'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($available as $key => $module): 
                    $isActive = $modules->isActive($key);
                    $isRequired = $module['required'];
                ?>
                <tr>
                    <td>
                        <input type="checkbox" 
                               name="module_<?php echo esc_attr($key); ?>" 
                               <?php checked($isActive); ?> 
                               <?php disabled($isRequired); ?>>
                    </td>
                    <td><strong><?php echo esc_html($module['name']); ?></strong></td>
                    <td><?php echo esc_html($module['description']); ?></td>
                    <td><?php echo esc_html($module['version']); ?></td>
                    <td>
                        <?php if ($isRequired): ?>
                            <span class="adnetwork-badge adnetwork-badge-required"><?php _e('Required', 'adnetwork'); ?></span>
                        <?php elseif ($isActive): ?>
                            <span class="adnetwork-badge adnetwork-badge-active"><?php _e('Active', 'adnetwork'); ?></span>
                        <?php else: ?>
                            <span class="adnetwork-badge adnetwork-badge-inactive"><?php _e('Inactive', 'adnetwork'); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p class="submit">
            <button type="submit" class="button button-primary">
                <?php _e('Save Changes', 'adnetwork'); ?>
            </button>
        </p>
    </form>
</div>
