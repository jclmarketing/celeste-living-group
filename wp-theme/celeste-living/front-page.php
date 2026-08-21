<?php
/**
 * Front Page — Homepage (from home.html)
 */
get_header();
$pid = get_the_ID();
$d   = clg_home_defaults();

// === Hero ===
$hero_eyebrow   = clg_meta($pid, '_clg_home_hero_eyebrow', $d['_clg_home_hero_eyebrow']);
$hero_title     = clg_meta($pid, '_clg_home_hero_title', $d['_clg_home_hero_title']);
$hero_lead      = clg_meta($pid, '_clg_home_hero_lead', $d['_clg_home_hero_lead']);
$hero_btn1_text = clg_meta($pid, '_clg_home_hero_btn1_text', $d['_clg_home_hero_btn1_text']);
$hero_btn1_url  = clg_meta($pid, '_clg_home_hero_btn1_url', $d['_clg_home_hero_btn1_url']);
$hero_btn2_text = clg_meta($pid, '_clg_home_hero_btn2_text', $d['_clg_home_hero_btn2_text']);
$hero_btn2_url  = clg_meta($pid, '_clg_home_hero_btn2_url', $d['_clg_home_hero_btn2_url']);
$hero_stats     = clg_meta_repeater($pid, '_clg_home_hero_stats', $d['_clg_home_hero_stats']);
$hero_image     = clg_meta($pid, '_clg_home_hero_image', $d['_clg_home_hero_image']);
$hero_image_alt = clg_meta($pid, '_clg_home_hero_image_alt', $d['_clg_home_hero_image_alt']);
$hero_badge     = clg_meta($pid, '_clg_home_hero_badge', $d['_clg_home_hero_badge']);
$marquee_items  = clg_list(clg_meta($pid, '_clg_home_marquee', $d['_clg_home_marquee']));

// === What we do ===
$wwd_eyebrow  = clg_meta($pid, '_clg_home_wwd_eyebrow', $d['_clg_home_wwd_eyebrow']);
$wwd_title    = clg_meta($pid, '_clg_home_wwd_title', $d['_clg_home_wwd_title']);
$wwd_text     = clg_meta($pid, '_clg_home_wwd_text', $d['_clg_home_wwd_text']);
$wwd_btn_text = clg_meta($pid, '_clg_home_wwd_btn_text', $d['_clg_home_wwd_btn_text']);
$wwd_btn_url  = clg_meta($pid, '_clg_home_wwd_btn_url', $d['_clg_home_wwd_btn_url']);
$wwd_cards    = clg_meta_repeater($pid, '_clg_home_wwd_cards', $d['_clg_home_wwd_cards']);

// === Principles ===
$prin_eyebrow = clg_meta($pid, '_clg_home_prin_eyebrow', $d['_clg_home_prin_eyebrow']);
$prin_title   = clg_meta($pid, '_clg_home_prin_title', $d['_clg_home_prin_title']);
$prin_text    = clg_meta($pid, '_clg_home_prin_text', $d['_clg_home_prin_text']);
$principles   = clg_meta_repeater($pid, '_clg_home_principles', $d['_clg_home_principles']);

// === Image / Quote ===
$quote_image     = clg_meta($pid, '_clg_home_quote_image', $d['_clg_home_quote_image']);
$quote_image_alt = clg_meta($pid, '_clg_home_quote_image_alt', $d['_clg_home_quote_image_alt']);
$quote_eyebrow   = clg_meta($pid, '_clg_home_quote_eyebrow', $d['_clg_home_quote_eyebrow']);
$quote_text      = clg_meta($pid, '_clg_home_quote_text', $d['_clg_home_quote_text']);
$quote_para      = clg_meta($pid, '_clg_home_quote_para', $d['_clg_home_quote_para']);
$quote_btn_text  = clg_meta($pid, '_clg_home_quote_btn_text', $d['_clg_home_quote_btn_text']);
$quote_btn_url   = clg_meta($pid, '_clg_home_quote_btn_url', $d['_clg_home_quote_btn_url']);

// === How we work (preview) ===
$how_eyebrow  = clg_meta($pid, '_clg_home_how_eyebrow', $d['_clg_home_how_eyebrow']);
$how_title    = clg_meta($pid, '_clg_home_how_title', $d['_clg_home_how_title']);
$how_text     = clg_meta($pid, '_clg_home_how_text', $d['_clg_home_how_text']);
$how_steps    = clg_meta_repeater($pid, '_clg_home_how_steps', $d['_clg_home_how_steps']);
$how_btn_text = clg_meta($pid, '_clg_home_how_btn_text', $d['_clg_home_how_btn_text']);
$how_btn_url  = clg_meta($pid, '_clg_home_how_btn_url', $d['_clg_home_how_btn_url']);
?>

