<?php
    // 現在のページURLを取得してURLエンコード
    $url_encode = urlencode( get_permalink() );
    // 現在のページのタイトルを取得してURLエンコード
    $title_encode = urlencode( get_the_title() );
    ?>
    <div class="text-gray-400 text-[10px] lg:text-xs font-medium">この記事をシェアする</div>
    <ul class="flex mt-4 gap-2 lg:gap-4 justify-center">
        <li class="">
            <a href="<?php echo esc_url( 'https://line.me/R/msg/text/?' . $title_encode . '%0A' . $url_encode ); ?>"
            class="block w-8 h-8 lg:w-10 lg:h-10 group rounded-full transition-all duration-500 ease-in-out hover:bg-[#F5F5F5]"
            target="_blank" rel="noopener noreferrer">
                <img
                    src="<?php echo get_template_directory_uri(); ?>/assets/image/sns-line-icon.svg"
                    alt="LINEで共有する"
                    loading="lazy"
                    class="w-full h-full object-contain"
                    width="40" height="40"
                >
            </a>
        </li>
        <li class="">
            <a href="https://x.com/teraoka_natsumi" class="block w-8 h-8 lg:w-10 lg:h-10 group rounded-full transition-all duration-500 ease-in-out hover:bg-[#F5F5F5]" target="_blank" rel="noopener noreferrer">
                <img
                    src="<?php echo get_template_directory_uri(); ?>/assets/image/sns-x-icon.svg"
                    alt="X（旧Twitter）アカウントへのリンク"
                    loading="lazy"
                    class="w-full h-full object-contain"
                    width="40" height="40"
                >
            </a>
        </li>
    </ul>
