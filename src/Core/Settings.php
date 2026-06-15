<?php
/**
 * Settings
 * 
 * Zentrale Konfiguration für das Plugin
 */

namespace ADNetwork\Core;

class Settings {
    
    /** @var array Plugin-Einstellungen */
    private $settings = [];
    
    /** @var array Defaults */
    private $defaults = [
        'network_name' => 'ADNetwork',
        'project_email' => 'info@adnetwork.management',
        'currency' => 'EUR',
        'currency_symbol' => '€',
        'maintenance_mode' => false,
        'user_approval' => 'auto', // auto, email, admin
        'sponsor_approval' => 'auto',
        'default_user_balance' => 0,
        'default_sponsor_balance' => 0,
        'min_payout' => 10.00,
        'referral_levels' => 15,
        'referral_commission' => 10, // Prozent
        'cookie_consent' => true,
        'seo_urls' => true,
        'ip_country_check' => false,
        'email_blacklist' => [],
        'allowed_countries' => [],
        'backup_auto' => false,
        'backup_interval' => 'daily',
    ];
    
    public function __construct() {
        $this->load();
    }
    
    /**
     * Einstellungen aus DB laden
     */
    private function load(): void {
        $saved = get_option('adnetwork_settings', []);
        $this->settings = wp_parse_args($saved, $this->defaults);
    }
    
    /**
     * Einstellung abrufen
     */
    public function get(string $key, $default = null) {
        return $this->settings[$key] ?? $default ?? $this->defaults[$key] ?? null;
    }
    
    /**
     * Einstellung setzen
     */
    public function set(string $key, $value): void {
        $this->settings[$key] = $value;
        $this->save();
    }
    
    /**
     * Mehrere Einstellungen setzen
     */
    public function setMultiple(array $values): void {
        foreach ($values as $key => $value) {
            $this->settings[$key] = $value;
        }
        $this->save();
    }
    
    /**
     * Alle Einstellungen abrufen
     */
    public function all(): array {
        return $this->settings;
    }
    
    /**
     * Einstellungen in DB speichern
     */
    private function save(): void {
        update_option('adnetwork_settings', $this->settings);
    }
    
    /**
     * Auf Defaults zurücksetzen
     */
    public function reset(): void {
        $this->settings = $this->defaults;
        $this->save();
    }
}
