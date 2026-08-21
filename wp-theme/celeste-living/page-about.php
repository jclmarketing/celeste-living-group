<?php
/**
 * Template: About (from about.html)
 */
get_header();
$pid = get_the_ID();
$d   = clg_about_defaults();

// === Why property ===
$why_image     = clg_meta($pid, '_clg_about_why_image', $d['_clg_about_why_image']);
$why_image_alt = clg_meta($pid, '_clg_about_why_image_alt', $d['_clg_about_why_image_alt']);
$why_eyebrow   = clg_meta($pid, '_clg_about_why_eyebrow', $d['_clg_about_why_eyebrow']);
$why_title     = clg_meta($pid, '_clg_about_why_title', $d['_clg_about_why_title']);
$why_paras     = clg_meta($pid, '_clg_about_why_paras', $d['_clg_about_why_paras']);

// === Founders ===
$founders_eyebrow = clg_meta($pid, '_clg_about_founders_eyebrow', $d['_clg_about_founders_eyebrow']);
$founders_title   = clg_meta($pid, '_clg_about_founders_title', $d['_clg_about_founders_title']);
$founders_text    = clg_meta($pid, '_clg_about_founders_text', $d['_clg_about_founders_text']);
$founders = array();
for ($i = 1; $i <= 2; $i++) {
    $founders[] = array(
        'image' => clg_meta($pid, "_clg_about_f{$i}_image", $d["_clg_about_f{$i}_image"]),
        'name'  => clg_meta($pid, "_clg_about_f{$i}_name", $d["_clg_about_f{$i}_name"]),
        'role'  => clg_meta($pid, "_clg_about_f{$i}_role", $d["_clg_about_f{$i}_role"]),
        'bio'   => clg_meta($pid, "_clg_about_f{$i}_bio", $d["_clg_about_f{$i}_bio"]),
    );
}

// === Where we work ===
$where_eyebrow   = clg_meta($pid, '_clg_about_where_eyebrow', $d['_clg_about_where_eyebrow']);
$where_title     = clg_meta($pid, '_clg_about_where_title', $d['_clg_about_where_title']);
$where_text      = clg_meta($pid, '_clg_about_where_text', $d['_clg_about_where_text']);
$where_stats     = clg_meta_repeater($pid, '_clg_about_where_stats', $d['_clg_about_where_stats']);
$where_image     = clg_meta($pid, '_clg_about_where_image', $d['_clg_about_where_image']);
$where_image_alt = clg_meta($pid, '_clg_about_where_image_alt', $d['_clg_about_where_image_alt']);

// === How we work (values) ===
$how_eyebrow = clg_meta($pid, '_clg_about_how_eyebrow', $d['_clg_about_how_eyebrow']);
$how_title   = clg_meta($pid, '_clg_about_how_title', $d['_clg_about_how_title']);
$how_text    = clg_meta($pid, '_clg_about_how_text', $d['_clg_about_how_text']);
$values      = clg_meta_repeater($pid, '_clg_about_values', $d['_clg_about_values']);

// === What we're building ===
$build_image     = clg_meta($pid, '_clg_about_build_image', $d['_clg_about_build_image']);
$build_image_alt = clg_meta($pid, '_clg_about_build_image_alt', $d['_clg_about_build_image_alt']);
$build_eyebrow   = clg_meta($pid, '_clg_about_build_eyebrow', $d['_clg_about_build_eyebrow']);
$build_title     = clg_meta($pid, '_clg_about_build_title', $d['_clg_about_build_title']);
$build_para      = clg_meta($pid, '_clg_about_build_para', $d['_clg_about_build_para']);
$build_quote     = clg_meta($pid, '_clg_about_build_quote', $d['_clg_about_build_quote']);
$build_btn1_text = clg_meta($pid, '_clg_about_build_btn1_text', $d['_clg_about_build_btn1_text']);
$build_btn1_url  = clg_meta($pid, '_clg_about_build_btn1_url', $d['_clg_about_build_btn1_url']);
$build_btn2_text = clg_meta($pid, '_clg_about_build_btn2_text', $d['_clg_about_build_btn2_text']);
$build_btn2_url  = clg_meta($pid, '_clg_about_build_btn2_url', $d['_clg_about_build_btn2_url']);

