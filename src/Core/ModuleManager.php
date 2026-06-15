<?php
/**
 * Module Manager
 * 
 * Verwaltet alle verfügbaren Module und deren Aktivierungsstatus.
 * Jedes Modul kann einzeln an-/ausgeschaltet werden.
 */

namespace ADNetwork\Core;

class ModuleManager {
    
    /** @var Plugin */
    private $plugin;
    
    /** @var array Alle verfügbaren Module */
    private $availableModules = [];
    
    /** @var array Aktivierte Module */
    private $activeModules = [];
    
    public function __construct(Plugin $plugin) {
        $this->plugin = $plugin;
        $this->registerModules();
        $this->loadActiveStates();
    }
    
    /**
     * Alle verfügbaren Module registrieren
     * 
     * Hier definieren wir welche Module es gibt und welche Klassen sie laden.
     */
    private function registerModules(): void {
        $this->availableModules = [
            'user' => [
                'name' => __('User System', 'adnetwork'),
                'description' => __('Registration, Login, Profiles, Roles', 'adnetwork'),
                'version' => '1.0.0',
                'required' => true, // Kann nicht deaktiviert werden
                'class' => 'ADNetwork\User\UserModule',
            ],
            'campaign' => [
                'name' => __('Campaigns', 'adnetwork'),
                'description' => __('Banner, Textlinks, Popups, MailAds, Targeting', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Campaign\CampaignModule',
            ],
            'publisher' => [
                'name' => __('Publisher', 'adnetwork'),
                'description' => __('Website registration, Embed codes, Stats', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Publisher\PublisherModule',
            ],
            'finance' => [
                'name' => __('Finance', 'adnetwork'),
                'description' => __('Balance, Transactions, Payouts, Invoices', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Finance\FinanceModule',
            ],
            'gsc' => [
                'name' => __('GSC-Coin', 'adnetwork'),
                'description' => __('GSC Wallet, Vault, Shredder, Transfers', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\GSC\GSCModule',
            ],
            'referral' => [
                'name' => __('Referral System', 'adnetwork'),
                'description' => __('15-level referral tree with commissions', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Referral\ReferralModule',
            ],
            'surfbar' => [
                'name' => __('Surfbar', 'adnetwork'),
                'description' => __('Auto-surf for credits', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Earning\SurfbarModule',
            ],
            'click4win' => [
                'name' => __('Click4Win', 'adnetwork'),
                'description' => __('Click-to-win game', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Earning\Click4WinModule',
            ],
            'luckywheel' => [
                'name' => __('Lucky Wheel', 'adnetwork'),
                'description' => __('Spin-to-win game', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Earning\LuckyWheelModule',
            ],
            'searchgame' => [
                'name' => __('Search Game', 'adnetwork'),
                'description' => __('Find hidden items', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Earning\SearchGameModule',
            ],
            'coinflip' => [
                'name' => __('CoinFlip', 'adnetwork'),
                'description' => __('Coin flip gambling game', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Earning\CoinFlipModule',
            ],
            'dice' => [
                'name' => __('Dice', 'adnetwork'),
                'description' => __('Dice gambling game', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Earning\DiceModule',
            ],
            'rally' => [
                'name' => __('Rallies', 'adnetwork'),
                'description' => __('Competitions and contests', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Earning\RallyModule',
            ],
            'cashback' => [
                'name' => __('Cashback', 'adnetwork'),
                'description' => __('Earn back percentage of spending', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Earning\CashbackModule',
            ],
            'jackpot' => [
                'name' => __('Jackpot', 'adnetwork'),
                'description' => __('Progressive prize pool', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Earning\JackpotModule',
            ],
            'paidmail' => [
                'name' => __('PaidMails', 'adnetwork'),
                'description' => __('Paid email reading system', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\PaidMail\PaidMailModule',
            ],
            'walls' => [
                'name' => __('Walls', 'adnetwork'),
                'description' => __('ADCityCentral Walls with subscription system', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\Walls\WallsModule',
            ],
            'adsmedia' => [
                'name' => __('ADS-MEDIA', 'adnetwork'),
                'description' => __('ADS-MEDIA system integration', 'adnetwork'),
                'version' => '1.0.0',
                'required' => false,
                'class' => 'ADNetwork\AdsMedia\AdsMediaModule',
            ],
        ];
    }
    
    /**
     * Aktivierungsstatus aus DB laden
     */
    private function loadActiveStates(): void {
        $saved = get_option('adnetwork_active_modules', []);
        
        foreach ($this->availableModules as $key => $module) {
            // Required modules immer aktiv
            if ($module['required']) {
                $this->activeModules[$key] = true;
                continue;
            }
            
            // Gespeicherten Status nutzen oder default true
            $this->activeModules[$key] = $saved[$key] ?? true;
        }
    }
    
    /**
     * Alle aktivierten Module laden
     */
    public function loadActiveModules(): void {
        foreach ($this->activeModules as $key => $active) {
            if (!$active || !isset($this->availableModules[$key])) {
                continue;
            }
            
            $module = $this->availableModules[$key];
            
            // Prüfen ob Klasse existiert
            if (!class_exists($module['class'])) {
                $this->plugin->logger->warning("Module class not found: {$module['class']}");
                continue;
            }
            
            // Modul initialisieren
            try {
                $instance = new $module['class']($this->plugin);
                $this->activeModules[$key] = $instance;
            } catch (\Exception $e) {
                $this->plugin->logger->error("Failed to load module {$key}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Modul aktivieren
     */
    public function activate(string $key): bool {
        if (!isset($this->availableModules[$key])) {
            return false;
        }
        
        if ($this->availableModules[$key]['required']) {
            return false; // Required modules können nicht deaktiviert werden
        }
        
        $saved = get_option('adnetwork_active_modules', []);
        $saved[$key] = true;
        update_option('adnetwork_active_modules', $saved);
        
        $this->activeModules[$key] = true;
        
        return true;
    }
    
    /**
     * Modul deaktivieren
     */
    public function deactivate(string $key): bool {
        if (!isset($this->availableModules[$key])) {
            return false;
        }
        
        if ($this->availableModules[$key]['required']) {
            return false;
        }
        
        $saved = get_option('adnetwork_active_modules', []);
        $saved[$key] = false;
        update_option('adnetwork_active_modules', $saved);
        
        $this->activeModules[$key] = false;
        
        return true;
    }
    
    /**
     * Prüfen ob Modul aktiv ist
     */
    public function isActive(string $key): bool {
        return isset($this->activeModules[$key]) && $this->activeModules[$key] !== false;
    }
    
    /**
     * Alle verfügbaren Module abrufen
     */
    public function getAvailable(): array {
        return $this->availableModules;
    }
    
    /**
     * Alle aktiven Module abrufen
     */
    public function getActive(): array {
        $active = [];
        foreach ($this->activeModules as $key => $value) {
            if ($value !== false) {
                $active[$key] = $this->availableModules[$key] ?? null;
            }
        }
        return array_filter($active);
    }
}
