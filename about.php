<?php
/*
Template Name: About Page
*/

get_header(); ?>
<main data-barba="container" data-barba-namespace="page">
    <div class="w-[90%] mx-auto xl:max-w-180 mt-16">
        <article class="">
            <header class="">
                <h1 class="text-center lg:text-left text-[32px] lg:text-[45px] font-garamond italic tracking-[0.15em] leading-[1.25]">About</h1>
            </header>
            <div class="youtube mt-7 lg:mt-16 w-full lg:max-w-180 aspect-[16/9]">
                <?php the_field('about-page-youtube'); ?>
            </div>
            <div class="mt-10 lg:mt-25 lg:flex">
                <div class="lg:w-[290px]">
                    <?php
                        get_template_part('templates/heading/heading-with-brackets', null, [
                            'heading_text' => 'プロフィール',
                            'heading_tag'  => 'h2',
                        ]);
                    ?>
                </div>
                <div class="lg:w-[calc(100%-290px)] mt-5 lg:mt-0">
                    <div class="text-base lg:text-xl font-medium" style="letter-spacing:0.1em;">てらおかなつみ</div>
                    <div class="mt-1 lg:mt-2 text-xs lg:text-sm" style="letter-spacing:0.1em;">Teraoka Natsumi</div>
                    <div class="mt-5 lg:mt-7 w-[30px] lg:w-10 h-[1px] bg-[#333]"></div>
                    <p class="mt-5 text-sm lg:text-base" style="line-height:1.75;">
                        <?php the_field('about-page-profile-text'); ?>
                    </p>
                    <?php
                    get_template_part('templates/common/sns-links', null, [
                    'bg_base' => 'bg-white',
                    'hover_bg' => 'hover:bg-[#F5F5F5]',
                    'class' => 'is-about'
                    ]);
                    ?>

                </div>
            </div>

            <div class="mt-15 lg:mt-30 lg:flex">
                <!-- <div class="lg:w-[290px] font-medium" style="letter-spacing:0.1em;">(　過去の主なお仕事　)</div> -->
                <div class="lg:w-[290px]">
                    <?php
                        get_template_part('templates/heading/heading-with-brackets', null, [
                            'heading_text' => '過去の主なお仕事',
                            'heading_tag'  => 'h2',
                        ]);
                    ?>
                </div>
                <div class="lg:w-[calc(100%-290px)] mt-5 lg:mt-0">
                    <?php
                    $works_list = SCF::get('works_list');
                    $grouped_works = [];

                    foreach ($works_list as $fields) {
                        $year = $fields['works_year'];
                        $contents = [];

                        for ($i = 1; $i <= 10; $i++) {
                            $key = 'works-content' . $i;
                            if (!empty($fields[$key])) {
                                $contents[] = $fields[$key];
                            }
                        }

                        if (!empty($year) && !empty($contents)) {
                            if (!isset($grouped_works[$year])) {
                                $grouped_works[$year] = [];
                            }
                            $grouped_works[$year] = array_merge($grouped_works[$year], $contents);
                        }
                    }

                    krsort($grouped_works); // 年を新しい順にソート
                    $is_first = true;
                    ?>

                    <div class="accordion-group-1 space-y-0">
                        <?php
                        $is_first = true;
                        foreach ($grouped_works as $year => $contents):
                            $is_open = $is_first;
                            $is_first = false;
                            $border_top_class = $is_open ? 'border-t' : ''; // 最初の要素だけborder-topを追加
                            $rotate_class = $is_open ? 'rotate-180' : ''; // 開いている場合はアイコンを回転
                        ?>
                            <div class="<?php echo $border_top_class; ?> border-b border-gray-300">
                                <button
                                    class="w-full flex justify-between items-center px-4 py-3 text-left text-lg font-semibold transition-colors js-accordion-button <?php echo $is_open ? 'open' : ''; ?>"
                                    type="button"
                                    aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                                >
                                    <span class="tracking-[0.1em]"  style="font-family: 'Open Sans', sans-serif;"><?php echo esc_html($year); ?></span>
                                    <span class="transition-colors duration-250 hover:bg-[#f5f5f5] rounded-full p-1 js-accordion-icon">
                                        <svg class="w-5 h-5 icon-plus <?php echo $is_open ? 'hidden' : ''; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        <svg class="w-5 h-5 icon-minus <?php echo $is_open ? '' : 'hidden'; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                        </svg>
                                    </span>
                                </button>

                                <div class="js-accordion-content px-4 pt-3 pb-8 <?php echo $is_open ? '' : 'hidden'; ?> bg-white ">
                                    <ul class="list-disc pl-5 space-y-2 text-sm">
                                        <?php foreach ($contents as $content): ?>
                                            <li><?php echo esc_html($content); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="mt-4 lg:mt-6 text-right">
                        <?php get_template_part('templates/common/link-button', null, [
                            'url' => home_url('/works'),
                            'label' => 'View All'
                        ]); ?>
                    </div>
                </div>
            </div>

            <div class="mt-15 lg:mt-30 lg:flex">
                <!-- <div class="lg:w-[290px] font-medium" style="letter-spacing:0.1em;">(　過去の主なイベント　)</div> -->
                <div class="lg:w-[290px]">
                    <?php
                        get_template_part('templates/heading/heading-with-brackets', null, [
                            'heading_text' => '過去の主なイベント',
                            'heading_tag'  => 'h2',
                        ]);
                    ?>
                </div>
                <div class="lg:w-[calc(100%-290px)] mt-5 lg:mt-0">
                    <?php
                    $works_list = SCF::get('about_event');
                    $grouped_works = [];

                    foreach ($works_list as $fields) {
                        $year = $fields['about-event-year'];
                        $contents = [];

                        for ($i = 1; $i <= 10; $i++) {
                            $key = 'about-event-content' . $i;
                            if (!empty($fields[$key])) {
                                $contents[] = $fields[$key];
                            }
                        }

                        if (!empty($year) && !empty($contents)) {
                            if (!isset($grouped_works[$year])) {
                                $grouped_works[$year] = [];
                            }
                            $grouped_works[$year] = array_merge($grouped_works[$year], $contents);
                        }
                    }

                    krsort($grouped_works); // 年を新しい順にソート
                    $is_first = true;
                    ?>

                    <div class="accordion-group-2 space-y-0">
                        <?php foreach ($grouped_works as $year => $contents): ?>
                            <?php
                            $is_open = $is_first;
                            $is_first = false;
                            $border_top_class = $is_open ? 'border-t' : '';
                            $rotate_class = $is_open ? 'rotate-180' : '';
                            ?>
                            <div class="<?php echo $border_top_class; ?> border-b border-gray-300">
                                <button
                                    class="w-full flex justify-between items-center px-4 py-3 text-left text-lg font-semibold transition-colors js-accordion-button <?php echo $is_open ? 'open' : ''; ?>"
                                    type="button"
                                    aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                                >
                                    <span class="tracking-[0.1em]"  style="font-family: 'Open Sans', sans-serif;"><?php echo esc_html($year); ?></span>
                                    <span class="transition-colors duration-250 hover:bg-[#f5f5f5] rounded-full p-1 js-accordion-icon">
                                        <svg class="w-5 h-5 icon-plus <?php echo $is_open ? 'hidden' : ''; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        <svg class="w-5 h-5 icon-minus <?php echo $is_open ? '' : 'hidden'; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                        </svg>
                                    </span>
                                </button>

                                <div class="js-accordion-content px-4 pt-3 pb-8 <?php echo $is_open ? '' : 'hidden'; ?> bg-white ">
                                    <ul class="list-disc pl-5 space-y-2 text-sm">
                                        <?php foreach ($contents as $content): ?>
                                            <li><?php echo esc_html($content); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-4 lg:mt-6 text-right">
                        <?php get_template_part('templates/common/link-button', null, [
                            'url' => home_url('/events'),
                            'label' => 'View All'
                        ]); ?>
                    </div>
                </div>
            </div>
        </article>
    </div>
</main>

<?php get_footer(); ?>
