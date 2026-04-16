<?php

namespace AiZippy\Core;

defined('ABSPATH') || exit;

/**
 * Theme setup: supports, blocks, block categories.
 */
class ThemeSetup
{
    /**
     * Register hooks.
     */
    public static function register(): void
    {
        add_action('after_setup_theme', [self::class, 'setup']);
        add_action('init', [self::class, 'registerBlocks']);
        add_action('init', [self::class, 'registerPatternCategories']);
        add_filter('block_categories_all', [self::class, 'blockCategories']);
        add_action('wp_enqueue_scripts', 'wp_enqueue_global_styles', 1);
        add_action('wp_enqueue_scripts', [self::class, 'enqueueFonts']);
        add_action('enqueue_block_editor_assets', [self::class, 'enqueueFonts']);
        add_filter('get_the_archive_title', [self::class, 'cleanupArchiveTitle']);
    }

    /**
     * Theme supports.
     */
    public static function setup(): void
    {
        add_theme_support('wp-block-styles');
        add_theme_support('editor-styles');
        add_theme_support('woocommerce');
        add_theme_support('responsive-embeds');

        // Enable revisions for pages and products.
        add_post_type_support('page', 'revisions');
        add_post_type_support('product', 'revisions');
    }

    /**
     * Register custom blocks from assets/blocks (wp-scripts build output).
     */
    public static function registerBlocks(): void
    {
        $blocks_dir = AI_ZIPPY_THEME_DIR . '/assets/blocks';

        if (!is_dir($blocks_dir)) {
            return;
        }

        foreach (glob($blocks_dir . '/*/block.json') as $block_json) {
            register_block_type(dirname($block_json));
        }
    }

    /**
     * Enqueue Google Fonts.
     */
    public static function enqueueFonts(): void
    {
        wp_enqueue_style(
            'ai-zippy-google-fonts',
            'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Montserrat:wght@300;400;500;600;700&display=swap',
            [],
            null
        );
    }

    /**
     * Register custom block category.
     */
    public static function blockCategories(array $categories): array
    {
        array_unshift($categories, [
            'slug'  => 'ai-zippy',
            'title' => 'AI Zippy',
            'icon'  => 'star-filled',
        ]);

        return $categories;
    }

    /**
     * Register custom pattern categories.
     */
    public static function registerPatternCategories(): void
    {
        register_block_pattern_category('ai-zippy', [
            'label' => __('AI Zippy', 'ai-zippy'),
        ]);
    }
    /**
     * Remove "Archive:" prefix from titles.
     */
    public static function cleanupArchiveTitle(string $title): string
    {
        if (is_shop()) {
            return __('Our Collection', 'ai-zippy');
        }

        if (is_category() || is_tax()) {
            return single_term_title('', false);
        }

        if (is_post_type_archive()) {
            return post_type_archive_title('', false);
        }

        return $title;
    }
}
