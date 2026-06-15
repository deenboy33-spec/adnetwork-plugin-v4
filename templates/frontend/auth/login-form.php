<?php
/**
 * Login Form Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$error = $_GET['login_error'] ?? '';
?>

<div class="adnetwork-auth-container">
    <h2><?php _e('Login', 'adnetwork'); ?></h2>
    
    <?php if ($error): ?>
        <div class="adnetwork-error">
            <?php
            switch ($error) {
                case 'empty_fields':
                    _e('Please fill in all fields.', 'adnetwork');
                    break;
                case 'invalid_credentials':
                    _e('Invalid username or password.', 'adnetwork');
                    break;
                default:
                    _e('An error occurred. Please try again.', 'adnetwork');
            }
            ?>
        </div>
    <?php endif; ?>
    
    <form method="post" action="">
        <?php wp_nonce_field('adnetwork_login_nonce'); ?>
        
        <div class="adnetwork-form-group">
            <label for="username"><?php _e('Username or Email', 'adnetwork'); ?></label>
            <input type="text" name="username" id="username" required>
        </div>
        
        <div class="adnetwork-form-group">
            <label for="password"><?php _e('Password', 'adnetwork'); ?></label>
            <input type="password" name="password" id="password" required>
        </div>
        
        <div class="adnetwork-form-group">
            <label>
                <input type="checkbox" name="remember" value="1">
                <?php _e('Remember me', 'adnetwork'); ?>
            </label>
        </div>
        
        <button type="submit" name="adnetwork_login" class="adnetwork-btn adnetwork-btn-primary">
            <?php _e('Login', 'adnetwork'); ?>
        </button>
        
        <p class="adnetwork-auth-links">
            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php _e('Forgot password?', 'adnetwork'); ?></a>
        </p>
    </form>
</div>
