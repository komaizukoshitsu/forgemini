<article class="w-full mt-5 lg:mt-10 max-w-243">
    <a href="#" class="block group">
        <!-- 画像部分 -->
        <div class="relative overflow-hidden rounded-[10px] lg:rounded-[20px] aspect-[4/3] lg:aspect-[2/1]">
            <img class="w-full h-full object-cover rounded-[10px] lg:rounded-[20px]" src="<?php echo get_template_directory_uri(); ?>/assets/image/sns-icon.webp" alt="" loading="lazy">

            <!-- PC用：hoverで出るテキスト -->
            <div class="absolute inset-0 hidden lg:flex flex-col justify-end p-4 lg:p-8 text-white transition-opacity duration-250 bg-gradient-to-b from-black/10 to-black/40 opacity-0 group-hover:opacity-100">
                <div class="translate-y-4 opacity-0 transition-all duration-250 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col justify-end items-baseline gap-1 lg:gap-2">
                    <div class="text-base leading-[1.4]">SNSアイコン制作 in 『タクラミ』</div>
                    <div class="flex items-baseline text-sm leading-[1.3]">
                        <div>¥</div>
                        <div class="price " style="font-family: 'Open Sans', sans-serif;">44,000</div>
                        <div class="text-xs tracking-[0.05em]">（税込）</div>
                    </div>
                    <div class="flex text-xs leading-[1.3]">似顔絵</div>
                </div>
            </div>
        </div>

        <!-- スマホ用：画像の下に常に表示されるテキスト -->
        <div class="block lg:hidden mt-2">
            <div class="text-sm leading-[1.4]">SNSアイコン制作 in 『タクラミ』</div>
            <div class="flex items-baseline text-xs leading-[1.3] mt-1">
                <div>¥</div>
                <div class="price " style="font-family: 'Open Sans', sans-serif;">44,000</div>
                <div class="text-[10px] tracking-[0.05em] ml-1">（税込）</div>
            </div>
            <div class="text-[10px] leading-[1.3] mt-1">似顔絵</div>
        </div>
    </a>
</article>
