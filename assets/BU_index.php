<?php get_header(); ?>

<!-- mv -->
<div class="mv h-[100vh] bg-[#FEF6E9] flex justify-center items-center relative">
    <div class="w-[80%] md:w-[40%]">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/mv-img.webp" alt="おおした動物病院">
    </div>

    <div class="absolute bottom-0 left-0 w-full">
        <div class="flex items-center gap-[20px] lg:gap-8 bg-[#FFF] rounded-tr-[20px] py-2 lg:py-[10px] pl-[10px] lg:pl-[20px] pr-5 lg:pr-4 px-0 w-[95%] lg:w-[40%] max-w-[506px]">
            <?php
                $pickupList = array(
                    'category_name' => 'news',
                    'post_type' => 'post',
                    'posts_per_page' => 1,
                );
                ?>
            <?php query_posts($pickupList); ?>
            <?php if (have_posts()) : while(have_posts()) : the_post(); ?>
            <a href="<?php the_permalink(); ?>" class="mv-news py-[1px] lg:py-[10px] px-[10px] rounded-[8px] flex gap-5 lg:gap-8 items-center">
                <div class="font-semibold text-center" style="font-family: 'Nunito', serif;">
                    <div class="text-[8px] lg:text-[10px] text-center border-b border-[#D9D9D9] mb-1"><?php echo get_the_date('Y'); ?></div>
                    <div class="mt-[2px] text-[14px] lg:text-[18px]"><?php echo get_the_date('m.d'); ?></div>
                </div>
                <div class="post-title max-w-[344px] w-[100%] text-[14px]" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);"><?php the_title(); ?></div>
            </a>
            <?php endwhile; endif; wp_reset_query(); ?>
            <div class="flex flex-col items-center justify-center">
                <button class="mv-arrow-previous"></button>
                <span class="block w-full h-[1px] bg-[#D9D9D9] my-1 lg:my-2"></span>
                <button class="mv-arrow-next"></button>
            </div>
        </div>
        <div class="sp-mv-reserved hidden">
            <div class="flex justify-center bg-[#FFF] px-[5vw] gap-3 py-[10px]">
                <div class="" style="width: calc(50% - 12px * 1 / 2);"><a href="<?php bloginfo('url'); ?>" class="mv-reserved-btn web flex gap-2 justify-center items-center h-[48px] rounded-[12px] text-[#FFF] bg-[#D68100] font-bold text-[14px]" target="_blank"><span><img src="<?php echo get_template_directory_uri(); ?>/assets/image/drawer-reserved-icon.svg" alt="予約のアイコン"></span>24時間WEB予約</a></div>
                <div class="" style="width: calc(50% - 12px * 1 / 2);"><a href="tel:078-731-0001" class="mv-reserved-btn tel flex gap-2 justify-center items-center h-[48px] rounded-[12px] text-[#D68100] bg-[#FFF] outline outline-2 outline-offset-[-2px] outline-[#FDECD2] font-bold text-[14px]" target="_blank"><span><img src="<?php echo get_template_directory_uri(); ?>/assets/image/drawer-tel-icon.svg" alt="電話のアイコン"></span>問合せ</a></div>
            </div>
        </div>
    </div>

    <div id="clinic-schedule">
        <p>午前の診療時間: <span id="morning-schedule">読み込み中...</span></p>
        <p>午後の診療時間: <span id="afternoon-schedule">読み込み中...</span></p>
    </div>


    <div class="mv-bottom-right absolute bottom-5 right-10">
        <div class="test flex items-center bg-[#FFF] rounded-[20px] py-3 px-4">
            <div class="w-[45%] text-[#D68100] font-bold  border-r border-[#D68100] mr-4 py-1" style="line-height:1.5;">
                <div>本日の診療情報</div>
                <div class="text-[12px]"><span style="font-family: 'Nunito', serif;"><?php echo date('m'); ?></span>月<span style="font-family: 'Nunito', serif;"><?php echo date('d'); ?></span>日 <span style="font-family: 'Nunito', serif;"><?php echo date('H'); ?></span>時<span style="font-family: 'Nunito', serif;"><?php echo date('i'); ?></span>分</div>
            </div>
            <div class="w-[54%] text-[14px] font-bold grow" style="font-family: 'Nunito', serif;">
            <?php
