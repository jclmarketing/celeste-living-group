<?php
/**
 * Template: Contact (from contact.html)
 * The form posts directly to FormSubmit (plain HTML form — no plugins).
 */
get_header();
$pid = get_the_ID();
$d   = clg_contact_defaults();

// === Contact methods ===
$methods_eyebrow = clg_meta($pid, '_clg_contact_methods_eyebrow', $d['_clg_contact_methods_eyebrow']);
$methods_title   = clg_meta($pid, '_clg_contact_methods_title', $d['_clg_contact_methods_title']);
$methods_text    = clg_meta($pid, '_clg_contact_methods_text', $d['_clg_contact_methods_text']);

$email_label = clg_meta($pid, '_clg_contact_email_label', $d['_clg_contact_email_label']);
$email_value = clg_meta($pid, '_clg_contact_email_value', $d['_clg_contact_email_value']);
$email_note  = clg_meta($pid, '_clg_contact_email_note', $d['_clg_contact_email_note']);

$book_label    = clg_meta($pid, '_clg_contact_book_label', $d['_clg_contact_book_label']);
$book_title    = clg_meta($pid, '_clg_contact_book_title', $d['_clg_contact_book_title']);
// Click-to-call: use the dedicated field when set, otherwise dial the number shown.
$book_tel      = clg_tel_href(clg_meta($pid, '_clg_contact_book_tel', $book_title));
$book_note     = clg_meta($pid, '_clg_contact_book_note', $d['_clg_contact_book_note']);
$book_btn_text = clg_meta($pid, '_clg_contact_book_btn_text', $d['_clg_contact_book_btn_text']);
$book_btn_url  = clg_meta($pid, '_clg_contact_book_btn_url', $d['_clg_contact_book_btn_url']);

$area_label = clg_meta($pid, '_clg_contact_area_label', $d['_clg_contact_area_label']);
$area_title = clg_meta($pid, '_clg_contact_area_title', $d['_clg_contact_area_title']);
$area_note  = clg_meta($pid, '_clg_contact_area_note', $d['_clg_contact_area_note']);

// === Form ===
$form_eyebrow   = clg_meta($pid, '_clg_contact_form_eyebrow', $d['_clg_contact_form_eyebrow']);
$form_title     = clg_meta($pid, '_clg_contact_form_title', $d['_clg_contact_form_title']);
$form_intro     = clg_meta($pid, '_clg_contact_form_intro', $d['_clg_contact_form_intro']);
$label_name     = clg_meta($pid, '_clg_contact_label_name', $d['_clg_contact_label_name']);
$label_email    = clg_meta($pid, '_clg_contact_label_email', $d['_clg_contact_label_email']);
$label_phone    = clg_meta($pid, '_clg_contact_label_phone', $d['_clg_contact_label_phone']);
$label_interest = clg_meta($pid, '_clg_contact_label_interest', $d['_clg_contact_label_interest']);
$label_message  = clg_meta($pid, '_clg_contact_label_message', $d['_clg_contact_label_message']);
$interest_opts  = clg_list(clg_meta($pid, '_clg_contact_interest_options', $d['_clg_contact_interest_options']));
$msg_placeholder = clg_meta($pid, '_clg_contact_message_placeholder', $d['_clg_contact_message_placeholder']);
$submit_text    = clg_meta($pid, '_clg_contact_submit_text', $d['_clg_contact_submit_text']);
$form_note      = clg_meta($pid, '_clg_contact_form_note', $d['_clg_contact_form_note']);

// === Bottom CTA (light) ===
$cta_eyebrow  = clg_meta($pid, '_clg_contact_cta_eyebrow', $d['_clg_contact_cta_eyebrow']);
$cta_title    = clg_meta($pid, '_clg_contact_cta_title', $d['_clg_contact_cta_title']);
$cta_text     = clg_meta($pid, '_clg_contact_cta_text', $d['_clg_contact_cta_text']);
$cta_btn_text = clg_meta($pid, '_clg_contact_cta_btn_text', $d['_clg_contact_cta_btn_text']);
$cta_btn_url  = clg_meta($pid, '_clg_contact_cta_btn_url', $d['_clg_contact_cta_btn_url']);

