<?php
/**
 * Template: Compliance (from compliance.html)
 */
get_header();
$pid = get_the_ID();
$d   = clg_comp_defaults();

// === Registrations ===
$reg_eyebrow = clg_meta($pid, '_clg_comp_reg_eyebrow', $d['_clg_comp_reg_eyebrow']);
$reg_title   = clg_meta($pid, '_clg_comp_reg_title', $d['_clg_comp_reg_title']);
$reg_text    = clg_meta($pid, '_clg_comp_reg_text', $d['_clg_comp_reg_text']);
$reg_items   = clg_meta_repeater($pid, '_clg_comp_reg_items', $d['_clg_comp_reg_items']);

// === Investor onboarding ===
$inv_eyebrow  = clg_meta($pid, '_clg_comp_inv_eyebrow', $d['_clg_comp_inv_eyebrow']);
$inv_title    = clg_meta($pid, '_clg_comp_inv_title', $d['_clg_comp_inv_title']);
$inv_paras    = clg_meta($pid, '_clg_comp_inv_paras', $d['_clg_comp_inv_paras']);
$inv_btn_text = clg_meta($pid, '_clg_comp_inv_btn_text', $d['_clg_comp_inv_btn_text']);
$inv_btn_url  = clg_meta($pid, '_clg_comp_inv_btn_url', $d['_clg_comp_inv_btn_url']);
$pack_eyebrow = clg_meta($pid, '_clg_comp_pack_eyebrow', $d['_clg_comp_pack_eyebrow']);
$pack_title   = clg_meta($pid, '_clg_comp_pack_title', $d['_clg_comp_pack_title']);
$pack_text    = clg_meta($pid, '_clg_comp_pack_text', $d['_clg_comp_pack_text']);
$pack_groups  = clg_meta_repeater($pid, '_clg_comp_pack_groups', $d['_clg_comp_pack_groups']);

// === Why it matters ===
$why_eyebrow   = clg_meta($pid, '_clg_comp_why_eyebrow', $d['_clg_comp_why_eyebrow']);
$why_title     = clg_meta($pid, '_clg_comp_why_title', $d['_clg_comp_why_title']);
$why_paras     = clg_meta($pid, '_clg_comp_why_paras', $d['_clg_comp_why_paras']);
$why_image     = clg_meta($pid, '_clg_comp_why_image', $d['_clg_comp_why_image']);
$why_image_alt = clg_meta($pid, '_clg_comp_why_image_alt', $d['_clg_comp_why_image_alt']);

// Registration item icons (fixed SVGs, cycled by index — same order as the static site)
$reg_icons = array(
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l8 4v5c0 4.5-3.3 8.3-8 9-4.7-.7-8-4.5-8-9V7l8-4z"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 7h18M3 12h18M3 17h18"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 7h16v11H4z"/><path d="M4 11h16M9 7V4h6v3"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 20V8l8-4 8 4v12"/><path d="M9 20v-6h6v6"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 2v20M4 8h16M4 16h16"/></svg>',
);

get_template_part('template-parts/page-hero');
?>

<!-- OUR SIDE -->
<section>
  <div class="container">
    <div style="max-width:56ch;">
      <span class="eyebrow"><?php echo esc_html($reg_eyebrow); ?></span>
      <h2 class="mt-sm"><?php echo clg_rich($reg_title); ?></h2>
      <p class="mt-md"><?php echo clg_rich($reg_text); ?></p>
    </div>

    <div class="grid-3 mt-lg" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap:16px;">
      <?php foreach ($reg_items as $i => $item) : ?>
      <div class="compliance-item reveal">
        <span class="compliance-item__icon" aria-hidden="true">
          <?php echo $reg_icons[$i % count($reg_icons)]; // Fixed SVG icon set ?>
        </span>
        <div>
          <h3><?php echo esc_html(isset($item['title']) ? $item['title'] : ''); ?></h3>
          <p><?php echo clg_rich(isset($item['text']) ? $item['text'] : ''); ?></p>
        </div>
        <span class="tag"><?php echo esc_html(isset($item['tag']) ? $item['tag'] : ''); ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- INVESTOR SIDE -->
<section class="principles">
  <div class="container">
    <div class="split w-55">
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($inv_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($inv_title); ?></h2>
        <?php clg_paras($inv_paras); ?>
        <?php if ($inv_btn_text) : ?>
          <a href="<?php echo esc_url(clg_url($inv_btn_url)); ?>" class="btn btn--primary mt-md"><?php echo esc_html($inv_btn_text); ?> <span class="arrow">&rarr;</span></a>
        <?php endif; ?>
      </div>

      <div class="investor-checklist reveal">
        <span class="eyebrow" style="color:var(--sage-300);"><?php echo esc_html($pack_eyebrow); ?></span>
        <h3 style="color:var(--cream-100); margin-top:12px;"><?php echo clg_rich($pack_title); ?></h3>
        <p><?php echo clg_rich($pack_text); ?></p>

        <?php foreach ($pack_groups as $group) : ?>
        <div class="mt-md">
          <h4 style="color:var(--cream-100); font-size:15px; font-family:var(--font-body); letter-spacing:0.12em; text-transform:uppercase;"><?php echo esc_html(isset($group['heading']) ? $group['heading'] : ''); ?></h4>
          <ul class="checklist">
            <?php foreach (clg_list(isset($group['items']) ? $group['items'] : '') as $item) : ?>
              <li><?php echo clg_rich($item); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- WHY IT MATTERS -->
<section>
  <div class="container">
    <div class="split">
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($why_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($why_title); ?></h2>
        <?php clg_paras($why_paras); ?>
      </div>
      <div class="feature-image reveal">
        <img src="<?php echo esc_url($why_image); ?>" alt="<?php echo esc_attr($why_image_alt); ?>">
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<?php get_template_part('template-parts/cta-banner'); ?>

<?php get_footer(); ?>
