<?php
$prefix = isset($args['prefix']) ? esc_attr($args['prefix']) : 'default'; //'store-modal-0'
?>

<!-- Prev Button //'store-modal-0-swiper-prev' -->
<button class="<?php echo $prefix; ?>-swiper-prev swiper-button-prev !relative !w-10 !h-10 after:hidden flex items-center justify-center group">
    <svg class="w-10 h-10 block align-middle leading-none" viewBox="0 0 40 40" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <circle cx="20" cy="20" r="20" transform="rotate(-180 20 20)" fill="white"
            class="transition-all duration-200 ease-in-out group-hover:fill-[#F5F5F5]" />
        <path d="M22 14L16 20L22 26" stroke="#333" stroke-width="2" />
    </svg>
</button>

<!-- Next Button -->
<button class="<?php echo $prefix; ?>-swiper-next swiper-button-next !relative !w-10 !h-10 after:hidden flex items-center justify-center group">
    <svg class="w-10 h-10 block align-middle leading-none" viewBox="0 0 40 40" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <circle cx="20" cy="20" r="20" fill="white"
            class="transition-all duration-200 ease-in-out group-hover:fill-[#F5F5F5]" />
        <path d="M22 14L16 20L22 26" stroke="#333" stroke-width="2"
            transform="rotate(180 20 20)" />
    </svg>
</button>