<!-- HERO -->
<section class="hero">
  <div class="container">
    <div class="hero__grid">
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($hero_eyebrow); ?></span>
        <h1 class="mt-sm"><?php echo clg_rich($hero_title); ?></h1>
        <p class="lead mt-md"><?php echo clg_rich($hero_lead); ?></p>
        <div class="mt-lg" style="display:flex; gap:14px; flex-wrap:wrap;">
          <?php if ($hero_btn1_text) : ?>
            <a href="<?php echo esc_url(clg_url($hero_btn1_url)); ?>" class="btn btn--primary"><?php echo esc_html($hero_btn1_text); ?> <span class="arrow">&rarr;</span></a>
          <?php endif; ?>
          <?php if ($hero_btn2_text) : ?>
            <a href="<?php echo esc_url(clg_url($hero_btn2_url)); ?>" class="btn btn--ghost"><?php echo esc_html($hero_btn2_text); ?></a>
          <?php endif; ?>
        </div>
        <div class="hero__meta">
          <?php foreach ($hero_stats as $stat) : ?>
          <div>
            <strong><?php echo esc_html(isset($stat['number']) ? $stat['number'] : ''); ?></strong><span><?php echo esc_html(isset($stat['label']) ? $stat['label'] : ''); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="hero__visual reveal">
        <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_image_alt); ?>" loading="eager">
        <p class="badge"><?php echo clg_rich($hero_badge); ?></p>
      </div>
    </div>
  </div>

  <div class="marquee" aria-hidden="true">
    <div class="marquee__track">
      <?php for ($m = 0; $m < 2; $m++) : foreach ($marquee_items as $item) : ?><span><?php echo esc_html($item); ?></span><?php endforeach; endfor; ?>
    </div>
  </div>
</section>

<!-- WHAT WE DO -->
<section>
  <div class="container">
    <div class="split w-35">
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($wwd_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($wwd_title); ?></h2>
        <p class="mt-md"><?php echo clg_rich($wwd_text); ?></p>
        <?php if ($wwd_btn_text) : ?>
          <a href="<?php echo esc_url(clg_url($wwd_btn_url)); ?>" class="btn btn--ghost mt-md"><?php echo esc_html($wwd_btn_text); ?> <span class="arrow">&rarr;</span></a>
        <?php endif; ?>
      </div>
      <div class="grid-3 reveal" style="grid-template-columns:1fr;">
        <?php foreach ($wwd_cards as $card) : ?>
        <article class="card">
          <span class="card__num"><?php echo esc_html(isset($card['num']) ? $card['num'] : ''); ?></span>
          <h3><?php echo esc_html(isset($card['title']) ? $card['title'] : ''); ?></h3>
          <p><?php echo clg_rich(isset($card['text']) ? $card['text'] : ''); ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- PRINCIPLES -->
<section class="principles">
  <div class="container">
    <div style="max-width:56ch;">
      <span class="eyebrow"><?php echo esc_html($prin_eyebrow); ?></span>
      <h2 class="mt-sm"><?php echo clg_rich($prin_title); ?></h2>
      <p class="mt-md"><?php echo clg_rich($prin_text); ?></p>
    </div>

    <div class="mt-lg">
      <?php foreach ($principles as $value) : ?>
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

<!-- IMAGE / QUOTE -->
<section>
  <div class="container">
    <div class="split">
      <div class="feature-image reveal">
        <img src="<?php echo esc_url($quote_image); ?>" alt="<?php echo esc_attr($quote_image_alt); ?>" loading="lazy">
      </div>
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($quote_eyebrow); ?></span>
        <h2 class="mt-sm" style="font-size:clamp(28px,3.2vw,46px); line-height:1.12;">
          <span class="script">&ldquo;</span><?php echo clg_rich($quote_text); ?><span class="script">&rdquo;</span>
        </h2>
        <p class="mt-md"><?php echo clg_rich($quote_para); ?></p>
        <?php if ($quote_btn_text) : ?>
          <a href="<?php echo esc_url(clg_url($quote_btn_url)); ?>" class="btn btn--ghost mt-md"><?php echo esc_html($quote_btn_text); ?> <span class="arrow">&rarr;</span></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS (preview) -->
<section class="principles">
  <div class="container">
    <div class="split w-55">
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($how_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($how_title); ?></h2>
        <p class="mt-md"><?php echo clg_rich($how_text); ?></p>
      </div>
      <div class="steps reveal">
        <?php foreach ($how_steps as $step) : ?>
        <div class="step">
          <div class="step__num"><?php echo esc_html(isset($step['num']) ? $step['num'] : ''); ?> <span><?php echo esc_html(isset($step['label']) ? $step['label'] : ''); ?></span></div>
          <div>
            <h3><?php echo esc_html(isset($step['title']) ? $step['title'] : ''); ?></h3>
            <p><?php echo clg_rich(isset($step['text']) ? $step['text'] : ''); ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php if ($how_btn_text) : ?>
    <div style="text-align:center; margin-top:48px;" class="reveal">
      <a href="<?php echo esc_url(clg_url($how_btn_url)); ?>" class="btn btn--primary"><?php echo esc_html($how_btn_text); ?> <span class="arrow">&rarr;</span></a>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- CTA -->
<?php get_template_part('template-parts/cta-banner'); ?>

<?php get_footer(); ?>
