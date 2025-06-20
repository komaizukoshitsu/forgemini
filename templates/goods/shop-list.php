<?php
get_template_part('templates/heading/heading-with-brackets', null, [
    'heading_text' => 'ショップ一覧',
    'heading_tag'  => 'h2',
]);
?>

<ul class="mt-5 xl:mt-10 flex flex-wrap gap-3 xl:gap-4">
<?php
    $terms = get_terms([
        'taxonomy'   => 'store',
        'hide_empty' => false,
        'orderby'    => 'meta_value_num',
        'meta_key'   => 'sort_order',
        'order'      => 'ASC',
    ]);

    // デバッグログは問題解決後削除してください

    if (!empty($terms) && !is_wp_error($terms)):
    foreach ($terms as $index => $term):
        $store_name = $term->name;
        // Goods単一記事ページに合わせ、モーダルIDは 'store-' + タームID の形式に統一
        $modal_id = 'store-' . $term->term_id; // 例: 'store-50'

        $store_image = get_field('store_img', 'term_' . $term->term_id); // 画像データ（URL文字列または配列）
        $store_url = get_field('store_url', 'term_' . $term->term_id);   // URL文字列

        $base_class = 'shop-list';
        $classes = [$base_class];
        if ($index === 0 || $index === 1) {
            $classes[] = 'w-full xl:w-[calc(50%-9px)]';
            $classes[] = 'h-25 xl:h-35';
        } else {
            $classes[] = 'w-[calc(50%-6px)] xl:w-[calc(33.333%-12px)]';
            $classes[] = 'h-15 xl:h-[90px]';
        }

        $image_src = '';
        $image_alt = esc_attr($store_name);

        if (!empty($store_image)) {
            if (is_array($store_image) && isset($store_image['url'])) {
                $image_src = $store_image['url'];
                $image_alt = isset($store_image['alt']) && !empty($store_image['alt']) ? esc_attr($store_image['alt']) : $image_alt;
            } elseif (is_string($store_image)) {
                $image_src = $store_image;
            }
        }
?>
    <li class="<?php echo implode(' ', $classes); ?>">
        <div class="w-full h-full relative group">
            <div
            class="absolute top-0 left-0 w-full h-full flex items-center gap-[11px] xl:gap-[18px] cursor-pointer outline-1 outline-[#D9D9D9] rounded-lg transition-all duration-200 ease-in-out js-modal-trigger"
            data-modal-target="<?= esc_attr($modal_id); ?>"> <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 rounded-lg transition-opacity duration-200 pointer-events-none"></div>
                <div class="shop-image aspect-square <?php echo ($index === 0 || $index === 1) ? 'h-25 xl:h-35' : 'h-15 xl:h-[90px]'; ?>">
                    <?php if (!empty($image_src)): ?>
                        <img
                            class="h-full w-full object-cover"
                            src="<?php echo esc_url($image_src); ?>"
                            alt="<?php echo esc_attr($image_alt); ?>"
                            loading="lazy"
                        >
                    <?php endif; ?>
                </div>
                <div class="shop-name text-sm xl:text-base pr-5 leading-[1.4] break-all min-w-0">
                    <?php echo esc_html($store_name); ?>
                </div>
            </div>
        </div>
    </li>
    <?php
        // ★ここをGoods単一記事ページに渡す引数の形式と完全に合わせる★
        // モーダル内で画像表示もしたい場合、`store_image_data` も渡す
        get_template_part('templates/modal/modal-content-shop', null, [
            'context' => 'shop',
            'store_name' => $store_name,
            'modal_id_suffix' => $term->term_id, // タームIDを渡す
            'store_url' => $store_url,
            // もしモーダル内で画像を表示したい場合は、この行を追加してください。
            // modal-content.php 側でこれを受け取って表示するロジックが必要です。
            'store_image_data' => $store_image,
        ]);
    ?>
    <?php endforeach; else: ?>
    <li class="w-full text-center text-sm text-gray-500">データがありません。</li>
<?php endif; ?>
</ul>
