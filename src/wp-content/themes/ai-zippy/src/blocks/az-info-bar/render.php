<?php

/**
 * AZ Info Bar Render Template
 */

$items = $attributes['items'] ?? [];
$wrapper_attributes = get_block_wrapper_attributes(['class' => 'info-bar']);
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="info-bar-inner">
        <?php foreach ($items as $item) : ?>
            <div class="info-bar-item">
                <span class="info-icon"><?php echo wp_kses_post($item['icon'] ?? ''); ?></span>
                <span class="info-text">
                    <strong><?php echo wp_kses_post($item['title'] ?? ''); ?></strong>
                    &nbsp;<?php echo wp_kses_post($item['text'] ?? ''); ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
</div>