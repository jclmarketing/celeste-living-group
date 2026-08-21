<?php
/**
 * Template: How We Work / Customer Journey (from customer-journey.html)
 */
get_header();
$pid = get_the_ID();
$d   = clg_cj_defaults();

$get_heading = clg_meta($pid, '_clg_cj_get_heading', $d['_clg_cj_get_heading']);
$steps       = clg_meta_repeater($pid, '_clg_cj_steps', $d['_clg_cj_steps']);

$book_eyebrow  = clg_meta($pid, '_clg_cj_book_eyebrow', $d['_clg_cj_book_eyebrow']);
$book_title    = clg_meta($pid, '_clg_cj_book_title', $d['_clg_cj_book_title']);
$book_text     = clg_meta($pid, '_clg_cj_book_text', $d['_clg_cj_book_text']);
$book_features = clg_meta_repeater($pid, '_clg_cj_book_features', $d['_clg_cj_book_features']);
$calendly_url  = clg_meta($pid, '_clg_cj_calendly_url', $d['_clg_cj_calendly_url']);
$book_note     = clg_meta($pid, '_clg_cj_book_note', $d['_clg_cj_book_note']);
$noscript_text = clg_meta($pid, '_clg_cj_noscript', $d['_clg_cj_noscript']);

$faq_eyebrow = clg_meta($pid, '_clg_cj_faq_eyebrow', $d['_clg_cj_faq_eyebrow']);
$faq_title   = clg_meta($pid, '_clg_cj_faq_title', $d['_clg_cj_faq_title']);
$faqs        = clg_meta_repeater($pid, '_clg_cj_faqs', $d['_clg_cj_faqs']);

// Feature bullet icons (fixed SVGs, cycled by index)
$feature_icons = array(
    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15 10l4-4-4-4M5 20v-5a4 4 0 0 1 4-4h10"/></svg>',
    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2l9 4.5v7c0 4.5-4 9-9 10-5-1-9-5.5-9-10v-7L12 2z"/></svg>',
);

get_template_part('template-parts/page-hero');
?>

<!-- STEPS -->
<section>
  <div class="container">
    <div class="steps">
      <?php foreach ($steps as $step) : ?>
      <div class="step reveal">
        <div class="step__num"><?php echo esc_html(isset($step['num']) ? $step['num'] : ''); ?> <span><?php echo esc_html(isset($step['label']) ? $step['label'] : ''); ?></span></div>
        <div>
          <h3><?php echo esc_html(isset($step['title']) ? $step['title'] : ''); ?></h3>
          <p><?php echo clg_rich(isset($step['text']) ? $step['text'] : ''); ?></p>
        </div>
        <div>
          <p style="color:var(--ink-500); font-size:14px; font-weight:600; letter-spacing:0.14em; text-transform:uppercase;"><?php echo esc_html($get_heading); ?></p>
          <p class="mt-sm"><?php echo clg_rich(isset($step['get_text']) ? $step['get_text'] : ''); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CALENDLY BOOKING -->
<section class="principles" id="book">
  <div class="container">
    <div class="split w-35">
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($book_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($book_title); ?></h2>
        <p class="mt-md"><?php echo clg_rich($book_text); ?></p>
        <div style="margin-top:28px; display:grid; gap:14px;">
          <?php foreach ($book_features as $i => $feature) : ?>
          <div style="display:flex; gap:12px; align-items:center;">
            <span style="width:38px; height:38px; border-radius:999px; background:var(--sage-100); color:var(--forest-800); display:inline-flex; align-items:center; justify-content:center;">
              <?php echo $feature_icons[$i % count($feature_icons)]; // Fixed SVG icon set ?>
            </span>
            <div>
              <strong style="display:block; font-family:var(--font-display); font-size:20px; color:var(--forest-800);"><?php echo esc_html(isset($feature['title']) ? $feature['title'] : ''); ?></strong>
              <span style="color:var(--ink-500); font-size:14px;"><?php echo esc_html(isset($feature['text']) ? $feature['text'] : ''); ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="reveal">
        <div class="calendly-wrap">
          <div class="calendly-inline-widget"
               data-url="<?php echo esc_url($calendly_url); ?>"
               style="min-width:320px; height:720px;"></div>
          <noscript>
            <p><?php echo clg_rich($noscript_text); ?></p>
          </noscript>
        </div>
        <p class="form-note" style="text-align:center;"><?php echo clg_rich($book_note); ?></p>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section>
  <div class="container">
    <div style="max-width:56ch;">
      <span class="eyebrow"><?php echo esc_html($faq_eyebrow); ?></span>
      <h2 class="mt-sm"><?php echo clg_rich($faq_title); ?></h2>
    </div>

    <div class="mt-lg" style="max-width:820px;">
      <?php foreach ($faqs as $faq) : ?>
      <details class="value-row reveal" style="cursor:pointer; grid-template-columns:1fr;">
        <summary style="list-style:none; display:flex; justify-content:space-between; align-items:center; gap:20px;">
          <h3 style="font-size:22px;"><?php echo esc_html(isset($faq['question']) ? $faq['question'] : ''); ?></h3>
          <span style="font-family:var(--font-display); font-size:28px; color:var(--forest-600);">+</span>
        </summary>
        <p class="mt-md"><?php echo clg_rich(isset($faq['answer']) ? $faq['answer'] : ''); ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
