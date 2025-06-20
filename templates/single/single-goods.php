<main data-barba="container" data-barba-namespace="single">
    <div class="mt-17">
        <div class="w-[90%] mx-auto xl:ml-[25%] xl:max-w-243">
            <a href="<?php bloginfo('url'); ?>/goods"
            class="relative pl-[29px] text-[24px] italic tracking-[0.15em] font-garamond group">
                Goods
                <span class="absolute left-[5px] top-1/2 w-3 h-[10px] -translate-y-1/2 rotate-180 bg-[url('../image/view-more-icon.svg')] bg-center bg-no-repeat bg-contain transition-all duration-[250ms] ease-in-out group-hover:left-0"></span>
            </a>
        </div>
        <article class="w-[90%] mx-auto xl:ml-[25%] xl:max-w-243 mt-5 xl:mt-10">
            <div class="mt-5 xl:mt-[105px]">
                <div class="flex flex-col xl:flex-row gap-6 xl:gap-18">
                    <div class="xl:w-[55%]">
                        <div>
                            <?php get_template_part('templates/swiper/swiper-single-goods'); ?>
                        </div>
                    </div>
                    <div class="xl:flex-1">
                        <header class="space-y-2 xl:space-y-3">
                            <?php get_template_part('templates/badge-new'); ?>
                            <h1 class="text-lg xl:text-2xl font-medium tracking-[0.05em] leading-[1.4]"><?php the_title(); ?></h1>
                            <div class="flex text-xs xl:text-base leading-[1.3] pb-4 xl:pb-8 border-b border-gray-300">
                                <div class="">
                                    <?php
                                    // goods_category タクソノミーからタームを取得
                                    $categories = get_the_terms(get_the_ID(), 'goods_category');
                                    if ($categories && !is_wp_error($categories)) {
                                        $output = [];
                                        foreach ($categories as $category) {
                                            $output[] = '<span>' . esc_html($category->name) . '</span>';
                                        }
                                        echo implode(', ', $output); // 複数のカテゴリがある場合はカンマで区切る
                                    } else {
                                        echo '<span>未分類</span>'; // カテゴリがない場合の表示
                                    }
                                    ?>
                                </div>
                            </div>
                        </header>
                        <dl class="space-y-3 xl:space-y-4 mt-4 xl:mt-10">
                            <div class="flex flex-row items-baseline leading-[1.4]">
                                <dt class="text-xs xl:text-sm w-20 xl:w-25 flex-shrink-0">価格</dt>
                                <dd class="flex-1 text-base xl:text-[20px] flex items-baseline gap-1">
                                    <div class="">¥</div>
                                    <div class="price leading-[1.4]" style="font-family: 'Open Sans', sans-serif;"><?php the_field('goods-price'); ?></div>
                                    <div class="text-[10px] xl:text-xs">（税込）</div>
                                </dd>
                            </div>
                            <div class="flex flex-row items-baseline">
                                <dt class="text-xs xl:text-sm w-20 xl:w-25 flex-shrink-0">詳細</dt>
                                <dd class="flex-1 text-sm xl:text-base leading-[1.6]"><?php the_field('goods-detail'); ?></dd>
                            </div>
                            <div class="flex flex-row items-baseline">
                                <dt class="text-xs xl:text-sm w-20 xl:w-25 flex-shrink-0">仕様</dt>
                                <dd class="flex-1 text-sm xl:text-base leading-[1.6]"><?php the_field('goods-specification'); ?></dd>
                            </div>
                            <?php
                            // 販売店舗の取得と表示を store タクソノミーから行う
                            $stores = get_the_terms(get_the_ID(), 'store'); // 'store' タクソノミースラッグを使用

                            // 全ての店舗情報を保持する配列を初期化
                            $all_stores_data = [];
                            $store_names_output = []; // 店舗名を格納する配列を追加

                            if ($stores && !is_wp_error($stores)) :
                                foreach ($stores as $store_term) :
                                    $store_name = esc_html($store_term->name);
                                    $store_term_id = $store_term->term_id;
                                    $store_url = get_field('store_url', 'term_' . $store_term_id); // ACFフィールド名 'store_url'
                                    $modal_id = 'store-' . $store_term_id; // 各店舗に固有のモーダルID

                                    // 店舗名をカンマ区切りで表示するために格納
                                    // 修正点1: 店舗名とモーダルトリガーをまとめて処理し、data-modal-targetをspanに移動
                                    $store_names_output[] = '<span class="cursor-pointer js-modal-trigger underline" data-modal-target="' . esc_attr($modal_id) . '">' . $store_name . '</span>';

                                    // モーダル表示に必要なデータを配列に格納
                                    $all_stores_data[] = [
                                        'name' => $store_name,
                                        'term_id' => $store_term_id,
                                        'url' => $store_url,
                                        'modal_id' => $modal_id,
                                    ];
                                endforeach; // foreach ($stores as $store_term) の終わり
                            endif; // if ($stores ...) の終わり
                            ?>
                            <div class="flex flex-row items-baseline">
                                <dt class="text-xs xl:text-sm w-20 xl:w-25 flex-shrink-0">取扱店舗</dt>
                                <dd class="flex-1 text-sm xl:text-base leading-[1.4]">
                                    <?php
                                    // 修正点2: 店舗名リストを表示。店舗がない場合は「取扱店舗なし」などのメッセージ
                                    if (!empty($store_names_output)) {
                                        echo implode(', ', $store_names_output);
                                    } else {
                                        echo '取扱店舗はありません'; // 例: 店舗がない場合の表示
                                    }
                                    ?>
                                </dd>
                            </div>
                        </dl>
                        <?php
                        // 取得した全ての店舗に対してモーダルを読み込む
                        if (!empty($all_stores_data)) {
                            foreach ($all_stores_data as $store_data) {
                                get_template_part('templates/modal/modal-content-shop', null, [
                                    'context' => 'shop',
                                    'store_name' => $store_data['name'],
                                    'modal_id_suffix' => $store_data['term_id'],
                                    'store_url' => $store_data['url'],
                                ]);
                            }
                        }
                        ?>
                        <ul class="mt-7 xl:mt-10 tracking-wide list-disc py-4 xl:py-6 pl-[30px] xl:pl-[50px] pr-5 xl:pr-[30px] bg-[#F5F5F5] text-red-500 rounded-[10px] xl:rounded-[20px] text-xs xl:text-sm leading-[1.75]">
                            <li>表示されている価格は情報更新日時点のものです。実際の価格とは異なる場合があります。</li>
                            <li>実際の価格や送料等については、販売サイトにてご確認ください。</li>
                        </ul>
                        <div class="mt-10 text-center">
                        <?php
                        // 「販売サイトで購入する」リンクは、全ての店舗のリンクをどのように表示するか考慮が必要
                        // ここでは、もし複数店舗がある場合、最初の店舗のURLを使用するか、
                        // あるいは「複数の販売サイト」のようなテキストにするか検討
                        // 現状は、もし店舗が一つでもあればそのURL、なければ空とする
                        $display_link_url = !empty($all_stores_data) ? $all_stores_data[0]['url'] : '';
                        $link_text = '販売サイトで購入する';

                        get_template_part('templates/common/external-site-link', null, [
                            'url' => $display_link_url,
                            'text' => $link_text,
                        ]);
                        ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php get_template_part( 'templates/parts/single-footer' ); ?>
        </article>
        <aside class="w-full mx-auto xl:max-w-180 mt-15 xl:mt-[150px]">
            <div class="flex justify-between px-[5%] xl:px-0">
                <?php
                get_template_part('templates/heading/heading-with-brackets', null, [
                    'heading_text' => 'グッズ',
                    'heading_tag'  => 'h2',
                ]);
                ?>
                <?php get_template_part('templates/common/link-button', null, [
                    'url' => home_url('/goods'),
                    'label' => 'View All'
                ]); ?>
            </div>
            <?php get_template_part('templates/parts/related-posts'); ?>
        </aside>
    </div>
</main>
