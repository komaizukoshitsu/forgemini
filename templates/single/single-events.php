<main data-barba="container" data-barba-namespace="single">
    <div class="mt-17">
        <div class="w-[90%] mx-auto xl:max-w-180">
            <a href="<?php bloginfo('url'); ?>/events"
            class="relative pl-[29px] text-[24px] italic tracking-[0.15em] font-garamond group">
                Event
                <span class="absolute left-[5px] top-1/2 w-3 h-[10px] -translate-y-1/2 rotate-180 bg-[url('../image/view-more-icon.svg')] bg-center bg-no-repeat bg-contain transition-all duration-[250ms] ease-in-out group-hover:left-0"></span>
            </a>
        </div>
        <article class="w-[90%] mx-auto xl:max-w-180 mt-5 lg:mt-10">
            <header class="space-y-2 lg:space-y-5">
                <?php get_template_part('templates/common/event-status'); ?>
                <h1 class="text-lg lg:text-2xl font-medium tracking-[0.05em] leading-[1.4]"><?php the_title(); ?></h1>
                <div class="flex text-sm lg:text-base leading-[1.3]">
                    <div class="tracking-[0.05em]" style="font-family: 'Open Sans', sans-serif;"><?php echo get_the_date(); ?></div>
                    <div class="ml-2 lg:ml-3 pl-2 lg:pl-3 border-l border-[#D9D9D9]">
                        <?php
                            // ★修正点1: イベントタイプ (event_type) タクソノミーからタームを取得
                            $event_types = get_the_terms(get_the_ID(), 'event_type');
                            if ($event_types && !is_wp_error($event_types)) {
                                $output = [];
                                foreach ($event_types as $type) {
                                    $output[] = '<span>' . esc_html($type->name) . '</span>';
                                }
                                echo implode(', ', $output); // 複数の種類がある場合はカンマで区切る
                            }
                        ?>
                    </div>
                </div>
            </header>
            <div class="my-5 lg:my-10 rounded-[10px] lg:rounded-[20px]">
                <?php the_post_thumbnail(); ?>
            </div>
            <div class="">
                <?php
                get_template_part('templates/heading-with-brackets', null, [
                    'heading_text' => 'イベント概要',
                    'heading_tag'  => 'div',
                ]);
                ?>
                <dl class="space-y-2 lg:space-y-3 bg-[#FFFAD1] rounded-[10px] lg:rounded-[20px] mt-5 lg:mt-7 mb-4 lg:mb-10 py-4 lg:py-6 px-5 lg:px-[30px]">
                    <div class="flex flex-row items-baseline">
                        <dt class="text-xs lg:text-sm w-20 lg:w-25 flex-shrink-0">タイトル</dt>
                        <dd class="flex-1 text-sm lg:text-base items-baseline leading-[1.4]">『<?php the_title(); ?>』</dd>
                    </div>
                    <div class="flex flex-row items-baseline">
                        <dt class="text-xs lg:text-sm w-20 lg:w-25 flex-shrink-0">会期</dt>
                        <dd class="flex-1 text-sm lg:text-base items-baseline leading-[1.4]">
                            <?php
                            set_query_var('event_date_class', 'text-sm lg:text-base');
                            get_template_part('templates/event/event-date-range');
                            ?>

                        </dd>
                    </div>
                    <div class="flex flex-row items-baseline leading-[1.4]">
                        <dt class="text-xs lg:text-sm w-20 lg:w-25 flex-shrink-0">開場</dt>
                        <dd class="flex-1 text-sm lg:text-base items-baseline leading-[1.4] tracking-[0.05em]" style="font-family: 'Open Sans', sans-serif;">
                            <?php
                            $event_opening = get_field('event-opening', false, false);
                            if ($event_opening) {
                                $opening_time = new DateTime($event_opening, new DateTimeZone('UTC'));
                                echo esc_html($opening_time->format('H:i'));
                            }

                            echo '<span class="mx-1">－</span>';

                            $event_closed = get_field('event-closed', false, false);
                            if ($event_closed) {
                                $closed_time = new DateTime($event_closed, new DateTimeZone('UTC'));
                                echo esc_html($closed_time->format('H:i'));
                            }
                            ?>
                        </dd>
                    </div>
                    <div class="flex flex-row items-baseline">
                        <dt class="text-xs lg:text-sm w-20 lg:w-25 flex-shrink-0">会場</dt>
                        <dd class="flex-1 text-sm lg:text-base items-baseline leading-[1.4]">
                            <?php
                            // ★修正点2: 'event_venue' カスタムタクソノミーから会場名を取得
                            $venues = get_the_terms(get_the_ID(), 'event_venue');
                            if ($venues && !is_wp_error($venues)) {
                                $venue = $venues[0]; // 最初の会場タームを取得 (通常は一つのみ想定)
                                echo esc_html($venue->name);
                            } else {
                                echo '場所未定'; // 会場が設定されていない場合の代替テキスト
                            }
                            ?>
                        </dd>
                    </div>
                    <div class="flex flex-row items-baseline">
                        <dt class="text-xs lg:text-sm w-20 lg:w-25 flex-shrink-0">その他</dt>
                        <dd class="flex-1 text-sm lg:text-base items-baseline leading-[1.4]"><?php the_field('event-others'); ?></dd>
                    </div>
                    <div class="flex flex-row items-baseline">
                        <dt class="text-xs lg:text-sm w-20 lg:w-25 flex-shrink-0">アクセス</dt>
                        <dd class="flex-1 text-sm lg:text-base items-baseline leading-[1.4]">
                            <?php
                            // ★修正点3: 'event_venue' タクソノミーから venue_url を取得
                            $venue_url = '';
                            if ($venues && !is_wp_error($venues)) { // 上で取得した $venues を再利用
                                $venue = $venues[0];
                                $venue_url = get_field('venue_url', 'term_' . $venue->term_id); // タームに紐付けたACFフィールドを取得
                            }

                            if ($venue_url) {
                                echo '<a href="' . esc_url($venue_url) . '" class="google-map relative pl-[14px] border-b border-solid border-gray-800 pb-0.5 transition-all duration-250 ease-linear" target="_blank" rel="noopener noreferrer">Google Maps</a>';
                            } else {
                                echo '情報なし'; // URLがない場合の代替テキスト
                            }
                            ?>
                        </dd>
                    </div>
                </dl>
            </div>
            <div class="mt-4 lg:mt-10 single-content"><?php the_content(); ?></div>
            <?php get_template_part( 'templates/single-footer' ); ?>
        </article>
        <aside class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-243 mt-15 lg:mt-[150px]">
            <div class="flex justify-between">
                <?php
                    get_template_part('templates/heading/heading-with-brackets', null, [
                        'heading_text' => 'イベントスケジュール',
                        'heading_tag'  => 'h2',
                    ]);
                ?>
                <?php get_template_part('templates/link-button', null, [
                    'url' => home_url('/event'), // event は投稿タイプスラッグ
                    'label' => 'View All'
                ]); ?>
            </div>
            <?php
                // ★修正点4: 'post_type' を 'events' に変更
                $args = [
                    'post_type' => 'events', // イベント投稿タイプを指定
                    'posts_per_page' => 4, // 表示件数を指定 (numberposts は非推奨)
                    'post__not_in' => [get_the_ID()], // 現在の投稿を除外
                    'orderby' => 'date', // 並び順を指定
                    'order' => 'DESC', // 降順
                ];
            ?>
            <div id="post-list" class="space-y-4 lg:space-y-6 mt-5 lg:mt-10 pt-4 lg:pt-6 border-t border-[#D9D9D9]">
                <?php
                $custom_posts = get_posts($args);
                foreach ( $custom_posts as $post ): setup_postdata($post);
                    // ここで event-list-item テンプレートパーツを呼び出す
                    get_template_part('templates/event/event-list-item');
                endforeach;
                wp_reset_postdata();
                ?>
                </div>
        </aside>
    </div>
</main>
