<?php get_header(); ?>

<main data-barba="container" data-barba-namespace="archive">
    <div class="flex flex-row justify-between items-center xl:ml-[25%] xl:mr-[7.5%] mt-16">
        <div class="w-full lg:w-auto">
            <?php
            get_template_part('templates/heading/custom-heading', null, [
                'title' => 'Goods'
            ]);
            ?>
        </div>
        <div class="hidden lg:flex justify-center items-center gap-8 !h-10">
            <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'default']); ?>
        </div>
    </div>
    <section class="w-full xl:w-[75%] xl:ml-[25%] mt-7 lg:mt-16">
        <?php get_template_part('templates/swiper/swiper-default'); ?>
    </section>
    <section class="w-full xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 lg:mt-30">
        <!-- <h1 class="font-medium px-5 lg:px-0 mb-5 lg:mb-7 tracking-[0.1em]">（&ensp;<?php single_cat_title(); ?>&ensp;）</h1> -->
        <div class="mb-5 lg:mb-6 px-[5%] lg:px-0">
            <?php
            get_template_part('templates/heading/heading-with-brackets', null, [
                'heading_text' => 'グッズ一覧',
                'heading_tag'  => 'h2',
            ]);
            ?>
        </div>

        <?php
        $category = get_queried_object();
        $category_slug = $category->slug;

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            'category_name' => $category_slug,
            'fields' => 'ids',
        );
        $query = new WP_Query($args);
        $post_ids = $query->posts;

        $tags = wp_get_object_terms($post_ids, 'post_tag');

        if (!empty($tags) && !is_wp_error($tags)) :
        ?>
        <!-- <ul class="tag-list flex flex-nowrap w-max gap-1 lg:gap-2 px-5 lg:px-0 overflow-x-auto touch-pan-x"> -->
        <ul class="tag-list flex flex-nowrap overflow-x-auto overflow-y-hidden whitespace-nowrap max-w-full gap-1 lg:gap-2 px-5 lg:px-0 scroll-smooth [-webkit-overflow-scrolling:touch] no-scrollbar">
            <li>
                <a href="#" class="active tag-link flex items-center h-[26px] lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-2 lg:px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] active:bg-[#F5F5F5] active:border-[#333] active:text-[#000] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="all" data-category="<?= esc_attr($category_slug); ?>">
                すべて
                </a>
            </li>
            <?php foreach ($tags as $tag) : ?>
                <li>
                    <a href="#" class="tag-link flex items-center h-[26px] lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-2 lg:px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] active:bg-[#F5F5F5] active:border-[#333] active:text-[#000] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="<?= esc_attr($tag->slug); ?>" data-category="<?= esc_attr($category_slug); ?>">
                        <?= esc_html($tag->name); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <!-- 投稿一覧 -->
        <div id="post-list">
            <?php
            if (have_posts()) :
                echo '<div class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-2 gap-y-6 lg:gap-x-9 lg:gap-y-15">';
                while (have_posts()) : the_post();
                    $categories = get_the_category();
                    $post_category_slug = $categories ? $categories[0]->slug : '';
                    get_template_part('templates/parts/image-with-text', null, array(
                        'rounded' => 'lg:rounded-[20px]',
                        'mt_below' => 'mt-2 lg:mt-5'
                    ));
                endwhile;
                echo '</div>';
            else :
                echo '<p class="mt-10 text-center text-sm text-gray-500">投稿が見つかりませんでした。</p>';
            endif;
            ?>
        </div>
    </section>
    <section class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 lg:mt-30">
        <?php
        get_template_part('templates/heading/heading-with-brackets', null, [
            'heading_text' => 'SNSアイコン制作',
            'heading_tag'  => 'h2',
        ]);
        ?>
        <?php get_template_part('templates/category/takurami');?>
    </section>
    <section class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 lg:mt-30">
        <?php get_template_part('shop-list'); ?>
    </section>
</main>
<?php get_footer(); ?>
