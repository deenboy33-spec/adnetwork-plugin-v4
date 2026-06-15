<?php
/**
 * Shortcode Compatibility
 * 
 * Registriert alte Shortcodes als Aliase für neue Funktionalität.
 * Damit alte Seiten nicht kaputt gehen.
 */

namespace ADNetwork\Core;

class ShortcodeCompat {
    
    public function __construct() {
        $this->registerOldShortcodes();
    }
    
    /**
     * Alte Shortcodes als Aliase registrieren
     */
    private function registerOldShortcodes(): void {
        // Hub / Paid4 Bereich
        add_shortcode('wf_hub', [$this, 'renderHub']);
        
        // GSC Info
        add_shortcode('wf_gsc_info', [$this, 'renderGSCInfo']);
        
        // News
        add_shortcode('wf_news', [$this, 'renderNews']);
        
        // Walls
        add_shortcode('wf_walls', [$this, 'renderWalls']);
        
        // Alte Login/Register (Fallback)
        add_shortcode('wf_login', [$this, 'renderLogin']);
        add_shortcode('wf_register', [$this, 'renderRegister']);
        add_shortcode('wf_profile', [$this, 'renderProfile']);
        add_shortcode('wf_account', [$this, 'renderAccount']);
        
        // Alte Stats
        add_shortcode('wf_stats', [$this, 'renderStats']);
        
        // Alte Kampagnen
        add_shortcode('wf_campaigns', [$this, 'renderCampaigns']);
        add_shortcode('wf_campaign', [$this, 'renderCampaigns']);
        
        // Alte Publisher
        add_shortcode('wf_publisher', [$this, 'renderPublisher']);
        
        // Alte Spiele
        add_shortcode('wf_surfbar', [$this, 'renderSurfbar']);
        add_shortcode('wf_click4win', [$this, 'renderClick4Win']);
        add_shortcode('wf_click2win', [$this, 'renderClick4Win']);
        add_shortcode('wf_luckywheel', [$this, 'renderLuckyWheel']);
        add_shortcode('wf_lucky_wheel', [$this, 'renderLuckyWheel']);
        add_shortcode('wf_searchgame', [$this, 'renderSearchGame']);
        add_shortcode('wf_search_game', [$this, 'renderSearchGame']);
        add_shortcode('wf_coinflip', [$this, 'renderCoinFlip']);
        add_shortcode('wf_coin_flip', [$this, 'renderCoinFlip']);
        add_shortcode('wf_dice', [$this, 'renderDice']);
        add_shortcode('wf_rally', [$this, 'renderRally']);
        add_shortcode('wf_rallies', [$this, 'renderRally']);
        add_shortcode('wf_cashback', [$this, 'renderCashback']);
        add_shortcode('wf_jackpot', [$this, 'renderJackpot']);
        
        // Alte Finanzen
        add_shortcode('wf_referrals', [$this, 'renderReferrals']);
        add_shortcode('wf_referral', [$this, 'renderReferrals']);
        add_shortcode('wf_balance', [$this, 'renderBalance']);
        add_shortcode('wf_payout', [$this, 'renderPayout']);
        add_shortcode('wf_payouts', [$this, 'renderPayout']);
        
        // PaidMails
        add_shortcode('wf_paidmail', [$this, 'renderPaidMail']);
        add_shortcode('wf_paidmails', [$this, 'renderPaidMail']);
        add_shortcode('wf_mail', [$this, 'renderPaidMail']);
        
        // Alte Info-Seiten
        add_shortcode('wf_faq', [$this, 'renderFAQ']);
        add_shortcode('wf_contact', [$this, 'renderContact']);
        add_shortcode('wf_terms', [$this, 'renderTerms']);
        add_shortcode('wf_privacy', [$this, 'renderPrivacy']);
        add_shortcode('wf_imprint', [$this, 'renderImprint']);
        
        // Dashboard / Übersicht
        add_shortcode('wf_dashboard', [$this, 'renderDashboard']);
        add_shortcode('wf_overview', [$this, 'renderDashboard']);
    }
    
