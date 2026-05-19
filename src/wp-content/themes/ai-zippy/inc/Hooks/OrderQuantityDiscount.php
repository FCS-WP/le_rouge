<?php

namespace AiZippy\Hooks;

defined('ABSPATH') || exit;

/**
 * Order quantity discount hooks.
 *
 * Applies a discount based on configured promotion rules.
 */
class OrderQuantityDiscount
{
    /**
     * Register hooks.
     */
    public static function register(): void
    {
        add_action('woocommerce_cart_calculate_fees', [self::class, 'applyDiscount']);
    }

    /**
     * Apply a cart discount based on total item quantity.
     *
     * @param \WC_Cart $cart WooCommerce cart instance.
     */
    public static function applyDiscount(\WC_Cart $cart): void
    {
        if (is_admin() && !wp_doing_ajax()) {
            return;
        }

        $promotion = self::getCartPromotionSummary($cart);

        if (empty($promotion['has_discount'])) {
            return;
        }

        if ($promotion['discount_amount'] <= 0) {
            return;
        }

        $cart->add_fee(
            self::getDiscountLabel((float) $promotion['current_discount']),
            -$promotion['discount_amount']
        );
    }

    /**
     * Get the current cart promotion details.
     */
    public static function getCartPromotionSummary(\WC_Cart $cart): array
    {
        $settings = Promotions::getSettings();

        if (empty($settings['rules'])) {
            return self::getEmptyPromotionSummary();
        }

        $eligible_quantity = self::getEligibleQuantity($cart, $settings['categories']);
        $current_discount = self::getCurrentDiscount($eligible_quantity, $settings['rules']);
        $next_rule = self::getNextRule($eligible_quantity, $settings['rules']);
        $discount_amount = $cart->get_cart_contents_total() * ($current_discount / 100);

        return [
            'eligible_quantity' => $eligible_quantity,
            'current_discount' => $current_discount,
            'current_discount_percent' => self::formatPercent($current_discount),
            'discount_amount' => $discount_amount,
            'has_discount' => $current_discount > 0 && $discount_amount > 0,
            'next_quantity' => $next_rule ? max((int) $next_rule['quantity'] - $eligible_quantity, 0) : 0,
            'next_discount' => $next_rule ? (float) $next_rule['discount'] : 0.0,
            'next_discount_percent' => $next_rule ? self::formatPercent((float) $next_rule['discount']) : '',
        ];
    }

    /**
     * Format a discount percentage without unnecessary trailing zeros.
     */
    public static function formatPercent(float $percent): string
    {
        return rtrim(rtrim(wc_format_decimal($percent, 2), '0'), '.');
    }

    /**
     * Get a short category scope note for display below the discount label.
     */
    public static function getCategoryScopeNote(): string
    {
        $settings = Promotions::getSettings();

        if (empty($settings['categories'])) {
            return '';
        }

        $category_names = [];

        foreach ($settings['categories'] as $category_id) {
            $category = get_term((int) $category_id, 'product_cat');

            if (!$category || is_wp_error($category)) {
                continue;
            }

            $category_names[] = $category->name;
        }

        if (empty($category_names)) {
            return '';
        }

        return sprintf(
            /* translators: %s: comma-separated product category names. */
            __('*Only for %s products', 'ai-zippy'),
            implode(', ', $category_names)
        );
    }

    /**
     * Get eligible cart quantity for selected product categories.
     */
    private static function getEligibleQuantity(\WC_Cart $cart, array $category_ids): int
    {
        $eligible_quantity = 0;

        foreach ($cart->get_cart() as $cart_item) {
            $product_id = absint($cart_item['product_id'] ?? 0);

            if (
                !empty($category_ids)
                && (!$product_id || !has_term($category_ids, 'product_cat', $product_id))
            ) {
                continue;
            }

            $eligible_quantity += absint($cart_item['quantity'] ?? 0);
        }

        return $eligible_quantity;
    }

    /**
     * Get the current discount percent for the eligible cart quantity.
     */
    private static function getCurrentDiscount(int $quantity, array $rules): float
    {
        $discount = 0.0;

        foreach ($rules as $rule) {
            if ($quantity < (int) $rule['quantity']) {
                continue;
            }

            $discount = (float) $rule['discount'];
        }

        return $discount;
    }

    /**
     * Get the next promotion rule above the current eligible quantity.
     */
    private static function getNextRule(int $quantity, array $rules): ?array
    {
        foreach ($rules as $rule) {
            if ($quantity < (int) $rule['quantity']) {
                return $rule;
            }
        }

        return null;
    }

    /**
     * Get the discount fee label.
     */
    private static function getDiscountLabel(float $discount): string
    {
        return sprintf(
            /* translators: %s: discount percentage. */
            __('Promotion (%s%%)', 'ai-zippy'),
            self::formatPercent($discount)
        );
    }

    /**
     * Get an empty promotion summary.
     */
    private static function getEmptyPromotionSummary(): array
    {
        return [
            'eligible_quantity' => 0,
            'current_discount' => 0.0,
            'current_discount_percent' => '',
            'discount_amount' => 0.0,
            'has_discount' => false,
            'next_quantity' => 0,
            'next_discount' => 0.0,
            'next_discount_percent' => '',
        ];
    }
}
