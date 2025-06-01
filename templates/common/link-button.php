<?php
/**
 * 汎用リンクボタンパーツ
 * 使用例:
 * get_template_part('template-parts/link-button', null, [
 *   'url' => get_permalink(),
 *   'label' => 'View More'
 * ]);
 */

$url = $args['url'] ?? '#';
$label = $args['label'] ?? 'View';
?>

<a href="<?php echo esc_url($url); ?>" class="group relative inline-flex items-center pr-5 text-base lg:text-[18px] leading-[1.25] tracking-[0.05em] italic font-garamond font-semibold">
  <?php echo esc_html($label); ?>
  <span class="ml-1 w-3 h-[10px] bg-no-repeat bg-center bg-contain absolute right-0 top-1/2 -translate-y-1/2 transition-all duration-300 ease-in-out group-hover:right-[-5px] bg-[url('../image/view-more-icon.svg')]"></span>
</a>
