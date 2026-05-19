<?php

namespace AiZippy\Hooks;

use AiZippy\Core\ViteAssets;

defined('ABSPATH') || exit;

/**
 * WooCommerce promotions settings.
 */
class Promotions
{
    public const OPTION_NAME = 'az_promotions_settings';

    private const NONCE_ACTION = 'az_save_promotions';
    private const NONCE_NAME = 'az_promotions_nonce';

    /**
     * Register hooks.
     */
    public static function register(): void
    {
        add_action('admin_menu', [self::class, 'addMenuPage']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueueAssets']);
        add_action('admin_post_az_save_promotions', [self::class, 'save']);
    }

    /**
     * Add Promotions under the WooCommerce menu.
     */
    public static function addMenuPage(): void
    {
        add_submenu_page(
            'woocommerce',
            __('Promotions', 'ai-zippy'),
            __('Promotions', 'ai-zippy'),
            'manage_woocommerce',
            'az-promotions',
            [self::class, 'render']
        );
    }

    /**
     * Enqueue admin styles for the Promotions page.
     */
    public static function enqueueAssets(string $hook_suffix): void
    {
        if ('woocommerce_page_az-promotions' !== $hook_suffix) {
            return;
        }

        ViteAssets::enqueue('ai-zippy-promotions-admin', 'src/wp-content/themes/ai-zippy/src/scss/style.scss');
    }

    /**
     * Render the promotions settings page.
     */
    public static function render(): void
    {
        if (!current_user_can('manage_woocommerce')) {
            wp_die(esc_html__('You do not have permission to manage promotions.', 'ai-zippy'));
        }

        $settings = self::getSettings();
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        ?>
        <div class="wrap az-promotions">
            <div class="az-promotions__header">
                <h1><?php esc_html_e('Promotions', 'ai-zippy'); ?></h1>
                <p><?php esc_html_e('Create quantity-based percentage discounts for WooCommerce carts. Choose categories to limit the promotion, or leave categories empty to apply the rules to every product.', 'ai-zippy'); ?></p>
            </div>

            <?php if ('true' === sanitize_text_field(wp_unslash($_GET['settings-updated'] ?? ''))) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php esc_html_e('Promotion settings saved.', 'ai-zippy'); ?></p>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="az_save_promotions">
                <?php wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME); ?>

                <div class="az-promotions__grid">
                    <section class="az-promotions__panel">
                        <div class="az-promotions__panel-header">
                            <div>
                                <h2><?php esc_html_e('Eligible categories', 'ai-zippy'); ?></h2>
                                <p><?php esc_html_e('Select multiple product categories. Leave all unchecked to make every product eligible.', 'ai-zippy'); ?></p>
                            </div>
                        </div>

                        <div class="az-promotions__category-tools">
                            <input
                                type="search"
                                class="regular-text az-promotions__search"
                                id="az-promotion-category-search"
                                placeholder="<?php echo esc_attr__('Search categories', 'ai-zippy'); ?>"
                            >
                            <button type="button" class="button" id="az-select-all-categories">
                                <?php esc_html_e('Select all', 'ai-zippy'); ?>
                            </button>
                            <button type="button" class="button" id="az-clear-categories">
                                <?php esc_html_e('Clear selection', 'ai-zippy'); ?>
                            </button>
                        </div>

                        <div class="az-promotions__category-list" id="az-promotion-categories">
                            <?php if (!is_wp_error($categories) && !empty($categories)) : ?>
                                <?php foreach ($categories as $category) : ?>
                                    <label
                                        class="az-promotions__category-option"
                                        data-category-name="<?php echo esc_attr(strtolower($category->name)); ?>"
                                    >
                                        <input
                                            type="checkbox"
                                            name="az_promotions[categories][]"
                                            value="<?php echo esc_attr((string) $category->term_id); ?>"
                                            <?php checked(in_array((int) $category->term_id, $settings['categories'], true)); ?>
                                        >
                                        <span><?php echo esc_html($category->name); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p class="az-promotions__category-empty">
                                    <?php esc_html_e('No product categories found yet.', 'ai-zippy'); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <p class="az-promotions__hint">
                            <?php esc_html_e('Current behavior: no selected categories means all products can trigger the promotion.', 'ai-zippy'); ?>
                        </p>
                    </section>

                    <section class="az-promotions__panel">
                        <div class="az-promotions__panel-header">
                            <div>
                                <h2><?php esc_html_e('Discount rules', 'ai-zippy'); ?></h2>
                                <p><?php esc_html_e('Example: set minimum quantity to 3 and discount to 15 to give 15% off the cart product subtotal when at least 3 eligible items are in the cart.', 'ai-zippy'); ?></p>
                            </div>
                        </div>

                        <table class="az-promotions__rules">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Minimum eligible quantity', 'ai-zippy'); ?></th>
                                    <th><?php esc_html_e('Discount percentage', 'ai-zippy'); ?></th>
                                    <th><?php esc_html_e('Action', 'ai-zippy'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="az-promotion-rules">
                                <?php foreach ($settings['rules'] as $index => $rule) : ?>
                                    <?php self::renderRuleRow((int) $index, (int) $rule['quantity'], (float) $rule['discount']); ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="az-promotions__actions">
                            <button type="button" class="button" id="az-add-promotion-rule">
                                <?php esc_html_e('Add more', 'ai-zippy'); ?>
                            </button>
                            <?php submit_button(__('Save promotions', 'ai-zippy'), 'primary', 'submit', false); ?>
                        </div>
                    </section>
                </div>
            </form>
        </div>

        <template id="az-promotion-rule-template">
            <?php self::renderRuleRow(999999, 3, 15.0); ?>
        </template>

        <script>
            (() => {
                const tableBody = document.getElementById('az-promotion-rules');
                const addButton = document.getElementById('az-add-promotion-rule');
                const template = document.getElementById('az-promotion-rule-template');
                const categoryList = document.getElementById('az-promotion-categories');
                const searchInput = document.getElementById('az-promotion-category-search');
                const selectAllButton = document.getElementById('az-select-all-categories');
                const clearButton = document.getElementById('az-clear-categories');

                if (!tableBody || !addButton || !template) {
                    return;
                }

                const refreshNames = () => {
                    tableBody.querySelectorAll('tr').forEach((row, index) => {
                        row.querySelectorAll('[data-rule-field]').forEach((field) => {
                            field.name = `az_promotions[rules][${index}][${field.dataset.ruleField}]`;
                        });
                    });
                };

                addButton.addEventListener('click', () => {
                    const fragment = template.content.cloneNode(true);
                    tableBody.appendChild(fragment);
                    refreshNames();
                });

                tableBody.addEventListener('click', (event) => {
                    const removeButton = event.target.closest('[data-remove-rule]');

                    if (!removeButton) {
                        return;
                    }

                    const rows = tableBody.querySelectorAll('tr');

                    if (rows.length <= 1) {
                        rows[0].querySelectorAll('input').forEach((input) => {
                            input.value = input.dataset.ruleField === 'quantity' ? '3' : '15';
                        });
                        return;
                    }

                    removeButton.closest('tr').remove();
                    refreshNames();
                });

                if (categoryList && searchInput) {
                    searchInput.addEventListener('input', () => {
                        const query = searchInput.value.trim().toLowerCase();

                        categoryList.querySelectorAll('[data-category-name]').forEach((option) => {
                            option.hidden = query !== '' && !option.dataset.categoryName.includes(query);
                        });
                    });
                }

                if (categoryList && selectAllButton) {
                    selectAllButton.addEventListener('click', () => {
                        categoryList.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
                            checkbox.checked = true;
                        });
                    });
                }

                if (categoryList && clearButton) {
                    clearButton.addEventListener('click', () => {
                        categoryList.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
                            checkbox.checked = false;
                        });
                    });
                }
            })();
        </script>
        <?php
    }

    /**
     * Save promotion settings.
     */
    public static function save(): void
    {
        if (!current_user_can('manage_woocommerce')) {
            wp_die(esc_html__('You do not have permission to manage promotions.', 'ai-zippy'));
        }

        check_admin_referer(self::NONCE_ACTION, self::NONCE_NAME);

        $raw_settings = wp_unslash($_POST['az_promotions'] ?? []);
        $settings = self::sanitizeSettings(is_array($raw_settings) ? $raw_settings : []);

        update_option(self::OPTION_NAME, $settings);

        wp_safe_redirect(add_query_arg(
            'settings-updated',
            'true',
            admin_url('admin.php?page=az-promotions')
        ));
        exit;
    }

    /**
     * Get saved promotion settings.
     */
    public static function getSettings(): array
    {
        $settings = get_option(self::OPTION_NAME, []);

        if (!is_array($settings)) {
            $settings = [];
        }

        $settings = wp_parse_args($settings, [
            'categories' => [],
            'rules' => [
                [
                    'quantity' => 3,
                    'discount' => 15.0,
                ],
            ],
        ]);

        return self::sanitizeSettings($settings);
    }

    /**
     * Render one discount rule row.
     */
    private static function renderRuleRow(int $index, int $quantity, float $discount): void
    {
        ?>
        <tr>
            <td>
                <input
                    type="number"
                    min="1"
                    step="1"
                    aria-label="<?php echo esc_attr__('Minimum eligible quantity', 'ai-zippy'); ?>"
                    data-rule-field="quantity"
                    name="az_promotions[rules][<?php echo esc_attr((string) $index); ?>][quantity]"
                    value="<?php echo esc_attr((string) $quantity); ?>"
                >
            </td>
            <td>
                <input
                    type="number"
                    min="0"
                    max="100"
                    step="0.01"
                    aria-label="<?php echo esc_attr__('Discount percentage', 'ai-zippy'); ?>"
                    data-rule-field="discount"
                    name="az_promotions[rules][<?php echo esc_attr((string) $index); ?>][discount]"
                    value="<?php echo esc_attr((string) $discount); ?>"
                >
                <span>%</span>
            </td>
            <td>
                <button type="button" class="button-link-delete" data-remove-rule>
                    <?php esc_html_e('Remove', 'ai-zippy'); ?>
                </button>
            </td>
        </tr>
        <?php
    }

    /**
     * Sanitize promotion settings.
     */
    private static function sanitizeSettings(array $settings): array
    {
        $raw_categories = $settings['categories'] ?? [];
        $raw_rules = $settings['rules'] ?? [];

        $categories = is_array($raw_categories) ? array_map('absint', $raw_categories) : [];
        $categories = array_values(array_filter(array_unique($categories)));

        $rules = [];

        foreach ((is_array($raw_rules) ? $raw_rules : []) as $rule) {
            if (!is_array($rule)) {
                continue;
            }

            $quantity = absint($rule['quantity'] ?? 0);
            $discount = (float) sanitize_text_field((string) ($rule['discount'] ?? 0));

            if ($quantity < 1 || $discount <= 0) {
                continue;
            }

            $rules[] = [
                'quantity' => $quantity,
                'discount' => min($discount, 100.0),
            ];
        }

        usort($rules, static function (array $first, array $second): int {
            return $first['quantity'] <=> $second['quantity'];
        });

        if (empty($rules)) {
            $rules[] = [
                'quantity' => 3,
                'discount' => 15.0,
            ];
        }

        return [
            'categories' => $categories,
            'rules' => $rules,
        ];
    }
}
