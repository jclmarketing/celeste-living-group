<?php
/**
 * 404 — Page not found.
 * Copy editable under Appearance → Celeste Settings.
 */
get_header();

$clg_404_title = get_option('clg_404_title', "This page doesn't stack.");
$clg_404_text  = get_option('clg_404_text', "The page you're looking for has moved, or never existed. Let's get you back somewhere useful.");
$clg_404_btn   = get_option('clg_404_btn_text', 'Back to the homepage');
?>

<section class="page-hero">
  <div class="container">
    <div class="breadcrumb">404</div>
    <h1><?php echo esc_html($clg_404_title); ?></h1>
    <p class="lead"><?php echo clg_rich($clg_404_text); ?></p>
    <div class="mt-lg" style="display:flex; gap:14px; flex-wrap:wrap;">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary"><?php echo esc_html($clg_404_btn); ?> <span class="arrow">&rarr;</span></a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