global $wpdb;
$appointments = $wpdb->get_results("
    SELECT * FROM {$wpdb->prefix}bookly_appointments
    WHERE DATE(start_date) = CURDATE()  -- 本日の日付と一致するデータを取得
    ORDER BY start_date ASC
");

echo '<ul class="appointment-schedule">';

// 診療時間帯を設定
$morning_start = "09:00";
$morning_end = "11:30";
$afternoon_start = "15:30";
$afternoon_end = "18:00";

// 診療時間帯の初期設定と確認
$has_morning_appointment = false;
$has_afternoon_appointment = false;

// 予約をループして午前と午後の診療状況を確認
foreach ($appointments as $appointment) {
    $appointment_time = date('H:i', strtotime($appointment->start_date));
    $appointment_end_time = date('H:i', strtotime($appointment->end_date));

    // 午前の診療予約があるかどうか確認
    if ($appointment_time >= $morning_start && $appointment_time <= $morning_end) {
        $has_morning_appointment = true;
    }

    // 午後の診療予約がある場合、診療時間を更新
    if ($appointment_time >= $afternoon_start && $appointment_time <= $afternoon_end) {
        $has_afternoon_appointment = true;
        $afternoon_start = $appointment_time;
        $afternoon_end = $appointment_end_time;
    }
}

// 診療状況を表示
if (!$has_morning_appointment && !$has_afternoon_appointment) {
    echo '<li class="appointment-time flex font-ja">本日は休診日です</li>';
} else {
    // 午前の診療状況を表示
    if ($has_morning_appointment) {
        echo '<li class="appointment-time flex items-center">';
        echo '<span class="time-range">' . esc_html($morning_start . ' - ' . $morning_end) . '</span>';
        echo '<span class="status flex justify-center items-center w-[37px] bg-[#A8E8FF] rounded-[11px] text-[10px] font-medium ml-auto font-ja h-[18px]">診療</span>';
        echo '</li>';
    } else {
        echo '<li class="appointment-time flex font-ja">午前は休診です</li>';
    }

    // 午後の診療状況を表示
    if ($has_afternoon_appointment) {
        echo '<li class="appointment-time flex items-center">';
        echo '<span class="time-range">' . esc_html($afternoon_start . ' - ' . $afternoon_end) . '</span>';
        echo '<span class="status flex justify-center items-center w-[37px] bg-[#A8E8FF] rounded-[11px] text-[10px] font-medium ml-auto font-ja h-[18px]">診療</span>';
        echo '</li>';
    } else {
        echo '<li class="appointment-time flex font-ja">午後は休診です</li>';
    }
}

echo '</ul>';
?>
            </div>
        </div>
        <ul class="mt-5 flex gap-4">
            <li>
                <a href="<?php bloginfo('url'); ?>" class="mv-reserved-btn web flex flex-col justify-center items-center gap-2 w-[160px] h-[80px] rounded-[20px] text-[#FFF] bg-[#D68100] font-bold" target="_blank">
                    <div class="text-center">
                        <div class="w-5 mb-1 mx-auto"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/drawer-reserved-icon.svg" alt="予約のアイコン"></div>
                        <div>24時間WEB予約</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="tel:078-731-0001" class="mv-reserved-btn tel flex flex-col justify-center items-center gap-2 w-[160px] h-[80px] rounded-[20px] bg-[#FFF] outline outline-2 outline-offset-[-2px] outline-[#FDECD2] font-bold" target="_blank">
                    <div class="text-center">
                        <div class="w-5 mb-1 mx-auto"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/drawer-tel-icon.svg" alt="電話のアイコン"></div>
                        <div class="text-[#D68100]">問合せ</div>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="second-header-show"></div>

<!-- fixed-btn -->
<div class="fixed-btn fixed top-[50%] right-0 z-[100] pc-only">
    <ul>
        <li>
            <a href="<?php bloginfo('url'); ?>" class="web w-[60px] h-[200px] flex items-center justify-center rounded-tl-[12px] rounded-bl-[12px] text-[#FFF] bg-[#D68100] font-bold px-2 py-2" style="writing-mode: vertical-rl; text-orientation: upright;" target="_blank">
                <span class="w-5"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/drawer-reserved-icon.svg" alt="予約のアイコン"></span>
                <span class="mt-1" style="text-combine-upright: all;">24</span>時間WEB予約
            </a>
        </li>
        <li class="mt-2">
            <a href="tel:078-731-0001" class="tel w-[60px] h-[200px] flex items-center justify-center rounded-tl-[12px] rounded-bl-[12px] text-[#D68100] bg-[#FFF] font-bold px-2 py-2" style="writing-mode: vertical-rl; text-orientation: upright;" target="_blank">
                <span class="w-5 mb-1"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/drawer-tel-icon.svg" alt="電話のアイコン"></span>
                問合せ
            </a>
        </li>
    </ul>
</div>

<!-- top-description -->
<section class="mt-[110px] lg:mt-[130px]">
    <div class="mx-[5vw] lg:mx-[13vw]">
        <div class="flex flex-col lg:flex-row gap-[60px] lg:gap-[7.5vw] items-center">
            <div class="w-[100%] md:w-[70%] lg:w-[33.5vw] px-[5vw] lg:px-0 animated__fadeIn step2-img">
                <div style="aspect-ratio: 4 / 5;">
                    <div class="block bg-[#EFF3FA] w-[100%] relative overflow-hidden" style="border-top-left-radius: 280px; border-top-right-radius: 280px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; aspect-ratio: 4 / 5;">
                        <div class="absolute top-[50%] left-[50%] w-[207px] md:w-[70%] parallax" style="transform: translateX(-50%) translateY(-50%)">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/image/top-description.webp" alt="猫のイラスト" loading="lazy" width="100%" height="100%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-[100%] lg:w-[33vw] lg:pt-[40px]" style="width:fit-content">
                <div class="text-[28px] lg:text-[32px] font-bold animated__fadeIn step1" style="letter-spacing:0.15em; line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">
                    <div class="block pl-2 relative mx-auto lg:mx-0" style="width:fit-content;">愛情ごと、<span class="block w-full h-[10px] bg-[#FDECD2] rounded-[5px] absolute bottom-0 left-0 z-[-1]"></span></div>
                    <div class="block px-2 relative mx-auto lg:mx-0" style="width:fit-content;">おあずかりします。<span class="block w-full h-[10px] bg-[#FDECD2] rounded-[5px] absolute bottom-0 left-0 z-[-1]"></span></div>
                </div>
                <p class="mt-8 text-base animated__fadeIn step2 tracking-wider" style="line-height:1.75; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">
                小さないのち。大切な家族。<br>
                そんな存在を守りたいと思い、獣医になりました。<br>
                だから私たちは、飼い主さんから<br>
                ワンちゃんやネコちゃんをお預かりするとき、<br>
                注がれてきた愛情ごと、大切に受けとめるようにしています。<br>
                この子にとって、飼い主さんにとって、<br>
                一番いいケアはなんだろう。<br>
                小さな家族を守るチームの一員として、<br>
                一緒に考えたいと思っています。
                </p>
                <div class="top-description-name w-[285px] lg:w-[326px] flex justify-center mt-3 lg:mt-6 gap-4 items-center mx-auto lg:ml-0 animated__fadeIn step3 relative">
                    <p class="mt-[40px] font-bold text-base lg:text-[20px] tracking-widest"><span class="text-[10px] lg:text-[12px] font-semibold mr-4">院長</span>大下修平</p>
                    <div class="w-[91px] lg:w-[104px]"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/doctor-oshita.webp" alt="院長 大下修平" loading="lazy" width="100%" height="100%"></div>
                    <!-- <div class="svg-wrapper relative">

                    </div> -->
                    <!-- <svg class="absolute bottom-0 left-0 w-full h-full" width="400" height="400" viewBox="0 0 400 400">
                            <circle cx="200" cy="200" r="200" fill="#ccc" />
                        </svg> -->
                    <svg class="absolute top-[45%] left-0 w-full h-full" width="326" height="36" viewBox="0 0 326 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.47682 0C6.41136 10.9608 15.2387 20.2858 26.2938 21.3291C34.208 22.071 41.8007 18.6649 49.3386 15.2835C57.7476 11.5112 66.0882 7.76966 74.7305 9.85172C79.8388 11.0819 84.1766 14.2072 88.535 17.3472C91.953 19.8097 95.3836 22.2813 99.2083 23.8551C111.919 29.1033 126.139 23.6467 138.873 18.4475C151.607 13.2483 166.157 8.30669 178.513 14.4132C180.605 15.4482 182.544 16.7701 184.485 18.093C186.178 19.2465 187.872 20.4006 189.667 21.3659C203.793 28.9807 221.078 23.708 235.428 16.5714C235.713 16.4301 235.998 16.2878 236.284 16.1453C240.364 14.1112 244.552 12.0231 249.07 11.8627C252.277 11.7401 255.354 12.5984 258.373 13.7879C261.603 15.0509 264.516 17.0496 267.121 19.4039C270.423 22.3959 274.208 24.7012 278.476 25.5963C290.503 28.0978 301.951 18.5824 308.578 7.85299C310.146 5.31966 311.556 2.69325 312.845 0H326V36H0V0H2.47682Z" fill="#fff"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- hours-news -->
<section class="hours-news-sec mt-[120px] lg:mt-[200px] pt-[10px] pb-[80px] lg:pt-[100px] lg:pb-[120px] bg-[#F4F8E9] animated__fadeIn hours-news-step1">
    <div class="inner flex flex-col lg:flex-row">
        <div class="w-full lg:w-[45%] hours-sec">
            <h2 class="section-title animated__fadeIn hours-news-step2">HOURS<span class="mt-1 lg:mt-0">診療時間</span></h2>
            <div class="table-wrapper mx-auto max-w-[500px] lg:m-0 animated__fadeIn hours-news-step3">
                <span class="block mt-[36px] lg:mt-[60px] rounded-[10px] border border-[#D68100] bg-[#D68100] overflow-hidden">
                    <table class="open-hour-table text-[14px] lg:text-[16px] w-full tracking-wider" style="line-height:1.2; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">
                        <tbody>
                            <tr class="text-[#FFF] text-bold">
                                <th><span class="pc-only"></span></th>
                                <th>月</th>
                                <th>火</th>
                                <th>水</th>
                                <th>木</th>
                                <th>金</th>
                                <th>土</th>
                                <th>日</th>
                                <th>祝</th>
                            </tr>
                            <tr class="text-[#D68100] font-bold bg-[#FFF]">
                                <td class="text-[#333]" style="font-family: 'Nunito', serif;">9:00 - 11:30</td>
                                <td>●</td>
                                <td>●</td>
                                <td>●</td>
                                <td>●</td>
                                <td>●</td>
                                <td>●</td>
                                <td>●</td>
                                <td>●</td>
                            </tr>
                            <tr class="text-[#D68100] font-bold bg-[#FFF] border-t border-[#D9D9D9]">
                                <td class="text-[#333]" style="font-family: 'Nunito', serif;">15:30 - 18:00</td>
                                <td>●</td>
                                <td>●</td>
                                <td>●</td>
                                <td>●</td>
                                <td>●</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </span>
                <div class="text-[14px] lg:text-[16px] mt-3 tracking-wider" style="font-family: 'Nunito', serif;"><span class="text-[#D68100] font-medium" style="font-family: 'Zen Maru Gothic', serif;">（受付）</span>9:00 - 11:30 / 15:30 - 18:00</div>
                <div class="text-[14px] lg:text-[16px] tracking-wider"><span class="text-[#D68100] font-medium">（休診）</span>土・日・祝日の午後</div>
            </div>
        </div>
        <div class="w-full mt-[100px] lg:mt-0 lg:w-[52%] ml-auto overflow-hidden">
            <div class="flex justify-center items-center">
                <h2 class="section-title animated__fadeIn hours-news-step4">NEWS<span class="mt-1 lg:mt-0">お知らせ</span></h2>
                <div class="ml-auto pc-only animated__fadeIn hours-news-step5"><a href="<?php bloginfo('url');?>/news" class="c-btn">お知らせ一覧</a></div>
            </div>
            <ul class="news-post-items mt-[36px] lg:mt-[60px] border-t border-[#D68100] max-w-[500px] lg:max-w-full mx-auto lg:ml-auto animated__fadeIn hours-news-step5-list">
                <?php
                $pickupList = array(
                    'category_name' => 'news',
                    'post_type' => 'post',
                    'posts_per_page' => 4,
                );
                ?>
            <?php query_posts($pickupList); ?>
            <?php if (have_posts()) : while(have_posts()) : the_post(); ?>
            <li class="w-[100%] border-b border-[#D68100] py-2 lg:py-[10px]">
                <a href="<?php the_permalink(); ?>" class="flex items-center gap-5 lg:gap-[32px] py-[1px] px-[10px] rounded-[8px]">
                    <div class="font-semibold" style="font-family: 'Nunito', serif;">
                        <div class="text-[8px] lg:text-[10px] text-center border-b border-[#D9D9D9] mb-1"><?php echo get_the_date('Y'); ?></div>
                        <div class="text-[14px] lg:text-[18px]" style="line-height:1.2; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);"><?php echo get_the_date('m.d'); ?></div>
                    </div>
                    <div class="post-title text-[14px] lg:text-base" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);"><?php the_title(); ?></div>
                </a>
            </li>
            <?php endwhile; endif; wp_reset_query(); ?>
        </ul>
        <div class="mt-[28px] sp-only animated__fadeIn hours-news-step6"><a href="<?php bloginfo('url');?>/news" class="c-btn mx-auto">お知らせ一覧</a></div>
        </div>
    </div>
    <div class="dog-illustration z-[100] animated__fadeIn hours-news-step7"></div>
