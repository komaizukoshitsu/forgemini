<ul class="mt-5 lg:mt-[60px] flex flex-col justify-center gap-3 lg:gap-5">
    <?php
    $contact_design = SCF::get('contact_design', 375);

    // データが空でないか確認しながらループ
    if (!empty($contact_design)) {
    foreach ($contact_design as $index => $fields) {

    ?>
    <li class="h-10 lg:h-13">
        <div class="modal">
            <div class="modalBtn second-btn mx-auto" data-modal-target="modal-<?php echo $index; ?>">
                <div class="text-sm lg:text-base">
                <?php echo esc_html($fields['contact-design-name']); ?>について
                </div>
            </div><!-- /.modalBtn -->
            <div class="modalBg" id="modal-<?php echo $index; ?>">
                <div class="modalContent">
                    <div class="modalContent-inner">
                        <div class="swiper modalSwiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide modalSwiper-item3">
                                    <img class="w-full object-cover rounded-[10px] lg:rounded-[24px]" src="<?php echo esc_url(wp_get_attachment_url($fields['contact-slider-img3'])); ?>" alt="" loading="lazy" width="100%" height="100%">
                                </div>
                                <div class="swiper-slide modalSwiper-item1">
                                    <img class="w-full object-cover rounded-[10px] lg:rounded-[24px]" src="<?php echo esc_url(wp_get_attachment_url($fields['contact-slider-img1'])); ?>" alt="" loading="lazy" width="100%" height="100%">
                                </div>
                                <div class="swiper-slide modalSwiper-item2">
                                    <img class="w-full object-cover rounded-[10px] lg:rounded-[24px]" src="<?php echo esc_url(wp_get_attachment_url($fields['contact-slider-img2'])); ?>" alt="" loading="lazy" width="100%" height="100%">
                                </div>
                                <div class="swiper-slide modalSwiper-item3">
                                    <img class="w-full object-cover rounded-[10px] lg:rounded-[24px]" src="<?php echo esc_url(wp_get_attachment_url($fields['contact-slider-img3'])); ?>" alt="" loading="lazy" width="100%" height="100%">
                                </div>
                                <div class="swiper-slide modalSwiper-item1">
                                    <img class="w-full object-cover rounded-[10px] lg:rounded-[24px]" src="<?php echo esc_url(wp_get_attachment_url($fields['contact-slider-img1'])); ?>" alt="" loading="lazy" width="100%" height="100%">
                                </div>
                                <div class="swiper-slide modalSwiper-item2">
                                    <img class="w-full object-cover rounded-[10px] lg:rounded-[24px]" src="<?php echo esc_url(wp_get_attachment_url($fields['contact-slider-img2'])); ?>" alt="" loading="lazy" width="100%" height="100%">
                                </div>
                            </div>

                            <div class="hidden lg:flex justify-center gap-4 mt-7">
                                <div class="modal-swiper-button-prev">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/image/arrow.svg" alt="" loading="lazy" width="100%" height="100%">
                                </div>
                                <div class="modal-swiper-button-next">
                                    <img class="rotate-180" src="<?php echo get_template_directory_uri(); ?>/assets/image/arrow.svg" alt="" loading="lazy" width="100%" height="100%">
                                </div>
                            </div>
                        </div>
                        <div class="flex max-w-75 lg:max-w-130 mx-auto mt-5 text-left">
                            <div class="w-[30%] font-medium text-xs lg:text-base"><?php echo esc_html($fields['contact-design-name']); ?></div>
                            <div class="w-[70%] text-xs lg:text-base">
                                <p><?php echo esc_html($fields['contact-design-text']); ?></p>
                                <a href="<?php echo esc_html($fields['contact-design-url']); ?>" target="_blank"><?php echo esc_html($fields['contact-design-url']); ?></a>
                            </div>
                        </div>
                        <div class="modal-btn mt-5 lg:mt-10">
                            <a href="<?php echo esc_html($fields['contact-design-url']); ?>" class="second-btn flex items-center gap-2" target="_blank">
                                <div><?php echo esc_html($fields['contact-design-name']); ?>に問い合わせる</div>
                                <div><img src="<?php echo get_template_directory_uri(); ?>/assets/image/mail-icon.svg" alt="" loading="lazy" width="100%" height="100%"></div>
                            </a>
                        </div>
                    </div>
                    <div class="modalClose"><button type="button" class="modal-close"><img class=" w-8 h-8 lg:w-10 lg:h-10" src="<?php echo get_template_directory_uri(); ?>/assets/image/close-icon.svg" alt="" loading="lazy" width="100%" height="100%"></button></div><!-- /.modalClose -->
                </div>
            </div><!-- /.modalBg -->
        </div><!-- /.modal -->
    </li>
    <?php
}
        } else {
            echo 'データがありません。';
        }
    ?>
</ul>
