<?php
/**
 * Auth — Login, Register, Logout
 */

namespace ADNetwork\User;

class Auth {
    
    /** @var \ADNetwork\Core\Plugin */
    private $plugin;
    
    public function __construct(\ADNetwork\Core\Plugin $plugin) {
        $this->plugin = $plugin;
        $this->registerHooks();
    }
    
    private function registerHooks(): void {
        add_action('init', [$this, 'handleAuthActions']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueAuthAssets']);
        add_shortcode('adnetwork_login', [$this, 'renderLoginForm']);
        add_shortcode('adnetwork_register', [$this, 'renderRegisterForm']);
        add_shortcode('adnetwork_logout', [$this, 'renderLogoutLink']);
    }
    
    public function enqueueAuthAssets(): void {
        wp_enqueue_style('adnetwork-auth', ADN_PLUGIN_URL . 'assets/css/auth.css', [], ADN_VERSION);
        wp_enqueue_script('adnetwork-auth', ADN_PLUGIN_URL . 'assets/js/auth.js', ['jquery'], ADN_VERSION, true);
        
        wp_localize_script('adnetwork-auth', 'adnetworkAuth', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('adnetwork_auth_nonce'),
        ]);
    }
    
    /**
     * Login/Register/Logout Aktionen verarbeiten
     */
    public function handleAuthActions(): void {
        // Login
        if (isset($_POST['adnetwork_login'])) {
            $this->processLogin();
        }
        
        // Register
        if (isset($_POST['adnetwork_register'])) {
            $this->processRegister();
        }
        
        // Logout
        if (isset($_GET['adnetwork_logout'])) {
            $this->processLogout();
        }
    }
    
    /**
     * Login verarbeiten
     */
    private function processLogin(): void {
        if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'adnetwork_login_nonce')) {
            wp_die(__('Security check failed.', 'adnetwork'));
        }
        
        $username = sanitize_user($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        if (empty($username) || empty($password)) {
            wp_redirect(add_query_arg('login_error', 'empty_fields', wp_get_referer()));
            exit;
        }
        
        $user = wp_signon([
            'user_login' => $username,
            'user_password' => $password,
            'remember' => $remember,
        ]);
        
        if (is_wp_error($user)) {
            wp_redirect(add_query_arg('login_error', 'invalid_credentials', wp_get_referer()));
            exit;
        }
        
        wp_redirect(remove_query_arg('login_error', wp_get_referer()));
        exit;
    }
    
    /**
     * Registration verarbeiten
     */
    private function processRegister(): void {
        if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'adnetwork_register_nonce')) {
            wp_die(__('Security check failed.', 'adnetwork'));
        }
        
        $username = sanitize_user($_POST['username'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $role = sanitize_text_field($_POST['role'] ?? 'member'); // member oder sponsor
        
        // Validierung
        if (empty($username) || empty($email) || empty($password)) {
            wp_redirect(add_query_arg('register_error', 'empty_fields', wp_get_referer()));
            exit;
        }
        
        if ($password !== $password_confirm) {
            wp_redirect(add_query_arg('register_error', 'password_mismatch', wp_get_referer()));
            exit;
        }
        
        if (strlen($password) < 6) {
            wp_redirect(add_query_arg('register_error', 'password_too_short', wp_get_referer()));
            exit;
        }
        
        if (!is_email($email)) {
            wp_redirect(add_query_arg('register_error', 'invalid_email', wp_get_referer()));
            exit;
        }
        
        // Blacklist prüfen
        $blacklist = $this->plugin->settings->get('email_blacklist', []);
        $domain = substr(strrchr($email, '@'), 1);
        if (in_array($domain, $blacklist)) {
            wp_redirect(add_query_arg('register_error', 'email_blacklisted', wp_get_referer()));
            exit;
        }
        
        // User erstellen
        $user_id = wp_insert_user([
            'user_login' => $username,
            'user_email' => $email,
            'user_pass' => $password,
            'role' => 'subscriber', // WordPress-Standard, wir setzen unsere eigene Rolle
        ]);
        
        if (is_wp_error($user_id)) {
            wp_redirect(add_query_arg('register_error', $user_id->get_error_code(), wp_get_referer()));
            exit;
        }
        
        // ADNetwork-Profil erstellen
        $this->createUserProfile($user_id, $role);
        
        // Auto-login nach Registration
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        
        wp_redirect(remove_query_arg(['register_error', 'login_error'], wp_get_referer()));
        exit;
    }
    
    /**
     * User-Profil in ADNetwork-Tabelle erstellen
     */
    private function createUserProfile(int $user_id, string $role): void {
        global $wpdb;
        
        $table = $wpdb->prefix . 'adn_user_profiles';
        
        // Referral-Code generieren
        $referral_code = $this->generateReferralCode();
        
        // Länder-Check
        $country = '';
        if ($this->plugin->settings->get('ip_country_check')) {
            $country = $this->getCountryFromIP();
        }
        
        $wpdb->insert($table, [
            'user_id' => $user_id,
            'role' => $role,
            'balance' => $this->plugin->settings->get('default_user_balance', 0),
            'gsc_balance' => 0,
            'referral_code' => $referral_code,
            'country' => $country,
            'status' => 'active',
        ]);
    }
    
    /**
     * Referral-Code generieren
     */
    private function generateReferralCode(): string {
        return 'ADN' . wp_rand(100000, 999999);
    }
    
    /**
     * Land aus IP ermitteln
     */
    private function getCountryFromIP(): string {
        // Vereinfacht — in Produktion mit GeoIP
        return '';
    }
    
    /**
     * Logout verarbeiten
     */
    private function processLogout(): void {
        wp_logout();
        wp_redirect(home_url());
        exit;
    }
    
    /**
     * Login-Formular Shortcode
     */
    public function renderLoginForm($atts): string {
        if (is_user_logged_in()) {
            return '<p>' . __('You are already logged in.', 'adnetwork') . '</p>';
        }
        
        ob_start();
        include ADN_PLUGIN_DIR . 'templates/frontend/auth/login-form.php';
        return ob_get_clean();
    }
    
    /**
     * Register-Formular Shortcode
     */
    public function renderRegisterForm($atts): string {
        if (is_user_logged_in()) {
            return '<p>' . __('You are already registered.', 'adnetwork') . '</p>';
        }
        
        ob_start();
        include ADN_PLUGIN_DIR . 'templates/frontend/auth/register-form.php';
        return ob_get_clean();
    }
    
    /**
     * Logout-Link Shortcode
     */
    public function renderLogoutLink($atts): string {
        if (!is_user_logged_in()) {
            return '';
        }
        
        $url = add_query_arg('adnetwork_logout', '1');
        return '<a href="' . esc_url($url) . '" class="adnetwork-logout-link">' . __('Logout', 'adnetwork') . '</a>';
    }
}