</section>

<!-- medical-course -->
<section class="medical-course mt-[180px] animated__fadeIn-base">
    <div class="mx-[5.5vw]">
        <div class="text-center">
            <h2 class="section-title text-center inline-block">MEDICAL <br class="sp-only" style="line-height:0;">COURSE<span class="block mt-1 lg:mt-0">診療案内</span></h2>
        </div>
        <ul class="flex flex-col lg:flex-row gap-[12px] lg:gap-[2.5vw] mt-[28px] lg:mt-[60px]">
            <li class="medical-course-item w-[100%] lg:w-[15.8vw] max-w-[500px] mx-auto border border-[#D9D9D9] rounded-[12px] lg:rounded-[20px] flex flex-row lg:flex-col overflow-hidden animated__fadeIn-row --delay0">
                <div class="medical-course-icon w-[100px] md:w-[160px] lg:w-full flex items-center justify-center">
                    <img class="w-[57px] lg:w-[118px]" src="<?php echo get_template_directory_uri(); ?>/assets/image/medical-icon1.svg" alt="聴診器のアイコン" loading="lazy" width="100%" height="100%">
                </div>
                <div class="medical-course-item-text py-5 lg:py-[24px] px-5 flex flex-col justify-center">
                    <h3 class="text-[20px] font-bold lg:text-center text-[#D68100] leading-none" style="letter-spacing:0.2em;">一般診療</h3>
                    <p class="mt-3 lg:mt-4 text-[14px] tracking-wider" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">ワンちゃん・ネコちゃんの気になることを何でもご相談ください。</p>
                </div>
            </li>
            <li class="medical-course-item w-[100%] lg:w-[15.8vw] max-w-[500px] mx-auto border border-[#D9D9D9] rounded-[12px] lg:rounded-[20px] flex flex-row lg:flex-col lg:h-auto overflow-hidden animated__fadeIn-row --delay1">
                <div class="medical-course-icon w-[100px] md:w-[160px] lg:w-full flex items-center justify-center">
                    <img class="w-[67px] lg:w-[138px]" src="<?php echo get_template_directory_uri(); ?>/assets/image/medical-icon2.svg" alt="注射器のアイコン" loading="lazy" width="100%" height="100%">
                </div>
                <div class="medical-course-item-text py-5 lg:py-[24px] px-5 flex flex-col justify-center">
                    <h3 class="text-[20px] font-bold lg:text-center text-[#D68100] leading-none" style="letter-spacing:0.2em;">予防医療</h3>
                    <p class="mt-3 lg:mt-4 text-[14px] tracking-wider" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">フィラリア、ノミ・ダニ、ワクチンなどの予防接種を行っています。</p>
                </div>
            </li>
            <li class="medical-course-item w-[100%] lg:w-[15.8vw] max-w-[500px] mx-auto border border-[#D9D9D9] rounded-[12px] lg:rounded-[20px] flex flex-row lg:flex-col lg:h-auto overflow-hidden animated__fadeIn-row --delay2">
                <div class="medical-course-icon w-[100px] md:w-[160px] lg:w-full flex items-center justify-center">
                    <img class="w-[71px] lg:w-[148px]" src="<?php echo get_template_directory_uri(); ?>/assets/image/medical-icon3.svg" alt="性別記号のアイコン" loading="lazy" width="100%" height="100%">
                </div>
                <div class="medical-course-item-text py-5 lg:py-[24px] px-5 flex flex-col justify-center">
                    <h3 class="text-[20px] font-bold lg:text-center text-[#D68100] leading-none" style="letter-spacing:0.2em;">避妊・去勢</h3>
                    <p class="mt-3 lg:mt-4 text-[14px] tracking-wider" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">望まない妊娠を防ぐとともに、様々な病気の発症リスクを抑えます。</p>
                </div>
            </li>
            <li class="medical-course-item w-[100%] lg:w-[15.8vw] max-w-[500px] mx-auto border border-[#D9D9D9] rounded-[12px] lg:rounded-[20px] flex flex-row lg:flex-col lg:h-auto overflow-hidden animated__fadeIn-row --delay3">
                <div class="medical-course-icon w-[100px] md:w-[160px] lg:w-full flex items-center justify-center">
                    <img class="w-[50px] lg:w-[103px]" src="<?php echo get_template_directory_uri(); ?>/assets/image/medical-icon4.svg" alt="カルテのアイコン" loading="lazy" width="100%" height="100%">
                </div>
                <div class="medical-course-item-text py-5 lg:py-[24px] px-5 flex flex-col justify-center">
                    <h3 class="text-[20px] font-bold lg:text-center text-[#D68100] leading-none" style="letter-spacing:0.2em;">健康診断</h3>
                    <p class="mt-3 lg:mt-4 text-[14px] tracking-wider" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">健康維持や病気の早期発見のために、定期的な健康診断がお勧めです。</p>
                </div>
            </li>
            <li class="medical-course-item w-[100%] lg:w-[15.8vw] max-w-[500px] mx-auto border border-[#D9D9D9] rounded-[12px] lg:rounded-[20px] flex flex-row lg:flex-col lg:h-auto overflow-hidden animated__fadeIn-row --delay4">
                <div class="medical-course-icon w-[100px] md:w-[160px] lg:w-full flex items-center justify-center">
                    <img class="w-[44px] lg:w-[92px]" src="<?php echo get_template_directory_uri(); ?>/assets/image/medical-icon5.svg" alt="メスのアイコン" loading="lazy" width="100%" height="100%">
                </div>
                <div class="medical-course-item-text py-5 lg:py-[24px] px-5 flex flex-col justify-center">
                    <h3 class="text-[20px] font-bold lg:text-center text-[#D68100] leading-none" style="letter-spacing:0.2em;">手　術</h3>
                    <p class="mt-3 lg:mt-4 text-[14px] tracking-wider" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">避妊・去勢手術から、各種の外科手術にも日常的に対応しています。</p>
                </div>
            </li>
        </ul>
    </div>
