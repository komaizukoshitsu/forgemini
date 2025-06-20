<ul class="designer-list flex flex-col gap-3 xl:gap-5 items-center">
    <?php
    $designers_data = [
        [
            'name' => 'こまゐ図考室',
            'images' => [
                '画像のURL-A1.jpg',
                '画像のURL-A2.jpg',
                '画像のURL-A3.jpg',
            ],
            'text' => 'デザイナーAは、ミニマリストで機能的なデザインを得意としています。シンプルな美しさを追求し、空間に調和するプロダクトを生み出します。',
            'url' => 'https://koma-i-design.com',
            'email' => 'designerA@example.com',
        ],
        [
            'name' => 'ondoデザイン室',
            'images' => [
                '画像のURL-B1.jpg',
                '画像のURL-B2.jpg',
                '画像のURL-B3.jpg',
            ],
            'text' => 'デザイナーBは、自然素材と伝統技術を融合させたデザインが特徴です。温かみのある手触りと、時の経過と共に味わいを増す作品を提供します。',
            'url' => 'https://example.com/designer-b',
            'email' => 'designerB@example.com',
        ],
    ];

    $start_id_for_designers = 500; // 既存のIDと重複しないように調整

    foreach ($designers_data as $index => $designer):
        $designer_name = $designer['name'];
        $designer_images = $designer['images'];
        $designer_text = $designer['text'];
        $designer_url = $designer['url'];
        $designer_email = $designer['email'];

        $modal_id_number = $start_id_for_designers + $index;
        $modal_id_suffix = (string)$modal_id_number;

        // get_template_part の第3引数で渡す変数を定義
        // modal-content-designer.php が受け取る変数名に合わせる
        $modal_args = [
            'modal_id_suffix' => $modal_id_suffix,
            'designer_name' => $designer_name, // 変数名をdesigner_nameに統一
            'slider_images' => $designer_images,
            'designer_text' => $designer_text, // 変数名をdesigner_textに統一
            'designer_url' => $designer_url,   // 変数名をdesigner_urlに統一
            'designer_email' => $designer_email, // 変数名をdesigner_emailに統一
        ];
    ?>
        <li>
            <button
                type="button"
                class="inline-flex justify-center items-center w-60 xl:w-[350px] h-10 xl:h-13 px-[15px] border border-[#D9D9D9] hover:border-[#999] hover:bg-[#F5F5F5] rounded-full text-sm xl:text-base gap-2 transition-all duration-300 js-modal-trigger mx-auto"
                data-modal-target="store-<?php echo esc_attr($modal_id_suffix); ?>">
                <?php echo esc_html($designer_name); ?>について
            </button>
            <?php
            // ★ここを新しいテンプレートパーツに変更★
            get_template_part('templates/modal/modal-content-designer', null, $modal_args);
            ?>
        </li>
    <?php endforeach; ?>
</ul>
