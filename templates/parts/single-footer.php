<div class="mt-4 xl:mt-10 text-gray-500 text-xs tracking-[0.05em] text-right" style="font-family: 'Open Sans', sans-serif;">
    <p class="inline-flex items-center mr-4 xl:mr-6">
        <span class="mr-1"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/edit.svg" alt="執筆" width="16" height="16" loading="lazy" style="vertical-align: middle;"></span>
        <?php echo get_the_date('y.m.d'); ?>
    </p>
    <?php
    $updated_date = get_the_modified_date('y.m.d'); // フォーマットを指定
    $published_date = get_the_date('y.m.d'); // フォーマットを指定

    // 執筆日時と更新日時が異なる場合のみ更新日時を表示
    if ($updated_date !== $published_date) :
    ?>
        <p class="inline-flex items-center">
        <span class="mr-1"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/update.svg" alt="更新" width="16" height="16" loading="lazy" style="vertical-align: middle;"></span>
            <?php echo $updated_date; ?>
        </p>
    <?php endif; ?>
</div>
<div class="mt-8 xl:mt-20 text-center">
    <?php get_template_part( 'templates/parts/share-buttons' ); ?>
    <div class="mt-4 xl:mt-10">
        <?php
        $back_link_url = home_url('/'); // デフォルトはサイトトップ
        $button_text = 'Back to List';

        // 現在の投稿タイプを取得
        $post_type = get_post_type();

        if ( $post_type == 'works' ) {
            $back_link_url = home_url( '/works' );
        } elseif ( $post_type == 'events' ) {
            $back_link_url = home_url( '/events' );
        } elseif ( $post_type == 'goods' ) {
            $back_link_url = home_url( '/goods' );
        } elseif ( $post_type == 'post' ) { // 通常の投稿（ブログ）の場合
            $back_link_url = home_url( '/news' ); // または get_permalink( get_option( 'page_for_posts' ) ); でブログトップページ
        }
        // その他のカスタム投稿タイプがある場合は、ここに追加
        // elseif ( $post_type == 'your_custom_post_type' ) {
        //     $back_link_url = home_url( '/your-custom-post-type-slug' );
        // }
        ?>
        <a href="<?php echo esc_url( $back_link_url ); ?>" class="group relative inline-block pl-6 text-base xl:text-[18px] leading-[1.25] tracking-[0.05em] italic font-garamond font-semibold">
            <?php echo esc_html( $button_text ); ?><span class="absolute left-[5px] top-1/2 -translate-y-1/2 w-3 h-[10px] bg-[url('../image/view-more-icon.svg')] bg-center bg-no-repeat bg-contain rotate-180 transition-all duration-200 ease-in-out group-hover:left-0"></span>
        </a>
    </div>
</div>
