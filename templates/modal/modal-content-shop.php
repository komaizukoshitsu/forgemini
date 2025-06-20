<?php
// templates/modal/modal.php

// 呼び出し元から渡される引数を取得
$context = $args['context'] ?? 'shop';
$store_name = $args['store_name'] ?? null;
$modal_id_suffix = $args['modal_id_suffix'] ?? '0';
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
    // 'store_img' は画像タイプなので、画像オブジェクトを返すように設定している場合を想定
    $main_image_obj = get_field('store_img', $shop_term);
    $main_image_url = is_array($main_image_obj) && isset($main_image_obj['url']) ? $main_image_obj['url'] : '';
    $text = wp_strip_all_tags(term_description($shop_term->term_id, 'store'));
    if (empty($text)) {
        $text = 'この店舗に関する情報はまだ登録されていません。';
    }

    // デフォルト画像（画像がない場合）
    $no_image_url = get_template_directory_uri() . '/assets/image/no-image.webp';

    $modal_id = 'store-' . esc_attr($modal_id_suffix);
    // Swiper関連のクラス変数は不要になるため削除
?>

<div id="<?= esc_attr($modal_id) ?>" class="js-modal modal hidden fixed z-[9999] inset-0 bg-black/50 flex items-center justify-center">
    <div class="modal-content bg-white px-5 xl:px-9 pt-5 xl:pt-9 pb-7 xl:pb-15 rounded-[20px] xl:rounded-[40px] max-w-[90%] xl:max-w-[740px] w-full relative max-h-[90vh] overflow-y-auto">
        <button type="button" class="js-modal-close modal-close absolute top-2 xl:top-[18px] right-2 xl:right-[18px] shadow-md hover:shadow-lg transition-shadow duration-300 z-10 rounded-full">
            <img class="w-8 h-8 xl:w-10 xl:h-10" src="<?php echo get_template_directory_uri(); ?>/assets/image/close-icon.svg" alt="閉じる" loading="lazy" width="100%" height="100%">
        </button>

        <div class="mb-4 xl:mb-10 aspect-[3/2] overflow-hidden rounded-[10px] xl:rounded-[20px]">
            <?php if (!empty($main_image_url)): // メイン画像がある場合 ?>
                <img src="<?= esc_url($main_image_url); ?>" class="w-full h-full object-cover" alt="<?= esc_attr($store_name); ?>の店舗画像">
            <?php else: // 画像がない場合はデフォルト画像を表示 ?>
                <img src="<?= esc_url($no_image_url); ?>" class="w-full h-full object-cover" alt="画像なし">
            <?php endif; ?>
        </div>
        <div class="flex flex-col xl:flex-row xl:items-start gap-2 xl:gap-9 leading-relaxed px-4 xl:px-10">
            <div class="text-base xl:text-lg font-semibold xl:w-50 flex-shrink-0"><?= esc_html($store_name); ?></div>
            <div class="text-sm xl:text-base flex-1 leading-[1.75]"><?= esc_html($text); ?></div>
        </div>

        <?php if ($context === 'shop' && !empty($external_link_url)): ?>
            <div class="mt-3 xl:mt-10 text-center">
                <?php
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
