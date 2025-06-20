<?php
/**
 * カスタム見出しテンプレートパーツ
 *
 * @package YourTheme
 */

$custom_title = $args['title'] ?? '';
$heading_tag  = $args['tag'] ?? 'h1';

// スマホ中央揃えクラスを条件付きで追加
$align_class = wp_is_mobile() ? 'text-center' : '';
?>

<<?php echo esc_attr($heading_tag); ?> class="text-[32px] xl:text-[45px] font-garamond italic tracking-[0.15em] leading-[1.25] text-center">
    <?= esc_html($custom_title); ?>
</<?php echo esc_attr($heading_tag); ?>>
