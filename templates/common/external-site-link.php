<?php
$url = $args['url'] ?? '#';
$text = $args['text'] ?? '外部サイトへ';
?>

<a href="<?= esc_url($url); ?>" class="inline-flex justify-center items-center w-full max-w-60 lg:max-w-[350px] h-10 lg:h-13 px-[15px] border border-[#D9D9D9] hover:border-[#999] hover:bg-[#F5F5F5] rounded-full text-sm lg:text-base gap-2 transition-all duration-300" target="_blank" rel="noopener noreferrer">
    <?= esc_html($text); ?>
    <span>
        <img src="<?= get_template_directory_uri(); ?>/assets/image/separate-tab-icon.svg" alt="">
    </span>
</a>
