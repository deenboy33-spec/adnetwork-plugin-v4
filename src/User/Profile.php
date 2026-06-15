<?php
/**
 * User Profile — Profil anzeigen & bearbeiten
 */

namespace ADNetwork\User;

class Profile {
    
    /** @var \ADNetwork\Core\Plugin */
    private $plugin;
    
    public function __construct(\ADNetwork\Core\Plugin $plugin) {
        $this->plugin = $plugin;
        $this->registerHooks();
    }
    
    private function registerHooks(): void {
        add_shortcode('adnetwork_profile', [$this, 'renderProfile']);
        add_shortcode('adnetwork_account', [$this, 'renderAccount']);
        add_action('init', [$this, 'handleProfileUpdate']);
    }
    
    /**
     * Profil-Update verarbeiten
     */
    public function handleProfileUpdate(): void {
        if (!isset($_POST['adnetwork_update_profile'])) {
            return;
        }
        
        if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'adnetwork_profile_nonce')) {
            wp_die(__('Security check failed.', 'adnetwork'));
        }
        
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_redirect(wp_login_url());
            exit;
        }
        
        // WordPress-Profil aktualisieren
        wp_update_user([
            'ID' => $user_id,
            'display_name' => sanitize_text_field($_POST['display_name'] ?? ''),
            'first_name' => sanitize_text_field($_POST['first_name'] ?? ''),
            'last_name' => sanitize_text_field($_POST['last_name'] ?? ''),
        ]);
        
        // ADNetwork-Profil aktualisieren
        $this->updateADNetworkProfile($user_id);
        
        wp_redirect(add_query_arg('profile_updated', '1', wp_get_referer()));
        exit;
    }
    
    /**
     * ADNetwork-Profil aktualisieren
     */
    private function updateADNetworkProfile(int $user_id): void {
        global $wpdb;
        
        $table = $wpdb->prefix . 'adn_user_profiles';
        
        $data = [
            'gender' => sanitize_text_field($_POST['gender'] ?? ''),
            'birthdate' => sanitize_text_field($_POST['birthdate'] ?? ''),
            'interests' => sanitize_textarea_field($_POST['interests'] ?? ''),
            'updated_at' => current_time('mysql'),
        ];
        
        $wpdb->update($table, $data, ['user_id' => $user_id]);
    }
    
    /**
     * Profil-Shortcode
     */
    public function renderProfile($atts): string {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please login to view your profile.', 'adnetwork') . '</p>';
        }
        
        $user = wp_get_current_user();
        $profile = $this->getProfile($user->ID);
        
        ob_start();
        include ADN_PLUGIN_DIR . 'templates/frontend/profile/view.php';
        return ob_get_clean();
    }
    
    /**
     * Account-Shortcode (Mein Konto)
     */
    public function renderAccount($atts): string {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please login to view your account.', 'adnetwork') . '</p>';
        }
        
        $user = wp_get_current_user();
        $profile = $this->getProfile($user->ID);
        
        ob_start();
        include ADN_PLUGIN_DIR . 'templates/frontend/account/dashboard.php';
        return ob_get_clean();
    }
    
    /**
     * Profil-Daten abrufen
     */
    public function getProfile(int $user_id): ?object {
        global $wpdb;
        
        $table = $wpdb->prefix . 'adn_user_profiles';
        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table} WHERE user_id = %d",
            $user_id
        ));
        
        return $row;
    }
    
    /**
     * Guthaben abrufen
     */
    public function getBalance(int $user_id): float {
        $profile = $this->getProfile($user_id);
        return $profile ? (float) $profile->balance : 0;
    }
    
    /**
     * Rolle abrufen
     */
    public function getRole(int $user_id): string {
        $profile = $this->getProfile($user_id);
        return $profile ? $profile->role : 'member';
    }
}
