<?php
// 呼び出し元 => templates/parts/image-with-text.php (想定)

// ピックアップバッジの処理 (is_pickup_works ACFフィールドから取得)
$badge_pickup = '';
if (get_field('is_pickup_works', get_the_ID())) { // ACFフィールドから直接ピックアップ状態を取得
    ob_start();
    get_template_part('templates/common/badge-pickup');
    $badge_pickup = trim(ob_get_clean());
}

// badge-new の処理は元のまま使えます
ob_start();
get_template_part('templates/common/badge-new');
$badge_new = trim(ob_get_clean());
?>

<?php if (!empty($badge_new) || !empty($badge_pickup)) : ?>
    <div class="flex gap-1 xl:gap-2 h-5 xl:h-6 items-center">
        <?= $badge_new; ?>
        <?= $badge_pickup; ?>
    </div>
<?php endif; ?>

<div class="text-sm xl:text-base leading-[1.4]">
    <?php the_title(); ?>
</div>

<div class="text-xs xl:text-sm leading-[1.3]">
    <?php
    // ★修正点1: works_client タクソノミーからクライアント名を取得
    $clients = get_the_terms(get_the_ID(), 'works_client');
    if ($clients && !is_wp_error($clients)) {
        echo esc_html($clients[0]->name);
    } else {
        echo 'クライアント名なし'; // デフォルト表示
    }
    ?>
</div>

<div class="flex text-[10px] xl:text-xs leading-[1.3] ">
    <div class="tracking-[0.05em]" style="font-family: 'Open Sans', sans-serif;">
        <?php
        // ★修正点2: works_year タクソノミーから年を取得
        $years = get_the_terms(get_the_ID(), 'works_year');
        if ($years && !is_wp_error($years)) {
            echo esc_html($years[0]->name); // 最初の年を表示
        } else {
            echo '年なし'; // デフォルト表示
        }
        ?>
    </div>
    <div class="ml-2 xl:ml-3 pl-2 xl:pl-3 border-l border-[#D9D9D9] border-solid">
    <?php
    // カスタムタクソノミー 'works_category' のターム（カテゴリー）を取得
    $categories = get_the_terms(get_the_ID(), 'works_category');

    // カテゴリーが存在し、かつWordPressのエラーでない場合
    if ($categories && !is_wp_error($categories)) {
        $output = [];
        foreach ($categories as $category) {
            // カテゴリー名をHTMLエスケープして配列に追加
            $output[] = '<span>' . esc_html($category->name) . '</span>';
        }
        // 複数のカテゴリがある場合はカンマとスペースで区切って表示
        echo implode(', ', $output);
    } else {
        // カテゴリーが存在しない場合、または取得に失敗した場合
        echo '<span>カテゴリーなし</span>';
    }
    ?>
    </div>
</div>
