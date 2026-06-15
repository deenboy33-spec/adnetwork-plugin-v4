<?php
/**
 * Admin Dashboard Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$plugin = ADNetwork\Core\Plugin::instance();
$activeModules = $plugin->modules->getActive();
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="adnetwork-dashboard">
        <div class="adnetwork-stats-grid">
            <div class="adnetwork-stat-card">
                <h3><?php _e('Active Modules', 'adnetwork'); ?></h3>
                <div class="adnetwork-stat-number"><?php echo count($activeModules); ?></div>
                <p><?php _e('of', 'adnetwork'); ?> <?php echo count($plugin->modules->getAvailable()); ?> <?php _e('total', 'adnetwork'); ?></p>
            </div>
            
            <div class="adnetwork-stat-card">
                <h3><?php _e('Version', 'adnetwork'); ?></h3>
                <div class="adnetwork-stat-number"><?php echo esc_html(ADN_VERSION); ?></div>
            </div>
        </div>
        
        <h2><?php _e('Active Modules', 'adnetwork'); ?></h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Module', 'adnetwork'); ?></th>
                    <th><?php _e('Description', 'adnetwork'); ?></th>
                    <th><?php _e('Version', 'adnetwork'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activeModules as $key => $module): ?>
                <tr>
                    <td><strong><?php echo esc_html($module['name']); ?></strong></td>
                    <td><?php echo esc_html($module['description']); ?></td>
                    <td><?php echo esc_html($module['version']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
