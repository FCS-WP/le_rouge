<?php

namespace AiZippy\Core;

defined('ABSPATH') || exit;

/**
 * Vite manifest reader and asset enqueue helper.
 */
class ViteAssets
{
    private static ?array $manifest = null;

    /**
     * Register hooks.
     */
    public static function register(): void
    {
        add_action('wp_enqueue_scripts', [self::class, 'enqueueTheme']);
        add_filter('script_loader_tag', [self::class, 'addModuleType'], 10, 2);
        add_action('after_setup_theme', [self::class, 'enqueueEditor']);
    }

    /**
     * Enqueue the main theme JS + CSS.
     */
    public static function enqueueTheme(): void
    {
        self::enqueue('ai-zippy-theme', 'src/wp-content/themes/ai-zippy/src/js/theme.js');

        // Provide WC Store API nonce globally (used by add-to-cart, cart-api modules)
        wp_add_inline_script(
            'ai-zippy-theme',
            'var wcBlocksMiddlewareConfig = wcBlocksMiddlewareConfig || {
                storeApiNonce: "' . esc_js(wp_create_nonce('wc_store_api')) . '",
                wcStoreApiNonceTimestamp: "' . esc_js(time()) . '"
            };',
            'before'
        );
    }

    /**
     * Add type="module" to Vite-built scripts.
     */
    public static function addModuleType(string $tag, string $handle): string
    {
        if (str_starts_with($handle, 'ai-zippy-')) {
            return str_replace(' src=', ' type="module" src=', $tag);
        }
        return $tag;
    }

    /**
     * Get the Vite manifest.
     */
    public static function getManifest(): array
    {
        if (self::$manifest !== null) {
            return self::$manifest;
        }

        $path = AI_ZIPPY_THEME_DIR . '/assets/dist/.vite/manifest.json';

        if (!file_exists($path)) {
            return self::$manifest = [];
        }

        self::$manifest = json_decode(file_get_contents($path), true);

        return self::$manifest ?: [];
    }

    /**
     * Enqueue a Vite-built asset by its source entry key.
     */
    public static function enqueue(string $handle, string $entry): void
    {
        $manifest = self::getManifest();

        if (empty($manifest[$entry])) {
            return;
        }

        $asset = $manifest[$entry];
        $dist_uri = AI_ZIPPY_THEME_URI . '/assets/dist';
        $dist_dir = AI_ZIPPY_THEME_DIR . '/assets/dist';

        // Enqueue JS
        if (!empty($asset['file']) && str_ends_with($asset['file'], '.js')) {
            $file_path = $dist_dir . '/' . $asset['file'];
            $version = file_exists($file_path) ? filemtime($file_path) : AI_ZIPPY_THEME_VERSION;

            wp_enqueue_script(
                $handle,
                $dist_uri . '/' . $asset['file'],
                [],
                $version,
                true
            );
        }

        // CSS-only entry (no JS, file is .css directly)
        if (!empty($asset['file']) && str_ends_with($asset['file'], '.css') && empty($asset['css'])) {
            $file_path = $dist_dir . '/' . $asset['file'];
            $version = file_exists($file_path) ? filemtime($file_path) : AI_ZIPPY_THEME_VERSION;

            wp_enqueue_style(
                $handle,
                $dist_uri . '/' . $asset['file'],
                [],
                $version
            );
        }

        // Enqueue associated CSS (bundled with JS entries)
        if (!empty($asset['css'])) {
            foreach ($asset['css'] as $index => $css_file) {
                $file_path = $dist_dir . '/' . $css_file;
                $version = file_exists($file_path) ? filemtime($file_path) : AI_ZIPPY_THEME_VERSION;

                wp_enqueue_style(
                    $handle . '-css-' . $index,
                    $dist_uri . '/' . $css_file,
                    [],
                    $version
                );
            }
        }
    }

    /**
     * Load the theme CSS into the Gutenberg editor using add_editor_style.
     */
    public static function enqueueEditor(): void
    {
        $manifest = self::getManifest();
        $entry = 'src/wp-content/themes/ai-zippy/src/scss/style.scss';

        if (empty($manifest[$entry]['file'])) {
            return;
        }

        // Vite CSS filename
        $css_file = $manifest[$entry]['file'];

        // Path relative to theme root for add_editor_style
        add_editor_style('assets/dist/' . $css_file);
    }
}
