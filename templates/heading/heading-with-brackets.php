<?php
/**
 * 括弧付き見出しテンプレートパーツ（要素変更可能）
 *
 * @package YourTheme
 */

$heading_text = $args['heading_text'] ?? '見出し';
$heading_tag  = $args['heading_tag'] ?? 'div'; // デフォルトは div 要素
$allowed_tags = ['div', 'h2', 'h3', 'h4', 'h5', 'h6', 'span']; // 許可する要素

// 許可された要素であるか確認し、そうでなければデフォルトの div を使用
$heading_tag = in_array(strtolower($heading_tag), $allowed_tags, true) ? strtolower($heading_tag) : 'div';
?>

<<?php echo esc_attr($heading_tag); ?> class="text-base font-medium tracking-[0.1em]">（&ensp;<?php echo esc_html($heading_text); ?>&ensp;）</<?php echo esc_attr($heading_tag); ?>>
