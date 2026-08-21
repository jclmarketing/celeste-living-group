<?php
/**
 * Shared dark-green CTA banner.
 * Values from the CTA Banner metabox with per-slug defaults.
 */
$pid  = get_the_ID();
$slug = get_post_field('post_name', $pid);
$cd   = clg_cta_defaults($slug);

$cta_eyebrow   = clg_meta($pid, '_clg_cta_eyebrow', $cd['eyebrow']);
$cta_title     = clg_meta($pid, '_clg_cta_title', $cd['title']);
$cta_text      = clg_meta($pid, '_clg_cta_text', $cd['text']);
$cta_btn1_text = clg_meta($pid, '_clg_cta_btn1_text', $cd['btn1_text']);
$cta_btn1_url  = clg_meta($pid, '_clg_cta_btn1_url', $cd['btn1_url']);
$cta_btn2_text = clg_meta($pid, '_clg_cta_btn2_text', $cd['btn2_text']);
$cta_btn2_url  = clg_meta($pid, '_clg_cta_btn2_url', $cd['btn2_url']);
$cta_title_max = $cd['title_max'];
?>
<section>
  <div class="container">
    <div class="reveal" style="background:var(--forest-900); color:var(--cream-100); border-radius:var(--radius-lg); padding:clamp(40px, 5vw, 84px); text-align:center; position:relative; overflow:hidden;">
      <span class="eyebrow" style="color:var(--sage-300);"><?php echo esc_html($cta_eyebrow); ?></span>
      <h2 style="color:var(--cream-100); margin-top:16px; max-width:<?php echo esc_attr($cta_title_max); ?>; margin-inline:auto;"><?php echo clg_rich($cta_title); ?></h2>
      <p style="color:rgba(247,244,237,0.78); margin:20px auto 0; max-width:50ch;"><?php echo clg_rich($cta_text); ?></p>
      <div style="margin-top:36px; display:flex; gap:14px; flex-wrap:wrap; justify-content:center;">
        <?php if ($cta_btn1_text) : ?>
          <a href="<?php echo esc_url(clg_url($cta_btn1_url)); ?>" class="btn btn--light"><?php echo esc_html($cta_btn1_text); ?></a>
        <?php endif; ?>
        <?php if ($cta_btn2_text) : ?>
          <a href="<?php echo esc_url(clg_url($cta_btn2_url)); ?>" class="btn btn--ghost" style="color:var(--cream-100); border-color:rgba(247,244,237,0.4);"><?php echo esc_html($cta_btn2_text); ?></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