</section>

<!-- top-about -->
<section class="top-about-sec mt-[100px] lg:mt-[200px]">
    <div class="inner flex flex-col lg:flex-row gap-[32px] lg:gap-[5vw] items-center overflow-hidden">
        <div class="top-about-left lg:max-w-full mx-auto lg:ml-0">
            <h2 class="section-title animated__fadeIn top-about-step1">ABOUT<span class="mt-1 lg:mt-0">病院紹介</span></h2>
            <div class="mt-[36px] ml-auto sp-only animated__fadeIn top-about-step2" style="aspect-ratio: 3 / 2;">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/image/top-about-img.webp" alt="おおした動物病院 外観写真" loading="lazy" width="100%" height="100%">
            </div>
            <div class="flex mt-[32px] lg:mt-[60px] items-center flex-col lg:flex-row flex-wrap animated__fadeIn top-about-step2">
                <div class="w-[219px]">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/image/logo-text.svg" alt="おおした動物病院" loading="lazy" width="100%" height="100%">
                </div>
                <div class="text-base lg:text-[20px] font-bold mt-2 lg:ml-[10px] lg:mt-0 tracking-widest">について</div>
            </div>
            <p class="mt-[28px] lg:mt-[30px] animated__fadeIn top-about-step3 tracking-wider" style="line-height:1.75; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">
            私たち「おおした動物病院」は、長年地域に愛されてきた「わたなべ動物病院」の渡邊正明先生が引退されるのを機に、建物と設備を承継する形で2024年12月に新しくスタートいたしました。<br>
            私たちは、「わたなべ動物病院」の信頼と実績を大切に引き継ぎ、獣医療の最新技術や知識を積極的に取り入れながら、より充実した医療サービスを提供してまいります。<br>
            スタッフ一同、地域の皆様とワンちゃんネコちゃんに寄り添い、心を込めて診療にあたりますので、どうぞお気軽にご来院ください。
            </p>
        </div>
        <div class="top-about-right pc-only animated__fadeIn top-about-step3-img">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/image/top-about-img.webp" alt="おおした動物病院 外観写真" loading="lazy" width="100%" height="100%">
        </div>
    </div>
