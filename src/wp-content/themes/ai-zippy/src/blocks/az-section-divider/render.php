<?php

/**
 * AZ Section Divider Render Template
 */

$text = $attributes['text'] ?? '';
$wrapper_attributes = get_block_wrapper_attributes(['class' => 'section-divider']);
?>

<div <?php echo $wrapper_attributes; ?>>
    <span class="section-divider-inner">&#9670; <?php echo esc_html($text); ?> &#9670;</span>
</div>