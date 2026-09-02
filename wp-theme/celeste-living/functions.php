<?php
/**
 * Celeste Living Theme Functions
 *
 * Prefix: clg
 * Text domain: celeste-living
 */

// ===== THEME SETUP =====
function clg_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'celeste-living'),
        'mobile'  => __('Mobile Menu', 'celeste-living'),
        'footer'  => __('Footer Menu', 'celeste-living'),
    ));
}
add_action('after_setup_theme', 'clg_theme_setup');

// ===== ENQUEUE SCRIPTS & STYLES =====
function clg_enqueue_assets() {
    // Google Fonts — same request as the static site
    wp_enqueue_style(
        'clg-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    // Theme stylesheet
    wp_enqueue_style('clg-style', get_stylesheet_uri(), array('clg-google-fonts'), wp_get_theme()->get('Version'));

    // Main JS
    wp_enqueue_script('clg-main', get_template_directory_uri() . '/js/main.js', array(), wp_get_theme()->get('Version'), true);

    // Calendly embed — only on the customer journey page
    if (is_page('customer-journey')) {
        wp_enqueue_style('clg-calendly', 'https://assets.calendly.com/assets/external/widget.css', array(), null);
        wp_enqueue_script('clg-calendly', 'https://assets.calendly.com/assets/external/widget.js', array(), null, true);
    }
}
add_action('wp_enqueue_scripts', 'clg_enqueue_assets');

// ===== ADMIN: MEDIA LIBRARY PICKER FOR META BOX IMAGE FIELDS =====
function clg_admin_assets($hook) {
    if ($hook !== 'post.php' && $hook !== 'post-new.php') return;
    wp_enqueue_media();
    wp_enqueue_script('clg-admin-media', get_template_directory_uri() . '/js/admin-media.js', array('jquery'), wp_get_theme()->get('Version'), true);
}
add_action('admin_enqueue_scripts', 'clg_admin_assets');

// ===== FAVICON FALLBACK (theme mark.svg when no Site Icon is set) =====
function clg_favicon() {
    if (!has_site_icon()) {
        echo '<link rel="icon" type="image/svg+xml" href="' . esc_url(get_template_directory_uri() . '/images/mark.svg') . '">' . "\n";
    }
    echo '<meta name="theme-color" content="#1B4332">' . "\n";
}
add_action('wp_head', 'clg_favicon', 5);

// ===== CUSTOM NAV WALKER (flat anchors — matches .nav__links / .mobile-menu markup) =====
class CLG_Nav_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {}
    function end_lvl(&$output, $depth = 0, $args = null) {}

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes   = empty($item->classes) ? array() : (array) $item->classes;
        $is_active = in_array('current-menu-item', $classes, true)
            || in_array('current_page_item', $classes, true)
            || in_array('current-menu-ancestor', $classes, true);
        $active = $is_active ? ' class="is-active"' : '';
        $output .= '<a href="' . esc_url($item->url) . '"' . $active . '>' . esc_html($item->title) . '</a>';
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {}
}

// ===== CONTENT HELPERS =====

/**
 * Resolve a URL value from meta. Relative paths ("/contact/") resolve
 * against home_url(); absolute URLs, anchors, mailto: and tel: pass through.
 */
function clg_url($url) {
    $url = trim((string) $url);
    if ($url === '') return home_url('/');
    if (preg_match('#^(https?:|mailto:|tel:|\#)#i', $url)) return $url;
    return home_url($url);
}

/**
 * Rich text renderer for meta fields.
 * Escapes everything, then supports:
 *   [it]text[/it]          -> <span class="serif-it">text</span>
 *   [em]text[/em]          -> <em>text</em>
 *   [script]text[/script]  -> <span class="script">text</span>
 *   [link=/path/]text[/link] -> styled inline link
 *   newlines               -> <br>
 */
