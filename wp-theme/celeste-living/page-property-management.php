<?php
/**
 * Template: Property Management (from property-management.html)
 */
get_header();
$pid = get_the_ID();
$d   = clg_pm_defaults();

// === What we do ===
$wwd_eyebrow   = clg_meta($pid, '_clg_pm_wwd_eyebrow', $d['_clg_pm_wwd_eyebrow']);
$wwd_title     = clg_meta($pid, '_clg_pm_wwd_title', $d['_clg_pm_wwd_title']);
$wwd_paras     = clg_meta($pid, '_clg_pm_wwd_paras', $d['_clg_pm_wwd_paras']);
$wwd_btn_text  = clg_meta($pid, '_clg_pm_wwd_btn_text', $d['_clg_pm_wwd_btn_text']);
$wwd_btn_url   = clg_meta($pid, '_clg_pm_wwd_btn_url', $d['_clg_pm_wwd_btn_url']);
$wwd_image     = clg_meta($pid, '_clg_pm_wwd_image', $d['_clg_pm_wwd_image']);
$wwd_image_alt = clg_meta($pid, '_clg_pm_wwd_image_alt', $d['_clg_pm_wwd_image_alt']);

// === Approach ===
$appr_eyebrow = clg_meta($pid, '_clg_pm_appr_eyebrow', $d['_clg_pm_appr_eyebrow']);
$appr_title   = clg_meta($pid, '_clg_pm_appr_title', $d['_clg_pm_appr_title']);
$appr_text    = clg_meta($pid, '_clg_pm_appr_text', $d['_clg_pm_appr_text']);
$values       = clg_meta_repeater($pid, '_clg_pm_values', $d['_clg_pm_values']);

// === What you get ===
$get_eyebrow  = clg_meta($pid, '_clg_pm_get_eyebrow', $d['_clg_pm_get_eyebrow']);
$get_title    = clg_meta($pid, '_clg_pm_get_title', $d['_clg_pm_get_title']);
$get_text     = clg_meta($pid, '_clg_pm_get_text', $d['_clg_pm_get_text']);
$get_btn_text = clg_meta($pid, '_clg_pm_get_btn_text', $d['_clg_pm_get_btn_text']);
$get_btn_url  = clg_meta($pid, '_clg_pm_get_btn_url', $d['_clg_pm_get_btn_url']);
$incl_eyebrow = clg_meta($pid, '_clg_pm_incl_eyebrow', $d['_clg_pm_incl_eyebrow']);
$incl_title   = clg_meta($pid, '_clg_pm_incl_title', $d['_clg_pm_incl_title']);
$incl_items   = clg_list(clg_meta($pid, '_clg_pm_incl_items', $d['_clg_pm_incl_items']));

// === The standard ===
$std_image     = clg_meta($pid, '_clg_pm_std_image', $d['_clg_pm_std_image']);
$std_image_alt = clg_meta($pid, '_clg_pm_std_image_alt', $d['_clg_pm_std_image_alt']);
$std_eyebrow   = clg_meta($pid, '_clg_pm_std_eyebrow', $d['_clg_pm_std_eyebrow']);
$std_quote     = clg_meta($pid, '_clg_pm_std_quote', $d['_clg_pm_std_quote']);
$std_text      = clg_meta($pid, '_clg_pm_std_text', $d['_clg_pm_std_text']);

// === Who this is for ===
$who_eyebrow   = clg_meta($pid, '_clg_pm_who_eyebrow', $d['_clg_pm_who_eyebrow']);
$who_title     = clg_meta($pid, '_clg_pm_who_title', $d['_clg_pm_who_title']);
$who_paras     = clg_meta($pid, '_clg_pm_who_paras', $d['_clg_pm_who_paras']);
$who_btn_text  = clg_meta($pid, '_clg_pm_who_btn_text', $d['_clg_pm_who_btn_text']);
$who_btn_url   = clg_meta($pid, '_clg_pm_who_btn_url', $d['_clg_pm_who_btn_url']);
$who_image     = clg_meta($pid, '_clg_pm_who_image', $d['_clg_pm_who_image']);
$who_image_alt = clg_meta($pid, '_clg_pm_who_image_alt', $d['_clg_pm_who_image_alt']);

