<?php
    // templates/parts/image-thumbnail.php

    // 初期化
    $img_url = '';
    // 投稿IDが引数で渡されればそれを使用、なければ現在の投稿ID
    $post_id = $args['post_id'] ?? get_the_ID();

    // 1. ACFギャラリーフィールド 'goods-gallery' から最初の画像URLを取得 (goods投稿タイプの場合)
    if (get_post_type($post_id) === 'goods' && function_exists('get_field')) {
        $goods_gallery_urls = get_field('goods-gallery', $post_id);
        if ($goods_gallery_urls && is_array($goods_gallery_urls) && !empty($goods_gallery_urls[0])) {
            $img_url = $goods_gallery_urls[0]; // ギャラリーの最初の画像を使用
        }
    }

    // 2. 投稿のアイキャッチ画像 (サムネイル) を使用
    // 上記で画像が取得できなかった場合のみ試みる
    if (empty($img_url)) {
        if (has_post_thumbnail($post_id)) {
            $img_url = get_the_post_thumbnail_url($post_id, 'full'); // フルサイズ画像を取得
        }
    }

    // 3. いずれの画像も存在しない場合のデフォルト画像
    // 上記で画像が取得できなかった場合のみ試みる
    if (empty($img_url)) {
        $img_url = get_template_directory_uri() . '/assets/image/no-image.webp';
    }

    // 引数として渡されたアスペクト比と角丸クラスを使用、なければデフォルト値を設定
    $aspect_ratio = $args['aspect_ratio'] ?? 'aspect-square';
    $rounded_class = $args['rounded'] ?? 'rounded-[10px] lg:rounded-[20px]';
?>

<div class="group overflow-hidden <?php echo esc_attr($rounded_class); ?> <?php echo esc_attr($aspect_ratio); ?>">
    <img src="<?php echo esc_url($img_url); ?>" alt="商品画像" class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" />
</div>
