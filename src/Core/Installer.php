<?php
/**
 * Installer
 * 
 * Erstellt automatisch alle benötigten WordPress-Seiten beim Plugin-Aktivieren.
 * Mit Hub-System für Kategorien.
 */

namespace ADNetwork\Core;

class Installer {
    
    /** @var array Hub-Seilen (mehrere Funktionen pro Seite) */
    private $hubPages = [
        'paid4' => [
            'title' => 'Paid4 Bereich',
            'content' => '[adnetwork_hub type="paid4"]',
        ],
        'werben' => [
            'title' => 'Werbe Bereich',
            'content' => '[adnetwork_hub type="werben"]',
        ],
        'konto' => [
            'title' => 'Mein Konto',
            'content' => '[adnetwork_hub type="konto"]',
        ],
        'gsc' => [
            'title' => 'GSC Coin Bereich',
            'content' => '[adnetwork_hub type="gsc"]',
        ],
        'news' => [
            'title' => 'News & Community',
            'content' => '[adnetwork_hub type="news"]',
        ],
    ];
    
    /** @var array Einzelne Seilen (für spezifische Funktionen) */
    private $singlePages = [
        'login' => [
            'title' => 'Login',
            'content' => '[adnetwork_login]',
        ],
        'register' => [
            'title' => 'Register',
            'content' => '[adnetwork_register]',
        ],
        'terms' => [
            'title' => 'Terms of Service',
            'content' => '<p>Please add your terms of service here.</p>',
        ],
        'privacy' => [
            'title' => 'Privacy Policy',
            'content' => '<p>Please add your privacy policy here.</p>',
        ],
        'imprint' => [
            'title' => 'Imprint',
            'content' => '<p>Please add your imprint here.</p>',
        ],
    ];
    
    /**
     * Installation durchführen
     */
    public static function install(): void {
        $instance = new self();
        $instance->createHubPages();
        $instance->createSinglePages();
        $instance->setupOptions();
    }
    
    /**
     * Hub-Seilen erstellen
     */
    public function createHubPages(): void {
        $createdPages = get_option('adnetwork_hub_pages', []);
        
        foreach ($this->hubPages as $key => $pageData) {
            $slug = 'adnetwork-' . $key;
            
            // Prüfen ob Seile schon existiert
            $existing = $this->getPageBySlug($slug);
            
            if ($existing) {
                $createdPages[$key] = $existing->ID;
                continue;
            }
            
            $pageId = wp_insert_post([
                'post_title'   => $pageData['title'],
                'post_name'    => $slug,
                'post_content' => $pageData['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ]);
            
            if ($pageId && !is_wp_error($pageId)) {
                $createdPages[$key] = $pageId;
            }
        }
        
        update_option('adnetwork_hub_pages', $createdPages);
    }
    
    /**
     * Einzelne Seilen erstellen
     */
    public function createSinglePages(): void {
        $createdPages = get_option('adnetwork_pages', []);
        
        foreach ($this->singlePages as $key => $pageData) {
            $slug = 'adnetwork-' . $key;
            
            $existing = $this->getPageBySlug($slug);
            
            if ($existing) {
                $createdPages[$key] = $existing->ID;
                continue;
            }
            
            $pageId = wp_insert_post([
                'post_title'   => $pageData['title'],
                'post_name'    => $slug,
                'post_content' => $pageData['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ]);
            
            if ($pageId && !is_wp_error($pageId)) {
                $createdPages[$key] = $pageId;
            }
        }
        
        update_option('adnetwork_pages', $createdPages);
    }
    
    /**
     * Seile anhand des Slugs finden
     */
    private function getPageBySlug(string $slug): ?\WP_Post {
        $query = new \WP_Query([
            'post_type'      => 'page',
            'name'           => $slug,
            'posts_per_page' => 1,
        ]);
        
        return $query->have_posts() ? $query->posts[0] : null;
    }
    
    /**
     * Standard-Optionen setzen
     */
    private function setupOptions(): void {
        if (false === get_option('adnetwork_settings')) {
            update_option('adnetwork_settings', [
                'network_name' => 'ADNetwork',
                'project_email' => get_option('admin_email'),
                'currency_main' => 'CP',
                'cookie_consent' => true,
                'seo_urls' => true,
            ]);
        }
        
        if (false === get_option('adnetwork_active_modules')) {
            update_option('adnetwork_active_modules', [
                'user' => true,
                'campaign' => true,
                'publisher' => true,
                'finance' => true,
                'gsc' => true,
                'referral' => true,
                'surfbar' => true,
                'click4win' => true,
                'luckywheel' => true,
                'searchgame' => true,
                'coinflip' => true,
                'dice' => true,
                'rally' => true,
                'cashback' => true,
                'jackpot' => true,
                'paidmail' => true,
                'walls' => true,
                'adsmedia' => true,
            ]);
        }
    }
    
    /**
     * Seilen-URL abrufen
     */
    public static function getPageUrl(string $pageKey): string {
        $pages = get_option('adnetwork_pages', []);
        
        if (!isset($pages[$pageKey])) {
            return home_url();
        }
        
        return get_permalink($pages[$pageKey]);
    }
    
    /**
     * Hub-URL abrufen
     */
    public static function getHubUrl(string $hubKey): string {
        $hubs = get_option('adnetwork_hub_pages', []);
        
        if (!isset($hubs[$hubKey])) {
            return home_url();
        }
        
        return get_permalink($hubs[$hubKey]);
    }
    
    /**
     * Alle erstellten Seilen abrufen
     */
    public static function getPages(): array {
        return get_option('adnetwork_pages', []);
    }
    
    /**
     * Alle Hub-Seilen abrufen
     */
    public static function getHubs(): array {
        return get_option('adnetwork_hub_pages', []);
    }
}