    /**
     * Hub / Paid4 Bereich
     */
    public function renderHub($atts): string {
        $atts = shortcode_atts(['type' => 'paid4'], $atts, 'wf_hub');
        $type = sanitize_text_field($atts['type']);
        
        ob_start();
        ?>
        <div class="adnetwork-hub">
            <h2><?php echo esc_html(ucfirst($type)); ?> Bereich</h2>
            <p><?php _e('This section is being updated. Please check back soon.', 'adnetwork'); ?></p>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * GSC Info (Alias)
     */
    public function renderGSCInfo($atts): string {
        return do_shortcode('[adnetwork_gsc_info]');
    }
    
    /**
     * News (Alias)
     */
    public function renderNews($atts): string {
        return do_shortcode('[adnetwork_news]');
    }
    
    /**
     * Walls (Alias)
     */
    public function renderWalls($atts): string {
        return do_shortcode('[adnetwork_walls]');
    }
    
    /**
     * Dashboard (Alias)
     */
    public function renderDashboard($atts): string {
        return do_shortcode('[adnetwork_dashboard]');
    }
    
    /**
     * Login (Alias)
     */
    public function renderLogin($atts): string {
        return do_shortcode('[adnetwork_login]');
    }
    
    /**
     * Register (Alias)
     */
    public function renderRegister($atts): string {
        return do_shortcode('[adnetwork_register]');
    }
    
    /**
     * Profile (Alias)
     */
    public function renderProfile($atts): string {
        return do_shortcode('[adnetwork_profile]');
    }
    
    /**
     * Account (Alias)
     */
    public function renderAccount($atts): string {
        return do_shortcode('[adnetwork_account]');
    }
    
    /**
     * Stats (Alias)
     */
    public function renderStats($atts): string {
        return do_shortcode('[adnetwork_stats]');
    }
    
    /**
     * Campaigns (Alias)
     */
    public function renderCampaigns($atts): string {
        return do_shortcode('[adnetwork_campaigns]');
    }
    
    /**
     * Publisher (Alias)
     */
    public function renderPublisher($atts): string {
        return do_shortcode('[adnetwork_publisher]');
    }
    
    /**
     * Surfbar (Alias)
     */
    public function renderSurfbar($atts): string {
        return do_shortcode('[adnetwork_surfbar]');
    }
    
    /**
     * Click4Win (Alias)
     */
    public function renderClick4Win($atts): string {
        return do_shortcode('[adnetwork_click4win]');
    }
    
    /**
     * Lucky Wheel (Alias)
     */
    public function renderLuckyWheel($atts): string {
        return do_shortcode('[adnetwork_luckywheel]');
    }
    
    /**
     * Search Game (Alias)
     */
    public function renderSearchGame($atts): string {
        return do_shortcode('[adnetwork_searchgame]');
    }
    
    /**
     * CoinFlip (Alias)
     */
    public function renderCoinFlip($atts): string {
        return do_shortcode('[adnetwork_coinflip]');
    }
    
    /**
     * Dice (Alias)
     */
    public function renderDice($atts): string {
        return do_shortcode('[adnetwork_dice]');
    }
    
    /**
     * Rally (Alias)
     */
    public function renderRally($atts): string {
        return do_shortcode('[adnetwork_rally]');
    }
    
    /**
     * Cashback (Alias)
     */
    public function renderCashback($atts): string {
        return do_shortcode('[adnetwork_cashback]');
    }
    
    /**
     * Jackpot (Alias)
     */
    public function renderJackpot($atts): string {
        return do_shortcode('[adnetwork_jackpot]');
    }
    
    /**
     * PaidMail (Alias)
     */
    public function renderPaidMail($atts): string {
        return do_shortcode('[adnetwork_paidmail]');
    }
    
    /**
     * Referrals (Alias)
     */
    public function renderReferrals($atts): string {
        return do_shortcode('[adnetwork_referrals]');
    }
    
    /**
     * Balance (Alias)
     */
    public function renderBalance($atts): string {
        return do_shortcode('[adnetwork_balance]');
    }
    
    /**
     * Payout (Alias)
     */
    public function renderPayout($atts): string {
        return do_shortcode('[adnetwork_payout]');
    }
    
    /**
     * FAQ (Alias)
     */
    public function renderFAQ($atts): string {
        return do_shortcode('[adnetwork_faq]');
    }
    
    /**
     * Contact (Alias)
     */
    public function renderContact($atts): string {
        return do_shortcode('[adnetwork_contact]');
    }
    
    /**
     * Terms (Alias)
     */
    public function renderTerms($atts): string {
        return do_shortcode('[adnetwork_terms]');
    }
    
    /**
     * Privacy (Alias)
     */
    public function renderPrivacy($atts): string {
        return do_shortcode('[adnetwork_privacy]');
    }
    
    /**
     * Imprint (Alias)
     */
    public function renderImprint($atts): string {
        return do_shortcode('[adnetwork_imprint]');
    }
}
