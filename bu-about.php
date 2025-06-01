<?php
/*
Template Name: About Page
*/

get_header(); ?>

<div class="w-[90%] max-w-[720px] mx-auto mt-2 lg:mt-[65px]">
    <h1 class="lower-title">About</h1>

    <div class="youtube-iframe mt-5 lg:mt-[84px]">
        <?php the_field('about-page-youtube'); ?>
    </div>

    <div class="mt-10 lg:mt-[100px] lg:flex">
        <div class="lg:w-[290px] font-medium" style="letter-spacing:0.1em;">(　プロフィール　)</div>
        <div class="lg:w-[calc(100%-290px)] mt-5 lg:mt-0">
            <div class="text-[16px] lg:text-[20px] font-medium" style="letter-spacing:0.1em;">てらおかなつみ</div>
            <div class="mt-1 lg:mt-2 text-[12px] lg:text-[14px]" style="letter-spacing:0.1em;">Teraoka Natsumi</div>
            <div class="mt-5 lg:mt-7 w-[30px] lg:w-10 h-[1px] bg-[#333]"></div>
            <p class="mt-5 text-[14px] lg:text-base" style="line-height:1.75;">
                <?php the_field('about-page-profile-text'); ?>
            </p>
            <ul class="drawer-sns flex gap-2 mt-5 lg:mt-7">
                <li>
                    <a href="https://www.instagram.com/teraoka_natsumi/" class="block w-[32px] lg:w-[40px]" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/sns-instagram-icon.svg" alt="" loading="lazy" width="100%" height="100%">
                    </a>
                </li>
                <li>
                    <a href="https://x.com/teraoka_natsumi" class="block w-[32px] lg:w-[40px]" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/sns-x-icon.svg" alt="" loading="lazy" width="100%" height="100%">
                    </a>
                </li>
                <li>
                    <a href="https://www.youtube.com/channel/UCCGRvfriRBu1pLApl-35scw/featured?view_as=subscriber" class="block w-[32px] lg:w-[40px]" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/sns-youtube-icon.svg" alt="" loading="lazy" width="100%" height="100%">
                    </a>
                </li>
                <li>
                    <a href="https://www.tiktok.com/@teraoka_natsumi" class="block w-[32px] lg:w-[40px]" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/sns-tiktok-icon.svg" alt="" loading="lazy" width="100%" height="100%">
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="mt-[60px] lg:mt-[120px] lg:flex">
        <div class="lg:w-[290px] font-medium" style="letter-spacing:0.1em;">(　過去の主なお仕事　)</div>
        <div class="lg:w-[calc(100%-290px)] mt-5 lg:mt-0">
            <ul class="border-b border-solid border-[#D9D9D9]">
            <?php
                // SCF 繰り返しフィールド "works_list" を取得
                $works_list = SCF::get('works_list');

                // 繰り返しフィールドがある場合、ループで処理
                foreach ($works_list as $fields) {

                    // "works_year" と "works-content1" が空でない場合のみ表示
                    if (!empty($fields['works_year']) && !empty($fields['works-content1'])) {
            ?>
                <li class="about-ac">
                    <div class="ac-head py-[9px] lg:py-3 border-t border-solid border-[#D9D9D9] relative text-[14px] lg:text-[15px]">
                        <span class="text-[16px] lg:text-[18px]" style="font-family: 'Roboto', sans-serif; letter-spacing:0.1em; line-height:1.25">
                            <?php echo esc_html($fields['works_year']); ?>
                        </span>年
                        <span class="about-ac-icon"></span>
                    </div>
                    <div class="ac-body transition-[height] duration-250 ease-out">
                        <ul class="pt-[10px] lg:pt-2 pb-5 lg:pb-9">
                            <?php if (!empty($fields['works-content1'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['works-content1']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['works-content2'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['works-content2']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['works-content3'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['works-content3']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['works-content4'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['works-content4']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['works-content5'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['works-content5']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['works-content6'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['works-content6']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['works-content7'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['works-content7']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['works-content8'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['works-content8']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['works-content9'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['works-content9']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['works-content10'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['works-content10']); ?></li>
                            <?php } ?>

                        </ul>
                    </div>
                </li>
                <?php
                        }
                    }
                ?>
            </ul>
            <div class="mt-4 lg:mt-6 text-right">
                <a href="<?php echo get_template_directory_uri(); ?>/works" class="link">View All</a>
            </div>
        </div>
    </div>

    <div class="mt-[60px] lg:mt-[120px] lg:flex">
        <div class="lg:w-[290px] font-medium" style="letter-spacing:0.1em;">(　過去の主なイベント　)</div>
        <div class="lg:w-[calc(100%-290px)] mt-5 lg:mt-0">
            <ul class="border-b border-solid border-[#D9D9D9]">
            <?php
                // SCF 繰り返しフィールド "works_list" を取得
                $about_event = SCF::get('about_event');

                // 繰り返しフィールドがある場合、ループで処理
                foreach ($about_event as $fields) {

                    if (!empty($fields['about-event-year']) && !empty($fields['about-event-content1'])) {
            ?>
                <li class="event-ac">
                    <div class="ac-head py-[9px] lg:py-3 border-t border-solid border-[#D9D9D9] relative text-sm lg:text-[15px]">
                        <span class="text-base lg:text-[18px]" style="font-family: 'Roboto', sans-serif; letter-spacing:0.1em; line-height:1.25">
                            <?php echo esc_html($fields['about-event-year']); ?>
                        </span>年
                        <span class="about-ac-icon"></span>
                    </div>
                    <div class="ac-body transition-[height] duration-250 ease-out">
                        <ul class="pt-4 lg:pt-2 pb-5 lg:pb-9">
                            <?php if (!empty($fields['about-event-content1'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['about-event-content1']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['about-event-content2'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['about-event-content3']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['about-event-content3'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['about-event-content3']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['about-event-content4'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['about-event-content4']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['about-event-content5'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['about-event-content5']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['about-event-content6'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['about-event-content6']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['about-event-content7'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['about-event-content7']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['about-event-content8'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['about-event-content8']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['about-event-content9'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['about-event-content9']); ?></li>
                            <?php } ?>

                            <?php if (!empty($fields['about-event-content10'])) { ?>
                            <li class="text-[14px] lg:text-base mt-2 lg:mt-3 pl-6 lg:pl-8 relative"><?php echo esc_html($fields['about-event-content10']); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
                <?php
                        }
                    }
                ?>
            </ul>
            <div class="mt-4 lg:mt-6 text-right">
                <a href="<?php echo get_template_directory_uri(); ?>/event" class="link">View All</a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
