<?php get_header(); ?>
<?php
global $top_page_excluded_event_ids;
$top_page_excluded_event_ids = [];
?>

<main data-barba="container" data-barba-namespace="home">
    <section class="relative overflow-hidden h-[100svh]">
        <div id="hero" class="fixed top-0 left-0 w-full h-[100svh] overflow-hidden transition-opacity duration-500 ease-in-out opacity-100 -z-1">
            <img class="pc-only hidden xl:block w-full h-full object-cover" src="<?php the_field('mv-img'); ?>" alt="ヒーローイメージ">
            <img class="sp-only block xl:hidden w-full h-auto object-cover" src="<?php the_field('sp-mv-img'); ?>" alt="ヒーローイメージ (SP)">
        </div>
        <?php
        $today = date('Y-m-d'); // 今日の日付を取得

        $args = array(
            'post_type' => 'events', // イベント投稿タイプを指定
            'posts_per_page' => 1,   // 表示する投稿数
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'event-start', // ACFフィールドキー：イベント開始日
                    'value' => $today,
                    'compare' => '<=',       // 開始日が今日以前
                    'type' => 'DATE'
                ),
                array(
                    'key' => 'event-end',   // ACFフィールドキー：イベント終了日
                    'value' => $today,
                    'compare' => '>=',       // 終了日が今日以降
                    'type' => 'DATE'
                ),
            ),
            'orderby' => 'meta_value', // event-start でソート
            'meta_key' => 'event-start',
            'order' => 'ASC',          // 古いイベントから表示
        );

        // クエリを実行
        $current_event_query = new WP_Query($args);

        // 投稿があるか確認
        if ($current_event_query->have_posts()) :
            while($current_event_query->have_posts()) : $current_event_query->the_post(); ?>
                <div id="event-info" class="absolute bottom-24 xl:bottom-20 right-0 w-full xl:w-[37.5rem] transition-opacity duration-300 ease-in-out">
                    <a href="<?php the_permalink(); ?>" class="group block bg-white py-3 xl:py-5 pl-4 xl:pl-7 rounded-tl-[55px] rounded-bl-[55px]">
                        <div class="mv-news-inner flex items-center transition-all duration-200 ease-linear">
                            <div class="xl:text-2xl italic tracking-[0.15em] font-garamond">Event</div>
                            <div class="flex flex-col gap-1 xl:gap-2 ml-3 xl:ml-4 px-4 xl:px-5 border-l border-[#D9D9D9]">
                                <div class="flex flex-row items-center gap-1 xl:gap-2">
                                    <?php get_template_part('templates/event/event-status'); ?>
                                    <?php get_template_part('templates/event/event-date-range'); ?>
                                </div>
                                <div class="text-sm xl:text-base leading-[1.4] w-full relative"> <span class="bg-gradient-to-t from-neutral-800 to-neutral-800/0 bg-[length:100%_0px] bg-no-repeat bg-left-bottom transition-all duration-250 group-hover:bg-[length:100%_1px]">
                                        <?php the_title(); ?>
                                    </span>
                                </div>
                                <div class="flex items-center text-[10px] xl:text-xs leading-[1.3]">
                                    <p class="">
                                        <?php
                                        $venues = get_the_terms(get_the_ID(), 'event_venue');
                                        if ($venues && !is_wp_error($venues)) {
                                            echo esc_html($venues[0]->name);
                                        } else {
                                            echo '会場未定';
                                        }
                                        ?>
                                    </p>
                                    <p class="ml-2 xl:ml-3 pl-2 xl:pl-3 border-l border-gray-300">
                                        <?php
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
            // 現在開催中のイベントがない場合
            ?>
            <div id="event-info-none" class="absolute bottom-24 xl:bottom-20 right-0 w-full xl:w-[37.5rem] transition-opacity duration-300 ease-in-out">
                <div class="bg-white py-3 xl:py-5 pl-4 xl:pl-7 rounded-tl-[55px] rounded-bl-[55px] text-center text-sm xl:text-base text-gray-600">
                    現在開催中のイベントはありません。
                </div>
            </div>
        <?php endif;

        wp_reset_postdata();
        ?>
    </section>
    <div>
        <div class="bg-white pb-10 xl:pb-25">
            <section class="relative top-goods pt-10 xl:pt-21">
                <div class="w-full xl:w-[85%] max-w-306 mx-auto px-[5%] xl:px-0">
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
                        echo '<div class="grid grid-cols-2 xl:grid-cols-4 mt-5 xl:mt-15 gap-x-4 gap-y-4 xl:gap-x-9 xl:gap-y-9">';
                        $i = 1;
                        while ($pickup_query->have_posts()) : $pickup_query->the_post();
                            $col_span = '';
                            $row_span = '';
                            $col_start = '';
                            $row_start = '';

                            if ($i === 1) {
                                $col_span = 'col-span-2 xl:col-span-2 row-span-2 xl:row-span-2 col-start-1 xl:col-start-1 row-start-1 xl:row-start-1'; // 2マス取り
                            } elseif ($i === 2) {
                                $col_start = 'col-start-1 xl:col-start-1 row-start-3 xl:row-start-3'; // スマホpc1列目--スマホpc2行目
                            } elseif ($i === 3) {
                                $col_start = 'col-start-2 xl:col-start-2 row-start-3 xl:row-start-3'; // 2列目
                            } elseif ($i === 4) {
                                $col_start = 'col-start-1 xl:col-start-3 row-start-6 xl:row-start-1'; // スマホ1列目・pc3列目--スマホ4行目・pc1行目
                            } elseif ($i === 5) {
                                $col_start = 'col-start-2 xl:col-start-4 row-start-6 xl:row-start-1'; //スマホ2列目・pc4列目--スマホ4行目・pc1行目
                            } elseif ($i === 6) {
                                $col_span = 'col-span-2 xl:col-span-2 row-span-2 xl:row-span-2'; // 2マス取り
                                $col_start = 'col-start-1 xl:col-start-3 row-start-4 xl:row-start-2'; // スマホ1列目・pc3列目--スマホ3行目・pc2行目
                            }
                            echo '<div class="grid-item ' . $col_span . ' ' . $row_span . ' ' . $col_start . ' ' . $row_start . '">';
                            get_template_part('templates/parts/image-with-text', null, array(
                                'text_position' => 'overlay',
                                'mt_below' => 'mt-2 xl:mt-5',
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

                    <div class="mt-5 xl:mt-10 text-right">
                        <?php get_template_part('templates/common/link-button', null, [
                            'url' => home_url('/goods'),
                            'label' => 'View All'
                        ]); ?>
                    </div>
                </div>
            </section>

            <section class="relative overflow-hidden h-[90px] xl:h-[151px] bg-wave z-0 mt-10 xl:mt-40">
                <div class="absolute inset-0 bg-wave-pattern z-0 pointer-events-none"></div>
                <div class="slide-track absolute inset-0 flex items-center z-[1] space-x-8 xl:space-x-20">
                    <ul class="top-slide1 flex gap-8 xl:gap-20 items-center">
                        <li class="w-[59px] xl:w-25"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-1.webp" alt=""></li>
                        <li class="w-[61px] xl:w-[103px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-2.webp" alt=""></li>
                        <li class="w-[83px] xl:w-[144px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-3.webp" alt=""></li>
                        <li class="w-[47px] xl:w-20"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-4.webp" alt=""></li>
                        <li class="w-[58px] xl:w-[98px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-5.webp" alt=""></li>
                        <li class="w-[59px] xl:w-[99px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-6.webp" alt=""></li>
                        <li class="w-15 xl:w-[102px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-7.webp" alt=""></li>
                        <li class="w-[54px] xl:w-[91px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-8.webp" alt=""></li>
                    </ul>
                    <ul class="top-slide1 flex gap-8 xl:gap-20 items-center">
                        <li class="w-[59px] xl:w-25"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-1.webp" alt=""></li>
                        <li class="w-[61px] xl:w-[103px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-2.webp" alt=""></li>
                        <li class="w-[83px] xl:w-[144px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-3.webp" alt=""></li>
                        <li class="w-[47px] xl:w-20"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-4.webp" alt=""></li>
                        <li class="w-[58px] xl:w-[98px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-5.webp" alt=""></li>
                        <li class="w-[59px] xl:w-[99px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-6.webp" alt=""></li>
                        <li class="w-15 xl:w-[102px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-7.webp" alt=""></li>
                        <li class="w-[54px] xl:w-[91px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/slide-8.webp" alt=""></li>
                    </ul>
                </div>
            </section>

            <div class="mt-15 xl:mt-100 flex gap-[5%] relative">
                <div class="hidden xl:block w-[20%]">
                    <div class="parallax-small aspect-[2/3]">
                        <img src="<?php the_field('top-page-event-top-img1'); ?>" alt="" loading="lazy" width="100%" height="100%">
                    </div>
                </div>
                <div class="w-full xl:w-[75%] overflow-hidden">
                    <div class="w-full aspect-[2/3] xl:aspect-[3/2]">
                        <img class="w-full h-full object-cover" src="<?php the_field('top-page-event-top-img2'); ?>" alt="" loading="lazy" width="100%" height="100%">
                    </div>
                </div>
            </div>
            <div class="mt-15 text-right hidden xl:flex justify-end">
                <div class="w-[15%] parallax-small aspect-[20/9]">
                    <img src="<?php the_field('top-page-event-top-img3'); ?>" alt="" loading="lazy" width="100%" height="100%">
                </div>
            </div>

            <section class="mt-15 xl:-mt-60">
                <div class="w-full xl:w-[85%] max-w-306 mx-auto">
                    <h2 class="ml-[10%] xl:ml-[5%] text-[32px] xl:text-[60px] font-garamond italic leading-[1.25] tracking-[0.15em]">Events</h2>
                    <?php
                    // current-event-section.php は内部で $top_page_excluded_event_ids を更新します
                    get_template_part('templates/home/current-event-section');
                    ?>
                </div>
            </section>

            <section class="top-schedule mt-10 xl:mt-[155px] overflow-hidden">
                <div class="flex flex-row justify-between items-center ml-[5%] xl:ml-[25%] xl:mr-[5%] mt-16">
                    <?php
                    get_template_part('templates/heading/heading-with-brackets', null, [
                        'heading_text' => '今後のスケジュール',
                        'heading_tag'  => 'h2',
                    ]);
                    ?>
                    <div class="hidden xl:flex justify-center items-center gap-8 !h-10">
                        <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'event']); ?>
                    </div>
                </div>
                <div class="mt-5 xl:mt-16 ml-auto mr-0 xl:ml-[25%] xl:w-[75%]">
                    <?php
                    get_template_part('templates/swiper/swiper-event', null, [
                        'exclude_ids' => $top_page_excluded_event_ids
                    ]);
                    ?>
                </div>
                <div class="mt-5 xl:mt-10 mr-[5%] text-right">
                    <?php get_template_part('templates/common/link-button', null, [
                        'url' => home_url('/events'),
                        'label' => 'View All'
                    ]); ?>
                </div>
            </section>

            <section class="mt-15 xl:mt-75">
                <div class="youtube-iframe relative overflow-hidden w-full h-[80vh] md:h-screen js-autoplay-video-section">
                    <video autoplay loop muted playsinline class="absolute top-0 left-0 w-full h-full object-cover">
                        <source src="<?php echo get_template_directory_uri(); ?>/assets/image/about-top-youtube_v2.webm" type="video/webm">
                        お使いのブラウザはビデオをサポートしていません。
                    </video>
                </div>
            </section>

            <div class="mt-11 xl:mt-40 relative">
                <div class="hidden xl:block absolute top-30 left-0 w-[10%] aspect-[9/20]">
                    <img src="<?php the_field('top-page-works-top-img1'); ?>" alt="" loading="lazy" width="100%" height="100%">
                </div>
                <div class="w-full h-auto xl:w-[50%] xl:ml-[30%]">
                    <div class="parallax-2">
                        <img src="<?php the_field('top-page-works-top-img2'); ?>" class="h-full xl:h-auto object-cover" alt="カステラと犬" loading="lazy">
                    </div>
                </div>
            </div>

            <section class="top-works-sec mt-15 xl:mt-100">
                <div class="w-full xl:w-[85%] max-w-306 mx-auto">
                    <div class="flex justify-between items-baseline px-[5%] xl:px-0">
                        <h2 class="ml-[5%] xl:ml-[5%] text-center font-garamond italic text-[32px] xl:text-[60px] leading-[1.25] tracking-[0.15em]">Works</h2>
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
                        echo '<div class="grid grid-cols-2 xl:grid-cols-4 mt-5 xl:mt-15 gap-x-2 gap-y-6 xl:gap-x-9 xl:gap-y-15">';
                        while ($query->have_posts()) : $query->the_post();
                            get_template_part('templates/parts/image-with-text', null, array(
                                'rounded' => 'xl:rounded-[20px]',
                                'mt_below' => 'mt-2 xl:mt-5'
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

            <section class="mt-30 xl:mt-60">
                <?php
                get_template_part('templates/swiper/swiper-home-works', null, array(
                    'post_type_slug' => 'works'
                ));
                ?>
            </section>

            <section class="top-news-about mt-15 xl:mt-75 py-15 xl:py-50 relative overflow-hidden xl:min-h-100">
                <div class="hidden xl:block parallax-bg w-full absolute left-0 -top-250 z-0 pointer-events-none">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/image/top-news-about-bg.webp" class="w-full" alt="背景画像" id="parallaxBgImg">
                </div>
                <div class="inner-content px-[8%] relative z-1">
                    <div class="xl:w-[75%] xl:mx-auto xl:flex xl:max-w-270 gap-[90px]">
                        <div class="xl:w-[48%] flex flex-col">
                            <div class="top-suzuri-service bg-[#FFFAD1] rounded-[10px] xl:rounded-[20px]">
                                <a href="https://suzuri.jp/takurami" class="flex items-center py-4 xl:py-6 px-5 xl:px-10 gap-[20px] xl:gap-10" target="_blank" rel="noopener noreferrer">
                                    <div class="w-16 xl:w-[92px]">
                                        <img class="w-full h-full" src="<?php echo get_template_directory_uri(); ?>/assets/image/surisurikun.svg" alt="タクラミ" loading="lazy" width="100%" height="100%">
                                    </div>
                                    <div class="w-[calc(100%-84px)] xl:w-[calc(100%-132px)]">
                                        <p class="text-sm xl:text-base tracking-[0.05em] leading-[1.4]">SUZURIの新サービス『タクラミ』を利用して、SNSアイコンのご依頼受付をはじめました。</p>
                                        <p class="mt-2 text-[#F00] text-[10px] xl:text-sm tracking-[0.05em]">※売り切れている場合があります。</p>
                                    </div>
                                </a>
                            </div>
                            <div class="mt-5 xl:mt-15 bg-[#FFF] py-8 xl:py-15 px-5 xl:px-[50px] rounded-[10px] xl:rounded-[20px] flex-grow">
                                <h2 class="text-center font-garamond italic text-[32px] xl:text-[45px] leading-[1.25] tracking-[0.15em]">News</h2>

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
                                    <ul class="mt-5 xl:mt-10 pt-4 xl:pt-6 space-y-3 xl:space-y-5 border-t border-[#D9D9D9]">
                                        <?php while($news_query->have_posts()) : $news_query->the_post(); ?>
                                            <li class="pb-3 xl:pb-5 pl-3 xl:pl-4 border-b border-[#D9D9D9]">
                                                <a href="<?php the_permalink(); ?>" class="group block">
                                                    <div class="flex flex-col gap-1 xl:gap-2">
                                                        <?php get_template_part('templates/common/badge-new'); ?>
                                                        <div class="text-sm xl:text-base leading-[1.4] w-fit relative">
                                                            <span class="bg-gradient-to-t from-neutral-800 to-neutral-800/0 bg-[length:100%_0px] bg-no-repeat bg-left-bottom transition-all duration-250 group-hover:bg-[length:100%_1px]">
                                                                <?php the_title(); ?>
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center text-[10px] xl:text-xs leading-[1.3]">
                                                            <div class="tracking-[0.05em]" style="font-family: 'Open Sans', sans-serif;"><?php echo get_the_date(); ?></div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php endif; ?>
                                <?php wp_reset_postdata(); ?>
                                <div class="mt-6 xl:mt-9 text-center">
                                    <?php get_template_part('templates/common/link-button', null, [
                                        'url' => home_url('/news'),
                                        'label' => 'View All'
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="xl:w-[48%] mt-5 xl:mt-0 bg-[#FFF] rounded-[10px] xl:rounded-[20px] flex flex-col justify-between">
                            <div class="pt-8 xl:pt-15 px-5 xl:px-[50px]">
                                <h2 class="text-center font-garamond italic text-[32px] xl:text-[45px] leading-[1.25] tracking-[0.15em]">About</h2>
                                <div class="w-[30%] mx-auto mt-8">
                                    <img class="w-full" src="<?php echo get_template_directory_uri(); ?>/assets/image/profile.svg" alt="プロフィールイラスト" loading="lazy" width="100%" height="100%">
                                </div>
                                <div class="mt-4 text-center text-lg xl:text-2xl font-medium tracking-[0.1em]">てらおかなつみ</div>
                                <div class="mt-1 xl:mt-2 text-center text-xs xl:text-sm leading-[1.3] tracking-[0.1em]" style="font-family: 'Open Sans', sans-serif;">Teraoka Natsumi</div>
                                <p class="mt-6 xl:mt-8 text-sm xl:text-base leading-[1.75]">
                                    <?php the_field('top-page-about-text'); ?>
                                </p>
                                <div class="mt-6 xl:mt-8 text-center">
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

            <section class="mt-8 xl:mt-25 text-center">
                <div class="w-full xl:w-[85%] max-w-306 mx-auto px-[5%] xl:px-0">
                    <h2 class="text-center font-garamond italic text-[32px] xl:text-[60px] leading-[1.25] tracking-[0.15em]">Contact</h2>
                    <div class="mt-5 xl:mt-10 mx-auto w-24 xl:w-40">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/top-contact.webp" alt="犬のイラスト" loading="lazy" width="100%" height="100%">
                    </div>
                    <p class="mt-4 xl:mt-[25px] text-sm xl:text-base leading-[1.5]">
                        お仕事のご依頼、展示のお誘いについては、<br>
                        こちらからお問い合わせください。
                    </p>
                    <div class="mt-5 xl:mt-[32px]">
                        <a href="<?php bloginfo('url');?>/contact" class="inline-flex justify-center items-center w-full max-w-60 xl:max-w-[350px] h-10 xl:h-13 px-[15px] border border-[#D9D9D9] hover:border-[#999] hover:bg-[#F5F5F5] rounded-full text-sm xl:text-base gap-2 transition-all duration-300">
                            <span class="text-sm xl:text-base">お問い合わせ</span>
                        </a>
                    </div>
                </div>
            </section>

            <section class="mt-8 xl:mt-30 overflow-hidden">
                <?php
                echo do_shortcode('[instagram-feed feed=2 showfollow=false num=10 cols=10]');
                ?>
                <div class=" text-center">
                    <a href="https://www.instagram.com/teraoka_natsumi/" class="inline-block" target="_blank" rel="noopener noreferrer">
                        <div class="flex">
                            <div class="w-[16px] xl:w-[20px] flex justify-center">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/image/instagram-icon.svg" alt="" loading="lazy" width="100%" height="100%">
                            </div>
                            <p class="ml-[6px] xl:ml-3 text-sm xl:text-[20px] font-[600] tracking-[0.05em] leading-[1.25] italic" style="font-family: 'Cormorant Garamond', serif;">Follow Me</p>
                        </div>
                    </a>
                </div>
            </section>
        </div>
    </div>
</main>

<?php get_footer(); ?>
