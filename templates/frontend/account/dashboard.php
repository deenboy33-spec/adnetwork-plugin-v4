<?php
/**
 * Account Dashboard Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$user = wp_get_current_user();
$profile = $profile ?? null;
?>

<div class="adnetwork-account">
    <h2><?php printf(__('Welcome, %s!', 'adnetwork'), esc_html($user->display_name)); ?></h2>
    
    <div class="adnetwork-account-stats">
        <div class="adnetwork-stat-card">
            <h3><?php _e('Balance', 'adnetwork'); ?></h3>
            <div class="adnetwork-stat-number"><?php echo number_format($profile->balance ?? 0, 2); ?> €</div>
        </div>
        
        <div class="adnetwork-stat-card">
            <h3><?php _e('GSC Balance', 'adnetwork'); ?></h3>
            <div class="adnetwork-stat-number"><?php echo number_format($profile->gsc_balance ?? 0, 8); ?> GSC</div>
        </div>
        
        <div class="adnetwork-stat-card">
            <h3><?php _e('Referral Code', 'adnetwork'); ?></h3>
            <div class="adnetwork-stat-number" style="font-size: 18px;"><?php echo esc_html($profile->referral_code ?? ''); ?></div>
        </div>
    </div>
    
    <div class="adnetwork-account-menu">
        <h3><?php _e('Quick Links', 'adnetwork'); ?></h3>
        <ul>
            <li><a href="<?php echo esc_url(get_permalink()); ?>?view=profile"><?php _e('My Profile', 'adnetwork'); ?></a></li>
            <li><a href="#"><?php _e('My Campaigns', 'adnetwork'); ?></a></li>
            <li><a href="#"><?php _e('My Websites', 'adnetwork'); ?></a></li>
            <li><a href="#"><?php _e('Transactions', 'adnetwork'); ?></a></li>
            <li><a href="#"><?php _e('Referrals', 'adnetwork'); ?></a></li>
            <li><a href="#"><?php _e('Settings', 'adnetwork'); ?></a></li>
        </ul>
    </div>
</div>
