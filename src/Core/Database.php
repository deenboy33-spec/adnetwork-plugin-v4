<?php
/**
 * Database
 * 
 * Datenbank-Schema und Migrationen
 */

namespace ADNetwork\Core;

class Database {
    
    /** @var string DB-Version */
    private $dbVersion = '4.0.0';
    
    /**
     * Plugin-Aktivierung — Tabellen erstellen
     */
    public static function activate(): void {
        $instance = new self();
        $instance->createTables();
        update_option('adnetwork_db_version', $instance->dbVersion);
    }
    
    /**
     * Alle Tabellen erstellen
     */
    public function createTables(): void {
        global $wpdb;
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $charset = $wpdb->get_charset_collate();
        
        // Core-Tabellen
        $this->createCoreTables($charset);
        
        // Weitere Tabellen werden von den Modulen erstellt
        do_action('adnetwork_create_tables', $charset);
    }
    
    /**
     * Core-Tabellen erstellen
     */
    private function createCoreTables(string $charset): void {
        global $wpdb;
        
        // Logs
        $table = $wpdb->prefix . 'adn_logs';
        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            level varchar(20) NOT NULL DEFAULT 'info',
            message text NOT NULL,
            context longtext DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY level (level),
            KEY created_at (created_at)
        ) {$charset};";
        dbDelta($sql);
        
        // API Keys
        $table = $wpdb->prefix . 'adn_api_keys';
        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned NOT NULL,
            api_key varchar(255) NOT NULL,
            permissions longtext DEFAULT NULL,
            last_used datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY api_key (api_key),
            KEY user_id (user_id)
        ) {$charset};";
        dbDelta($sql);
        
        // User Profiles (erweiterte Daten)
        $table = $wpdb->prefix . 'adn_user_profiles';
        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned NOT NULL,
            role varchar(50) NOT NULL DEFAULT 'member',
            balance decimal(15,2) NOT NULL DEFAULT 0.00,
            gsc_balance decimal(15,8) NOT NULL DEFAULT 0.00000000,
            referrer_id bigint(20) unsigned DEFAULT NULL,
            referral_code varchar(50) DEFAULT NULL,
            country varchar(10) DEFAULT NULL,
            gender varchar(20) DEFAULT NULL,
            birthdate date DEFAULT NULL,
            interests longtext DEFAULT NULL,
            settings longtext DEFAULT NULL,
            status varchar(20) NOT NULL DEFAULT 'active',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY user_id (user_id),
            UNIQUE KEY referral_code (referral_code),
            KEY role (role),
            KEY status (status)
        ) {$charset};";
        dbDelta($sql);
        
        // Transaktionen
        $table = $wpdb->prefix . 'adn_transactions';
        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned NOT NULL,
            type varchar(50) NOT NULL,
            amount decimal(15,2) NOT NULL,
            currency varchar(10) NOT NULL DEFAULT 'EUR',
            description text DEFAULT NULL,
            reference_type varchar(50) DEFAULT NULL,
            reference_id bigint(20) unsigned DEFAULT NULL,
            status varchar(20) NOT NULL DEFAULT 'completed',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY type (type),
            KEY status (status),
            KEY created_at (created_at)
        ) {$charset};";
        dbDelta($sql);
    }
    
    /**
     * Prüfen ob Tabelle existiert
     */
    public function tableExists(string $table): bool {
        global $wpdb;
        return $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}{$table}'") === $wpdb->prefix . $table;
    }
}
