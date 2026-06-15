<?php
/**
 * User Roles — Rollen & Rechte
 */

namespace ADNetwork\User;

class Roles {
    
    /** @var array Definierte Rollen */
    private $roles = [
        'member' => [
            'name' => 'Member',
            'capabilities' => [
                'read' => true,
                'adnetwork_member' => true,
            ],
        ],
        'sponsor' => [
            'name' => 'Sponsor',
            'capabilities' => [
                'read' => true,
                'adnetwork_member' => true,
                'adnetwork_sponsor' => true,
            ],
        ],
        'publisher' => [
            'name' => 'Publisher',
            'capabilities' => [
                'read' => true,
                'adnetwork_member' => true,
                'adnetwork_publisher' => true,
            ],
        ],
        'advertiser' => [
            'name' => 'Advertiser',
            'capabilities' => [
                'read' => true,
                'adnetwork_member' => true,
                'adnetwork_sponsor' => true,
                'adnetwork_advertiser' => true,
            ],
        ],
        'moderator' => [
            'name' => 'Moderator',
            'capabilities' => [
                'read' => true,
                'adnetwork_member' => true,
                'adnetwork_moderator' => true,
            ],
        ],
    ];
    
    public function __construct() {
        $this->registerRoles();
    }
    
    /**
     * Custom Rollen registrieren
     */
    private function registerRoles(): void {
        foreach ($this->roles as $role_key => $role_data) {
            add_role(
                'adnetwork_' . $role_key,
                $role_data['name'],
                $role_data['capabilities']
            );
        }
    }
    
    /**
     * Prüfen ob User eine bestimmte ADNetwork-Rolle hat
     */
    public static function hasRole(int $user_id, string $role): bool {
        $user = get_user_by('id', $user_id);
        if (!$user) {
            return false;
        }
        
        return in_array('adnetwork_' . $role, $user->roles) || user_can($user, 'adnetwork_' . $role);
    }
    
    /**
     * Rolle zuweisen
     */
    public static function assignRole(int $user_id, string $role): bool {
        $user = get_user_by('id', $user_id);
        if (!$user) {
            return false;
        }
        
        $user->add_role('adnetwork_' . $role);
        return true;
    }
    
    /**
     * Alle verfügbaren Rollen abrufen
     */
    public function getAll(): array {
        return $this->roles;
    }
    
    /**
     * Rollen-Name abrufen
     */
    public function getName(string $role): string {
        return $this->roles[$role]['name'] ?? ucfirst($role);
    }
}
