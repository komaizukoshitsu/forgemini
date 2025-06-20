<?php
// 呼び出し元で set_query_var された変数を受け取る
$months_str = get_query_var('months_str', '');
$tag_slugs = get_query_var('tag_slugs', '');
$style = $args['style'] ?? 'horizontal'; // デフォルトは horizontal
?>
<article class="event-item pb-4 xl:pb-6 px-3 xl:px-4 border-b border-[#D9D9D9]" data-months="<?php echo esc_attr($months_str); ?>" data-tags="<?php echo esc_attr($tag_slugs); ?>">
    <a href="<?php the_permalink(); ?>" class="flex items-center flex-col xl:flex-row gap-1 xl:gap-0">
        <div class="left w-full xl:w-70 flex flex-col xl:flex-shrink-0 gap-1 xl:gap-2">
            <?php get_template_part('templates/event/event-status'); ?>
            <?php get_template_part('templates/event/event-date-range'); ?>
        </div>
        <div class="right w-full flex flex-col xl:flex-1 gap-1 xl:gap-2">
            <div class="schedule-item-title text-sm xl:text-base leading-[1.4]"><?php the_title(); ?></div>
            <div class="flex items-center text-[10px] xl:text-xs leading-[1.3]">
                <div class=""><?php the_field('event-place'); ?></div>
                <div class="ml-2 xl:ml-3 pl-2 xl:pl-3 border-l border-gray-300">
                    <?php
                    // カスタムタクソノミー 'event_type' のタームを取得
                    $event_types = get_the_terms(get_the_ID(), 'event_type');
                    $output_html = '';

                    if ($event_types && !is_wp_error($event_types)) {
                        $output_array = [];
                        foreach ($event_types as $type) {
                            $output_array[] = '<span>' . esc_html($type->name) . '</span>';
                        }
                        $output_html = implode(', ', $output_array);
                    } else {
                        // イベントタイプが存在しない場合、または取得に失敗した場合
                        $output_html = '<span>イベントタイプなし</span>';
                    }
                    echo $output_html;
                    ?>
                </div>
            </div>
        </div>
    </a>
</article>
