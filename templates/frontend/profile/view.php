<?php
/**
 * Profile View Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$user = wp_get_current_user();
$profile = $profile ?? null;
$updated = isset($_GET['profile_updated']);
?>

<div class="adnetwork-profile">
    <h2><?php printf(__('Profile: %s', 'adnetwork'), esc_html($user->display_name)); ?></h2>
    
    <?php if ($updated): ?>
        <div class="adnetwork-success"><?php _e('Profile updated successfully.', 'adnetwork'); ?></div>
    <?php endif; ?>
    
    <div class="adnetwork-profile-grid">
        <div class="adnetwork-profile-info">
            <h3><?php _e('Account Information', 'adnetwork'); ?></h3>
            <p><strong><?php _e('Username:', 'adnetwork'); ?></strong> <?php echo esc_html($user->user_login); ?></p>
            <p><strong><?php _e('Email:', 'adnetwork'); ?></strong> <?php echo esc_html($user->user_email); ?></p>
            <p><strong><?php _e('Role:', 'adnetwork'); ?></strong> <?php echo esc_html(ucfirst($profile->role ?? 'member')); ?></p>
            <p><strong><?php _e('CashPoints (CP):', 'adnetwork'); ?></strong> <?php echo number_format($profile->balance_cp ?? 0, 2); ?> CP</p>
            <p><strong><?php _e('GoldSurferCoins (GSC):', 'adnetwork'); ?></strong> <?php echo number_format($profile->balance_gsc ?? 0, 8); ?> GSC</p>
            <p><strong><?php _e('Boost Punkte (BP):', 'adnetwork'); ?></strong> <?php echo number_format($profile->balance_bp ?? 0, 0); ?> BP</p>
            <p><strong><?php _e('Shimly (SH):', 'adnetwork'); ?></strong> <?php echo number_format($profile->balance_sh ?? 0, 2); ?> SH</p>
            <p><strong><?php _e('Referral Code:', 'adnetwork'); ?></strong> <?php echo esc_html($profile->referral_code ?? ''); ?></p>
        </div>
        
        <div class="adnetwork-profile-edit">
            <h3><?php _e('Edit Profile', 'adnetwork'); ?></h3>
            
            <form method="post" action="">
                <?php wp_nonce_field('adnetwork_profile_nonce'); ?>
                
                <div class="adnetwork-form-group">
                    <label for="display_name"><?php _e('Display Name', 'adnetwork'); ?></label>
                    <input type="text" name="display_name" id="display_name" value="<?php echo esc_attr($user->display_name); ?>">
                </div>
                
                <div class="adnetwork-form-group">
                    <label for="first_name"><?php _e('First Name', 'adnetwork'); ?></label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($user->first_name); ?>">
                </div>
                
                <div class="adnetwork-form-group">
                    <label for="last_name"><?php _e('Last Name', 'adnetwork'); ?></label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($user->last_name); ?>">
                </div>
                
                <div class="adnetwork-form-group">
                    <label for="gender"><?php _e('Gender', 'adnetwork'); ?></label>
                    <select name="gender" id="gender">
                        <option value=""><?php _e('Not specified', 'adnetwork'); ?></option>
                        <option value="male" <?php selected($profile->gender ?? '', 'male'); ?>><?php _e('Male', 'adnetwork'); ?></option>
                        <option value="female" <?php selected($profile->gender ?? '', 'female'); ?>><?php _e('Female', 'adnetwork'); ?></option>
                        <option value="other" <?php selected($profile->gender ?? '', 'other'); ?>><?php _e('Other', 'adnetwork'); ?></option>
                    </select>
                </div>
                
                <div class="adnetwork-form-group">
                    <label for="birthdate"><?php _e('Birthdate', 'adnetwork'); ?></label>
                    <input type="date" name="birthdate" id="birthdate" value="<?php echo esc_attr($profile->birthdate ?? ''); ?>">
                </div>
                
                <div class="adnetwork-form-group">
                    <label for="interests"><?php _e('Interests', 'adnetwork'); ?></label>
                    <textarea name="interests" id="interests" rows="3"><?php echo esc_textarea($profile->interests ?? ''); ?></textarea>
                </div>
                
                <button type="submit" name="adnetwork_update_profile" class="adnetwork-btn adnetwork-btn-primary">
                    <?php _e('Update Profile', 'adnetwork'); ?>
                </button>
            </form>
        </div>
    </div>
    
    <p>
        <a href="<?php echo esc_url(add_query_arg('adnetwork_logout', '1')); ?>" class="adnetwork-btn adnetwork-btn-danger">
            <?php _e('Logout', 'adnetwork'); ?>
        </a>
    </p>
</div>
