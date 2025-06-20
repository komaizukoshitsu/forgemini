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
        get_template_part('templates/swiper/swiper-default', null, array(
            'post_type_slug' => 'post'
        ));
        ?>
    </section>

    <section class="w-full xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 lg:mt-30">
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
            // ★修正ここから★
            // 'paged' の代わりにカスタムクエリ変数 'news_page' を使用
            $news_current_page = (get_query_var('news_page')) ? get_query_var('news_page') : 1;

            $news_args = array(
                'post_type'      => 'post',
                'posts_per_page' => 10,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'paged'          => $news_current_page, // WP_Query には 'paged' を渡す
            );
            $news_query = new WP_Query( $news_args );

            if ( $news_query->have_posts() ) :
                echo '<div class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-2 gap-y-6 lg:gap-x-9 lg:gap-y-15">';
                while ( $news_query->have_posts() ) : $news_query->the_post();
                    get_template_part('templates/parts/image-with-text', null, array(
                        'rounded'          => 'lg:rounded-[20px]',
                        'mt_below'         => 'mt-2 lg:mt-5',
                        'template_context' => 'news'
                    ));
                endwhile;
                echo '</div>';
            else :
                echo '<p class="mt-10 text-center text-sm text-gray-500">お知らせが見つかりませんでした。</p>';
            endif;
            wp_reset_postdata(); // これを忘れないでください。メインクエリをリセットします。
            // ★修正ここまで★
            ?>
        </div>
        <div id="pagination-container">
        <?php
        // ★修正ここから★
        // カスタムクエリである $news_query を使ってページネーションを生成
        $big = 999999999;

        // 現在の固定ページのパーマリンクを取得
        $current_page_base_url = get_permalink();

        // paginate_links を array タイプで実行
        $paginate_links_array = paginate_links( array(
            'base'         => add_query_arg( 'news_page', '%#%', esc_url( $current_page_base_url ) ),
            'format'       => '?news_page=%#%',
            'current'      => max( 1, $news_current_page ),
            'total'        => $news_query->max_num_pages,
            'prev_text'    => '&lt;', // 例: prev/next の表示を統一
            'next_text'    => '&gt;', // 例: prev/next の表示を統一
            'type'         => 'array', // ★ここを 'array' に変更★
            'before_page_number' => '<span class="screen-reader-text">Page </span>'
        ) );

        // ページネーションリンクが存在する場合のみ ul タグを出力
        if ( $paginate_links_array ) {
            // ★ここに ul タグとカスタムクラスを追加★
            echo '<ul class="pagination-list flex flex-wrap justify-center gap-2 mt-10 lg:mt-15">';
            foreach ( $paginate_links_array as $link ) {
                // WordPressが自動で付与する 'current' クラスを Tailwind の 'is-active' に変換 (任意)
                $link = str_replace('page-numbers current', 'page-numbers is-active', $link);
                // 個々の li にもクラスが必要であればここに追加
                echo '<li class="pagination-item">' . $link . '</li>';
            }
            echo '</ul>';
        }
        ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
