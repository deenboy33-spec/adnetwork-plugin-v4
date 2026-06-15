<?php
/**
 * Upgrader
 * 
 * Aktualisiert bestehende Seiten beim Plugin-Update.
 * Ersetzt alte Shortcodes ([wf_*]) durch neue ([adnetwork_*]).
 */

namespace ADNetwork\Core;

class Upgrader {
    
    /** @var array Shortcode-Mapping: alt => neu */
    private $shortcodeMap = [
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
    
    /**
     * Upgrade durchführen
     */
    public static function upgrade(): void {
        $instance = new self();
        $instance->updateExistingPages();
        $instance->createMissingPages();
        $instance->updatePageURLs();
    }
    
    /**
     * Bestehende Seiten aktualisieren (Shortcodes ersetzen)
     */
    private function updateExistingPages(): void {
        $pages = get_posts([
            'post_type' => 'page',
            'posts_per_page' => -1,
            'post_status' => 'any',
        ]);
        
        foreach ($pages as $page) {
            $content = $page->post_content;
            $updated = false;
            
            // Alte Shortcodes durch neue ersetzen
            foreach ($this->shortcodeMap as $old => $new) {
                if (strpos($content, $old) !== false) {
                    $content = str_replace($old, $new, $content);
                    $updated = true;
                }
            }
            
            // Falls Shortcodes aktualisiert wurden
            if ($updated) {
                wp_update_post([
                    'ID' => $page->ID,
                    'post_content' => $content,
                ]);
            }
        }
    }
    
    /**
     * Fehlende Seiten erstellen
     */
    private function createMissingPages(): void {
        $installer = new Installer();
        $installer->createPages();
    }
    
    /**
     * Seiten-URLs aktualisieren
     */
    private function updatePageURLs(): void {
        $pages = get_option('adnetwork_pages', []);
        $updated = false;
        
        foreach ($pages as $key => $pageId) {
            $page = get_post($pageId);
            if ($page) {
                // URL hat sich möglicherweise geändert
                $updated = true;
            }
        }
        
        if ($updated) {
            update_option('adnetwork_pages', $pages);
        }
    }
}
