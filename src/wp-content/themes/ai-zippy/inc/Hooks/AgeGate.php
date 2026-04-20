<?php

namespace AiZippy\Hooks;

defined('ABSPATH') || exit;

/**
 * Global Age Gate Hook
 * Injects the age verification popup into the footer.
 */
class AgeGate
{
    /**
     * Register hooks.
     */
    public static function register(): void
    {
        add_action('wp_footer', [self::class, 'render']);
    }

    /**
     * Render the age gate HTML.
     */
    public static function render(): void
    {
        // Don't show age gate in block editor
        if (is_admin() || is_customize_preview()) {
            return;
        }

        $logo_id = get_theme_mod('custom_logo');
        $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';

?>
        <div id="age-gate">
            <div class="ag-content">
                <?php if ($logo_url) : ?>
                    <div class="ag-logo">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>">
                    </div>
                <?php endif; ?>

                <h2 class="ag-title serif"><?php _e('Age Verification', 'ai-zippy'); ?></h2>

                <div class="ag-text">
                    <p><?php _e('The legal age to buy alcohol in Singapore is <strong>18 years</strong>.', 'ai-zippy'); ?></p>
                    <p><?php _e('Buying alcohol below the legal drinking age is a punishable offence.', 'ai-zippy'); ?></p>
                    <p><strong><?php _e('Are you over 18 years of age?', 'ai-zippy'); ?></strong></p>
                </div>

                <div class="ag-buttons">
                    <button class="btn-ag btn-yes"><?php _e('Yes, I am 18+', 'ai-zippy'); ?></button>
                    <button class="btn-ag btn-no"><?php _e('No', 'ai-zippy'); ?></button>
                </div>

                <label class="ag-remember" for="ag-remember-check">
                    <input type="checkbox" id="ag-remember-check" checked>
                    <span><?php _e('Remember my choice', 'ai-zippy'); ?></span>
                </label>
            </div>
        </div>
<?php
    }
}
