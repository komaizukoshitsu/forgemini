<?php
// templates/parts/filtered-items-event.php

$args = $args ?? [];
$months_str = $args['months_str'] ?? '';
$tag_slugs  = $args['tag_slugs'] ?? '';

// badge-event-status の出力キャプチャ
ob_start();
get_template_part('templates/event/event-status');
$badge_status = trim(ob_get_clean());

// event-date-range の出力キャプチャ
ob_start();
get_template_part('templates/event/event-date-range');
$event_date_range = trim(ob_get_clean());
?>

<article class="event-item pb-4 lg:pb-6 px-3 lg:px-4 border-b border-[#D9D9D9]" data-months="<?php echo esc_attr($months_str); ?>" data-tags="<?php echo esc_attr($tag_slugs); ?>">
    <a href="<?php the_permalink(); ?>" class="flex items-center flex-col lg:flex-row gap-1 lg:gap-0">
        <div class="left w-full lg:w-70 flex flex-col lg:flex-shrink-0 gap-1 lg:gap-2">
            <?= $badge_status; ?>
            <?= $event_date_range; ?>
        </div>
        <div class="right w-full flex flex-col lg:flex-1 gap-1 lg:gap-2">
            <div class="schedule-item-title text-sm lg:text-base leading-[1.4]">
                <?php the_title(); ?>
            </div>
            <div class="flex items-center text-[10px] lg:text-xs leading-[1.3]">
                <div class="">
                <?php
                    // カスタムタクソノミー 'event_venue' のタームを取得
                    $event_venues = get_the_terms(get_the_ID(), 'event_venue');
                    $event_venue_output = ''; // 出力用の変数を初期化

                    if ($event_venues && !is_wp_error($event_venues)) {
                        $output_array = [];
                        foreach ($event_venues as $venue) {
                            $output_array[] = '<span>' . esc_html($venue->name) . '</span>';
                        }
                        // 会場が存在する場合のみ結合して出力
                        if (!empty($output_array)) {
                            $event_venue_output = implode(', ', $output_array);
                        } else {
                            // タクソノミーは存在するが、選択されたタームがない場合
                            $event_venue_output = '<span>場所未定</span>';
                        }
                    } else {
                        // タクソノミーが設定されていない、または取得に失敗した場合
                        $event_venue_output = '<span>場所未定</span>';
                    }
                    echo $event_venue_output; // 結果を出力
                ?>
                </div>
                <div class="ml-2 lg:ml-3 pl-2 lg:pl-3 border-l border-[#D9D9D9] border-solid">
                <?php
                    // カスタムタクソノミー 'event_type' からタームを取得
                    $event_types = get_the_terms(get_the_ID(), 'event_type');
                    $event_types_output_html = ''; // 出力用の変数を初期化

                    if ($event_types && !is_wp_error($event_types)) {
                        $output_array = [];
                        foreach ($event_types as $type) {
                            $output_array[] = '<span>' . esc_html($type->name) . '</span>';
                        }
                        if (!empty($output_array)) {
                            $event_types_output_html = implode(', ', $output_array);
                        } else {
                            $event_types_output_html = '<span>イベントタイプなし</span>';
                        }
                    } else {
                        $event_types_output_html = '<span>イベントタイプなし</span>';
                    }
                    echo $event_types_output_html; // 結果を出力
                ?>
                </div>
            </div>
        </div>
    </a>
</article>
