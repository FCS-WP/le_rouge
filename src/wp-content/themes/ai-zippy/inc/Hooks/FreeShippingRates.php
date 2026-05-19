<?php

namespace AiZippy\Hooks;

defined('ABSPATH') || exit;

/**
 * Free shipping rate hooks.
 *
 * When WooCommerce returns a free shipping option, hide paid shipping methods
 * for the same package.
 */
class FreeShippingRates
{
    /**
     * Register hooks.
     */
    public static function register(): void
    {
        add_filter('woocommerce_package_rates', [self::class, 'onlyShowFreeShipping'], 100, 2);
    }

    /**
     * Keep only free shipping methods when available.
     *
     * @param array $rates   Shipping rates keyed by rate ID.
     * @param array $package Shipping package data.
     *
     * @return array
     */
    public static function onlyShowFreeShipping(array $rates, array $package): array
    {
        $free_shipping = [];

        foreach ($rates as $rate_id => $rate) {
            if (!is_object($rate) || !method_exists($rate, 'get_method_id')) {
                continue;
            }

            if ('free_shipping' === $rate->get_method_id()) {
                $free_shipping[$rate_id] = $rate;
            }
        }

        return !empty($free_shipping) ? $free_shipping : $rates;
    }
}
