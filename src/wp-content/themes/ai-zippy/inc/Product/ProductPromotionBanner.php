<?php

namespace AiZippy\Product;

use AiZippy\Hooks\OrderQuantityDiscount;
use AiZippy\Hooks\Promotions;

defined('ABSPATH') || exit;

/**
 * Product promotion banner shortcode for eligible product detail pages.
 */
class ProductPromotionBanner
{
    private const SHORTCODE = 'ai_zippy_product_promotion_banner';

    /**
     * Register hooks.
     */
    public static function register(): void
    {
        add_shortcode(self::SHORTCODE, [self::class, 'render']);
    }

    /**
     * Render the promotion banner.
     */
    public static function render(): string
    {
        if (!function_exists('wc_get_product')) {
            return '';
        }

        global $product;

        if (!$product instanceof \WC_Product) {
            $product_id = get_queried_object_id();
            $product = $product_id ? wc_get_product($product_id) : null;
        }

        if (!$product instanceof \WC_Product || !self::isEligibleProduct($product)) {
            return '';
        }

        $rules = Promotions::getSettings()['rules'] ?? [];

        if (empty($rules)) {
            return '';
        }

        $items = [];

        foreach ($rules as $rule) {
            $quantity = absint($rule['quantity'] ?? 0);
            $discount = (float) ($rule['discount'] ?? 0);

            if ($quantity < 1 || $discount <= 0) {
                continue;
            }

            if ($quantity >= 6) {
                $items[] = sprintf(
                    /* translators: 1: discount percentage. 2: minimum quantity. */
                    __('Enjoy %1$s%% off when you choose %2$d bottles or more.', 'ai-zippy'),
                    OrderQuantityDiscount::formatPercent($discount),
                    $quantity
                );
                continue;
            }

            $items[] = sprintf(
                /* translators: 1: discount percentage. 2: minimum quantity. */
                __('Enjoy %1$s%% off when you choose %2$d bottles.', 'ai-zippy'),
                OrderQuantityDiscount::formatPercent($discount),
                $quantity
            );
        }

        if (empty($items)) {
            return '';
        }

        ob_start();
        ?>
        <aside class="az-product-promo" aria-label="<?php echo esc_attr__('Product promotion', 'ai-zippy'); ?>">
            <p class="az-product-promo__eyebrow"><?php esc_html_e('Wine quantity offer', 'ai-zippy'); ?></p>
            <ul class="az-product-promo__list">
                <?php foreach ($items as $item) : ?>
                    <li><?php echo esc_html($item); ?></li>
                <?php endforeach; ?>
            </ul>
        </aside>
        <?php

        return preg_replace('/>\s+</', '><', trim((string) ob_get_clean())) ?: '';
    }

    /**
     * Check whether the current product should display the promotion.
     */
    private static function isEligibleProduct(\WC_Product $product): bool
    {
        $product_id = $product->get_id();

        if (has_term('chocolates', 'product_cat', $product_id)) {
            return false;
        }

        $category_ids = Promotions::getSettings()['categories'] ?? [];

        if (empty($category_ids)) {
            return true;
        }

        return has_term(array_map('absint', $category_ids), 'product_cat', $product_id);
    }
}
