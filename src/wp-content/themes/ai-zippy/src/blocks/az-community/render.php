<?php

/**
 * AZ Community Render Template
 */

$eyebrow  = $attributes['eyebrow'] ?? '';
$title    = $attributes['title'] ?? '';
$subtitle = $attributes['subtitle'] ?? '';
$btnText  = $attributes['btnText'] ?? '';
$btnUrl   = $attributes['btnUrl'] ?? '#';
$images   = $attributes['images'] ?? [];

$wrapper_attributes = get_block_wrapper_attributes(['class' => 'community']);
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="community-inner">
        <?php if ($eyebrow) : ?>
            <p class="eyebrow"><?php echo wp_kses_post($eyebrow); ?></p>
        <?php endif; ?>

        <?php if ($title) : ?>
            <h2 class="community-title"><?php echo wp_kses_post($title); ?></h2>
        <?php endif; ?>

        <?php if ($subtitle) : ?>
            <p class="community-sub"><?php echo wp_kses_post($subtitle); ?></p>
        <?php endif; ?>

        <?php if ($btnText) : ?>
            <a href="<?php echo esc_url($btnUrl); ?>" class="btn-primary"><?php echo esc_html($btnText); ?></a>
        <?php endif; ?>

        <div class="social-grid">
            <?php foreach ($images as $img) : ?>
                <div class="social-tile">
                    <?php if (!empty($img['url'])) : ?>
                        <img src="<?php echo esc_url($img['url']); ?>" alt="" loading="lazy">
                    <?php endif; ?>
                    <span class="social-label"><?php echo esc_html($img['label'] ?? ''); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>