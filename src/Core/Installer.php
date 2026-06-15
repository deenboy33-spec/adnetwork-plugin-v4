<?php
/**
 * Installer
 * 
 * Erstellt automatisch alle benötigten WordPress-Seilen beim Plugin-Aktivieren.
 * Aktualisiert bestehende Seilen statt neue zu erstellen.
 */

namespace ADNetwork\Core;

class Installer {
    
    /** @var array Standard-Seilen mit Shortcodes */
    private $defaultPages = [
        'login' => [
            'title' => 'Login',
            'content' => '[adnetwork_login]',
            'status' => 'publish',
        ],
        'register' => [
            'title' => 'Register',
            'content' => '[adnetwork_register]',
            'status' => 'publish',
        ],
        'profile' => [
            'title' => 'My Profile',
            'content' => '[adnetwork_profile]',
            'status' => 'publish',
        ],
        'account' => [
            'title' => 'My Account',
            'content' => '[adnetwork_account]',
            'status' => 'publish',
        ],
        'campaigns' => [
            'title' => 'Campaigns',
            'content' => '[adnetwork_campaigns]',
            'status' => 'publish',
        ],
        'publisher' => [
            'title' => 'Publisher Area',
            'content' => '[adnetwork_publisher]',
            'status' => 'publish',
        ],
        'surfbar' => [
            'title' => 'Surfbar',
            'content' => '[adnetwork_surfbar]',
            'status' => 'publish',
        ],
        'click4win' => [
            'title' => 'Click4Win',
            'content' => '[adnetwork_click4win]',
            'status' => 'publish',
        ],
        'luckywheel' => [
            'title' => 'Lucky Wheel',
            'content' => '[adnetwork_luckywheel]',
            'status' => 'publish',
        ],
        'referrals' => [
            'title' => 'My Referrals',
            'content' => '[adnetwork_referrals]',
            'status' => 'publish',
        ],
        'balance' => [
            'title' => 'My Balance',
            'content' => '[adnetwork_balance]',
            'status' => 'publish',
        ],
        'payout' => [
            'title' => 'Payout',
            'content' => '[adnetwork_payout]',
            'status' => 'publish',
        ],
        'faq' => [
            'title' => 'FAQ',
            'content' => '[adnetwork_faq]',
            'status' => 'publish',
        ],
        'contact' => [
            'title' => 'Contact',
            'content' => '[adnetwork_contact]',
            'status' => 'publish',
        ],
        'terms' => [
            'title' => 'Terms of Service',
            'content' => '<p>Please add your terms of service here.</p>',
            'status' => 'publish',
        ],
        'privacy' => [
            'title' => 'Privacy Policy',
            'content' => '<p>Please add your privacy policy here.</p>',
            'status' => 'publish',
        ],
        'imprint' => [
            'title' => 'Imprint',
            'content' => '<p>Please add your imprint here.</p>',
            'status' => 'publish',
        ],
    ];
    
    /**
     * Installation durchführen
     */
    public static function install(): void {
        $instance = new self();
        $instance->createPages();
        $instance->setupOptions();
    }
    
    /**
     * Alle Standard-Seilen erstellen oder aktualisieren
     */
    public function createPages(): void {
        $createdPages = get_option('adnetwork_pages', []);
        
        foreach ($this->defaultPages as $key => $pageData) {
            $slug = 'adnetwork-' . $key;
            
            // Prüfen ob Seile schon existiert
            $existing = $this->getPageBySlug($slug);
            
            if ($existing) {
                $createdPages[$key] = $existing->ID;
                continue;
            }
            
            // Auch nach alten wf-* Slugs suchen
            $oldSlug = 'wf-' . $key;
            $oldPage = $this->getPageBySlug($oldSlug);
            
            if ($oldPage) {
                // Alte Seile aktualisieren statt neue zu erstellen
                // Shortcodes ersetzen
                $content = $oldPage->post_content;
                $shortcodeMap = [
                    '[wf_login]' => '[adnetwork_login]',
                    '[wf_register]' => '[adnetwork_register]',
                    '[wf_profile]' => '[adnetwork_profile]',
                    '[wf_account]' => '[adnetwork_account]',
                    '[wf_stats]' => '[adnetwork_stats]',
                    '[wf_campaigns]' => '[adnetwork_campaigns]',
                    '[wf_publisher]' => '[adnetwork_publisher]',
                    '[wf_surfbar]' => '[adnetwork_surfbar]',
                    '[wf_click4win]' => '[adnetwork_click4win]',
                    '[wf_luckywheel]' => '[adnetwork_luckywheel]',
                    '[wf_referrals]' => '[adnetwork_referrals]',
                    '[wf_balance]' => '[adnetwork_balance]',
                    '[wf_payout]' => '[adnetwork_payout]',
                    '[wf_faq]' => '[adnetwork_faq]',
                    '[wf_contact]' => '[adnetwork_contact]',
                ];
                
                foreach ($shortcodeMap as $old => $new) {
                    $content = str_replace($old, $new, $content);
                }
                
                wp_update_post([
                    'ID' => $oldPage->ID,
                    'post_name' => $slug,
                    'post_title' => $pageData['title'],
                    'post_content' => $content,
                ]);
                $createdPages[$key] = $oldPage->ID;
                continue;
            }
            
            // Neue Seile erstellen
            $pageId = wp_insert_post([
                'post_title'   => $pageData['title'],
                'post_name'    => $slug,
                'post_content' => $pageData['content'],
                'post_status'  => $pageData['status'],
                'post_type'    => 'page',
                'post_author'  => 1,
            ]);
            
            if ($pageId && !is_wp_error($pageId)) {
                $createdPages[$key] = $pageId;
            }
        }
        
        // Seilen-IDs speichern
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
        // Nur setzen wenn noch nicht vorhanden
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
            // Alle Module standardmäßig aktivieren
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
     * Alle erstellten Seilen abrufen
     */
    public static function getPages(): array {
        return get_option('adnetwork_pages', []);
    }
}
