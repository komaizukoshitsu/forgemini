<?php get_header(); ?>
<?php
global $top_page_excluded_event_ids;
$top_page_excluded_event_ids = [];
?>

<main data-barba="container" data-barba-namespace="home">
    <section class="relative overflow-hidden h-[100svh]">
        <div id="hero" class="fixed top-0 left-0 w-full h-[100svh] overflow-hidden transition-opacity duration-500 ease-in-out opacity-100 -z-1">
            <img class="pc-only hidden lg:block w-full h-full object-cover" src="<?php the_field('mv-img'); ?>" alt="ヒーローイメージ">
            <img class="sp-only block lg:hidden w-full h-auto object-cover" src="<?php the_field('sp-mv-img'); ?>" alt="ヒーローイメージ (SP)">
        </div>
        <?php
        // 先頭固定表示（Sticky）された投稿のIDを取得
        $sticky_posts = get_option('sticky_posts');

        // 先頭固定表示された投稿がある場合のみクエリを実行
        if ($sticky_posts) {
            $args = array(
                'post__in' => $sticky_posts, // 固定表示の投稿のみ取得
                'post_type' => 'events', // ★修正点1: イベント投稿タイプを指定
                'posts_per_page' => 1, // 表示する投稿数
                'ignore_sticky_posts' => 1, // 通常のクエリでStickyを無視
                // 'category_name' => 'event', // ★イベントはカスタム投稿タイプなので、カテゴリは不要か、カスタムタクソノミーで絞る
                // もしイベントを特定のカテゴリで絞りたい場合は、tax_query を使用
                /*
                'tax_query' => array(
                    array(
                        'taxonomy' => 'event_type', // 仮にイベントのタクソノミーが 'event_type' なら
                        'field'    => 'slug',
                        'terms'    => 'featured-event', // 特定のタームスラッグで絞る
                    ),
                ),
                */
            );

            // クエリを実行
            $sticky_query = new WP_Query($args);

            // 投稿があるか確認
            if ($sticky_query->have_posts()) :
                while($sticky_query->have_posts()) : $sticky_query->the_post(); ?>
                    <div id="event-info" class="absolute bottom-24 lg:bottom-20 right-0 w-full lg:w-[37.5rem] transition-opacity duration-300 ease-in-out">
                        <a href="<?php the_permalink(); ?>" class="group block bg-white py-3 lg:py-5 pl-4 lg:pl-7 rounded-tl-[55px] rounded-bl-[55px]">
                            <div class="mv-news-inner flex items-center transition-all duration-200 ease-linear">
                                <div class="lg:text-2xl italic tracking-[0.15em] font-garamond">Event</div>
                                <div class="flex flex-col gap-1 lg:gap-2 ml-3 lg:ml-4 px-4 lg:px-5 border-l border-[#D9D9D9]">
                                    <div class="flex flex-row items-center gap-1 lg:gap-2">
                                        <?php get_template_part('templates/event/event-status'); ?>
                                        <?php get_template_part('templates/event/event-date-range'); ?>
                                    </div>
                                    <div class="text-sm lg:text-base leading-[1.4] w-full truncate overflow-hidden whitespace-nowrap border-b border-transparent transition-colors duration-200 group-hover:border-neutral-800">
                                        <?php the_title(); ?>
                                    </div>
                                    <div class="flex items-center text-[10px] lg:text-xs leading-[1.3]">
                                        <p class="">
                                            <?php
                                            // ★修正点2: event-place は event_venue タクソノミーから取得
                                            $venues = get_the_terms(get_the_ID(), 'event_venue');
                                            if ($venues && !is_wp_error($venues)) {
                                                echo esc_html($venues[0]->name);
                                            } else {
                                                echo '開催場所未定'; // または適切なデフォルト値
                                            }
                                            ?>
                                        </p>
                                        <p class="ml-2 lg:ml-3 pl-2 lg:pl-3 border-l border-gray-300">
                                            <?php
                                            // ★修正点3: get_the_tags() の代わりに event_type タクソノミーからタームを取得
                                            $event_types = get_the_terms(get_the_ID(), 'event_type');
                                            if ($event_types && !is_wp_error($event_types)) {
                                                $output = [];
                                                foreach ($event_types as $type) {
                                                    $output[] = '<span>' . esc_html($type->name) . '</span>';
                                                }
                                                echo implode(', ', $output);
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile;
            else :
                echo '<div>固定表示のイベント投稿はありません。</div>'; // メッセージを修正
            endif;

            wp_reset_postdata();
        } else {
            echo '<div>固定表示のイベント投稿はありません。</div>'; // メッセージを修正
        }
        ?>
    </section>
    <div>
        <div class="bg-white pb-10 lg:pb-25">
            <section class="relative top-goods pt-10 lg:pt-21">
                <div class="w-full lg:w-[85%] max-w-306 mx-auto px-[5%] lg:px-0">
                    <?php
                    $pickupList = array(
                        'post_type' => 'goods',
                        'posts_per_page' => 6,
                        // ★修正点4: pickup タクソノミーの代わりに is_pickup_goods ACF フィールドを使用
                        'meta_query' => array(
                            array(
                                'key'     => 'is_pickup_goods',
                                'value'   => 1, // '1' または 'true' にチェックがある場合
                                'compare' => '=',
                                'type'    => 'NUMERIC' // または 'BOOLEAN'
                            )
                        ),
                    );

                    $pickup_query = new WP_Query($pickupList);

                    if ($pickup_query->have_posts()) :
                        echo '<div class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-4 gap-y-4 lg:gap-x-9 lg:gap-y-9">';
                        $i = 1;
                        while ($pickup_query->have_posts()) : $pickup_query->the_post();
                            $col_span = '';
                            $row_span = '';
                            $col_start = '';
                            $row_start = '';

                            if ($i === 1) {
                                $col_span = 'col-span-2 lg:col-span-2 row-span-2 lg:row-span-2 col-start-1 lg:col-start-1 row-start-1 lg:row-start-1'; // 2マス取り
                            } elseif ($i === 2) {
                                $col_start = 'col-start-1 lg:col-start-1 row-start-3 lg:row-start-3'; // スマホpc1列目--スマホpc2行目
                            } elseif ($i === 3) {
                                $col_start = 'col-start-2 lg:col-start-2 row-start-3 lg:row-start-3'; // 2列目
                            } elseif ($i === 4) {
                                $col_start = 'col-start-1 lg:col-start-3 row-start-6 lg:row-start-1'; // スマホ1列目・pc3列目--スマホ4行目・pc1行目
                            } elseif ($i === 5) {
                                $col_start = 'col-start-2 lg:col-start-4 row-start-6 lg:row-start-1'; //スマホ2列目・pc4列目--スマホ4行目・pc1行目
                            } elseif ($i === 6) {
                                $col_span = 'col-span-2 lg:col-span-2 row-span-2 lg:row-span-2'; // 2マス取り
                                $col_start = 'col-start-1 lg:col-start-3 row-start-4 lg:row-start-2'; // スマホ1列目・pc3列目--スマホ3行目・pc2行目
                            }
                            echo '<div class="grid-item ' . $col_span . ' ' . $row_span . ' ' . $col_start . ' ' . $row_start . '">';
                            get_template_part('templates/parts/image-with-text', null, array(
                                'text_position' => 'overlay',
                                'mt_below' => 'mt-2 lg:mt-5',
                                'aspect_ratio' => 'aspect-[1/1]'
                            ));
                            echo '</div>';
                            $i++;
                        endwhile;
                        echo '</div>';
                    else :
                        echo '<p class="mt-10 text-center text-sm text-gray-500">投稿が見つかりませんでした。</p>';
                    endif;

                    // クエリのリセットを忘れずに
                    wp_reset_postdata();
                    ?>

                    <div class="mt-5 lg:mt-10 text-right">
                        <?php get_template_part('templates/common/link-button', null, [
                            'url' => home_url('/goods'),
                            'label' => 'View All'
                        ]); ?>
                    </div>
                </div>
            </section>

            <section class="relative overflow-hidden h-[90px] lg:h-[151px] bg-wave z-0 mt-10 lg:mt-40">
                <div class="absolute inset-0 bg-wave-pattern z-0 pointer-events-none"></div>
                <div class="slide-track absolute inset-0 flex items-center z-[1] space-x-8 lg:space-x-20">
                    <ul class="top-slide1 flex gap-8 lg:gap-20 items-center">
                        <li class="w-[59px] lg:w-25"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-1.webp" alt=""></li>
                        <li class="w-[61px] lg:w-[103px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-2.webp" alt=""></li>
                        <li class="w-[83px] lg:w-[144px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-3.webp" alt=""></li>
                        <li class="w-[47px] lg:w-20"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-4.webp" alt=""></li>
                        <li class="w-[58px] lg:w-[98px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-5.webp" alt=""></li>
                        <li class="w-[59px] lg:w-[99px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-6.webp" alt=""></li>
                        <li class="w-15 lg:w-[102px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-7.webp" alt=""></li>
                        <li class="w-[54px] lg:w-[91px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-8.webp" alt=""></li>
                    </ul>
                    <ul class="top-slide1 flex gap-8 lg:gap-20 items-center">
                        <li class="w-[59px] lg:w-25"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-1.webp" alt=""></li>
                        <li class="w-[61px] lg:w-[103px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-2.webp" alt=""></li>
                        <li class="w-[83px] lg:w-[144px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-3.webp" alt=""></li>
                        <li class="w-[47px] lg:w-20"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-4.webp" alt=""></li>
                        <li class="w-[58px] lg:w-[98px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-5.webp" alt=""></li>
                        <li class="w-[59px] lg:w-[99px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-6.webp" alt=""></li>
                        <li class="w-15 lg:w-[102px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-7.webp" alt=""></li>
                        <li class="w-[54px] lg:w-[91px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-8.webp" alt=""></li>
                    </ul>
                </div>
            </section>

            <div class="mt-15 lg:mt-100 flex gap-[5%] relative">
                <div class="hidden lg:block w-[20%]">
                    <div class="parallax-small aspect-[2/3]">
                        <img src="<?php the_field('top-page-event-top-img1'); ?>" alt="" loading="lazy" width="100%" height="100%">
                    </div>
                </div>
                <div class="w-full lg:w-[75%] overflow-hidden">
                    <div class="w-full aspect-[2/3] lg:aspect-[3/2]">
                        <img class="w-full h-full object-cover" src="<?php the_field('top-page-event-top-img2'); ?>" alt="" loading="lazy" width="100%" height="100%">
                    </div>
                </div>
            </div>
            <div class="mt-15 text-right hidden lg:flex justify-end">
                <div class="w-[15%] parallax-small aspect-[20/9]">
                    <img src="<?php the_field('top-page-event-top-img3'); ?>" alt="" loading="lazy" width="100%" height="100%">
                </div>
            </div>

            <section class="mt-15 lg:-mt-60">
                <div class="w-full lg:w-[85%] max-w-306 mx-auto">
                    <h2 class="ml-[10%] lg:ml-[5%] text-[32px] lg:text-[60px] font-garamond italic leading-[1.25] tracking-[0.15em]">Events</h2>
                    <?php
                    // current-event-section.php は内部で $top_page_excluded_event_ids を更新します
                    get_template_part('templates/home/current-event-section');
                    ?>
                </div>
            </section>

            <section class="top-schedule mt-10 lg:mt-[155px] overflow-hidden">
                <div class="flex flex-row justify-between items-center ml-[5%] lg:ml-[25%] lg:mr-[5%] mt-16">
                    <?php
                    // h2 要素で見出しを表示する場合
                    get_template_part('templates/heading/heading-with-brackets', null, [
                        'heading_text' => '今後のスケジュール',
                        'heading_tag'  => 'h2',
                    ]);
                    ?>
                    <div class="hidden lg:flex justify-center items-center gap-8 !h-10">
                        <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'event']); ?>
                    </div>
                </div>
                <div class="mt-5 lg:mt-16 ml-auto mr-0 lg:ml-[25%] lg:w-[75%]">
                    <?php
                    // 4. swiper-event.php に除外する ID を渡します。
                    //    $top_page_excluded_event_ids は、current-event-section.php の実行後には値が入っています。
                    get_template_part('templates/swiper/swiper-event', null, [
                        'exclude_ids' => $top_page_excluded_event_ids
                    ]);
                    ?>
                </div>
                <div class="mt-5 lg:mt-10 mr-[5%] text-right">
                    <?php get_template_part('templates/common/link-button', null, [
                        'url' => home_url('/events'),
                        'label' => 'View All'
                    ]); ?>
                </div>
            </section>

            <section class="mt-15 lg:mt-75">
                <div class="youtube-iframe relative overflow-hidden w-full h-[80vh] md:h-screen">
                    <video autoplay loop muted playsinline class="absolute top-0 left-0 w-full h-full object-cover">
                        <source src="<?php echo get_template_directory_uri(); ?>/assets/image/about-top-youtube_v2.webm" type="video/webm">
                        お使いのブラウザはビデオをサポートしていません。
                    </video>
                </div>
            </section>

            <div class="mt-11 lg:mt-40 relative">
                <div class="lg:block absolute top-30 left-0 w-[10%] aspect-[9/20]">
                    <img src="<?php the_field('top-page-works-top-img1'); ?>" alt="" loading="lazy" width="100%" height="100%">
                </div>
                <div class="w-full h-auto lg:w-[50%] lg:ml-[30%]">
                    <div class="parallax-2">
                        <img src="<?php the_field('top-page-works-top-img2'); ?>" class="h-full lg:h-auto object-cover" alt="カステラと犬" loading="lazy">
                    </div>
                </div>
            </div>

            <section class="top-works-sec mt-15 lg:mt-100">
                <div class="w-full lg:w-[85%] max-w-306 mx-auto">
                    <div class="flex justify-between items-baseline px-[5%] lg:px-0">
                        <h2 class="ml-[5%] lg:ml-[5%] text-center font-garamond italic text-[32px] lg:text-[60px] leading-[1.25] tracking-[0.15em]">Works</h2>
                        <div class="">
                            <?php get_template_part('templates/common/link-button', null, [
                                'url' => home_url('/works'),
                                'label' => 'View All'
                            ]); ?>
                        </div>
                    </div>
                    <?php
                    $args = array(
                        'post_type' => 'works',
                        'posts_per_page' => 8,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    );

                    $query = new WP_Query($args);

                    if ($query->have_posts()) :
                        echo '<div class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-2 gap-y-6 lg:gap-x-9 lg:gap-y-15">';
                        while ($query->have_posts()) : $query->the_post();
                            // ★修正点5: works_category タクソノミーからタームを取得 (ここでは使用されていないが、念のため)
                            // $terms = get_the_terms(get_the_ID(), 'works_category'); // カスタムタクソノミースラッグに変更
                            // $post_category_slug = $terms && !is_wp_error($terms) ? $terms[0]->slug : '';
                            get_template_part('templates/parts/image-with-text', null, array(
                                'rounded' => 'lg:rounded-[20px]',
                                'mt_below' => 'mt-2 lg:mt-5'
                            ));
                        endwhile;
                        echo '</div>';
                    else :
                        echo '<p class="mt-10 text-center text-sm text-gray-500">投稿が見つかりませんでした。</p>';
                    endif;

                    wp_reset_postdata();
                    ?>
                </div>
            </section>

            <section class="mt-30 lg:mt-60">
                <?php
                get_template_part('templates/swiper/swiper-default', null, array(
                    'post_type_slug' => 'works' // ★修正点6: category_slug ではなく post_type_slug を渡す (swiper-default.php の引数と合わせる)
                ));
                ?>

            </section>

            <section class="top-news-about mt-15 lg:mt-75 py-15 lg:py-50 relative overflow-hidden lg:min-h-100">
                <div class="hidden lg:block parallax-bg w-full absolute left-0 -top-250 z-0 pointer-events-none">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/image/top-news-about-bg.webp" class="w-full" alt="背景画像" id="parallaxBgImg">
                </div>
                <div class="inner-content px-[8%] relative z-1">
                    <div class="lg:w-[75%] lg:mx-auto lg:flex xl:max-w-270 gap-[90px]">
                        <div class="lg:w-[48%] flex flex-col">
                            <div class="top-suzuri-service bg-[#FFFAD1] rounded-[10px] lg:rounded-[20px]">
                                <a href="https://suzuri.jp/takurami" class="flex items-center py-4 lg:py-6 px-5 lg:px-10 gap-[20px] lg:gap-10" target="_blank" rel="noopener noreferrer">
                                    <div class="w-16 lg:w-[92px]">
                                        <img class="w-full h-full" src="<?php echo get_template_directory_uri(); ?>/assets/image/surisurikun.svg" alt="タクラミ" loading="lazy" width="100%" height="100%">
                                    </div>
                                    <div class="w-[calc(100%-84px)] lg:w-[calc(100%-132px)]">
                                        <p class="text-sm lg:text-base tracking-[0.05em] leading-[1.4]">SUZURIの新サービス『タクラミ』を利用して、SNSアイコンのご依頼受付をはじめました。</p>
                                        <p class="mt-2 text-[#F00] text-[10px] lg:text-sm tracking-[0.05em]">※売り切れている場合があります。</p>
                                    </div>
                                </a>
                            </div>
                            <div class="mt-5 lg:mt-15 bg-[#FFF] py-8 lg:py-15 px-5 lg:px-[50px] rounded-[10px] lg:rounded-[20px] flex-grow">
                                <h2 class="text-center font-garamond italic text-[32px] lg:text-[45px] leading-[1.25] tracking-[0.15em]">News</h2>

                                <?php
                                $news_args = array(
                                    'post_type'      => 'post', // リネームされた「お知らせ」の投稿タイプは 'post' です
                                    'posts_per_page' => 4,
                                    'orderby'        => 'date', // 日付順
                                    'order'          => 'DESC', // 新しいものから表示
                                );
                                $news_query = new WP_Query($news_args);
                                ?>
                                <?php if ($news_query->have_posts()) : ?>
                                    <ul class="mt-5 lg:mt-10 pt-4 lg:pt-6 space-y-3 lg:space-y-5 border-t border-[#D9D9D9]">
                                        <?php while($news_query->have_posts()) : $news_query->the_post(); ?>
                                            <li class="pb-3 lg:pb-5 pl-3 lg:pl-4 border-b border-[#D9D9D9]">
                                                <a href="<?php the_permalink(); ?>" class="group block">
                                                    <div class="flex flex-col gap-1 lg:gap-2">
                                                        <?php get_template_part('templates/common/badge-new'); ?>
                                                        <div class="text-sm lg:text-base leading-[1.4] w-fit relative">
                                                            <span class="bg-gradient-to-t from-neutral-800 to-neutral-800/0 bg-[length:100%_0px] bg-no-repeat bg-left-bottom transition-all duration-250 group-hover:bg-[length:100%_1px]">
                                                                <?php the_title(); ?>
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center text-[10px] lg:text-xs leading-[1.3]">
                                                            <div class="tracking-[0.05em]" style="font-family: 'Open Sans', sans-serif;"><?php echo get_the_date(); ?></div>
                                                            <div class="ml-2 lg:ml-3 pl-2 lg:pl-3 border-l border-gray-300">
                                                                <?php
                                                                // カテゴリーとタグが削除されたため、この部分は表示しないか、
                                                                // または別の情報（例: 投稿タイプ名など）を表示するように変更できます。
                                                                // 例: 投稿タイプ名を表示する場合
                                                                // echo '<span>お知らせ</span>';
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php endif; ?>
                                <?php wp_reset_postdata(); ?>
                                <div class="mt-6 lg:mt-9 text-center">
                                    <?php get_template_part('templates/common/link-button', null, [
                                        'url' => home_url('/news'),
                                        'label' => 'View All'
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="lg:w-[48%] mt-5 lg:mt-0 bg-[#FFF] rounded-[10px] lg:rounded-[20px] flex flex-col justify-between">
                            <div class="pt-8 lg:pt-15 px-5 lg:px-[50px]">
                                <h2 class="text-center font-garamond italic text-[32px] lg:text-[45px] leading-[1.25] tracking-[0.15em]">About</h2>
                                <div class="w-[30%] mx-auto mt-8">
                                    <img class="w-full" src="<?php echo get_template_directory_uri(); ?>/assets/image/profile.svg" alt="プロフィールイラスト" loading="lazy" width="100%" height="100%">
                                </div>
                                <div class="mt-4 text-center text-lg lg:text-2xl font-medium tracking-[0.1em]">てらおかなつみ</div>
                                <div class="mt-1 lg:mt-2 text-center text-xs lg:text-sm leading-[1.3] tracking-[0.1em]" style="font-family: 'Open Sans', sans-serif;">Teraoka Natsumi</div>
                                <p class="mt-6 lg:mt-8 text-sm lg:text-base leading-[1.75]">
                                    <?php the_field('top-page-about-text'); ?>
                                </p>
                                <div class="mt-6 lg:mt-8 text-center">
                                    <?php get_template_part('templates/common/link-button', null, [
                                        'url' => home_url('/about'),
                                        'label' => 'View More'
                                    ]); ?>
                                </div>
                            </div>
                            <div class="-mt-10">
                                <img class="w-full" src="<?php echo get_template_directory_uri(); ?>/assets/image/top-about.webp" alt="犬のイラスト" loading="lazy" width="100%" height="100%">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-8 lg:mt-25 text-center">
                <div class="w-full lg:w-[85%] max-w-306 mx-auto px-[5%] lg:px-0">
                    <h2 class="text-center font-garamond italic text-[32px] lg:text-[60px] leading-[1.25] tracking-[0.15em]">Contact</h2>
                    <div class="mt-5 lg:mt-10 mx-auto w-24 lg:w-40">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/top-contact.webp" alt="犬のイラスト" loading="lazy" width="100%" height="100%">
                    </div>
                    <p class="mt-4 lg:mt-[25px] text-sm lg:text-base leading-[1.5]">
                        お仕事のご依頼、展示のお誘いについては、<br>
                        こちらからお問い合わせください。
                    </p>
                    <div class="mt-5 lg:mt-[32px]">
                        <a href="<?php bloginfo('url');?>/contact" class="inline-flex justify-center items-center w-full max-w-60 lg:max-w-[350px] h-10 lg:h-13 px-[15px] border border-[#D9D9D9] hover:border-[#999] hover:bg-[#F5F5F5] rounded-full text-sm lg:text-base gap-2 transition-all duration-300">
                            <span class="text-sm lg:text-base">お問い合わせ</span>
                        </a>
                    </div>
                </div>
            </section>

            <section class="mt-8 lg:mt-30 overflow-hidden">
                <?php
                echo do_shortcode('[instagram-feed feed=2 showfollow=false num=10 cols=10]');
                ?>
                <div class=" text-center">
                    <a href="https://www.instagram.com/teraoka_natsumi/" class="inline-block" target="_blank" rel="noopener noreferrer">
                        <div class="flex">
                            <div class="w-[16px] lg:w-[20px] flex justify-center">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/image/instagram-icon.svg" alt="" loading="lazy" width="100%" height="100%">
                            </div>
                            <p class="ml-[6px] lg:ml-3 text-sm lg:text-[20px] font-[600] tracking-[0.05em] leading-[1.25] italic" style="font-family: 'Cormorant Garamond', serif;">Follow Me</p>
                        </div>
                    </a>
                </div>
            </section>
        </div>
    </div>
</main>

<?php get_footer(); ?>
