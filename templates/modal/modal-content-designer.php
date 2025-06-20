<?php
// templates/modal/modal-content-designer.php

// 呼び出し元から渡される引数を取得
$modal_id_suffix = $args['modal_id_suffix'] ?? '0';
$designer_name = $args['designer_name'] ?? 'デザイナー'; // 変数名をdesigner_nameに統一
$designer_images = $args['slider_images'] ?? []; // スライダー画像用の配列
$designer_text = $args['designer_text'] ?? 'デザイナーに関する情報がありません。'; // 説明文
$designer_url = $args['designer_url'] ?? '';   // 外部URL用
$designer_email = $args['designer_email'] ?? ''; // メールアドレス用

// リンクボタンのテキストとURLを決定
$display_link_url = '';
$link_button_text = '';

if (!empty($designer_url)) {
    $display_link_url = $designer_url;
    $link_button_text = 'ウェブサイトへ';
} elseif (!empty($designer_email)) {
    $display_link_url = 'mailto:' . esc_attr($designer_email);
    $link_button_text = 'ウェブサイトへ';
}

// デフォルト画像（画像がない場合）
$no_image_url = get_template_directory_uri() . '/assets/image/no-image.webp';

$modal_id = 'store-' . esc_attr($modal_id_suffix); // 既存のJSロジックに合わせる
$swiper_class = 'store-swiper-' . esc_attr($modal_id_suffix);
$pagination_class = 'store-swiper-pagination-' . esc_attr($modal_id_suffix);

// 画像の有無をチェック
$has_images = !empty($designer_images);
?>

<div id="<?= esc_attr($modal_id) ?>" class="js-modal modal hidden fixed z-[9999] inset-0 bg-black/50 flex items-center justify-center">
    <div class="modal-content bg-white px-5 xl:px-9 pt-5 xl:pt-9 pb-7 xl:pb-15 rounded-[20px] xl:rounded-[40px] max-w-[90%] xl:max-w-[740px] w-full relative max-h-[90vh] overflow-y-auto">
        <button type="button" class="js-modal-close modal-close absolute top-2 xl:top-[18px] right-2 xl:right-[18px] shadow-md hover:shadow-lg transition-shadow duration-300 z-10 rounded-full">
            <img class="w-8 h-8 xl:w-10 xl:h-10" src="<?php echo get_template_directory_uri(); ?>/assets/image/close-icon.svg" alt="閉じる" loading="lazy" width="100%" height="100%">
        </button>

        <?php if ($has_images): // 画像がある場合 ?>
            <div class="swiper mb-4 xl:mb-10 <?= esc_attr($swiper_class) ?>">
                <div class="swiper-wrapper">
                    <?php foreach ($designer_images as $img_url): ?>
                        <div class="swiper-slide aspect-[3/2] overflow-hidden rounded-[10px] xl:rounded-[20px]">
                            <img src="<?= esc_url($img_url); ?>" class="w-full h-full object-cover" alt="<?= esc_attr($designer_name); ?>の画像">
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($designer_images) > 1): // 画像が複数枚の場合のみナビゲーション表示 ?>
                    <div class="hidden absolute inset-y-1/2 left-0 right-0 z-10 -translate-y-1/2 xl:flex justify-between px-4">
                        <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'modal']); ?>
                    </div>
                <?php endif; ?>

                <?php if (count($designer_images) > 1): // 画像が複数枚の場合のみページネーション表示 ?>
                    <div class="swiper-pagination <?= esc_attr($pagination_class) ?> mt-3"></div>
                <?php endif; ?>
            </div>
        <?php else: // 画像がない場合はデフォルト画像を表示 ?>
            <div class="swiper mb-4 xl:mb-10 <?= esc_attr($swiper_class) ?>">
                <div class="swiper-wrapper">
                    <div class="swiper-slide aspect-[3/2] overflow-hidden rounded-[10px] xl:rounded-[20px]">
                        <img src="<?= esc_url($no_image_url); ?>" class="w-full h-full object-cover" alt="画像なし">
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="flex flex-col xl:flex-row xl:items-start gap-2 xl:gap-9 leading-relaxed px-4 xl:px-10">
            <div class="text-base xl:text-lg font-semibold xl:w-50 flex-shrink-0"><?= esc_html($designer_name); ?></div>
            <div class="text-sm xl:text-base flex-1 leading-[1.75]"><?= esc_html($designer_text); ?></div>
        </div>

        <?php if (!empty($display_link_url)): // リンクがある場合のみ表示 ?>
            <div class="mt-3 xl:mt-10 text-center">
                <?php
                get_template_part('templates/common/external-site-link', null, [
                    'url' => $display_link_url,
                    'text' => esc_html($link_button_text),
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>
