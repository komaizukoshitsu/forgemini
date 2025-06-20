<?php
global $top_page_excluded_event_ids;

$today = date('Y-m-d'); // 今日の日付を取得

$args = array(
    'post_type' => 'events',
    'posts_per_page' => 1,
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'event-start', // ACFフィールドキー
            'value' => $today,
            'compare' => '<=',
            'type' => 'DATE'
        ),
        array(
            'key' => 'event-end', // ACFフィールドキー
            'value' => $today,
            'compare' => '>=',
            'type' => 'DATE'
        ),
    ),
    'orderby' => 'meta_value',
    'meta_key' => 'event-start',
    'order' => 'ASC',
);

$today_event_query = new WP_Query($args);

if ($today_event_query->have_posts()) :
    while($today_event_query->have_posts()) : $today_event_query->the_post();
        // 2. 取得した投稿のIDをグローバル変数に追加します。
        $top_page_excluded_event_ids[] = get_the_ID();
        ?>
        <div class="xl:flex gap-25 items-end xl:items-center mt-5 xl:mt-15">
            <div class="w-full xl:w-[682px]">
                <a href="<?php the_permalink(); ?>" class="block w-full group">
                    <div class="px-[5%] xl:px-0">
                        <div class="overflow-hidden w-full rounded-[10px] xl:rounded-[20px] aspect-[4/3]">
                            <?php get_template_part('templates/parts/image-thumbnail', null, array(
                                'aspect_ratio' => 'aspect-[4/3]'
                            )); ?>
                        </div>
                    </div>
                </a>
            </div>
            <div class="w-[84%] xl:w-[370px] mx-auto xl:mx-0 mt-7 xl:mt-0">
                <div class="hidden xl:block">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/image/wavy-line.svg" alt="上波線" loading="lazy" width="100%" height="100%">
                </div>
                <div class="flex mt-5 xl:mt-10 items-center">
                    <?php get_template_part('templates/event/event-status'); ?>
                    <p class="text-[10px] xl:text-xs h-5 xl:h-6 px-[6px] inline-flex items-center justify-center ml-1 xl:ml-2 border border-[#333] rounded-[12px]">
                        <?php
                        // ★修正点1: events_tags の代わりに event_type タクソノミーからタームを取得
                        $event_types = wp_get_post_terms(get_the_ID(), 'event_type');
                        if ( $event_types && !is_wp_error($event_types) ) :
                            $output = [];
                            foreach ( $event_types as $type ) {
                                $output[] = '<span>' . esc_html($type->name) . '</span>';
                            }
                            echo implode(', ', $output);
                        endif;
                        ?>
                    </p>
                </div>
                <a href="<?php the_permalink(); ?>" class="group block w-fit">
                    <div class="mt-2 xl:mt-3 text-sm xl:text-[18px] font-medium leading-[1.5] relative w-fit">
                        <span class="bg-gradient-to-t from-neutral-800 to-neutral-800/0 bg-[length:100%_0px] bg-no-repeat bg-left-bottom transition-all duration-250 group-hover:bg-[length:100%_1px]"><?php the_title(); ?></span>
                    </div>
                </a>
                <dl class="mt-5 xl:mt-10 space-y-2 xl:space-y-3 leading-[1.3] tracking-[0.05em]" style="font-family: 'Open Sans', sans-serif;">
                    <div class="flex flex-row items-baseline">
                        <dt class="text-xs xl:text-sm w-20 xl:w-25 flex-shrink-0">会期</dt>
                        <dd class="flex-1 text-sm xl:text-base leading-[1.4]">
                            <?php
                            set_query_var('event_date_class', 'text-sm xl:text-base');
                            get_template_part('templates/event/event-date-range');
                            ?>
                        </dd>
                    </div>
                    <div class="flex flex-row items-baseline">
                        <dt class="text-xs xl:text-sm w-20 xl:w-25 flex-shrink-0">開場</dt>
                        <dd class="flex-1 text-sm xl:text-base leading-[1.4]">
                            <?php
                            $event_opening = get_field('event-opening');
                            if ($event_opening) {
                                $opening_time_obj = DateTime::createFromFormat('H:i', $event_opening);
                                if ($opening_time_obj) {
                                    echo esc_html($opening_time_obj->format('H:i'));
                                }
                            }
                            echo '<span class="mx-1">－</span>';
                            $event_closed = get_field('event-closed');
                            if ($event_closed) {
                                $closed_time_obj = DateTime::createFromFormat('H:i', $event_closed);
                                if ($closed_time_obj) {
                                    echo esc_html($closed_time_obj->format('H:i'));
                                }
                            }
                            ?>
                        </dd>
                    </div>
                    <div class="flex flex-row items-baseline">
                        <dt class="text-xs xl:text-sm w-20 xl:w-25  flex-shrink-0">会場</dt>
                        <dd class="flex-1 text-sm xl:text-base leading-[1.4]">
                            <?php
                            // ★修正点2: event-place の代わりに event_venue タクソノミーから会場名を取得
                            $venues = get_the_terms(get_the_ID(), 'event_venue');
                            if ($venues && !is_wp_error($venues)) {
                                echo esc_html($venues[0]->name);
                            } else {
                                echo '会場未定'; // または適切なデフォルト値
                            }
                            ?>
                        </dd>
                    </div>
                    <div class="flex flex-row items-baseline">
                        <dt class="text-xs xl:text-sm w-20 xl:w-25 flex-shrink-0">アクセス</dt>
                        <dd class="flex-1 text-sm xl:text-base leading-[1.4]">
                            <a href="<?php the_field('event-map'); ?>" class="google-map relative pl-[14px] border-b border-solid border-gray-800 pb-0.5 transition-all duration-250 ease-linear" target="_blank" rel="noopener noreferrer">Google Maps</a>
                        </dd>
                    </div>
                </dl>
                <div class="mt-7 xl:mt-12 text-center">
                    <?php get_template_part('templates/common/link-button', null, [
                        'url' => get_permalink(),
                        'label' => 'View More'
                    ]); ?>
                </div>
                <div class="mt-5 xl:mt-10">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/image/wavy-line.svg" alt="下波線" loading="lazy" width="100%" height="100%">
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
<?php else : ?>
    <div>現在開催中のイベントはありません。</div>
<?php endif; ?>
