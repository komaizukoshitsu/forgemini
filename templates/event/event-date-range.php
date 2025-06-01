<?php
$start_date_obj = get_field('event-start', false, false);
$end_date_obj = get_field('event-end', false, false);
$week = ['日','月','火','水','木','金','土'];
$class = get_query_var('event_date_class', 'text-sm'); // デフォルト

if ($start_date_obj && $end_date_obj):
    $start_time = strtotime($start_date_obj);
    $end_time = strtotime($end_date_obj);

    $start_year_short = date('y', $start_time);
    $end_year_short = date('y', $end_time);

    $start_month = date('n', $start_time);
    $start_day = date('j', $start_time);
    $start_weekday = $week[date('w', $start_time)];

    $end_month = date('n', $end_time);
    $end_day = date('j', $end_time);
    $end_weekday = $week[date('w', $end_time)];

    $output = '';

    if ($start_year_short === $end_year_short) {
        $output = sprintf(
            '%s.%s.%s（%s）－%s.%s（%s）',
            $start_year_short,
            $start_month,
            $start_day,
            $start_weekday,
            $end_month,
            $end_day,
            $end_weekday
        );
    } else {
        $output = sprintf(
            '%s.%s.%s（%s）－%s.%s.%s（%s）',
            $start_year_short,
            $start_month,
            $start_day,
            $start_weekday,
            $end_year_short,
            $end_month,
            $end_day,
            $end_weekday
        );
    }
?>
    <div class="<?= esc_attr($class) ?> tracking-[0.05em] leading-[1.3]" style="font-family: 'Open Sans', sans-serif;">
        <?= $output ?>
    </div>
<?php endif; ?>
