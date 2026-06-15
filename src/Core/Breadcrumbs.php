<?php
/**
 * Breadcrumbs (Brotkrume)
 *
 * Zeigt die Navigations-Hierarchie an.
 */

namespace ADNetwork\Core;

class Breadcrumbs {
    
    /** @var array Hub-Mapping für Breadcrumbs */
    private $hubMapping = [
        'adnetwork-paid4' => ['title' => 'Paid4', 'parent' => ''],
        'adnetwork-werben' => ['title' => 'Werben', 'parent' => ''],
        'adnetwork-konto' => ['title' => 'Mein Konto', 'parent' => ''],
        'adnetwork-gsc' => ['title' => 'GSC Coin', 'parent' => ''],
        'adnetwork-news' => ['title' => 'News', 'parent' => ''],
    ];
    
    public function __construct() {
        add_shortcode('adnetwork_breadcrumbs', [$this, 'render']);
    }
    
    /**
     * Breadcrumbs rendern
     *
     * Usage: [adnetwork_breadcrumbs]
     */
    public function render($atts): string {
        $breadcrumbs = $this->generate();
        
        if (empty($breadcrumbs)) {
            return '';
        }
        
        ob_start();
        ?>
        <nav class="adnetwork-breadcrumbs" aria-label="Breadcrumb">
            <ol class="breadcrumbs-list">
                <?php foreach ($breadcrumbs as $index => $crumb): ?>
                    <li class="breadcrumb-item">
                        <?php if ($index < count($breadcrumbs) - 1): ?>
                            <a href="<?php echo esc_url($crumb['url']); ?>">
                                <?php echo esc_html($crumb['title']); ?>
                            </a>
                            <span class="breadcrumb-separator">›</span>
                        <?php else: ?>
                            <span class="breadcrumb-current" aria-current="page">
                                <?php echo esc_html($crumb['title']); ?>
                            </span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </nav>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Breadcrumbs-Array generieren
     */
    private function generate(): array {
        $breadcrumbs = [];
        
        // Home
        $breadcrumbs[] = [
            'title' => __('Home', 'adnetwork'),
            'url' => home_url(),
        ];
        
        // Prüfen ob ADNetwork-Seite
        if (!is_page()) {
            return $breadcrumbs;
        }
        
        $slug = get_post_field('post_name', get_the_ID());
        
        // ADNetwork Hauptseite (wenn es eine gibt)
        // Hier könnte man eine "ADNetwork Startseite" verlinken
        
        // Hub-Seiten
        if (isset($this->hubMapping[$slug])) {
            $breadcrumbs[] = [
                'title' => $this->hubMapping[$slug]['title'],
                'url' => get_permalink(),
            ];
        }
        
        // Einzelne Seiten innerhalb eines Hubs
        // Diese Logik kann erweitert werden wenn man weiß,
        // welche Seite zu welchem Hub gehört
        
        return $breadcrumbs;
    }
    
    /**
     * Breadcrumb für eine spezifische Seite hinzufügen
     */
    public function addCrumb(string $title, string $url): void {
        // Kann in Templates genutzt werden
    }
}
