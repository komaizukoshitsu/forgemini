<?php
// menu-items.php

// get_template_part() の第三引数で渡された $args を受け取ります。
// $args が存在しない場合に備え、空の配列をデフォルト値として設定します。
$args = $args ?? [];

// クラス名の初期値を設定します。
// どちらか一方でも渡されなかった場合に備えて空文字列にしておきます。
$list_item_class = '';
$link_class = '';

// ヘッダーからのクラス名があればそれを優先
if (isset($args['list_item_class_from_drawer'])) {
    $list_item_class = $args['list_item_class_from_drawer'];
}
if (isset($args['link_class_from_drawer'])) {
    $link_class = $args['link_class_from_drawer'];
}

// フッターからのクラス名があればそれを適用（ヘッダーと競合しない限り）
// もし、どちらか一方からしかクラスが渡されない前提なら、?? 演算子でシンプルに書けます
// 例: $list_item_class = $args['list_item_class_from_drawer'] ?? $args['list_item_class_from_footer'] ?? '';

// もしフッターからのクラス名が渡されたら、現在の $list_item_class と $link_class を上書きします。
// （ただし、同じ変数名 'list_item_class_from_drawer' と 'list_item_class_from_footer' がある場合）
// 今回は異なるキー名を使っているので、それぞれ代入します。
if (isset($args['list_item_class_from_footer'])) {
    $list_item_class = $args['list_item_class_from_footer'];
}
if (isset($args['link_class_from_footer'])) {
    $link_class = $args['link_class_from_footer'];
}


$menu_items = [
  ['slug' => '', 'label' => 'Home', 'is_active' => is_front_page() || is_home()],
  // アクティブ判定ロジックは以前の修正案を適用してください
  ['slug' => 'events', 'label' => 'Events', 'is_active' => is_post_type_archive('events') || is_singular('events') || is_tax('event_category')],
  ['slug' => 'goods', 'label' => 'Goods', 'is_active' => is_post_type_archive('goods') || is_singular('goods') || is_tax('goods_category')], // 必要に応じてタクソノミーを追加
  ['slug' => 'works', 'label' => 'Works', 'is_active' => is_post_type_archive('works') || is_singular('works') || is_tax('works_category')], // 必要に応じてタクソノミーを追加
  ['slug' => 'news', 'label' => 'News', 'is_active' => is_category('news') || (is_single() && in_category('news')) || is_page('news')],
  ['slug' => 'about', 'label' => 'About', 'is_active' => is_page('about')],
  ['slug' => 'contact', 'label' => 'Contact', 'is_active' => is_page('contact')],
];
?>

<?php foreach ($menu_items as $item): ?>
  <li class="<?php echo esc_attr($list_item_class); ?>">
    <a href="<?php echo home_url('/' . $item['slug']); ?>"
       class="<?php echo esc_attr($link_class); ?> <?php if ($item['is_active']) echo 'active'; ?>">
      <?php echo esc_html($item['label']); ?>
    </a>
  </li>
<?php endforeach; ?>
