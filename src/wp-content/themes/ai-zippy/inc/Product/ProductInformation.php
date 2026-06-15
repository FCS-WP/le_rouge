<?php

namespace AiZippy\Product;

defined('ABSPATH') || exit;

/**
 * Product information shortcode for single product templates.
 */
class ProductInformation
{
    private const SHORTCODE = 'ai_zippy_product_information';

    /**
     * Register hooks.
     */
    public static function register(): void
    {
        add_shortcode(self::SHORTCODE, [self::class, 'render']);
    }

    /**
     * Render product content with a fallback message.
     */
    public static function render(): string
    {
        $product_id = get_queried_object_id();

        if (!$product_id) {
            return self::renderEmpty();
        }

        $content = get_post_field('post_content', $product_id);

        if (!is_string($content) || trim(wp_strip_all_tags($content)) === '') {
            return self::renderEmpty();
        }

        $content = apply_filters('the_content', $content);

        if (!is_string($content) || trim(wp_strip_all_tags($content)) === '') {
            return self::renderEmpty();
        }

        return '<div class="az-product-info-content">' . $content . '</div>';
    }

    /**
     * Render empty state for products without information.
     */
    private static function renderEmpty(): string
    {
        return '<p class="az-product-info-empty">' . esc_html__('No content found', 'ai-zippy') . '</p>';
    }
}
