<?php
$eyebrow = $attributes['eyebrow'] ?? '';
$title   = $attributes['title'] ?? '';
$subtitle = $attributes['subtitle'] ?? '';
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'contact-hero']); ?>>
    <div class="contact-hero-inner">
        <?php if ($eyebrow) : ?><p class="eyebrow"><?php echo wp_kses_post($eyebrow); ?></p><?php endif; ?>
        <?php if ($title) : ?><h1 class="contact-page-title serif"><?php echo wp_kses_post($title); ?></h1><?php endif; ?>
        <?php if ($subtitle) : ?><p style="font-size:13px; color:var(--text-muted); margin-top:14px;"><?php echo wp_kses_post($subtitle); ?></p><?php endif; ?>
    </div>
</section>
