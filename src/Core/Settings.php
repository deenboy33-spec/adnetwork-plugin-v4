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
        
        // Währungen
        'currency_main' => 'CP',       // Hauptwährung: CashPoints
        'currency_crypto' => 'GSC',    // Krypto: GoldSurferCoins
        'currency_points' => 'BP',     // Punkte: Boost Punkte
        'currency_social' => 'SH',     // Social: Shimly
        
        // Symbole
        'symbol_cp' => 'CP',
        'symbol_gsc' => 'GSC',
        'symbol_bp' => 'BP',
        'symbol_sh' => 'SH',
        
        // Dezimalstellen
        'decimals_cp' => 2,
        'decimals_gsc' => 8,
        'decimals_bp' => 0,
        'decimals_sh' => 2,
        
        // Startguthaben (neue User)
        'start_balance_cp' => 0,
        'start_balance_gsc' => 0,
        'start_balance_bp' => 0,
        'start_balance_sh' => 0,
        
        'maintenance_mode' => false,
        'user_approval' => 'auto',
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
