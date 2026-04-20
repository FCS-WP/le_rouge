<?php

/**
 * AZ Footer Menu Render Template
 */

$title = $attributes['title'] ?? '';
$links = $attributes['links'] ?? [];

$wrapper_attributes = get_block_wrapper_attributes(['class' => 'footer-col']);
?>

<div <?php echo $wrapper_attributes; ?>>
    <?php if ($title) : ?>
        <h4 class="footer-col-title"><?php echo esc_html($title); ?></h4>
    <?php endif; ?>

    <?php if (!empty($links)) : ?>
        <p>
            <?php foreach ($links as $link) : ?>
                <?php if (!empty($link['label'])) : ?>
                    <a href="<?php echo esc_url($link['url'] ?: '#'); ?>"><?php echo esc_html($link['label']); ?></a><br />
                <?php endif; ?>
            <?php endforeach; ?>
        </p>
    <?php endif; ?>
</div>