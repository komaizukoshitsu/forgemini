<?php
// 今日の日付を取得
$today = date('Y-m-d');
$today_timestamp = strtotime($today);

// ACFカスタムフィールドからイベント日を取得
$start = get_field('event-start');
$end = get_field('event-end');
$start_timestamp = $start ? strtotime($start) : null;
$end_timestamp = $end ? strtotime($end) : null;

// ステータス初期化
$status = '';
$bg_class = 'bg-[#FFF546]'; // デフォルト黄色

// 判定ロジック
if ($end_timestamp) {
    if ($today_timestamp >= $start_timestamp && $today_timestamp <= $end_timestamp) {
        $status = '開催中';
    } elseif ($today_timestamp < $start_timestamp) {
        $status = '開催予定';
        $bg_class = 'bg-[#FFFAD1]'; // グレー背景
    } else {
        $status = '開催終了';
        $bg_class = 'bg-[#DADADA]'; // グレー背景
    }
} elseif ($start_timestamp) {
    if ($today_timestamp < $start_timestamp) {
        $status = '開催予定';
    } elseif ($today_timestamp == $start_timestamp) {
        $status = '開催中';
    } else {
        $status = '開催終了';
        $bg_class = 'bg-[#DADADA]';
    }
} else {
    $status = '日付未設定';
    $bg_class = 'bg-[#EEE]';
}
?>

<p class="w-fit text-[10px] lg:text-xs h-5 lg:h-6 px-[6px] flex items-center justify-center text-[#333] rounded-[15px] leading-6 tracking-[0.07em] <?php echo $bg_class; ?>">
    <?php echo $status; ?>
</p>