// === Social ===
$social_eyebrow  = clg_meta($pid, '_clg_about_social_eyebrow', $d['_clg_about_social_eyebrow']);
$social_title    = clg_meta($pid, '_clg_about_social_title', $d['_clg_about_social_title']);
$social_text     = clg_meta($pid, '_clg_about_social_text', $d['_clg_about_social_text']);
$social_btn_text = clg_meta($pid, '_clg_about_social_btn_text', $d['_clg_about_social_btn_text']);
$social_btn_url  = clg_meta($pid, '_clg_about_social_btn_url', $d['_clg_about_social_btn_url']);
$social_cards = array();
for ($i = 1; $i <= 4; $i++) {
    $social_cards[] = array(
        'image'   => clg_meta($pid, "_clg_about_sc{$i}_image", $d["_clg_about_sc{$i}_image"]),
        'caption' => clg_meta($pid, "_clg_about_sc{$i}_caption", $d["_clg_about_sc{$i}_caption"]),
    );
}
$social_note = clg_meta($pid, '_clg_about_social_note', $d['_clg_about_social_note']);

get_template_part('template-parts/page-hero');
?>

<!-- WHY PROPERTY -->
<section>
  <div class="container">
    <div class="split">
      <div class="tall-image reveal">
        <img src="<?php echo esc_url($why_image); ?>" alt="<?php echo esc_attr($why_image_alt); ?>" loading="lazy">
      </div>
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($why_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($why_title); ?></h2>
        <?php clg_paras($why_paras); ?>
      </div>
    </div>
  </div>
</section>

<!-- FOUNDERS -->
<section class="principles">
  <div class="container">
    <div style="max-width:56ch;">
      <span class="eyebrow"><?php echo esc_html($founders_eyebrow); ?></span>
      <h2 class="mt-sm"><?php echo clg_rich($founders_title); ?></h2>
      <p class="mt-md"><?php echo clg_rich($founders_text); ?></p>
    </div>

    <div class="grid-3 mt-lg" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
      <?php foreach ($founders as $founder) : ?>
      <article class="card reveal">
        <div class="feature-image" style="aspect-ratio:4/3; margin-bottom:24px;">
          <img src="<?php echo esc_url($founder['image']); ?>" alt="<?php echo esc_attr($founder['name']); ?>" loading="lazy">
        </div>
        <h3><?php echo esc_html($founder['name']); ?></h3>
        <p style="margin-top:10px; color:var(--forest-600); font-size:13px; letter-spacing:0.14em; text-transform:uppercase; font-weight:600;"><?php echo esc_html($founder['role']); ?></p>
        <p class="mt-sm"><?php echo clg_rich($founder['bio']); ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- WHERE WE WORK -->
<section>
  <div class="container">
    <div class="split w-35">
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($where_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($where_title); ?></h2>
        <p class="mt-md"><?php echo clg_rich($where_text); ?></p>
        <div class="hero__meta" style="margin-top:40px;">
          <?php foreach ($where_stats as $stat) : ?>
          <div><strong><?php echo esc_html(isset($stat['number']) ? $stat['number'] : ''); ?></strong><span><?php echo esc_html(isset($stat['label']) ? $stat['label'] : ''); ?></span></div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="feature-image reveal">
        <img src="<?php echo esc_url($where_image); ?>" alt="<?php echo esc_attr($where_image_alt); ?>" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- HOW WE WORK -->
<section class="principles">
  <div class="container">
    <div style="max-width:56ch;">
      <span class="eyebrow"><?php echo esc_html($how_eyebrow); ?></span>
      <h2 class="mt-sm"><?php echo clg_rich($how_title); ?></h2>
      <p class="mt-md"><?php echo clg_rich($how_text); ?></p>
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

<!-- WHAT WE'RE BUILDING -->
<section>
  <div class="container">
    <div class="split">
      <div class="feature-image reveal">
        <img src="<?php echo esc_url($build_image); ?>" alt="<?php echo esc_attr($build_image_alt); ?>" loading="lazy">
      </div>
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($build_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($build_title); ?></h2>
        <p class="mt-md"><?php echo clg_rich($build_para); ?></p>
        <p class="mt-md" style="font-family:var(--font-display); font-style:italic; font-size:clamp(20px,2.4vw,28px); line-height:1.35; color:var(--forest-800); max-width:34ch;"><?php echo clg_rich($build_quote); ?></p>
        <div class="mt-md" style="display:flex; gap:14px; flex-wrap:wrap;">
          <?php if ($build_btn1_text) : ?>
            <a href="<?php echo esc_url(clg_url($build_btn1_url)); ?>" class="btn btn--ghost"><?php echo esc_html($build_btn1_text); ?> <span class="arrow">&rarr;</span></a>
          <?php endif; ?>
          <?php if ($build_btn2_text) : ?>
            <a href="<?php echo esc_url(clg_url($build_btn2_url)); ?>" class="btn btn--primary"><?php echo esc_html($build_btn2_text); ?> <span class="arrow">&rarr;</span></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SOCIAL -->
<section class="principles">
  <div class="container">
    <div style="display:flex; justify-content:space-between; align-items:end; gap:24px; flex-wrap:wrap;">
      <div style="max-width:56ch;">
        <span class="eyebrow"><?php echo esc_html($social_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($social_title); ?></h2>
        <p class="mt-md"><?php echo clg_rich($social_text); ?></p>
      </div>
      <?php if ($social_btn_text) : ?>
      <div style="display:flex; gap:12px;">
        <a href="<?php echo esc_url(clg_url($social_btn_url)); ?>" target="_blank" rel="noopener" class="btn btn--ghost"><?php echo esc_html($social_btn_text); ?></a>
      </div>
      <?php endif; ?>
    </div>

    <div class="socials mt-lg reveal">
      <?php foreach ($social_cards as $card) : ?>
      <a href="<?php echo esc_url(clg_url($social_btn_url)); ?>" target="_blank" rel="noopener" class="social-card">
        <img src="<?php echo esc_url($card['image']); ?>" alt="<?php echo esc_attr($card['caption']); ?>">
        <div class="social-card__platform" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.8.1 1.2.1 1.9.3 2.3.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.4 1.1.4 2.3.1 1.2.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 1.2-.3 1.9-.4 2.3-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1.1.4-2.3.4-1.2.1-1.6.1-4.8.1s-3.6 0-4.8-.1c-1.2-.1-1.9-.3-2.3-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.4-1.1-.4-2.3-.1-1.2-.1-1.6-.1-4.8s0-3.6.1-4.8c.1-1.2.3-1.9.4-2.3.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1.1-.4 2.3-.4 1.2-.1 1.6-.1 4.8-.1M12 0C8.7 0 8.3 0 7.1.1 5.8.1 5 .3 4.2.6 3.4.9 2.7 1.3 2 2c-.7.7-1.1 1.4-1.4 2.2C.3 5 .1 5.8.1 7.1 0 8.3 0 8.7 0 12s0 3.7.1 4.9c.1 1.3.3 2.1.6 2.9.3.8.7 1.5 1.4 2.2.7.7 1.4 1.1 2.2 1.4.8.3 1.6.5 2.9.6 1.2.1 1.6.1 4.9.1s3.7 0 4.9-.1c1.3-.1 2.1-.3 2.9-.6.8-.3 1.5-.7 2.2-1.4.7-.7 1.1-1.4 1.4-2.2.3-.8.5-1.6.6-2.9.1-1.2.1-1.6.1-4.9s0-3.7-.1-4.9c-.1-1.3-.3-2.1-.6-2.9-.3-.8-.7-1.5-1.4-2.2C21.3 1.3 20.6.9 19.8.6 19 .3 18.2.1 16.9.1 15.7 0 15.3 0 12 0zm0 5.8A6.2 6.2 0 1 0 18.2 12 6.2 6.2 0 0 0 12 5.8zm0 10.2A4 4 0 1 1 16 12a4 4 0 0 1-4 4zm6.4-11.8a1.4 1.4 0 1 0 1.5 1.4 1.4 1.4 0 0 0-1.5-1.4z"/></svg>
        </div>
        <div class="social-card__overlay">
          <span class="social-card__caption"><?php echo clg_rich($card['caption']); ?></span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php if ($social_note) : ?>
      <p class="form-note mt-md"><?php echo clg_rich($social_note); ?></p>
    <?php endif; ?>
  </div>
</section>

<!-- CTA -->
<?php get_template_part('template-parts/cta-banner'); ?>

<?php get_footer(); ?>
