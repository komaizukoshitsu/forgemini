<?php
/**
 * Template Name: お知らせ一覧ページ
 *
 * @package YourTheme
 */

get_header();
?>

<main data-barba="container" data-barba-namespace="page-news">
    <div class="flex lg:flex-row justify-center lg:justify-between items-center lg:ml-[25%] lg:mr-[7.5%] mt-16">
        <div class="w-full lg:w-auto">
            <?php
            get_template_part('templates/heading/custom-heading', null, [
                'title' => 'News'
            ]);
            ?>
        </div>
        <div class="hidden lg:flex justify-center items-center gap-8 !h-10">
            <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'default']); ?>
        </div>
    </div>
    <section class="w-full xl:ml-[25%] xl:w-[75%] mt-7 lg:mt-16">
        <?php
        // お知らせのスライダーが不要ならこのセクションは削除
        get_template_part('templates/swiper/swiper-default', null, array(
            'post_type_slug' => 'post' // 「お知らせ」の投稿タイプは 'post'
        ));
        ?>
    </section>

    <section class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 lg:mt-30">
        <div class="mb-5 lg:mb-6 px-[5%] lg:px-0">
            <?php
            get_template_part('templates/heading/heading-with-brackets', null, [
                'heading_text' => 'お知らせ一覧',
                'heading_tag'  => 'h2',
            ]);
            ?>
        </div>

        <div id="post-list">
            <?php
            // ★ここが重要な変更点★
            // 固定ページではメインクエリが使えないため、WP_Queryで投稿を取得します
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            $news_args = array(
                'post_type'      => 'post',        // WordPressのデフォルト投稿タイプ
                'posts_per_page' => 10,            // 1ページに表示するお知らせの数
                'orderby'        => 'date',
                'order'          => 'DESC',
                'paged'          => $paged,        // ページネーション対応
            );
            $news_query = new WP_Query( $news_args );

            if ( $news_query->have_posts() ) :
                echo '<div class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-2 gap-y-6 lg:gap-x-9 lg:gap-y-15">';
                while ( $news_query->have_posts() ) : $news_query->the_post();
                    // ★修正点: template_context を 'news' に設定する ★
                    get_template_part('templates/parts/image-with-text', null, array(
                        'rounded'          => 'lg:rounded-[20px]',
                        'mt_below'         => 'mt-2 lg:mt-5',
                        'template_context' => 'news' // ここで 'news' であることを伝える
                    ));
                endwhile;
                echo '</div>';
            else :
                echo '<p class="mt-10 text-center text-sm text-gray-500">お知らせが見つかりませんでした。</p>';
            endif;

            // ページネーション（$news_query に対応させる）
            $big = 999999999; // need an unlikely integer
            echo paginate_links( array(
                'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format'  => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total'   => $news_query->max_num_pages,
                'type'    => 'list', // ul li 形式で出力
                'prev_text' => '‹',
                'next_text' => '›',
            ) );

            wp_reset_postdata(); // WP_Query を使った後は必ずリセット
            ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
