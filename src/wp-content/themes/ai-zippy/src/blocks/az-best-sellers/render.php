<?php

/**
 * AZ Product Grid Render Template
 */

$eyebrow     = $attributes['eyebrow'] ?? '';
$title       = $attributes['title'] ?? '';
$viewAllText = $attributes['viewAllText'] ?? '';
$viewAllUrl  = $attributes['viewAllUrl'] ?? '#';

// Query parameters
$limit    = $attributes['limit'] ?? 4;
$category = $attributes['category'] ?? '';
$orderby  = $attributes['orderby'] ?? 'date';
$order    = $attributes['order'] ?? 'DESC';

$query_args = [
    'limit'    => $limit,
    'orderby'  => $orderby,
    'order'    => $order,
    'status'   => 'publish',
    'visibility' => 'catalog',
];

if (!empty($category)) {
    $query_args['category'] = [$category];
}

$wc_products = wc_get_products($query_args);
$products    = [];

foreach ($wc_products as $product) {
    $id = $product->get_id();
    $products[] = [
        'id'    => $id,
        'name'  => $product->get_name(),
        'price' => $product->get_price_html(),
        'image' => wp_get_attachment_image_url($product->get_image_id(), 'medium_large'),
        'tag'   => '', // Handled below
        'region' => '', // Handled below
        'badge' => has_term('new-arrivals', 'product_cat', $id) ? __('New', 'ai-zippy') : ($product->is_on_sale() ? __('Sale', 'ai-zippy') : ''),
        'url'   => $product->get_permalink(),
    ];
}

// Optionally fetch tags or custom attributes for 'tag' and 'region' fields
foreach ($products as &$p) {
    if (function_exists('wc_get_product')) {
        $prod_obj = wc_get_product($p['id']);
        // Example: Use categories as tag
        $cats = wp_get_post_terms($p['id'], 'product_cat');
        if (!empty($cats)) {
            $p['tag'] = $cats[0]->name;
        }

        // Example: Region from a custom attribute 'pa_region'
        $region = $prod_obj->get_attribute('pa_region');
        if ($region) {
            $p['region'] = $region;
        }
    }
}

$wrapper_attributes = get_block_wrapper_attributes(['class' => 'product-section']);
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="product-section-wrap">
        <?php if ($eyebrow) : ?>
            <p class="eyebrow" style="margin-bottom:12px;"><?php echo wp_kses_post($eyebrow); ?></p>
        <?php endif; ?>

        <div class="section-header">
            <?php if ($title) : ?>
                <h2 class="section-title"><?php echo wp_kses_post($title); ?></h2>
            <?php endif; ?>

            <?php if ($viewAllText) : ?>
                <a href="<?php echo esc_url($viewAllUrl); ?>" class="section-view-all"><?php echo esc_html($viewAllText); ?></a>
            <?php endif; ?>
        </div>

        <div class="product-grid">
            <?php foreach ($products as $prod) : ?>
                <div class="product-card">
                    <div class="product-img">
                        <a href="<?php echo esc_url($prod['url']); ?>">
                            <?php if (!empty($prod['badge'])) : ?>
                                <div class="product-badge <?php echo ($prod['badge'] === __('New', 'ai-zippy')) ? 'gold' : ''; ?>"><?php echo esc_html($prod['badge']); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($prod['image'])) : ?>
                                <img src="<?php echo esc_url($prod['image']); ?>" alt="<?php echo esc_attr($prod['name']); ?>" loading="lazy">
                            <?php else : ?>
                                <div class="placeholder-img" style="aspect-ratio:3/4; background:#eee;"></div>
                            <?php endif; ?>
                        </a>
                    </div>

                    <div class="product-info">
                        <p class="product-tag"><?php echo esc_html($prod['tag'] ?? ''); ?></p>
                        <h3 class="product-name">
                            <a href="<?php echo esc_url($prod['url']); ?>"><?php echo esc_html($prod['name'] ?? ''); ?></a>
                        </h3>
                        <?php if (!empty($prod['region'])) : ?>
                            <p class="product-region"><?php echo esc_html($prod['region']); ?></p>
                        <?php endif; ?>
                        <span class="product-price"><?php echo $prod['price']; // price_html is already escaped/safe 
                                                    ?></span>
                    </div>

                    <div class="product-hover-btn">
                        <a href="<?php echo esc_url($prod['url']); ?>" style="color:inherit; text-decoration:none;">
                            <?php _e('View Details', 'ai-zippy'); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>