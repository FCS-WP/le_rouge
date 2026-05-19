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

    /**
     * Get the configured free shipping minimum amount.
     */
    public static function getMinimumAmount(): ?float
    {
        if (!class_exists('\WC_Shipping_Zones')) {
            return null;
        }

        $amounts = [];

        foreach (self::getShippingZones() as $zone) {
            foreach ($zone->get_shipping_methods(true) as $method) {
                if ('free_shipping' !== $method->id || 'yes' !== $method->enabled) {
                    continue;
                }

                $requires = $method->get_option('requires');

                if (!in_array($requires, ['min_amount', 'either', 'both'], true)) {
                    continue;
                }

                $minimum = (float) $method->get_option('min_amount');

                if ($minimum > 0) {
                    $amounts[] = $minimum;
                }
            }
        }

        return !empty($amounts) ? min($amounts) : null;
    }

    /**
     * Get the free shipping notice text.
     */
    public static function getNoticeText(): string
    {
        $minimum = self::getMinimumAmount();

        if (null === $minimum) {
            return '';
        }

        return sprintf(
            /* translators: %s: formatted free shipping minimum amount. */
            __('* Free shipping on orders over %s', 'ai-zippy'),
            html_entity_decode(wp_strip_all_tags(wc_price($minimum)))
        );
    }

    /**
     * Get all WooCommerce shipping zones, including the default zone.
     */
    private static function getShippingZones(): array
    {
        $zones = [];

        foreach (\WC_Shipping_Zones::get_zones() as $zone_data) {
            $zones[] = \WC_Shipping_Zones::get_zone((int) $zone_data['zone_id']);
        }

        $zones[] = \WC_Shipping_Zones::get_zone(0);

        return $zones;
    }
}
