<?php
/**
 * Plugin Name:       ADNetwork — Advertising Network Platform
 * Plugin URI:        https://adnetwork.management
 * Description:       Vollständige Werbenetzwerk-Plattform. Module: Kampagnen, Publisher, Surfbar, GSC-Coin, Referral, PaidMails, Walls, Spiele, Finanzen. Jedes Modul einzeln aktivierbar.
 * Version:           4.0.6
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            123-Next-Generation-Marketing
 * Author URI:        https://123ngm.de
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       adnetwork
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

// Plugin Constants
define('ADN_VERSION', '4.0.6');
define('ADN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ADN_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ADN_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('ADN_PLUGIN_FILE', __FILE__);

// Autoloader
require_once ADN_PLUGIN_DIR . 'vendor/autoload.php';

// Main Plugin Class
use ADNetwork\Core\Plugin;

// Shortcode Compatibility (alte Shortcodes als Aliase)
add_action('init', function () {
    new ADNetwork\Core\ShortcodeCompat();
});

// Initialize
add_action('plugins_loaded', function () {
    Plugin::instance();
});

// Activation Hook
register_activation_hook(__FILE__, function () {
    require_once ADN_PLUGIN_DIR . 'src/Core/Database.php';
    require_once ADN_PLUGIN_DIR . 'src/Core/Installer.php';
    require_once ADN_PLUGIN_DIR . 'src/Core/MenuUpdater.php';
    require_once ADN_PLUGIN_DIR . 'src/Core/Upgrader.php';
    
    // Datenbank-Tabellen erstellen
    ADNetwork\Core\Database::activate();
    
    // Bestehende Seiten, Menüs und Shortcodes aktualisieren
    ADNetwork\Core\Upgrader::upgrade();
});

// Deactivation Hook
register_deactivation_hook(__FILE__, function () {
    // Cleanup if needed
});
