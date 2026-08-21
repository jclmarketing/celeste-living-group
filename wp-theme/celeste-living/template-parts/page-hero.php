<?php
/**
 * Shared inner-page hero.
 * Pull values from the Page Hero metabox with per-slug defaults.
 * Optional $args['after_lead'] — extra escaped HTML rendered after the lead.
 */
$pid  = get_the_ID();
$slug = get_post_field('post_name', $pid);
$hd   = clg_hero_defaults($slug);

$hero_breadcrumb = clg_meta($pid, '_clg_hero_breadcrumb', $hd['breadcrumb']);
$hero_title      = clg_meta($pid, '_clg_hero_title', $hd['title']);
$hero_lead       = clg_meta($pid, '_clg_hero_lead', $hd['lead']);
$hero_btn1_text  = clg_meta($pid, '_clg_hero_btn1_text', $hd['btn1_text']);
$hero_btn1_url   = clg_meta($pid, '_clg_hero_btn1_url', $hd['btn1_url']);
$hero_btn2_text  = clg_meta($pid, '_clg_hero_btn2_text', $hd['btn2_text']);
$hero_btn2_url   = clg_meta($pid, '_clg_hero_btn2_url', $hd['btn2_url']);
?>
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_option('clg_breadcrumb_home', 'Home')); ?></a> &middot; <?php echo esc_html($hero_breadcrumb); ?></div>
    <h1><?php echo clg_rich($hero_title); ?></h1>
    <p class="lead"><?php echo clg_rich($hero_lead); ?></p>
    <?php if (!empty($args['after_lead'])) echo $args['after_lead']; ?>
    <?php if ($hero_btn1_text || $hero_btn2_text) : ?>
    <div class="mt-lg" style="display:flex; gap:14px; flex-wrap:wrap;">
      <?php if ($hero_btn1_text) : ?>
        <a href="<?php echo esc_url(clg_url($hero_btn1_url)); ?>" class="btn btn--primary"><?php echo esc_html($hero_btn1_text); ?> <span class="arrow">&rarr;</span></a>
      <?php endif; ?>
      <?php if ($hero_btn2_text) : ?>
        <a href="<?php echo esc_url(clg_url($hero_btn2_url)); ?>" class="btn btn--ghost"><?php echo esc_html($hero_btn2_text); ?></a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
