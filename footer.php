
<div class="fixed-content z-[500] fixed lg:top-0 bottom-0 right-0 left-0 w-full lg:w-25 h-auto lg:h-full">
	<aside class="lg:absolute w-full lg:w-[270px] h-full">
		<?php if (is_front_page()) : ?>
			<h1 class="hidden lg:block header-logo text-center lg:absolute lg:top-15 lg:left-15 lg:z-20 lg:mx-0 lg:w-9">
				<a href="<?php bloginfo('url'); ?>" class="inline-block lg:w-9">
				<img class="hidden lg:block w-9 h-auto" src="<?php echo get_template_directory_uri(); ?>/assets/image/logo-header.svg" alt="てらおか なつみ">
				</a>
			</h1>
			<?php else : ?>
			<div class="hidden lg:block header-logo text-center lg:absolute lg:top-15 lg:left-15 lg:z-20 lg:mx-0 lg:w-9">
				<a href="<?php bloginfo('url'); ?>" class="inline-block lg:w-9">
				<img class="hidden lg:block w-9 h-auto" src="<?php echo get_template_directory_uri(); ?>/assets/image/logo-header.svg" alt="てらおか なつみ">
				</a>
			</div>
		<?php endif; ?>
		<?php if ( !wp_is_mobile() ) : ?>
		<?php endif; ?>
		<?php get_template_part('templates/nav/drawer-nav'); ?>
		<div class="z-[200] fixed bottom-5 right-[18px] lg:absolute lg:bottom-15 lg:left-15 lg:right-auto w-15 lg:w-[82px]">
			<?php get_template_part('templates/nav/drawer-toggle'); ?>
		</div>
	</aside>
</div>

<!-- footer -->
<footer class="footer bg-[#FFFAD1] <?php echo is_front_page() ? '' : 'mt-20 lg:mt-50'; ?> pt-8 lg:pt-20 relative pb-10 lg:pb-20 z-20">
	<div class="footer-contact px-5">
		<a href="<?php bloginfo('url');?>/contact" class="mx-auto flex max-w-308 w-[114px] lg:w-50 h-[114px] lg:h-50 relative justify-center items-center">
			<div class="w-15 lg:w-25">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-contact.webp" alt="犬のイラスト" loading="lazy" width="100%" height="100%">
			</div>
			<div class="footer-contact-circle absolute top-0 left-0">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/image/contact-wrapper.svg" alt="" loading="lazy" width="100%" height="100%">
			</div>
		</a>
	</div>
	<div class="flex flex-col-reverse lg:flex-row gap-[15%] mt-10 lg:mt-30">
		<div class="lg:w-[20%] mx-auto lg:ml-[7.5%] mt-7 lg:mt-10 px-[5%] lg:px-0">
			<div class="mx-auto w-[60%] max-w-80 lg:w-full">
				<a href="<?php bloginfo('url'); ?>" class="block w-full mx-auto lg:ml-0">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/image/logo.svg" alt="てらおか なつみ" loading="lazy" width="100" height="100">
				</a>
			</div>

			<?php
			get_template_part('templates/common/sns-links', null, [
			'bg_base' => 'bg-[#FFFAD1]',
			'hover_bg' => 'hover:bg-[#F2EEC7]',
			'class' => 'is-footer'
			]);
			?>

		</div>
		<nav class="w-full lg:w-[50%] mx-auto lg:mr-[7.5%] lg:ml-0 px-[5%] lg:px-0">
			<ul class="flex flex-wrap justify-between gap-y-0 lg:gap-y-4 tracking-[0.15em] leading-[1.25] italic" style="font-family: 'Cormorant Garamond', serif;">
				<?php
					get_template_part('templates/nav/menu-items', null, [
						'list_item_class_from_footer' => 'nav-item border-t border-[#D9D9D9]',
						'link_class_from_footer' => 'block py-3 text-[20px] lg:text-2xl transition-all duration-150 ease-linear',
					]);
				?>
			</ul>
		</nav>
	</div>
	<div class="overflow-hidden mt-10 h-[62px] lg:h-[103px] lg:mt-30">
		<div class="footer-slide h-full flex w-max">
			<div class="h-full">
				<img class="w-auto h-full" style="max-width: none; object-fit: cover;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-slide.webp" alt="犬のイラスト">
			</div>
			<div class="h-full">
				<img class="w-auto h-full" style="max-width: none; object-fit: cover;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-slide.webp" alt="犬のイラスト">
			</div>
			<div class="h-full">
				<img class="w-auto h-full" style="max-width: none; object-fit: cover;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-slide.webp" alt="犬のイラスト">
			</div>
		</div>
	</div>
	<div class="footer-bottom mt-10 lg:mt-[50px] px-5 lg:px-20 text-xs lg:text-base leading-[1.25]" style="font-family: 'Cormorant Garamond', serif; font-style: italic;">
		<div class="flex flex-col gap-2 text-center lg:flex-row lg:justify-between">
			<div class="flex flex-col-reverse lg:flex-row gap-2">
				<div class="copyright">
					<small>&copy; Teraoka Natsumi /  Inunoe Inc.</small>
				</div>
				<div class="footer-privacy-policy lg:ml-[60px]">
					<a href="<?php bloginfo('url');?>/privacy-policy">Privacy Policy</a>
				</div>
			</div>
			<div class="right">
				<p class="web-design">Website design by <a href="https://koma-i-design.com/" target="_blank">koma-i-design.com</a></p>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
