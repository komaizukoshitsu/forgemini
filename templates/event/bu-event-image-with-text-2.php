<!-- 呼び出し元 => category-event.php -->

<?php
$position = $args['text_position'] ?? 'below';
$rounded_class = $args['rounded'] ?? 'rounded-[10px] lg:rounded-[20px]';
$mt_below_class = $args['mt_below'] ?? 'mt-4 lg:mt-5';
$aspect_ratio = $args['aspect_ratio'] ?? 'aspect-square'; // デフォルトのアスペクト比
$months_str = get_query_var('months_str', '');
$tag_slugs = get_query_var('tag_slugs', '');
$template_context = $args['template_context'] ?? 'default'; // top などで切り替えられる
$categories = get_the_category();
$category_slug = $categories ? $categories[0]->slug : 'default';
// $aspect_ratio = 'aspect-[3/2]';
// $style = $args['style'] ?? 'horizontal';
?>
<article class="w-full" data-months="<?php echo esc_attr($months_str); ?>" data-tags="<?php echo esc_attr($tag_slugs); ?>">
    <a href="<?php the_permalink(); ?>" class="block group">
        <div class="relative overflow-hidden <?php echo esc_attr($rounded_class); ?> <?php echo esc_attr($aspect_ratio); ?>">
            <!-- 画像部分 -->
            <div class="w-full overflow-hidden <?php echo esc_attr($rounded_class); ?> <?php echo esc_attr($aspect_ratio); ?>">
                <?php get_template_part('template-parts/image-thumbnail', null, array(
                    'aspect_ratio' => $aspect_ratio,
                    'rounded' => ''
                )); ?>
            </div>

            <!-- オーバーレイ表示（PCのみ表示） -->
            <?php if ($position === 'overlay') : ?>
                <div class="hidden lg:flex absolute inset-0 flex-col justify-end p-4 lg:p-8 text-white transition-opacity duration-250 bg-gradient-to-b from-black/10 to-black/40 opacity-0 group-hover:opacity-100">
                    <div class="translate-y-4 opacity-0 transition-all duration-250 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col lg:justify-end lg:items-baseline gap-1 lg:gap-2">
                        <?php
                        $template_name = ($template_context === 'top') ? 'top' : $category_slug;
                        get_template_part('template-parts/item-contents/item-content-event');
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- 下に出すテキストエリア（スマホでは常に表示、PCではpositionによって出し分け） -->
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
            $template_name = ($template_context === 'top') ? 'top' : $category_slug;
            get_template_part('template-parts/item-contents/item-content-event');
            ?>
        </div>
    </a>
</article>
