
<?php
get_template_part('templates/heading/heading-with-brackets', null, [
    'heading_text' => 'ショップ一覧',
    'heading_tag'  => 'h2',
]);
?>

<ul class="mt-5 lg:mt-10 flex flex-wrap gap-3 lg:gap-4">
<?php
    $shop_list = SCF::get('shop_list', 339);
    if (!empty($shop_list)):
    foreach ($shop_list as $index => $fields):
        $store_name = $fields['shop-name'];
        $modal_id = 'modal-' . $index; //modal-0

        // ここで毎回変数を明示的にセット
        set_query_var('store_name', $store_name);
        set_query_var('post_id', $modal_id); //$post_id='modal-0'

        $shop_image = $fields['shop-image'];
        $slider_image1 = $fields['shop-slider-image1'];
        $slider_image2 = $fields['shop-slider-image2'];
        $slider_image3 = $fields['shop-slider-image3'];
        $shop_text = $fields['shop-text'];
        $shop_url = $fields['shop-url'];

        $base_class = 'shop-list';
        $classes = [$base_class];
        if ($index === 0 || $index === 1) {
        $classes[] = 'w-full lg:w-[calc(50%-9px)]';
        $classes[] = 'h-25 lg:h-35';
        } else {
        $classes[] = 'w-[calc(50%-6px)] lg:w-[calc(33.333%-12px)]';
        $classes[] = 'h-15 lg:h-[90px]';
        }
?>
<!-- store-modal-0 -->
    <li class="<?php echo implode(' ', $classes); ?>">
        <div class="w-full h-full relative group">
            <div
            class="absolute top-0 left-0 w-full h-full flex items-center gap-[11px] lg:gap-[18px] cursor-pointer outline-1 outline-[#D9D9D9] rounded-lg transition-all duration-200 ease-in-out js-modal-trigger"
            data-modal-target="store-<?= esc_attr($modal_id); ?>">
                <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 rounded-lg transition-opacity duration-200 pointer-events-none"></div>
                <div class="shop-image aspect-square <?php echo ($index === 0 || $index === 1) ? 'h-25 lg:h-35' : 'h-15 lg:h-[90px]'; ?>">
                    <?php if (!empty($shop_image)): ?>
                        <img
                            class="h-full w-full object-cover"
                            src="<?php echo esc_url(wp_get_attachment_url($shop_image)); ?>"
                            alt="<?php echo esc_attr($store_name); ?>"
                            loading="lazy"
                        >
                    <?php endif; ?>
                </div>
                <div class="shop-name text-sm lg:text-base pr-5 leading-[1.4] break-all min-w-0">
                    <?php echo esc_html($store_name); ?>
                </div>
            </div>
        </div>
    </li>
    <?php
        set_query_var('context', 'shop');
        get_template_part('templates/modal/modal-content');
    ?>
    <?php endforeach; else: ?>
    <li class="w-full text-center text-sm text-gray-500">データがありません。</li>
<?php endif; ?>
</ul>