function clg_rich($text) {
    $out = esc_html($text);
    $out = preg_replace('/\[it\](.*?)\[\/it\]/s', '<span class="serif-it">$1</span>', $out);
    $out = preg_replace('/\[em\](.*?)\[\/em\]/s', '<em>$1</em>', $out);
    $out = preg_replace('/\[script\](.*?)\[\/script\]/s', '<span class="script">$1</span>', $out);
    $out = preg_replace_callback('/\[link=([^\]]+)\](.*?)\[\/link\]/s', function ($m) {
        return '<a href="' . esc_url(clg_url($m[1])) . '" style="color:var(--forest-700); text-decoration:underline; text-decoration-color:var(--sage-300); text-underline-offset:4px;">' . $m[2] . '</a>';
    }, $out);
    return nl2br($out);
}

/**
 * Split a one-item-per-line textarea meta value into an array.
 */
function clg_list($text) {
    return array_values(array_filter(array_map('trim', explode("\n", (string) $text)), 'strlen'));
}

/**
 * Render a lines-based meta value as a run of paragraphs.
 */
function clg_paras($text, $class = 'mt-md') {
    foreach (clg_list($text) as $line) {
        echo '<p class="' . esc_attr($class) . '">' . clg_rich($line) . "</p>\n";
    }
}

/**
 * Header CTA button (label + url). Per-page meta override, then per-slug default.
 */
function clg_nav_cta() {
    $defaults = array('label' => 'Work with us', 'url' => '/contact/');
    $pid = is_singular() ? get_the_ID() : 0;

    if ($pid) {
        $slug = get_post_field('post_name', $pid);
        if ($slug === 'customer-journey') {
            $defaults = array('label' => 'Book a call', 'url' => '#book');
        } elseif ($slug === 'contact') {
            $tel = clg_tel_href(clg_phone());
            $defaults = $tel
                ? array('label' => 'Call us', 'url' => 'tel:' . $tel)
                : array('label' => 'Work with us', 'url' => '/contact/');
        }
        $label = clg_meta($pid, '_clg_nav_cta_label', $defaults['label']);
        $url   = clg_meta($pid, '_clg_nav_cta_url', $defaults['url']);
        return array('label' => $label, 'url' => $url);
    }

    return $defaults;
}

// ===== SECTION VISIBILITY =====

/**
 * Registry of toggleable sections, per page template slug.
 * Key => admin label. Used by the "Section Visibility" meta box and by the
 * page templates, so the two can never drift apart.
 */
function clg_page_sections($slug) {
    $map = array(
        'home' => array(
            'home_wwd'  => 'What we do (three cards)',
            'home_prin' => 'Principles (numbered rows)',
            'home_quote'=> 'Image / quote',
            'home_how'  => 'How it works (steps preview)',
            'home_cta'  => 'Closing CTA banner',
        ),
        'about' => array(
            'about_why'      => 'Why property',
            'about_founders' => 'Founders (Bruno & Kirstie cards)',
            'about_where'    => 'Where we work',
            'about_how'      => 'How we work (values)',
            'about_build'    => "What we're building",
            'about_social'   => 'Social feed',
            'about_cta'      => 'Closing CTA banner',
        ),
        'contact' => array(
            'contact_methods' => 'Contact methods + form',
            'contact_cta'     => 'Closing CTA banner',
        ),
    );
    return isset($map[$slug]) ? $map[$slug] : array();
}

/**
 * Is a section hidden on the front end?
 * Stored per page as _clg_hide_{key} = '1'.
 */
function clg_section_hidden($post_id, $key) {
    return get_post_meta($post_id, '_clg_hide_' . $key, true) === '1';
}

/** Convenience inverse, for readability in templates. */
function clg_show_section($post_id, $key) {
    return !clg_section_hidden($post_id, $key);
}

/**
 * Normalise a display phone number into a tel: href value.
 * Returns '' when the value clearly is not a phone number, so callers can
 * fall back to plain text.
 */
