<?php
/**
 * Template Part: アコーディオン形式のリストセクション
 *
 * @param array $args {
 * @type string $heading_text         セクションの見出しテキスト (例: '過去の主なお仕事')
 * @type string $post_type            カスタム投稿タイプ名 (例: 'works', 'events')
 * @type string $meta_key             カスタムフィールドのキー (例: 'work_show_on_about', 'event_show_on_about')
 * @type string $taxonomy             年を表すタクソノミー名 (例: 'works_year', 'event_year')
 * @type string $view_all_url         「View All」ボタンのリンク先URL (例: home_url('/works'))
 * @type string $empty_message        項目がない場合に表示するメッセージ (例: '仕事経歴はありません。')
 * @type string $accordion_group_class アコーディオンのグループクラス (例: 'accordion-group-1', 'accordion-group-2')
 * }
 */

$heading_text          = $args['heading_text'] ?? '';
$post_type             = $args['post_type'] ?? '';
$meta_key              = $args['meta_key'] ?? '';
$taxonomy              = $args['taxonomy'] ?? '';
$view_all_url          = $args['view_all_url'] ?? '#';
$empty_message         = $args['empty_message'] ?? '項目はありません。';
$accordion_group_class = $args['accordion_group_class'] ?? 'accordion-group-default';

// --- データ取得ロジック ---
$query_args = [
    'post_type'      => $post_type,
    'posts_per_page' => -1,
    'meta_query'     => [
        [
            'key'     => $meta_key,
            'value'   => 1,
            'compare' => '=',
            'type'    => 'NUMERIC',
        ],
    ],
    'orderby'        => 'date',
    'order'          => 'DESC',
];

$the_query = new WP_Query($query_args);
$grouped_items = [];

if ($the_query->have_posts()) :
    while ($the_query->have_posts()) : $the_query->the_post();
        $post_id = get_the_ID();
        $post_title = get_the_title();
        $post_permalink = get_permalink($post_id);

        $terms = get_the_terms($post_id, $taxonomy);
        $year = '';
        if ($terms && !is_wp_error($terms)) {
            $term = array_shift($terms);
            $year = $term->name;
        } else {
            $year = get_the_date('Y');
        }

        if (!empty($year) && !empty($post_title)) {
            if (!isset($grouped_items[$year])) {
                $grouped_items[$year] = [];
            }
            $grouped_items[$year][] = ['title' => $post_title, 'permalink' => $post_permalink];
        }
    endwhile;
    wp_reset_postdata();
endif;

krsort($grouped_items); // 年を新しい順にソート

$is_first_item = true;
?>

<div class="mt-15 xl:mt-30 xl:flex">
    <div class="xl:w-[290px]">
        <?php
            get_template_part('templates/heading/heading-with-brackets', null, [
                'heading_text' => $heading_text,
                'heading_tag'  => 'h2',
            ]);
        ?>
    </div>
    <div class="xl:w-[calc(100%-290px)] mt-5 xl:mt-0">
        <div class="<?php echo esc_attr($accordion_group_class); ?> space-y-0">
            <?php if (!empty($grouped_items)): ?>
                <?php foreach ($grouped_items as $year => $contents):
                    $is_open = $is_first_item;
                    $is_first_item = false;
                    $border_top_class = $is_open ? 'border-t' : '';
                ?>
                    <div class="<?php echo $border_top_class; ?> border-b border-gray-300">
                        <button
                            class="w-full flex justify-between items-center px-4 py-3 text-left text-lg font-semibold transition-colors js-accordion-button <?php echo $is_open ? 'open' : ''; ?>"
                            type="button"
                            aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                        >
                            <span class="tracking-[0.1em]" style="font-family: 'Open Sans', sans-serif;"><?php echo esc_html($year); ?></span>
                            <span class="transition-colors duration-250 hover:bg-[#f5f5f5] rounded-full p-1 js-accordion-icon">
                                <svg class="w-5 h-5 icon-plus <?php echo $is_open ? 'hidden' : ''; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <svg class="w-5 h-5 icon-minus <?php echo $is_open ? '' : 'hidden'; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                            </span>
                        </button>

                        <div class="js-accordion-content px-4 pb-4 xl:pb-8 <?php echo $is_open ? '' : 'hidden'; ?> bg-white ">
                            <ul class="list-disc pl-5 space-y-2 text-sm">
                                <?php foreach ($contents as $content_item): ?>
                                    <li><a href="<?php echo esc_url($content_item['permalink']); ?>"><?php echo esc_html($content_item['title']); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?php echo esc_html($empty_message); ?></p>
            <?php endif; ?>
        </div>
        <div class="mt-4 xl:mt-6 text-right">
            <?php get_template_part('templates/common/link-button', null, [
                'url' => esc_url($view_all_url),
                'label' => 'View All'
            ]); ?>
        </div>
    </div>
</div>
