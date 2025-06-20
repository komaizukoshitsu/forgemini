<?php
/**
 * イベントスケジュール詳細テンプレートパーツ
 *
 * @package YourTheme
 */
?>

<div class="flex flex-col items-start xl:flex-row xl:items-center gap-1 xl:gap-2">
    <?php get_template_part('templates/event/event-status'); ?>
    <?php get_template_part('templates/event/event-date-range'); ?>
</div>
<div class="schedule-item-title text-sm xl:text-base leading-[1.4]"><?php the_title(); ?></div>
<div class="flex items-center text-[10px] xl:text-xs leading-[1.3]">
    <?php
    // 会場情報の取得と表示
    $venue_output = '';
    $venues = get_the_terms(get_the_ID(), 'event_venue');
    if ($venues && !is_wp_error($venues)) {
        $venue = $venues[0]; // 最初の会場タームを取得
        $venue_name = esc_html($venue->name);
        $venue_url = get_field('venue_url', 'term_' . $venue->term_id); // タームに紐付けたACFフィールドを取得

        if ($venue_url) {
            $venue_output = $venue_name; // 単に会場名だけを出力
        } else {
            $venue_output = $venue_name;
        }
    } else {
        $venue_output = '場所未定'; // 会場が設定されていない場合の代替テキスト
    }

    if (!empty($venue_output)) :
    ?>
    <div>
        <?php echo $venue_output; ?>
    </div>
    <?php endif; ?>

    <?php
    // イベントタイプ（タグ）の取得と表示
    $event_types_output = '';
    $event_types = get_the_terms(get_the_ID(), 'event_type');
    if ($event_types && !is_wp_error($event_types)) {
        $output_array = [];
        foreach ($event_types as $type) {
            $output_array[] = '<span>' . esc_html($type->name) . '</span>';
        }
        $event_types_output = implode(', ', $output_array);
    } else {
        // イベントタイプが存在しない場合、または取得に失敗した場合
        $event_types_output = '<span>イベンタイプなし</span>';
    }
    // ★ここから修正
    // if (!empty($event_types_output)) : を削除
    ?>
    <div class="ml-2 xl:ml-3 pl-2 xl:pl-3 border-l border-gray-300">
        <?php echo $event_types_output; ?>
    </div>
    <?php
    // endif; を削除
    // ★ここまで修正
    ?>
</div>
