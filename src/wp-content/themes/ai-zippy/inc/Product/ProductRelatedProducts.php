<?php

namespace AiZippy\Product;

defined('ABSPATH') || exit;

/**
 * Related product carousel shortcode for single product templates.
 */
class ProductRelatedProducts
{
    private const SHORTCODE = 'ai_zippy_related_products';

    /**
     * Register hooks.
     */
    public static function register(): void
    {
        add_shortcode(self::SHORTCODE, [self::class, 'render']);
    }

    /**
     * Render related products excluding the current product.
     */
    public static function render(): string
    {
        if (!function_exists('wc_get_product')) {
            return '';
        }

        global $product;

        $product_id = get_queried_object_id();

        if (!$product_id && $product instanceof \WC_Product) {
            $product_id = $product->get_id();
        }
        $product_ids = self::getRelatedProductIds($product_id);

        if (empty($product_ids)) {
            return '';
        }

        $prev_action = self::getScrollAction(-1);
        $next_action = self::getScrollAction(1);

        ob_start();
        ?>
        <section class="az-related-products" aria-label="<?php echo esc_attr__('Related products', 'ai-zippy'); ?>">
            <div class="az-related-products__header">
                <h2><?php esc_html_e('You May Also Like', 'ai-zippy'); ?></h2>
                <div class="az-related-products__nav" aria-label="<?php echo esc_attr__('Related product navigation', 'ai-zippy'); ?>">
                    <button class="az-related-products__arrow" type="button" data-az-related-prev onclick="<?php echo esc_attr($prev_action); ?>" aria-label="<?php echo esc_attr__('Previous products', 'ai-zippy'); ?>">‹</button>
                    <button class="az-related-products__arrow" type="button" data-az-related-next onclick="<?php echo esc_attr($next_action); ?>" aria-label="<?php echo esc_attr__('Next products', 'ai-zippy'); ?>">›</button>
                </div>
            </div>
            <div class="az-related-products__viewport" data-az-related-viewport>
                <div class="az-related-products__track">
                    <?php foreach ($product_ids as $related_id) : ?>
                        <?php self::renderProductCard((int) $related_id); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php

        return preg_replace('/>\s+</', '><', trim((string) ob_get_clean())) ?: '';
    }

    /**
     * Get related product IDs.
     */
    private static function getRelatedProductIds(int $product_id): array
    {
        if (!$product_id) {
            return [];
        }

        $related_ids = function_exists('wc_get_related_products')
            ? wc_get_related_products($product_id, 4, [$product_id])
            : [];

        if (count($related_ids) >= 4) {
            return array_slice(array_map('absint', $related_ids), 0, 4);
        }

        $category_ids = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'ids']);

        if (is_wp_error($category_ids) || empty($category_ids)) {
            return array_slice(array_map('absint', $related_ids), 0, 4);
        }

        $fallback = wc_get_products([
            'category' => array_values(array_filter(array_map(static function ($category_id): string {
                $term = get_term((int) $category_id, 'product_cat');
                return $term && !is_wp_error($term) ? $term->slug : '';
            }, $category_ids))),
            'exclude' => array_merge([$product_id], $related_ids),
            'limit' => 4 - count($related_ids),
            'orderby' => 'date',
            'order' => 'DESC',
            'return' => 'ids',
            'status' => 'publish',
        ]);

        return array_slice(array_values(array_unique(array_map('absint', array_merge($related_ids, $fallback)))), 0, 4);
    }

    /**
     * Render one product card.
     */
    private static function renderProductCard(int $product_id): void
    {
        $product = wc_get_product($product_id);

        if (!$product instanceof \WC_Product) {
            return;
        }

        $image = $product->get_image('woocommerce_thumbnail', ['loading' => 'lazy']);
        ?>
        <article class="az-related-products__card">
            <a class="az-related-products__image" href="<?php echo esc_url(get_permalink($product_id)); ?>">
                <?php echo wp_kses_post($image); ?>
            </a>
            <div class="az-related-products__body">
                <h3><a href="<?php echo esc_url(get_permalink($product_id)); ?>"><?php echo esc_html($product->get_name()); ?></a></h3>
                <div class="az-related-products__price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
            </div>
        </article>
        <?php
    }

    /**
     * Get inline scroll action for carousel arrows.
     */
    private static function getScrollAction(int $direction): string
    {
        return sprintf(
            "const v=this.closest('.az-related-products').querySelector('[data-az-related-viewport]');if(v){v.scrollBy({left:%d*Math.max(v.clientWidth*.75,240),behavior:'smooth'});}",
            $direction
        );
    }
}
