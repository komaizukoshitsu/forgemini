<main class="single-default px-4 lg:px-10 py-10">
    <article class="prose max-w-3xl mx-auto">
        <h1 class="text-2xl lg:text-3xl font-bold mb-6"><?php the_title(); ?></h1>

        <div class="text-sm text-gray-500 mb-4">
            公開日：<?php echo get_the_date(); ?>
        </div>

        <div class="post-content">
            <?php the_content(); ?>
        </div>
    </article>
</main>
