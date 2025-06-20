<?php get_header(); ?>

<main data-barba="container" data-barba-namespace="works-archive">
    <div class="flex xl:flex-row justify-center xl:justify-between items-center xl:ml-[25%] xl:mr-[7.5%] mt-16">
        <div class="w-full xl:w-auto">
            <?php
            get_template_part('templates/heading/custom-heading', null, [
                'title' => 'Works'
            ]);
            ?>
        </div>
        <div class="hidden xl:flex justify-center items-center gap-8 !h-10">
            <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'default']); ?>
        </div>
    </div>
    <section class="w-full xl:ml-[25%] xl:w-[75%] mt-7 xl:mt-16">
        <?php
        // スライダーセクション
        get_template_part('templates/swiper/swiper-default', null, array(
            'post_type_slug' => 'works'
        ));
        ?>
    </section>
    <!-- <section class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 xl:mt-30"> -->
    <section class="w-full xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 xl:mt-30">
        <div class="mb-5 xl:mb-6 px-[5%] xl:px-0">
            <?php
            // Works一覧の見出し
            get_template_part('templates/heading/heading-with-brackets', null, [
                'heading_text' => 'お仕事一覧',
                'heading_tag'  => 'h2',
            ]);
            ?>
        </div>

        <?php
        $taxonomy_slug = 'works_category';
        $terms = get_terms( array(
            'taxonomy'   => $taxonomy_slug,
            'hide_empty' => false, // 投稿がないタグも表示する場合は true
        ) );

        if (!empty($terms) && !is_wp_error($terms)) :
        ?>
        <div class="works-archive-filter-area">
            <ul class="tag-list flex flex-nowrap overflow-x-auto overflow-y-hidden whitespace-nowrap max-w-full gap-1 xl:gap-2 px-5 xl:px-0 scroll-smooth [-webkit-overflow-scrolling:touch] no-scrollbar">
                <li>
                    <a href="#" class="is-active tag-link flex items-center h-[26px] xl:h-[30px] text-xs xl:text-sm tracking-[0.07em] px-2 xl:px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] is-active:bg-[#F5F5F5] is-active:border-[#333] is-active:text-[#000] [&.is-active]:bg-[#F5F5F5] [&.is-active]:border-[#333] [&.is-active]:text-[#000]" data-tag="all" data-category="<?= esc_attr($taxonomy_slug); ?>">
                    すべて
                    </a>
                </li>
                <?php foreach ($terms as $term) : ?>
                    <li>
                        <a href="#" class="tag-link flex items-center h-[26px] xl:h-[30px] text-xs xl:text-sm tracking-[0.07em] px-2 xl:px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] is-active:bg-[#F5F5F5] is-active:border-[#333] is-active:text-[#000] [&.is-active]:bg-[#F5F5F5] [&.is-active]:border-[#333] [&.is-active]:text-[#000]" data-tag="<?= esc_attr($term->slug); ?>" data-category="<?= esc_attr($taxonomy_slug); ?>">
                            <?= esc_html($term->name); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="works-archive-list" id="filtered-posts-container">
            <?php
            // メインクエリはWorks投稿タイプの結果を自動的に含みます
            if (have_posts()) :
                echo '<div class="grid grid-cols-2 xl:grid-cols-4 mt-5 xl:mt-15 gap-x-2 gap-y-6 xl:gap-x-9 xl:gap-y-15">';
                while (have_posts()) : the_post();
                    $taxonomy_slug = 'works_category'; // Worksのタクソノミースラッグ
                    $post_terms = get_the_terms(get_the_ID(), $taxonomy_slug);
                    $data_tags_value = [];
                    if (!empty($post_terms) && !is_wp_error($post_terms)) {
                        foreach ($post_terms as $term) {
                            $data_tags_value[] = $term->slug;
                        }
                    }
                    $data_tags_string = implode(' ', $data_tags_value); // スペース区切り

                    get_template_part('templates/parts/image-with-text', null, array(
                        'rounded' => 'xl:rounded-[20px]',
                        'mt_below' => 'mt-2 xl:mt-5',
                        'data_tags' => $data_tags_string // ★引き続き渡す★
                    ));
                endwhile;
                echo '</div>';
            else :
                echo '<p class="mt-10 text-center text-sm text-gray-500">作品が見つかりませんでした。</p>';
            endif;
            ?>
        </div>
        <div id="pagination-container">
            <?php
            // 初期ページのページネーション表示 (メインクエリ)
            global $wp_query;
            $big = 999999999; // need an unlikely integer
            echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $wp_query->max_num_pages,
                'prev_text'    => __('&laquo; Prev'),
                'next_text'    => __('Next &raquo;'),
                'type'         => 'list',
                'before_page_number' => '<span class="screen-reader-text">Page </span>'
            ) );
            ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
