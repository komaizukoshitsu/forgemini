<?php
// 今日の日付と1ヶ月前の日付を取得
$today = date('Y-m-d');
$post_date = get_the_date('Y-m-d', get_the_ID());
$one_month_ago = date('Y-m-d', strtotime('-1 month'));

// Newバッジを表示するかどうか判定
if (strtotime($post_date) >= strtotime($one_month_ago)) :
?>
    <div class="info">
        <span class="w-fit text-[10px] xl:text-xs h-5 xl:h-6 px-[5px] xl:px-[6px] flex items-center justify-center bg-[#FFF546] text-gray-800 rounded-[15px]" style="font-family: 'Open Sans', sans-serif;">New</span>
    </div>
<?php endif; ?>
