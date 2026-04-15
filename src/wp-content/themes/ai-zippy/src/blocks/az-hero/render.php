<?php

/**
 * AZ Hero Section Render Template
 */

$eyebrow         = $attributes['eyebrow'] ?? '';
$title           = $attributes['title'] ?? '';
$subtitle        = $attributes['subtitle'] ?? '';
$primaryBtnText  = $attributes['primaryBtnText'] ?? '';
$primaryBtnUrl   = $attributes['primaryBtnUrl'] ?? '#';
$ghostBtnText    = $attributes['ghostBtnText'] ?? '';
$ghostBtnUrl     = $attributes['ghostBtnUrl'] ?? '#';
$imageUrl        = $attributes['imageUrl'] ?? '';
$taglineSmall    = $attributes['taglineSmall'] ?? '';
$taglineLarge    = $attributes['taglineLarge'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes(['class' => 'hero']);
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="hero-content">
        <?php if ($eyebrow) : ?>
            <p class="eyebrow hero-eyebrow"><?php echo wp_kses_post($eyebrow); ?></p>
        <?php endif; ?>

        <?php if ($title) : ?>
            <h1 class="hero-title"><?php echo wp_kses_post($title); ?></h1>
        <?php endif; ?>

        <?php if ($subtitle) : ?>
            <p class="hero-subtitle"><?php echo wp_kses_post($subtitle); ?></p>
        <?php endif; ?>

        <div class="hero-cta">
            <?php if ($primaryBtnText) : ?>
                <a href="<?php echo esc_url($primaryBtnUrl); ?>" class="btn-primary"><?php echo esc_html($primaryBtnText); ?></a>
            <?php endif; ?>

            <?php if ($ghostBtnText) : ?>
                <a href="<?php echo esc_url($ghostBtnUrl); ?>" class="btn-ghost" style="color:var(--text-muted);text-decoration-color:var(--text-muted);"><?php echo esc_html($ghostBtnText); ?></a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero-visual">
        <?php if ($imageUrl) : ?>
            <img src="<?php echo esc_url($imageUrl); ?>" alt="" loading="eager">
        <?php endif; ?>
        <div class="hero-overlay"></div>
        <div class="hero-float-tag">
            <p><?php echo esc_html($taglineSmall); ?></p>
            <span><?php echo esc_html($taglineLarge); ?></span>
        </div>
    </div>
</section>