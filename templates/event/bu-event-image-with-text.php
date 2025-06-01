<!-- 呼び出し元 => category-event.php -->

<?php
// 呼び出し元で set_query_var された変数を受け取る
$months_str = get_query_var('months_str', '');
$tag_slugs = get_query_var('tag_slugs', '');
$aspect_ratio = 'aspect-[3/2]';
$style = $args['style'] ?? 'horizontal'; // デフォルトは horizontal
?>
<article class="w-full" data-months="<?php echo esc_attr($months_str); ?>" data-tags="<?php echo esc_attr($tag_slugs); ?>">
    <a href="<?php the_permalink(); ?>" class="block group">
        <div class="relative overflow-hidden rounded-[10px] lg:rounded-[20px] <?php echo esc_attr($aspect_ratio); ?>">
            <!-- 画像部分 -->
            <div class="w-full overflow-hidden rounded-[10px] lg:rounded-[20px] <?php echo esc_attr($aspect_ratio); ?>">
                <?php get_template_part('template-parts/image-thumbnail', null, array(
                    'aspect_ratio' => $aspect_ratio
                )); ?>
            </div>

            <div class="absolute inset-0 flex flex-col justify-end p-4 lg:p-8 text-white transition-opacity duration-250 bg-gradient-to-b from-black/10 to-black/40 opacity-0 group-hover:opacity-100">
                <div class="translate-y-4 opacity-0 transition-all duration-250 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col lg:justify-end lg:items-baseline gap-1 lg:gap-2">
                    <div class="flex flex-row items-center gap-1 lg:gap-2">
                        <?php get_template_part('template-parts/common/event-status'); ?>
                        <?php get_template_part('template-parts/event/event-date-range'); ?>
                    </div>
                    <div class="schedule-item-title text-sm lg:text-base leading-[1.4]"><?php the_title(); ?></div>
                    <div class="flex items-center text-[10px] lg:text-xs leading-[1.3]">
                        <div><?php the_field('event-place'); ?></div>
                        <div class="ml-2 lg:ml-3 pl-2 lg:pl-3 border-l border-gray-300">
                            <?php
                            $tags = get_the_tags();
                            if ($tags) {
                                foreach ($tags as $tag) {
                                    echo '<span>' . esc_html($tag->name) . '</span>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </a>
</article>
