<?php
/**
 * Menu Updater
 * 
 * Aktualisiert Menü-Einträge, die auf alte ADNetwork-Seiten verweisen.
 */

namespace ADNetwork\Core;

class MenuUpdater {
    
    /** @var array Alte zu neue Slug-Mapping */
    private $slugMapping = [
        'wf-login' => 'adnetwork-login',
        'wf-register' => 'adnetwork-register',
        'wf-profile' => 'adnetwork-profile',
        'wf-account' => 'adnetwork-account',
        'wf-campaigns' => 'adnetwork-campaigns',
        'wf-publisher' => 'adnetwork-publisher',
        'wf-surfbar' => 'adnetwork-surfbar',
        'wf-click4win' => 'adnetwork-click4win',
        'wf-luckywheel' => 'adnetwork-luckywheel',
        'wf-referrals' => 'adnetwork-referrals',
        'wf-balance' => 'adnetwork-balance',
        'wf-payout' => 'adnetwork-payout',
        'wf-faq' => 'adnetwork-faq',
        'wf-contact' => 'adnetwork-contact',
        'wf-terms' => 'adnetwork-terms',
        'wf-privacy' => 'adnetwork-privacy',
        'wf-imprint' => 'adnetwork-imprint',
    ];
    
    /**
     * Menü-Einträge aktualisieren
     */
    public static function update(): void {
        $instance = new self();
        $instance->updateMenuItems();
        $instance->addRedirects();
    }
    
    /**
     * Alle Menü-Einträge durchsuchen und aktualisieren
     */
    private function updateMenuItems(): void {
        $menus = wp_get_nav_menus();
        
        foreach ($menus as $menu) {
            $items = wp_get_nav_menu_items($menu->term_id);
            
            if (!$items) {
                continue;
            }
            
            foreach ($items as $item) {
                $url = $item->url;
                $updated = false;
                
                // URL nach alten Slugs durchsuchen
                foreach ($this->slugMapping as $old => $new) {
                    if (strpos($url, $old) !== false) {
                        $newUrl = str_replace($old, $new, $url);
                        update_post_meta($item->ID, '_menu_item_url', $newUrl);
                        $updated = true;
                        break;
                    }
                }
                
                // Wenn das Item mit einer Seite verknüpft ist
                if ($item->object == 'page' && $item->object_id) {
                    $page = get_post($item->object_id);
                    if ($page) {
                        $oldSlug = $page->post_name;
                        
                        // Prüfen ob die Seite aktualisiert wurde (wf-* zu adnetwork-*)
                        foreach ($this->slugMapping as $old => $new) {
                            if ($oldSlug === $new) {
                                // Seite wurde aktualisiert, Menü-Item verweist automatisch auf die richtige Seite
                                break;
                            }
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Redirects von alten URLs auf neue URLs hinzufügen
     * (Falls jemand die alte URL hat, z.B. aus Bookmarks)
     */
    private function addRedirects(): void {
        // WordPress rewrite rules für redirects hinzufügen
        add_action('template_redirect', [$this, 'handleRedirects']);
    }
    
    /**
     * Redirects verarbeiten
     */
    public function handleRedirects(): void {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        
        foreach ($this->slugMapping as $old => $new) {
            if (strpos($requestUri, $old) !== false) {
                $newUrl = str_replace($old, $new, $requestUri);
                wp_redirect(home_url($newUrl), 301); // Permanent redirect
                exit;
            }
        }
    }
}
