<?php
// templates/modal/modal.php

// 呼び出し元から渡される引数を取得
$context = $args['context'] ?? 'shop';
$store_name = $args['store_name'] ?? null;
$modal_id_suffix = $args['modal_id_suffix'] ?? '0';
// ★修正点3: single-goods.php から渡される store_url を受け取る
$external_link_url = $args['store_url'] ?? '';

// ショップ情報をカスタムタクソノミーから取得
$shop_term = null;
if ($store_name) {
    $terms = get_terms(array(
        'taxonomy'   => 'store', // カスタムタクソノミースラッグは 'store'
        'name'       => $store_name,
        'hide_empty' => false,
        'number'     => 1,
    ));

    if (!is_wp_error($terms) && !empty($terms)) {
        $shop_term = $terms[0];
    }
}

// ターム情報が取得できた場合のみ処理を続行
if ($shop_term) :
    // ★修正点4: タームに紐づくACFフィールドからデータを取得
    // 'store_img' は画像タイプなので、画像オブジェクトを返すように設定している場合を想定
    // 返り値が「画像 URL」であればそのまま $main_image_url を使用
    // 返り値が「画像オブジェクト」であれば $main_image['url'] を使用
    $main_image_obj = get_field('store_img', $shop_term);
    $main_image_url = is_array($main_image_obj) && isset($main_image_obj['url']) ? $main_image_obj['url'] : '';
    $text = wp_strip_all_tags(term_description($shop_term->term_id, 'store'));
    if (empty($text)) {
        $text = 'この店舗に関する情報はまだ登録されていません。';
    }

    // デフォルト画像（画像がない場合）
    $no_image_url = get_template_directory_uri() . '/assets/image/no-image.webp';

    $modal_id = 'store-' . esc_attr($modal_id_suffix);
    $swiper_class = 'store-swiper-' . esc_attr($modal_id_suffix);
    $pagination_class = 'store-swiper-pagination-' . esc_attr($modal_id_suffix);
?>

<div id="<?= esc_attr($modal_id) ?>" class="js-modal modal hidden fixed z-[9999] inset-0 bg-black/50 flex items-center justify-center">
    <div class="modal-content bg-white px-5 lg:px-9 pt-5 lg:pt-9 pb-7 lg:pb-15 rounded-[20px] lg:rounded-[40px] max-w-[90%] lg:max-w-[740px] w-full relative max-h-[90vh] overflow-y-auto">
        <button type="button" class="js-modal-close modal-close absolute top-2 lg:top-[18px] right-2 lg:right-[18px] shadow-md hover:shadow-lg transition-shadow duration-300 z-10 rounded-full">
            <img class="w-8 h-8 lg:w-10 lg:h-10" src="<?php echo get_template_directory_uri(); ?>/assets/image/close-icon.svg" alt="閉じる" loading="lazy" width="100%" height="100%">
        </button>

        <?php if (!empty($main_image_url)): // メイン画像がある場合 ?>
            <div class="swiper mb-4 lg:mb-10 <?= esc_attr($swiper_class) ?>">
                <div class="swiper-wrapper">
                    <div class="swiper-slide aspect-[3/2] overflow-hidden rounded-[10px] lg:rounded-[20px]">
                        <img src="<?= esc_url($main_image_url); ?>" class="w-full h-full object-cover" alt="<?= esc_attr($store_name); ?>の店舗画像">
                    </div>
                </div>

                <div class="hidden absolute inset-y-1/2 left-0 right-0 z-10 -translate-y-1/2 lg:flex justify-between px-4">
                    <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'modal']); ?>
                </div>

                <div class="swiper-pagination <?= esc_attr($pagination_class) ?> mt-3"></div>
            </div>
        <?php else: // 画像がない場合はデフォルト画像を表示 ?>
            <div class="swiper mb-4 lg:mb-10 <?= esc_attr($swiper_class) ?>">
                <div class="swiper-wrapper">
                    <div class="swiper-slide aspect-[3/2] overflow-hidden rounded-[10px] lg:rounded-[20px]">
                        <img src="<?= esc_url($no_image_url); ?>" class="w-full h-full object-cover" alt="画像なし">
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="flex flex-col lg:flex-row lg:items-start gap-2 lg:gap-9 leading-relaxed px-4 lg:px-10">
            <div class="text-base lg:text-lg font-semibold lg:w-50 flex-shrink-0"><?= esc_html($store_name); ?></div>
            <div class="text-sm lg:text-base flex-1 leading-[1.75]"><?= esc_html($text); ?></div>
        </div>

        <?php if ($context === 'shop' && !empty($external_link_url)): // ショップコンテキストでリンクがある場合のみ表示 ?>
            <div class="mt-3 lg:mt-10 text-center">
                <?php
                // ★修正点6: 外部サイトリンクのURLとして $external_link_url を使用
                get_template_part('templates/common/external-site-link', null, [
                    'url' => $external_link_url,
                    'text' => '販売サイトで購入する',
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php endif; // $shop_term が存在する場合のみ ?>
