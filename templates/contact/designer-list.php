<?php
$designers = SCF::get('contact_design', 375);
if (!empty($designers)):
?>
<ul class="designer-list flex flex-col gap-3 lg:gap-5 items-center">
<?php foreach ($designers as $index => $designer):
    $designer_name = $designer['contact-design-name'];
    $slider_img1 = $designer['contact-slider-img1'];
    $slider_img2 = $designer['contact-slider-img2'];
    $slider_img3 = $designer['contact-slider-img3'];
    $designer_text = $designer['contact-design-text'];
    $designer_url = $designer['contact-design-url'];
    $designer_email = $designer['contact-design-email'];

    $modal_id = 'modal-' . $index;

    // モーダルで使う変数をセット
    set_query_var('store_name', $designer_name);
    set_query_var('post_id', $modal_id);
    set_query_var('slider_image1', $slider_img1);
    set_query_var('slider_image2', $slider_img2);
    set_query_var('slider_image3', $slider_img3);
    set_query_var('shop_text', $designer_text);
    set_query_var('shop_url', $designer_url);
    set_query_var('shop_email', $designer_email);
?>
    <li>
        <button
            type="button"
            class="inline-flex justify-center items-center w-60 lg:w-[350px] h-10 lg:h-13 px-[15px] border border-[#D9D9D9] hover:border-[#999] hover:bg-[#F5F5F5] rounded-full text-sm lg:text-base gap-2 transition-all duration-300 js-modal-trigger mx-auto"
            data-modal-target="store-<?php echo esc_attr($modal_id); ?>">
            <?php echo esc_html($designer_name); ?>について
        </button>
        <?php
        // モーダルを出力（goods-store-modal.phpを使用）
        set_query_var('context', 'designer');
        get_template_part('templates/modal/modal-content');
        // get_template_part('templates/modal/goods-store-modal');
        ?>
    </li>
<?php endforeach; ?>
</ul>
<?php else: ?>
    <p class="text-center text-sm text-gray-500">デザイナー情報がありません。</p>
<?php endif; ?>