</section>

<!-- access -->
<section class="access-sec mt-[160px] lg:mt-[250px] bg-[#EFF3FA] pt-[70px] lg:pt-[100px] pb-[50px] lg:pb-[130px] animated__fadeIn-base">
    <div class="cat-illustration z-[100]"></div>
    <div class="inner">
        <h2 class="section-title sp-only animated__fadeIn access-step2">ACCESS<span class="mt-1 lg:mt-0">アクセス</span></h2>
        <div class="flex flex-col lg:flex-row lg:ml-0 gap-[5vw] items-center">
            <div class="google-map w-[100%] lg:w-[45vw] mt-[36px] lg:mt-0 animated__fadeIn access-step3-map">
                <div class="google-map-wrapper rounded-[20px] lg:rounded-[40px] overflow-hidden" style="aspect-ratio: 3 / 2;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6564.67058348097!2d135.12455731091572!3d34.64623437282625!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6000850dca3b4e69%3A0x20e3e7e01fae2cef!2z44KP44Gf44Gq44G55YuV54mp55eF6Zmi!5e0!3m2!1sja!2sjp!4v1729835214720!5m2!1sja!2sjp" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <div class="w-[100%] lg:w-[30vw] ml-auto">
                <h2 class="section-title pc-only animated__fadeIn access-step2">ACCESS<span>アクセス</span></h2>
                <div class="animated__fadeIn access-step3">
                    <dl class="mt-0 lg:mt-[60px] flex flex-wrap items-baseline">
                        <dt class="text-[10px] lg:text-[12px] w-[44px] lg:w-[48px] border border-[#333] rounded-[10px] text-center h-[20px]" style="line-height:18px; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">住 所</dt>
                        <dd class="ml-4 lg:ml-[30px] text-[14px] lg:text-base tracking-wider" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">〒654-0047　兵庫県神戸市須磨区磯馴町5-2-22</dd>
                        <dt class="mt-2 text-[10px] lg:text-[12px] w-[44px] lg:w-[48px] h-fit-content border border-[#333] rounded-[10px] text-center h-[20px]" style="line-height:18px; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">車</dt>
                        <dd class="mt-2 ml-4 lg:ml-[30px] text-[14px] lg:text-base tracking-wider" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">阪神高速3号神戸線「若宮IC」から530m（駐車場5台分）</dd>
                        <dt class="mt-2 text-[10px] lg:text-[12px] w-[44px] lg:w-[48px] h-fit-content border border-[#333] rounded-[10px] text-center h-[20px]" style="line-height:18px; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">電 車</dt>
                        <dd class="mt-2 ml-4 lg:ml-[30px] text-[14px] lg:text-base tracking-wider" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">JR山陽線「須磨海浜公園駅」から徒歩1分</dd>
                        <dt class="mt-2 text-[10px] lg:text-[12px] w-[44px] lg:w-[48px] h-fit-content border border-[#333] rounded-[10px] text-center h-[20px]" style="line-height:18px; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">バ ス</dt>
                        <dd class="mt-2 ml-4 lg:ml-[30px] text-[14px] lg:text-base tracking-wider" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);">10系統「『須磨水族園』バス停留所」から徒歩2分</dd>
                    </dl>
                </div>
                <div class="mt-[28px] lg:mt-6 animated__fadeIn access-step4">
                    <a href="https://maps.app.goo.gl/UuEVfvY7pgHeL16o6" class="c-btn mx-auto" target="_blank">Googleマップ</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- column -->
