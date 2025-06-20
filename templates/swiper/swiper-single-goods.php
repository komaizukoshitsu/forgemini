<?php
// templates/swiper/swiper-single-goods.php

// ★修正点1: ACFのギャラリーフィールド 'goods-gallery' から画像URLの配列を取得
$goods_gallery_urls = get_field('goods-gallery');

// ギャラリー画像がない場合のフォールバック画像URL
$no_image_url = get_template_directory_uri() . '/assets/image/no-image.webp';
?>

<div class="swiper goods-main mb-2 xl:mb-4">
    <div class="swiper-wrapper mb-2 xl:mb-4">
        <?php
        // ★修正点2: $goods_gallery_urls をループ
        if ($goods_gallery_urls) :
            foreach ($goods_gallery_urls as $img_url) : // $img_url は既に画像のURL
        ?>
                <div class="swiper-slide">
                    <div class="aspect-square overflow-hidden bg-gray-100 rounded-[10px] xl:rounded-[20px]">
                        <img src="<?= esc_url($img_url); ?>" alt="" class="w-full h-full object-cover" />
                    </div>
                </div>
            <?php
            endforeach;
        else :
            // ギャラリー画像がない場合はデフォルト画像を表示
            ?>
            <div class="swiper-slide">
                <div class="aspect-square overflow-hidden bg-gray-100 rounded-[10px] xl:rounded-[20px]">
                    <img src="<?= esc_url($no_image_url); ?>" alt="No Image" class="w-full h-full object-cover" />
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="hidden xl:flex absolute inset-y-1/2 left-0 right-0 z-10 -translate-y-1/2 justify-between px-4">
        <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'goods']); ?>
    </div>
    <div class="goods-swiper-pagination text-center"></div>
</div>

<div class="swiper goods-thumb">
    <div class="swiper-wrapper">
        <?php
        // ★修正点3: メインスライダーと同様に $goods_gallery_urls をループ
        if ($goods_gallery_urls) :
            foreach ($goods_gallery_urls as $img_url) : // $img_url は既に画像のURL
        ?>
                <div class="swiper-slide cursor-pointer">
                    <div class="aspect-square rounded-[10px] border border-transparent overflow-hidden">
                        <img src="<?= esc_url($img_url); ?>" alt="" class="w-full h-full object-cover" />
                    </div>
                </div>
            <?php
            endforeach;
        else :
            // ギャラリー画像がない場合はデフォルト画像を表示
            ?>
            <div class="swiper-slide cursor-pointer">
                <div class="aspect-square rounded-[10px] border border-transparent overflow-hidden">
                    <img src="<?= esc_url($no_image_url); ?>" alt="No Image" class="w-full h-full object-cover" />
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
