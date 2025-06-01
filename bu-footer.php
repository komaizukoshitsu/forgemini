	</div>
</main>
<div class="fixed-content z-[999] lg:fixed top-5 h-full w-25" >
	<aside class="header-content lg:absolute w-full lg:w-[270px] h-full ">
		<h1 class="header-logo text-center lg:absolute lg:top-15 lg:left-15 lg:z-20 mx-auto lg:mx-0 w-[50%] lg:w-9 pointer-events-auto transition-transform duration-[800ms] ease-in-out [@media(min-width:768px)]:[.is-hidden_&]:translate-x-[-1500%]">
			<a href="<?php bloginfo('url'); ?>" class="inline-block w-48 lg:w-9">
				<img class="lg:block w-full" src="<?php echo get_template_directory_uri(); ?>/assets/image/logo-header.svg" alt="てらおか なつみ">
				<img class="lg:hidden w-full" src="<?php echo get_template_directory_uri(); ?>/assets/image/logo.svg" alt="てらおか なつみ">
			</a>
		</h1>
		<?php get_template_part('template-parts/nav/drawer-nav'); ?>
		<div class="z-[200] absolute bottom-15 left-15 right-0">
			<?php get_template_part('template-parts/nav/drawer-toggle'); ?>
		</div>
	</aside>
</div>

<!-- footer -->
<footer class="footer hidden-area bg-[#FFFAD1] <?php echo is_front_page() ? '' : 'lg:mt-25'; ?> pt-8 lg:pt-20 relative pb-10 lg:pb-20 z-20">
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
		<div class="mx-[5%] lg:ml-[7.5%] lg:w-[20%] mt-7 lg:mt-10">
			<div class="max-w-[320px] w-[60%] lg:w-full mx-auto">
				<a href="<?php bloginfo('url');?>" class="block w-full mx-auto lg:ml-0">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/image/logo.svg" alt="てらおか なつみ" loading="lazy" width="100%" height="100%">
				</a>
			</div>

			<?php
			get_template_part('template-parts/common/sns-links', null, [
			'bg_base' => 'bg-[#FFFAD1]',
			'hover_bg' => 'hover:bg-[#F2EEC7]',
			'class' => 'is-footer'
			]);
			?>

		</div>
		<nav class="mx-[5%] lg:mr-[7.5%] lg:ml-0 w-full lg:w-[50%]">
			<ul class="flex flex-wrap justify-between gap-y-0 lg:gap-y-4 tracking-[0.15em] leading-[1.25] italic" style="font-family: 'Cormorant Garamond', serif;">
				<?php
					$list_item_class = 'nav-item border-t border-solid border-[#D9D9D9]';
					$link_class = 'block py-3 text-[20px] lg:text-2xl transition-all duration-150 ease-linear';
					include(locate_template('template-parts/nav/menu-items.php'));
				?>
			</ul>
		</nav>
	</div>
	<div class="overflow-hidden mt-10 h-[62px] lg:h-[103px] lg:mt-30">
		<div class="footer-slide h-full flex w-max">
			<div class="h-[100%]">
				<img class="w-auto h-[100%]" style="max-width: none; object-fit: cover;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-slide.webp" alt="犬のイラスト">
			</div>
			<div class="h-[100%]">
				<img class="w-auto h-[100%]" style="max-width: none; object-fit: cover;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-slide.webp" alt="犬のイラスト">
			</div>
			<div class="h-[100%]">
				<img class="w-auto h-[100%]" style="max-width: none; object-fit: cover;" src="<?php echo get_template_directory_uri(); ?>/assets/image/footer-slide.webp" alt="犬のイラスト">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  <?php if (is_front_page()) : ?>
  let lastScrollTop = 0;
  const logo = document.querySelector('.header');

  window.addEventListener('scroll', function() {
    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    if (currentScroll > lastScrollTop) {
      スクロールダウン時にロゴを隠す
      logo.classList.add('is-hidden');
    } else {
      スクロールアップ時にロゴを元に戻す
      logo.classList.remove('is-hidden');
    }

    lastScrollTop = currentScroll;
  });
  <?php endif; ?>
});
</script>

<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const filters = document.querySelectorAll('.filter');
    const postList = document.querySelector('#post-list');

    filters.forEach(function (filter) {
        filter.addEventListener('click', function () {
            const selectedTag = this.getAttribute('data-filter');

            他のフィルターから 'active' クラスを外し、クリックしたフィルターに 'active' クラスを追加
            filters.forEach(f => f.classList.remove('active'));
            this.classList.add('active');

            AJAXリクエストでタグフィルタに基づいて投稿を取得
            const xhr = new XMLHttpRequest();
            const url = `<?php echo esc_url(home_url('/')); ?>?cat=<?php echo get_queried_object_id(); ?>&tag=${selectedTag}`;

            xhr.open('GET', url, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const parser = new DOMParser();
                    const responseDocument = parser.parseFromString(xhr.responseText, 'text/html');
                    const newPosts = responseDocument.querySelector('#post-list').innerHTML;
                    const pagination = responseDocument.querySelector('.post-pagination-wrapper').innerHTML;

                    投稿リストとページネーションを更新
                    postList.innerHTML = newPosts;
                    document.querySelector('.post-pagination-wrapper').innerHTML = pagination;
                }
            };
            xhr.send();
        });
    });
});
</script> -->

</body>
</html>