get_template_part('template-parts/page-hero');
?>

<?php if (clg_show_section($pid, 'contact_methods')) : ?>
<section>
  <div class="container">
    <div class="split w-35">
      <div class="reveal">
        <span class="eyebrow"><?php echo esc_html($methods_eyebrow); ?></span>
        <h2 class="mt-sm"><?php echo clg_rich($methods_title); ?></h2>
        <p class="mt-md"><?php echo clg_rich($methods_text); ?></p>

        <div style="display:grid; gap:20px; margin-top:40px;">
          <div style="display:flex; gap:18px; align-items:flex-start; padding:24px; border:1px solid var(--line); border-radius:var(--radius-lg); background:var(--cream-50);">
            <span style="width:48px; height:48px; border-radius:14px; background:var(--forest-700); color:var(--cream-100); display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 6h16v12H4z"/><path d="M4 6l8 7 8-7"/></svg>
            </span>
            <div>
              <p style="font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:var(--forest-600); font-weight:600;"><?php echo esc_html($email_label); ?></p>
              <h3 style="font-size:22px; margin-top:4px;"><a href="mailto:<?php echo esc_attr($email_value); ?>"><?php echo esc_html($email_value); ?></a></h3>
              <p style="font-size:14px; color:var(--ink-500); margin-top:4px;"><?php echo esc_html($email_note); ?></p>
            </div>
          </div>

          <div style="display:flex; gap:18px; align-items:flex-start; padding:24px; border:1px solid var(--line); border-radius:var(--radius-lg); background:var(--cream-50);">
            <span style="width:48px; height:48px; border-radius:14px; background:var(--forest-700); color:var(--cream-100); display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
            </span>
            <div>
              <p style="font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:var(--forest-600); font-weight:600;"><?php echo esc_html($book_label); ?></p>
              <h3 style="font-size:22px; margin-top:4px;">
                <?php if ($book_tel) : ?>
                  <a href="tel:<?php echo esc_attr($book_tel); ?>"><?php echo esc_html($book_title); ?></a>
                <?php else : ?>
                  <?php echo esc_html($book_title); ?>
                <?php endif; ?>
              </h3>
              <?php if ($book_note) : ?><p style="font-size:14px; color:var(--ink-500); margin-top:4px;"><?php echo esc_html($book_note); ?></p><?php endif; ?>
              <?php if ($book_btn_text) : ?>
                <a href="<?php echo esc_url(clg_url($book_btn_url)); ?>" class="btn btn--ghost" style="margin-top:14px;"><?php echo esc_html($book_btn_text); ?> <span class="arrow">&rarr;</span></a>
              <?php endif; ?>
            </div>
          </div>

          <div style="display:flex; gap:18px; align-items:flex-start; padding:24px; border:1px solid var(--line); border-radius:var(--radius-lg); background:var(--cream-50);">
            <span style="width:48px; height:48px; border-radius:14px; background:var(--forest-700); color:var(--cream-100); display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 2l9 4.5v7c0 4.5-4 9-9 10-5-1-9-5.5-9-10v-7L12 2z"/><path d="M12 7v5l3 2"/></svg>
            </span>
            <div>
              <p style="font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:var(--forest-600); font-weight:600;"><?php echo esc_html($area_label); ?></p>
              <h3 style="font-size:22px; margin-top:4px;"><?php echo esc_html($area_title); ?></h3>
              <p style="font-size:14px; color:var(--ink-500); margin-top:4px;"><?php echo esc_html($area_note); ?></p>
            </div>
          </div>
        </div>
      </div>

      <div class="reveal">
        <form class="card" style="padding:clamp(28px, 3vw, 44px);" method="post" action="<?php echo esc_url(get_permalink($pid)); ?>" novalidate>
          <span class="eyebrow"><?php echo esc_html($form_eyebrow); ?></span>
          <h3 style="margin-top:14px; font-size:30px;"><?php echo clg_rich($form_title); ?></h3>
          <p style="margin-top:8px; color:var(--ink-500);"><?php echo clg_rich($form_intro); ?></p>

          <?php if (isset($_GET['sent'])) : ?>
            <p class="form-status form-status--ok" role="status">Thank you &mdash; your message is on its way. We&rsquo;ll come back to you within one working day.</p>
          <?php elseif (isset($_GET['enquiry']) && $_GET['enquiry'] === 'invalid') : ?>
            <p class="form-status form-status--error" role="alert">Please add your name, a valid email address and a message, then send it again.</p>
          <?php elseif (isset($_GET['enquiry']) && $_GET['enquiry'] === 'expired') : ?>
            <p class="form-status form-status--error" role="alert">That form had been open a while and the page expired. Please reload and send it again.</p>
          <?php elseif (isset($_GET['enquiry']) && $_GET['enquiry'] === 'failed') : ?>
            <p class="form-status form-status--error" role="alert">Something went wrong sending that. Please email us directly at <a href="mailto:<?php echo esc_attr($email_value); ?>"><?php echo esc_html($email_value); ?></a>.</p>
          <?php endif; ?>

          <div style="margin-top:28px;">
            <div class="form-field">
              <label for="name"><?php echo esc_html($label_name); ?></label>
              <input id="name" name="name" type="text" required autocomplete="name">
            </div>
            <div class="form-field">
              <label for="email"><?php echo esc_html($label_email); ?></label>
              <input id="email" name="email" type="email" required autocomplete="email">
            </div>
            <div class="form-field">
              <label for="phone"><?php echo esc_html($label_phone); ?></label>
              <input id="phone" name="phone" type="tel" autocomplete="tel">
            </div>
            <div class="form-field">
              <label for="interest"><?php echo esc_html($label_interest); ?></label>
              <select id="interest" name="interest">
                <?php foreach ($interest_opts as $opt) : ?>
                  <option><?php echo esc_html($opt); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-field">
              <label for="message"><?php echo esc_html($label_message); ?></label>
              <textarea id="message" name="message" rows="5" required placeholder="<?php echo esc_attr($msg_placeholder); ?>"></textarea>
            </div>

            <?php wp_nonce_field('clg_contact_form', 'clg_contact_nonce'); ?>
            <input type="hidden" name="clg_contact_submit" value="1">
            <p style="position:absolute; left:-9999px;" aria-hidden="true">
              <label>Website<input type="text" name="clg_website" tabindex="-1" autocomplete="off"></label>
            </p>

            <button type="submit" class="btn btn--primary" style="width:100%; justify-content:center;"><?php echo esc_html($submit_text); ?> <span class="arrow">&rarr;</span></button>
            <p class="form-note"><?php echo clg_rich($form_note); ?></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php endif; ?>

<?php if (clg_show_section($pid, 'contact_cta')) : ?>
<!-- CTA -->
<section class="principles">
  <div class="container">
    <div class="reveal" style="text-align:center; max-width:640px; margin-inline:auto;">
      <span class="eyebrow"><?php echo esc_html($cta_eyebrow); ?></span>
      <h2 class="mt-sm"><?php echo clg_rich($cta_title); ?></h2>
      <p class="mt-md"><?php echo clg_rich($cta_text); ?></p>
      <?php if ($cta_btn_text) : ?>
        <a href="<?php echo esc_url(clg_url($cta_btn_url)); ?>" class="btn btn--primary mt-md"><?php echo esc_html($cta_btn_text); ?> <span class="arrow">&rarr;</span></a>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php endif; ?>

<?php get_footer(); ?>
