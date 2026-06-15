<?php
/**
 * ADNetwork Main Plugin Class
 * 
 * Singleton — nur eine Instanz im gesamten WordPress-Lifecycle
 */

namespace ADNetwork\Core;

class Plugin {
    
    private static $instance = null;
    
    /** @var ModuleManager */
    public $modules;
    
    /** @var Settings */
    public $settings;
    
    /** @var Database */
    public $database;
    
    /** @var Logger */
    public $logger;
    
    private function __construct() {
        $this->loadCore();
        $this->initModules();
        $this->registerHooks();
    }
    
    public static function instance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Core-Komponenten laden
     */
    private function loadCore(): void {
        $this->settings = new Settings();
        $this->database = new Database();
        $this->logger = new Logger();
        $this->modules = new ModuleManager($this);
    }
    
    /**
     * Alle aktivierten Module initialisieren
     */
    private function initModules(): void {
        $this->modules->loadActiveModules();
    }
    
    /**
     * WordPress Hooks registrieren
     */
    private function registerHooks(): void {
        add_action('init', [$this, 'onInit']);
        add_action('admin_menu', [$this, 'onAdminMenu']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontendAssets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);
    }
    
    /**
     * WordPress init Hook
     */
    public function onInit(): void {
        load_plugin_textdomain(
            'adnetwork',
            false,
            dirname(ADN_PLUGIN_BASENAME) . '/languages/'
        );
        
        do_action('adnetwork_init', $this);
    }
    
    /**
     * Admin-Menü registrieren
     */
    public function onAdminMenu(): void {
        add_menu_page(
            __('ADNetwork', 'adnetwork'),
            __('ADNetwork', 'adnetwork'),
            'manage_options',
            'adnetwork',
            [$this, 'renderAdminDashboard'],
            'dashicons-megaphone',
            30
        );
        
        add_submenu_page(
            'adnetwork',
            __('Dashboard', 'adnetwork'),
            __('Dashboard', 'adnetwork'),
            'manage_options',
            'adnetwork',
            [$this, 'renderAdminDashboard']
        );
        
        add_submenu_page(
            'adnetwork',
            __('Modules', 'adnetwork'),
            __('Modules', 'adnetwork'),
            'manage_options',
            'adnetwork-modules',
            [$this, 'renderModulePage']
        );
        
        add_submenu_page(
            'adnetwork',
            __('Settings', 'adnetwork'),
            __('Settings', 'adnetwork'),
            'manage_options',
            'adnetwork-settings',
            [$this, 'renderSettingsPage']
        );
    }
    
    /**
     * Frontend Assets laden
     */
    public function enqueueFrontendAssets(): void {
        wp_enqueue_style(
            'adnetwork-frontend',
            ADN_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            ADN_VERSION
        );
        
        wp_enqueue_script(
            'adnetwork-frontend',
            ADN_PLUGIN_URL . 'assets/js/frontend.js',
            ['jquery'],
            ADN_VERSION,
            true
        );
    }
    
    /**
     * Admin Assets laden
     */
    public function enqueueAdminAssets($hook): void {
        if (strpos($hook, 'adnetwork') === false) {
            return;
        }
        
        wp_enqueue_style(
            'adnetwork-admin',
            ADN_PLUGIN_URL . 'assets/css/admin.css',
            [],
            ADN_VERSION
        );
        
        wp_enqueue_script(
            'adnetwork-admin',
            ADN_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery'],
            ADN_VERSION,
            true
        );
    }
    
    /**
     * Admin Dashboard rendern
     */
    public function renderAdminDashboard(): void {
        include ADN_PLUGIN_DIR . 'templates/admin/dashboard.php';
    }
    
    /**
     * Modul-Seite rendern
     */
    public function renderModulePage(): void {
        include ADN_PLUGIN_DIR . 'templates/admin/modules.php';
    }
    
    /**
     * Einstellungs-Seite rendern
     */
    public function renderSettingsPage(): void {
        include ADN_PLUGIN_DIR . 'templates/admin/settings.php';
    }
}