function clg_tel_href($number) {
    $raw = trim((string) $number);
    if ($raw === '') return '';
    $plus = (strpos($raw, '+') === 0);
    $digits = preg_replace('/\D+/', '', $raw);
    if (strlen($digits) < 9) return '';           // not a phone number
    if (!$plus && strpos($digits, '0') === 0) {   // UK national -> E.164
        return '+44' . substr($digits, 1);
    }
    return ($plus ? '+' : '') . $digits;
}

/** Site-wide telephone number (Appearance -> Celeste Settings). */
function clg_phone() {
    return trim((string) get_option('clg_phone', '0121 7989 081'));
}

// ===== CONTACT FORM =====

/**
 * Where enquiries go. Set in Appearance -> Celeste Settings.
 */
function clg_form_recipient() {
    $to = trim((string) get_option('clg_form_recipient', ''));
    if (!$to) $to = trim((string) get_option('clg_contact_email', 'info@celestelivinggroup.co.uk'));
    return $to;
}

/**
 * Handle the contact form post.
 * Posts back to the contact page itself and mails through wp_mail(), so the
 * site's SMTP connection is used and nothing depends on a third-party relay.
 */
add_action('template_redirect', 'clg_handle_contact_form');
function clg_handle_contact_form() {
    if (empty($_POST['clg_contact_submit'])) return;
    if (!isset($_POST['clg_contact_nonce']) || !wp_verify_nonce($_POST['clg_contact_nonce'], 'clg_contact_form')) return;

    $back = get_permalink(get_the_ID());

    // Honeypot — bots fill it, humans never see it.
    if (!empty($_POST['clg_website'])) {
        wp_safe_redirect(add_query_arg('sent', '1', $back));
        exit;
    }

    $name     = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $email    = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $phone    = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $interest = sanitize_text_field(wp_unslash($_POST['interest'] ?? ''));
    $message  = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

    if ($name === '' || !is_email($email) || $message === '') {
        wp_safe_redirect(add_query_arg('enquiry', 'invalid', $back));
        exit;
    }

    $to      = clg_form_recipient();
    $subject = clg_meta(get_the_ID(), '_clg_contact_form_subject', 'New enquiry — Celeste Living Group website');

    $body  = "New enquiry from the Celeste Living Group website.\n\n";
    $body .= "Name:      {$name}\n";
    $body .= "Email:     {$email}\n";
    if ($phone)    $body .= "Telephone: {$phone}\n";
    if ($interest) $body .= "Enquiry:   {$interest}\n";
    $body .= "\nMessage:\n{$message}\n";
    $body .= "\n---\nSent from " . home_url('/') . " on " . current_time('j M Y, H:i') . "\n";

    $from    = clg_mail_from();
    $headers = array(
        'From: Celeste Living Group Website <' . $from . '>',
        'Reply-To: ' . $name . ' <' . $email . '>',
        'Content-Type: text/plain; charset=UTF-8',
    );

    $sent = wp_mail($to, $subject, $body, $headers);

    wp_safe_redirect(add_query_arg($sent ? array('sent' => '1') : array('enquiry' => 'failed'), $back));
    exit;
}

/**
 * The address enquiries are sent FROM. Must be an address the SMTP
 * connection is allowed to send as.
 */
function clg_mail_from() {
    $from = trim((string) get_option('clg_mail_from', ''));
    return $from !== '' ? $from : 'jon@jclmarketing.co.uk';
}

// ===== INCLUDES =====
require_once get_template_directory() . '/inc/meta-boxes.php';
require_once get_template_directory() . '/inc/theme-setup.php';
require_once get_template_directory() . '/inc/theme-settings.php';

// ===== REMOVE WP EMOJI =====
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// ===== REMOVE WP ADMIN BAR INLINE MARGIN (handled in style.css) =====
function clg_remove_admin_bar_bump() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'clg_remove_admin_bar_bump');