get_template_part('template-parts/page-hero');
?>

<!-- WHAT WE DO -->
<section>
  <div class="container">
    <div class="split">
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($wwd_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($wwd_title); ?></h2>
        <?php clg_paras($wwd_paras); ?>
        <?php if ($wwd_btn_text) : ?>
          <a href="<?php echo esc_url(clg_url($wwd_btn_url)); ?>" class="btn btn--ghost mt-md"><?php echo esc_html($wwd_btn_text); ?> <span class="arrow">&rarr;</span></a>
        <?php endif; ?>
      </div>
      <div class="feature-image reveal">
        <img src="<?php echo esc_url($wwd_image); ?>" alt="<?php echo esc_attr($wwd_image_alt); ?>" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- OUR APPROACH -->
<section class="principles" id="approach">
  <div class="container">
    <div style="max-width:56ch;">
      <span class="eyebrow"><?php echo esc_html($appr_eyebrow); ?></span>
      <h2 class="mt-sm"><?php echo clg_rich($appr_title); ?></h2>
      <p class="mt-md"><?php echo clg_rich($appr_text); ?></p>
    </div>

    <div class="mt-lg">
      <?php foreach ($values as $value) : ?>
      <div class="value-row reveal">
        <span class="value-row__num"><?php echo esc_html(isset($value['num']) ? $value['num'] : ''); ?></span>
        <div>
          <h3><?php echo esc_html(isset($value['title']) ? $value['title'] : ''); ?></h3>
          <p><?php echo clg_rich(isset($value['text']) ? $value['text'] : ''); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- WHAT YOU GET -->
<section id="what-you-get">
  <div class="container">
    <div class="split w-35">
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($get_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($get_title); ?></h2>
        <p class="mt-md"><?php echo clg_rich($get_text); ?></p>
        <?php if ($get_btn_text) : ?>
          <a href="<?php echo esc_url(clg_url($get_btn_url)); ?>" class="btn btn--primary mt-md"><?php echo esc_html($get_btn_text); ?> <span class="arrow">&rarr;</span></a>
        <?php endif; ?>
      </div>
      <div class="reveal">
        <div class="investor-checklist">
          <span class="eyebrow" style="color:var(--sage-300);"><?php echo esc_html($incl_eyebrow); ?></span>
          <h3 style="margin-top:14px; font-size:28px;"><?php echo clg_rich($incl_title); ?></h3>
          <ul class="checklist">
            <?php foreach ($incl_items as $item) : ?>
              <li><?php echo clg_rich($item); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- QUOTE / STANDARD -->
<section class="principles">
  <div class="container">
    <div class="split">
      <div class="feature-image reveal">
        <img src="<?php echo esc_url($std_image); ?>" alt="<?php echo esc_attr($std_image_alt); ?>" loading="lazy">
      </div>
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($std_eyebrow); ?></span>
        <h2 class="mt-sm" style="font-size:clamp(28px,3.2vw,46px); line-height:1.12;">
          <span class="script">&ldquo;</span><?php echo clg_rich($std_quote); ?><span class="script">&rdquo;</span>
        </h2>
        <p class="mt-md"><?php echo clg_rich($std_text); ?></p>
      </div>
    </div>
  </div>
</section>

<!-- WHO THIS IS FOR -->
<section>
  <div class="container">
    <div class="split">
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($who_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($who_title); ?></h2>
        <?php clg_paras($who_paras); ?>
        <?php if ($who_btn_text) : ?>
          <a href="<?php echo esc_url(clg_url($who_btn_url)); ?>" class="btn btn--ghost mt-md"><?php echo esc_html($who_btn_text); ?> <span class="arrow">&rarr;</span></a>
        <?php endif; ?>
      </div>
      <div class="tall-image reveal">
        <img src="<?php echo esc_url($who_image); ?>" alt="<?php echo esc_attr($who_image_alt); ?>" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<?php get_template_part('template-parts/cta-banner'); ?>

<?php get_footer(); ?>
