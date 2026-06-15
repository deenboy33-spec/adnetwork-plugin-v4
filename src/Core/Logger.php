<?php
/**
 * Logger
 * 
 * Einfaches Logging-System
 */

namespace ADNetwork\Core;

class Logger {
    
    const LEVEL_DEBUG = 'debug';
    const LEVEL_INFO = 'info';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR = 'error';
    
    /**
     * Log-Eintrag erstellen
     */
    public function log(string $level, string $message, array $context = []): void {
        global $wpdb;
        
        $table = $wpdb->prefix . 'adn_logs';
        
        $wpdb->insert($table, [
            'level' => $level,
            'message' => $message,
            'context' => !empty($context) ? wp_json_encode($context) : null,
        ]);
    }
    
    public function debug(string $message, array $context = []): void {
        $this->log(self::LEVEL_DEBUG, $message, $context);
    }
    
    public function info(string $message, array $context = []): void {
        $this->log(self::LEVEL_INFO, $message, $context);
    }
    
    public function warning(string $message, array $context = []): void {
        $this->log(self::LEVEL_WARNING, $message, $context);
    }
    
    public function error(string $message, array $context = []): void {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }
    
    /**
     * Logs abrufen
     */
    public function get(int $limit = 100, string $level = null): array {
        global $wpdb;
        
        $table = $wpdb->prefix . 'adn_logs';
        $where = '';
        
        if ($level) {
            $where = $wpdb->prepare(" WHERE level = %s", $level);
        }
        
        return $wpdb->get_results(
            "SELECT * FROM {$table}{$where} ORDER BY created_at DESC LIMIT {$limit}",
            ARRAY_A
        );
    }
}
