<main data-barba="container" data-barba-namespace="single">
    <div class="mt-17">
        <div class="w-[90%] mx-auto xl:max-w-180">
            <a href="<?php bloginfo('url'); ?>/works"
            class="relative pl-[29px] text-[24px] italic tracking-[0.15em] font-garamond group">
                Works
                <span class="absolute left-[5px] top-1/2 w-3 h-[10px] -translate-y-1/2 rotate-180 bg-[url('../image/view-more-icon.svg')] bg-center bg-no-repeat bg-contain transition-all duration-[250ms] ease-in-out group-hover:left-0"></span>
            </a>
        </div>

        <article class="w-[90%] mx-auto xl:max-w-180 mt-5 lg:mt-10">
            <header class="space-y-2 lg:space-y-5">
                <?php get_template_part('templates/badge-new'); ?>
                <h1 class="text-lg lg:text-2xl font-medium tracking-[0.05em] leading-[1.4]"><?php the_title(); ?></h1>
                <div class="flex text-sm lg:text-base leading-[1.3]">
                    <div class="tracking-[0.05em]" style="font-family: 'Open Sans', sans-serif;"><?php echo get_the_date(); ?></div>
                    <div class="ml-2 lg:ml-3 pl-2 lg:pl-3 border-l border-[#D9D9D9]">
                        <?php
                            $tags = get_the_tags();
                            foreach ( $tags as $tag ) {
                                echo '<span>' . $tag->name . '</span>';
                            }
                        ?>
                    </div>
                </div>
            </header>
            <div class="my-5 lg:my-10 rounded-[10px] lg:rounded-[20px]">
                <?php the_post_thumbnail(); ?>
            </div>
            <div class="single-content"><?php the_content(); ?></div>
            <?php get_template_part( 'templates/parts/single-footer' ); ?>
        </article>
        <aside class="w-full mx-auto xl:max-w-180 mt-15 lg:mt-[150px]">
            <div class="flex justify-between px-[5%] lg:px-0">
                <?php
                get_template_part('templates/heading/heading-with-brackets', null, [
                    'heading_text' => 'お仕事',
                    'heading_tag'  => 'h2',
                ]);
                ?>
                <?php get_template_part('templates/common/link-button', null, [
                    'url' => home_url('/works'),
                    'label' => 'View All'
                ]); ?>
            </div>
            <?php get_template_part('templates/parts/related-posts'); ?>
        </aside>
    </div>
</main>
