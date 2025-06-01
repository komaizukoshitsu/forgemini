<?php
$category = get_queried_object();
$slug = $category->slug;

// スラッグに応じてテンプレートを分岐
if ($slug === 'event') {
    get_template_part('template-parts/category/category', 'event');
} elseif ($slug === 'goods') {
    get_template_part('template-parts/category/category', 'goods');
} else {
    get_template_part('template-parts/category/category', 'default');
}
?>
