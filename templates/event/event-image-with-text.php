<?php
// templates/event/event-image-with-text.php

$position = $args['text_position'] ?? 'below';
$rounded_class = $args['rounded'] ?? 'rounded-[10px] lg:rounded-[20px]';
$mt_below_class = $args['mt_below'] ?? 'mt-4 lg:mt-5';
$aspect_ratio = $args['aspect_ratio'] ?? 'aspect-square'; // デフォルトのアスペクト比

// archive-event.php から渡されることを想定
$months_str = get_query_var('months_str', '');
$tag_slugs = get_query_var('tag_slugs', '');

$template_context = $args['template_context'] ?? 'default';

// ★修正点1: get_the_category() の代わりにカスタムタクソノミーからタームを取得
// 'event_category' をイベント投稿に紐付けたタクソノミースラッグに修正してください
$event_terms = wp_get_post_terms(get_the_ID(), 'event_type'); // カスタムタクソノミーからタームを取得
$event_category_slug = !empty($event_terms) ? $event_terms[0]->slug : 'default'; // 最初のタームのスラッグを取得

// $template_name の決定にカスタムタクソノミーのスラッグを使用
$template_name = ($template_context === 'top') ? 'top' : $event_category_slug;
?>
<article class="w-full" data-months="<?php echo esc_attr($months_str); ?>" data-tags="<?php echo esc_attr($tag_slugs); ?>">
    <a href="<?php the_permalink(); ?>" class="block group">
        <div class="relative overflow-hidden <?php echo esc_attr($rounded_class); ?> <?php echo esc_attr($aspect_ratio); ?>">
            <div class="w-full overflow-hidden <?php echo esc_attr($rounded_class); ?> <?php echo esc_attr($aspect_ratio); ?>">
                <?php get_template_part('templates/parts/image-thumbnail', null, array(
                    'aspect_ratio' => $aspect_ratio,
                    'rounded' => ''
                )); ?>
            </div>

            <?php if ($position === 'overlay') : ?>
                <div class="hidden lg:flex absolute inset-0 flex-col justify-end p-4 lg:p-8 text-white transition-opacity duration-250 bg-gradient-to-b from-black/10 to-black/40 opacity-0 group-hover:opacity-100">
                    <div class="translate-y-4 opacity-0 transition-all duration-250 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col lg:justify-end lg:items-baseline gap-1 lg:gap-2">
                        <?php
                        // ★修正点2: item-content-event に template_name を渡す (もし必要なら)
                        get_template_part('templates/item-contents/item-content-event', null, ['template_name' => $template_name]);
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="
            <?php echo esc_attr($mt_below_class); ?>
            px-[5%] lg:px-0 flex flex-col items-baseline gap-1 lg:gap-2
            <?php
            if ($position === 'overlay') {
                echo 'block lg:hidden'; // overlayならスマホだけ表示
            } elseif ($position === 'below') {
                echo 'block'; // belowなら常に表示
            }
            ?>
        ">
            <?php
            // ★修正点3: item-content-event に template_name を渡す (もし必要なら)
            get_template_part('templates/item-contents/item-content-event', null, ['template_name' => $template_name]);
            ?>
        </div>
    </a>
</article>
