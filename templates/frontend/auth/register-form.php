<?php
/**
 * Register Form Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$error = $_GET['register_error'] ?? '';
?>

<div class="adnetwork-auth-container">
    <h2><?php _e('Register', 'adnetwork'); ?></h2>
    
    <?php if ($error): ?>
        <div class="adnetwork-error">
            <?php
            switch ($error) {
                case 'empty_fields':
                    _e('Please fill in all fields.', 'adnetwork');
                    break;
                case 'password_mismatch':
                    _e('Passwords do not match.', 'adnetwork');
                    break;
                case 'password_too_short':
                    _e('Password must be at least 6 characters.', 'adnetwork');
                    break;
                case 'invalid_email':
                    _e('Please enter a valid email address.', 'adnetwork');
                    break;
                case 'email_blacklisted':
                    _e('This email provider is not allowed.', 'adnetwork');
                    break;
                case 'existing_user_login':
                    _e('Username already exists.', 'adnetwork');
                    break;
                case 'existing_user_email':
                    _e('Email already registered.', 'adnetwork');
                    break;
                default:
                    _e('An error occurred. Please try again.', 'adnetwork');
            }
            ?>
        </div>
    <?php endif; ?>
    
    <form method="post" action="">
        <?php wp_nonce_field('adnetwork_register_nonce'); ?>
        
        <div class="adnetwork-form-group">
            <label for="reg_username"><?php _e('Username', 'adnetwork'); ?></label>
            <input type="text" name="username" id="reg_username" required>
        </div>
        
        <div class="adnetwork-form-group">
            <label for="reg_email"><?php _e('Email', 'adnetwork'); ?></label>
            <input type="email" name="email" id="reg_email" required>
        </div>
        
        <div class="adnetwork-form-group">
            <label for="reg_password"><?php _e('Password', 'adnetwork'); ?></label>
            <input type="password" name="password" id="reg_password" required minlength="6">
        </div>
        
        <div class="adnetwork-form-group">
            <label for="reg_password_confirm"><?php _e('Confirm Password', 'adnetwork'); ?></label>
            <input type="password" name="password_confirm" id="reg_password_confirm" required>
        </div>
        
        <div class="adnetwork-form-group">
            <label for="reg_role"><?php _e('Account Type', 'adnetwork'); ?></label>
            <select name="role" id="reg_role">
                <option value="member"><?php _e('Member (Earn money)', 'adnetwork'); ?></option>
                <option value="sponsor"><?php _e('Sponsor (Advertise)', 'adnetwork'); ?></option>
            </select>
        </div>
        
        <button type="submit" name="adnetwork_register" class="adnetwork-btn adnetwork-btn-primary">
            <?php _e('Register', 'adnetwork'); ?>
        </button>
        
        <p class="adnetwork-auth-links">
            <?php _e('Already have an account?', 'adnetwork'); ?> <a href="<?php echo esc_url(add_query_arg('action', 'login')); ?>"><?php _e('Login here', 'adnetwork'); ?></a>
        </p>
    </form>
</div>
