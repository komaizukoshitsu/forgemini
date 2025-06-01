<?php
$categories = get_the_category();

if (!empty($categories)) {
    foreach ($categories as $category) {
        $cat_slug = $category->slug;
        $acf_key = $cat_slug . '-pickup';

        $is_pickup = get_field($acf_key); // 投稿のカスタムフィールドを直接参照

        if ($is_pickup) {
            echo "<span class=\"w-fit text-[10px] lg:text-xs h-5 lg:h-6 px-[6px] flex items-center justify-center rounded-[15px] border border-[#FFF546]\" style=\"font-family: 'Open Sans', sans-serif;\">Pick Up</span>";
            break;
        }
    }
}
?>
