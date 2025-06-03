<!-- 呼び出し元 => drawer-nav, about, footer -->
<?php
// デフォルト値（トップページ用）を設定
$bg_base = $args['bg_base'] ?? 'bg-[#FFFAD1]';
$hover_bg = $args['hover_bg'] ?? 'hover:bg-[#F2EEC7]';
$additional_class = $args['class'] ?? '';
?>
<div class="drawer-sns flex gap-2 mt-4 lg:mt-6 justify-center lg:justify-start opacity-100 <?php echo esc_attr($additional_class); ?>">
  <?php
    $sns = ['x', 'youtube', 'tiktok', 'instagram'];
    foreach ($sns as $icon):
  ?>
    <a href="#" class="<?php echo "$bg_base $hover_bg"; ?> rounded-full transition-colors duration-200
      w-8 h-8 lg:w-10 lg:h-10 flex items-center justify-center">
      <img
        src="<?php echo get_template_directory_uri(); ?>/assets/image/sns-<?php echo $icon; ?>-icon.svg"
        alt="<?php echo ucfirst($icon); ?>"
        class="w-9 h-8 lg:w-10 lg:h-10"
      />
    </a>
  <?php endforeach; ?>
</div>
