<?php
/**
 * User Module — Initialisiert das User-System
 */

namespace ADNetwork\User;

class UserModule {
    
    public function __construct(\ADNetwork\Core\Plugin $plugin) {
        new Auth($plugin);
        new Profile($plugin);
        new Roles();
        
        // Admin-Hooks für User-Verwaltung
        add_action('adnetwork_admin_menu', [$this, 'addAdminMenu']);
    }
    
    public function addAdminMenu(): void {
        add_submenu_page(
            'adnetwork',
            __('Users', 'adnetwork'),
            __('Users', 'adnetwork'),
            'manage_options',
            'adnetwork-users',
            [$this, 'renderUsersPage']
        );
    }
    
    public function renderUsersPage(): void {
        include ADN_PLUGIN_DIR . 'templates/admin/users.php';
    }
}
