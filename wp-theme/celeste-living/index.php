<?php
/**
 * Blog / archive fallback.
 */
get_header();
?>

<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_option('clg_breadcrumb_home', 'Home')); ?></a></div>
    <h1><?php echo is_home() ? esc_html(get_the_title(get_option('page_for_posts'))) : esc_html(get_the_archive_title()); ?></h1>
  </div>
</section>

<section>
  <div class="container">
    <div style="max-width:820px;">
      <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
          <article class="card" style="margin-bottom:24px;">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="mt-sm"><?php echo esc_html(get_the_excerpt()); ?></p>
          </article>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
