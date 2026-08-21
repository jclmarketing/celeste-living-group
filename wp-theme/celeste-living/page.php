<?php
/**
 * Generic page fallback — pages without a dedicated template.
 */
get_header();
?>

<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_option('clg_breadcrumb_home', 'Home')); ?></a> &middot; <?php the_title(); ?></div>
    <h1><?php the_title(); ?></h1>
  </div>
</section>

<section>
  <div class="container">
    <div class="legal" style="max-width:820px;">
      <?php
      while (have_posts()) {
          the_post();
          the_content();
      }
      ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
