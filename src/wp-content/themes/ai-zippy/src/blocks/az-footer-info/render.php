<?php

/**
 * AZ Footer Info Render Template
 */

$logoUrl      = $attributes['logoUrl'] ?? '';
$description  = $attributes['description'] ?? '';
$outlet1Title = $attributes['outlet1Title'] ?? '';
$outlet1Addr  = $attributes['outlet1Address'] ?? '';
$outlet1Hours = $attributes['outlet1Hours'] ?? '';
$outlet2Title = $attributes['outlet2Title'] ?? '';
$outlet2Addr  = $attributes['outlet2Address'] ?? '';
$outlet2Hours = $attributes['outlet2Hours'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes(['class' => 'footer-info-col']);

// If no logo uploaded, try to get site logo
if (!$logoUrl) {
    $logo_id = get_theme_mod('custom_logo');
    $logoUrl = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <?php if ($logoUrl) : ?>
        <div class="footer-logo">
            <img src="<?php echo esc_url($logoUrl); ?>" alt="<?php bloginfo('name'); ?>" style="width:120px;">
        </div>
    <?php endif; ?>

    <?php if ($description) : ?>
        <p class="footer-desc"><?php echo wp_kses_post($description); ?></p>
    <?php endif; ?>

    <?php if ($outlet1Title) : ?>
        <div class="footer-contact-item">
            <span class="footer-contact-icon">◇</span>
            <div class="footer-contact-text">
                <strong><?php echo esc_html($outlet1Title); ?></strong><br />
                <?php echo esc_html($outlet1Addr); ?><br />
                <?php echo nl2br(esc_html($outlet1Hours)); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($outlet2Title) : ?>
        <div class="footer-contact-item">
            <span class="footer-contact-icon">◇</span>
            <div class="footer-contact-text">
                <strong><?php echo esc_html($outlet2Title); ?></strong><br />
                <?php echo esc_html($outlet2Addr); ?><br />
                <?php echo nl2br(esc_html($outlet2Hours)); ?>
            </div>
        </div>
    <?php endif; ?>
</div>