<section class="column-sec overflow-hidden mt-[80px] md:mt-[220px] text-center">
    <div class="inner flex flex-col-reverse items-center lg:flex-row gap-[5vw]">
        <div class="w-[100%] lg:w-[42.5vw] mt-[-20px] lg:mt-0 max-w-[500px] lg:max-w-full mx-auto lg:ml-0">
            <div class="column-step flex items-center flex-wrap justify-center leading-none">
                <div class="animated__fadeIn column-step1">
                    <h2 class="section-title mt-[-17px] lg:mt-0 block lg:contents">COLUMN<span class="mt-1 lg:mt-0">コラム</span></h2>
                </div>
                <div class="ml-auto pc-only animated__fadeIn column-step2"><a href="<?php bloginfo('url');?>/column" class="c-btn">コラム一覧</a></div>
            </div>
            <ul class="column-post-items mt-[36px] lg:mt-[60px] border-t border-[#D68100] animated__fadeIn column-step3">
            <?php
                $pickupList = array(
                'category_name' => 'column',
                'post_type' => 'post',
                'posts_per_page' => 4,
                );
            ?>
            <?php query_posts($pickupList); ?>
            <?php if (have_posts()) : while(have_posts()) : the_post(); ?>
                <li class="border-b border-[#D68100] py-2 lg:py-[10px]">
                    <a href="<?php the_permalink(); ?>" class="flex items-center gap-5 lg:gap-[32px] py-[1px] px-[10px] rounded-[8px]">
                        <div class="font-semibold" style="font-family: 'Nunito', serif;">
                            <div class="text-[8px] lg:text-[10px] text-center border-b border-[#D9D9D9] mb-1"><?php echo get_the_date('Y'); ?></div>
                            <div class="text-[14px] lg:text-[18px]" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);"><?php echo get_the_date('m.d'); ?></div>
                        </div>
                        <div class="post-title text-[14px] lg:text-base" style="line-height:1.4; padding-top: var(--leading-trim); padding-bottom: var(--leading-trim);"><?php the_title(); ?></div>
                    </a>
                </li>
                <?php endwhile; endif; wp_reset_query(); ?>
            </ul>
            <div class="mt-[28px] ml-auto sp-only animated__fadeIn column-step4"><a href="<?php bloginfo('url');?>/column" class="c-btn mx-auto">コラム一覧</a></div>
        </div>
        <div class="top-column-right w-[100vw] lg:w-[42.5vw] animated__fadeIn column-step3-img z-[-1]">
            <div class="block w-[91%] md:w-[70%] lg:w-[100%] relative overflow-hidden z-[-1] ml-auto lg:ml-0" style="border-top-left-radius: 230px; border-bottom-left-radius: 230px">
                <div style="aspect-ratio: 3 / 2;">
                    <div class="absolute top-0 left-0 w-[120%] top-column-parallax" style="transform: translateY(-50%)">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/top-column-img.webp" alt="犬のイラスト">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="overflow-hidden mt-[50px] lg:mt-[150px]">
    <div class="footer-top-slide h-[100px] lg:h-[110px] flex gap-[46px]">
        <img class="h-[100%]" style="max-width: none;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-top-slide.webp" alt="動物のイラスト">
        <img class="h-[100%]" style="max-width: none;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-top-slide.webp" alt="動物のイラスト">
        <img class="h-[100%]" style="max-width: none;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-top-slide.webp" alt="動物のイラスト">
        <img class="h-[100%]" style="max-width: none;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-top-slide.webp" alt="動物のイラスト">
        <img class="h-[100%]" style="max-width: none;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-top-slide.webp" alt="動物のイラスト">
        <img class="h-[100%]" style="max-width: none;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-top-slide.webp" alt="動物のイラスト">
    </div>
</div>

<div class="loader1">
    <div class="img">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/loader.webp" alt="おおした動物病院">
    </div>
</div>

<div class="loader2">
    <div class="img">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/mv-img.webp" alt="おおした動物病院">
    </div>
</div>

<?php get_footer(); ?>
