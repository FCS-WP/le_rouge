<?php

/**
 * AZ Brand Intro Render Template
 */

$eyebrow   = $attributes['eyebrow'] ?? '';
$heading   = $attributes['heading'] ?? '';
$btnText   = $attributes['btnText'] ?? '';
$btnUrl    = $attributes['btnUrl'] ?? '#';
$textLeft  = $attributes['textLeft'] ?? '';
$textRight = $attributes['textRight'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes(['class' => 'brand-intro']);
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="bi-inner">
        <div class="bi-left">
            <?php if ($eyebrow) : ?>
                <p class="eyebrow bi-label"><?php echo wp_kses_post($eyebrow); ?></p>
            <?php endif; ?>

            <?php if ($heading) : ?>
                <h2 class="bi-heading serif"><?php echo wp_kses_post($heading); ?></h2>
            <?php endif; ?>

            <div class="bi-divider"></div>

            <?php if ($btnText) : ?>
                <a href="<?php echo esc_url($btnUrl); ?>" class="btn-outline"><?php echo esc_html($btnText); ?></a>
            <?php endif; ?>
        </div>
        <div class="bi-right">
            <?php if ($textLeft) : ?>
                <p class="bi-text"><?php echo wp_kses_post($textLeft); ?></p>
            <?php endif; ?>

            <?php if ($textRight) : ?>
                <p class="bi-text"><?php echo wp_kses_post($textRight); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>