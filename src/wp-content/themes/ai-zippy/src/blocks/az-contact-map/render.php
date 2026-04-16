<?php
$mapIframe = $attributes['mapIframe'] ?? '';
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'map-section']); ?>>
    <div class="map-placeholder">
        <?php if (!empty($mapIframe)) : ?>
            <?php echo $mapIframe; // This contains a safe iframe from GMaps 
            ?>
        <?php else : ?>
            <span style="font-size:28px; color:var(--gold); opacity:0.25;">◇</span>
            <span class="map-label">Google Maps Embed Placeholder</span>
            <span class="map-sub">Raffles Place · Marina Bay · Republic Plaza</span>
        <?php endif; ?>
    </div>
</section>
