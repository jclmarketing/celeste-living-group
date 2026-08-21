<?php
/**
 * Template: Terms of Business (from terms.html)
 */
get_header();
$pid = get_the_ID();
$d   = clg_terms_defaults();

$terms_updated = clg_meta($pid, '_clg_terms_updated', $d['_clg_terms_updated']);
$terms_body    = clg_meta($pid, '_clg_terms_body', $d['_clg_terms_body']);

get_template_part('template-parts/page-hero', null, array(
    'after_lead' => '<p style="margin-top:20px; color:var(--ink-500); font-size:14px;">' . esc_html($terms_updated) . '</p>',
));
?>

<section>
  <div class="container">
    <div class="legal" style="max-width:820px;">
      <?php echo wp_kses_post($terms_body); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
