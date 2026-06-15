<?php
/**
 * Hub System
 * 
 * Bündelt mehrere Funktionen auf einer Seite nach Kategorien.
 */

namespace ADNetwork\Core;

class Hub {
    
    /** @var array Hub-Konfiguration */
    private $hubs = [
        'paid4' => [
            'title' => 'Paid4 Bereich',
            'description' => 'Verdiene Geld durch Aktivitäten',
            'modules' => [
                'surfbar' => ['name' => 'Surfbar', 'icon' => 'dashicons-desktop'],
                'click4win' => ['name' => 'Click4Win', 'icon' => 'dashicons-arrow-up-alt'],
                'luckywheel' => ['name' => 'Lucky Wheel', 'icon' => 'dashicons-image-rotate'],
                'searchgame' => ['name' => 'Search Game', 'icon' => 'dashicons-search'],
                'coinflip' => ['name' => 'CoinFlip', 'icon' => 'dashicons-money'],
                'dice' => ['name' => 'Dice', 'icon' => 'dashicons-dice'],
                'rally' => ['name' => 'Rallies', 'icon' => 'dashicons-awards'],
                'cashback' => ['name' => 'Cashback', 'icon' => 'dashicons-cart'],
                'jackpot' => ['name' => 'Jackpot', 'icon' => 'dashicons-tickets-alt'],
                'paidmail' => ['name' => 'PaidMails', 'icon' => 'dashicons-email-alt'],
            ],
        ],
        'werben' => [
            'title' => 'Werbe Bereich',
            'description' => 'Buche Werbung und verdiene als Publisher',
            'modules' => [
                'campaigns' => ['name' => 'Campaigns', 'icon' => 'dashicons-megaphone'],
                'publisher' => ['name' => 'Publisher', 'icon' => 'dashicons-admin-site'],
                'banner' => ['name' => 'Banner', 'icon' => 'dashicons-format-image'],
                'textlink' => ['name' => 'Textlinks', 'icon' => 'dashicons-admin-links'],
                'popup' => ['name' => 'Popups', 'icon' => 'dashicons-external'],
                'loginad' => ['name' => 'Login Ads', 'icon' => 'dashicons-admin-network'],
            ],
        ],
        'konto' => [
            'title' => 'Mein Konto',
            'description' => 'Verwalte dein Profil, Guthaben und Einstellungen',
            'modules' => [
                'profile' => ['name' => 'Profil', 'icon' => 'dashicons-admin-users'],
                'balance' => ['name' => 'Guthaben', 'icon' => 'dashicons-money-alt'],
                'referrals' => ['name' => 'Referrals', 'icon' => 'dashicons-networking'],
                'payout' => ['name' => 'Auszahlung', 'icon' => 'dashicons-bank'],
                'transactions' => ['name' => 'Transaktionen', 'icon' => 'dashicons-list-view'],
            ],
        ],
        'gsc' => [
            'title' => 'GSC Coin Bereich',
            'description' => 'GoldSurferCoins verwalten und nutzen',
            'modules' => [
                'gsc_info' => ['name' => 'GSC Info', 'icon' => 'dashicons-info'],
                'gsc_wallet' => ['name' => 'GSC Wallet', 'icon' => 'dashicons-wallet'],
                'gsc_vault' => ['name' => 'Tresor', 'icon' => 'dashicons-lock'],
                'gsc_shredder' => ['name' => 'Shredder', 'icon' => 'dashicons-trash'],
                'gsc_transfer' => ['name' => 'Transfer', 'icon' => 'dashicons-migrate'],
            ],
        ],
        'news' => [
            'title' => 'News & Community',
            'description' => 'Neuigkeiten und Community-Bereich',
            'modules' => [
                'news' => ['name' => 'News', 'icon' => 'dashicons-welcome-write-blog'],
                'faq' => ['name' => 'FAQ', 'icon' => 'dashicons-editor-help'],
                'contact' => ['name' => 'Kontakt', 'icon' => 'dashicons-email'],
            ],
        ],
    ];
    
    public function __construct() {
        add_shortcode('adnetwork_hub', [$this, 'renderHub']);
    }
    
    /**
     * Hub Shortcode
     * 
     * Usage: [adnetwork_hub type="paid4"]
     */
    public function renderHub($atts): string {
        $atts = shortcode_atts(['type' => 'paid4'], $atts, 'adnetwork_hub');
        $type = sanitize_text_field($atts['type']);
        
        if (!isset($this->hubs[$type])) {
            return '<p>' . __('Hub nicht gefunden.', 'adnetwork') . '</p>';
        }
        
        $hub = $this->hubs[$type];
        $plugin = Plugin::instance();
        
        ob_start();
        ?>
        <div class="adnetwork-hub-container">
            <h2><?php echo esc_html($hub['title']); ?></h2>
            <p class="hub-description"><?php echo esc_html($hub['description']); ?></p>
            
            <div class="adnetwork-hub-grid">
                <?php foreach ($hub['modules'] as $moduleKey => $module): ?>
                    <?php
                    // Prüfen ob Modul aktiv ist
                    $isActive = $plugin->modules->isActive($moduleKey);
                    $moduleUrl = Installer::getPageUrl($moduleKey);
                    ?>
                    <div class="adnetwork-hub-card <?php echo $isActive ? 'active' : 'inactive'; ?>">
                        <div class="hub-card-icon">
                            <span class="dashicons <?php echo esc_attr($module['icon']); ?>"></span>
                        </div>
                        <h3><?php echo esc_html($module['name']); ?></h3>
                        <?php if ($isActive): ?>
                            <a href="<?php echo esc_url($moduleUrl); ?>" class="adnetwork-btn">
                                <?php _e('Öffnen', 'adnetwork'); ?>
                            </a>
                        <?php else: ?>
                            <span class="hub-inactive"><?php _e('Deaktiviert', 'adnetwork'); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Alle verfügbaren Hubs abrufen
     */
    public function getHubs(): array {
        return $this->hubs;
    }
    
    /**
     * Hub-Informationen abrufen
     */
    public function getHub(string $type): ?array {
        return $this->hubs[$type] ?? null;
    }
}
