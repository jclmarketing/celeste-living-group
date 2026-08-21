<?php
/**
 * Custom Meta Boxes for Celeste Living
 * Every visible string on the site is editable here.
 * Defaults match the original static HTML exactly.
 */

// ===== HELPER FUNCTIONS =====

/**
 * Get a meta value with a fallback default.
 */
function clg_meta($post_id, $key, $default = '') {
    $val = get_post_meta($post_id, $key, true);
    return ($val !== '' && $val !== false) ? $val : $default;
}

/**
 * Get a repeater-style meta (stored as JSON).
 */
function clg_meta_repeater($post_id, $key, $default = array()) {
    $val = get_post_meta($post_id, $key, true);
    if ($val && is_string($val)) {
        $decoded = json_decode($val, true);
        if (is_array($decoded) && !empty($decoded)) return $decoded;
    }
    return $default;
}

/**
 * Output the save nonce once per edit screen.
 */
function clg_nonce_once() {
    static $done = false;
    if ($done) return;
    wp_nonce_field('clg_save_meta', 'clg_meta_nonce');
    $done = true;
}

/**
 * Render a text input field.
 */
function clg_field_text($post_id, $key, $label, $default = '', $desc = '') {
    $val = clg_meta($post_id, $key, $default);
    echo '<p><label><strong>' . esc_html($label) . '</strong></label>';
    if ($desc) echo '<br><span class="description">' . esc_html($desc) . '</span>';
    echo '<br><input type="text" name="' . esc_attr($key) . '" value="' . esc_attr($val) . '" style="width:100%;" /></p>';
}

/**
 * Render a textarea field.
 */
function clg_field_textarea($post_id, $key, $label, $default = '', $rows = 3, $desc = '') {
    $val = clg_meta($post_id, $key, $default);
    echo '<p><label><strong>' . esc_html($label) . '</strong></label>';
    if ($desc) echo '<br><span class="description">' . esc_html($desc) . '</span>';
    echo '<br><textarea name="' . esc_attr($key) . '" rows="' . intval($rows) . '" style="width:100%;">' . esc_textarea($val) . '</textarea></p>';
}

/**
 * Render an image field with Media Library picker and preview.
 */
function clg_field_image($post_id, $key, $label, $default = '', $desc = 'Click "Select Image" to choose from the Media Library, or leave the default') {
    $val = clg_meta($post_id, $key, $default);
    echo '<div class="clg-image-field" style="margin-bottom:16px;">';
    echo '  <label><strong>' . esc_html($label) . '</strong></label>';
    if ($desc) echo '<br><span class="description">' . esc_html($desc) . '</span>';
    echo '  <div class="clg-image-preview" style="margin:8px 0;' . ($val ? '' : 'display:none;') . '">';
    echo '    <img src="' . esc_url($val) . '" style="max-width:300px;max-height:200px;border-radius:8px;border:1px solid #ddd;display:block;" />';
    echo '  </div>';
    echo '  <input type="text" name="' . esc_attr($key) . '" value="' . esc_attr($val) . '" class="clg-image-url" style="width:100%;margin-bottom:6px;" />';
    echo '  <button type="button" class="button clg-image-upload">Select Image</button> ';
    echo '  <button type="button" class="button clg-image-remove" style="color:#a00;' . ($val ? '' : 'display:none;') . '">Remove Image</button>';
    echo '</div>';
}

/**
 * Render a one-item-per-line textarea.
 */
function clg_field_list($post_id, $key, $label, $default = '', $desc = 'One item per line') {
    $val = clg_meta($post_id, $key, $default);
    echo '<p><label><strong>' . esc_html($label) . '</strong></label>';
    echo '<br><span class="description">' . esc_html($desc) . '</span>';
    echo '<br><textarea name="' . esc_attr($key) . '" rows="6" style="width:100%;">' . esc_textarea($val) . '</textarea></p>';
}

/**
 * Render a repeater group (JSON-stored items).
 * Fields named "text", "description", "answer", "bio", "items", "get_text",
 * "caption" render as textareas; everything else as text inputs.
 */
function clg_field_repeater($post_id, $key, $label, $defaults = array(), $fields = array('title', 'text')) {
    $items = clg_meta_repeater($post_id, $key, $defaults);
    $textarea_fields = array('text', 'description', 'answer', 'bio', 'items', 'get_text', 'caption');

    echo '<div class="clg-repeater" data-key="' . esc_attr($key) . '" data-fields="' . esc_attr(implode(',', $fields)) . '">';
    echo '<h4 style="margin-bottom:6px;">' . esc_html($label) . '</h4>';
    echo '<div class="clg-repeater-items">';

    foreach ($items as $i => $item) {
        echo '<div class="clg-repeater-item" style="background:#f9f9f9;border:1px solid #ddd;padding:10px;margin:5px 0;border-radius:4px;position:relative;">';
        foreach ($fields as $field) {
            $field_label = ucfirst(str_replace('_', ' ', $field));
            $field_name  = $key . '[' . $i . '][' . $field . ']';
            $field_val   = isset($item[$field]) ? $item[$field] : '';
            echo '<label><small>' . esc_html($field_label) . '</small></label>';
            if (in_array($field, $textarea_fields, true)) {
                echo '<textarea name="' . esc_attr($field_name) . '" rows="2" style="width:100%;">' . esc_textarea($field_val) . '</textarea>';
            } else {
                echo '<input type="text" name="' . esc_attr($field_name) . '" value="' . esc_attr($field_val) . '" style="width:100%;" />';
            }
        }
        echo '<button type="button" class="button-link clg-repeater-remove" style="color:#a00;margin-top:6px;">Remove item</button>';
        echo '</div>';
    }
    echo '</div>';
    echo '<button type="button" class="button clg-repeater-add" style="margin-top:6px;">Add item</button>';
    echo '</div>';
}


// ===== PER-PAGE DEFAULTS (shared between meta boxes and templates) =====

/**
 * Page Hero defaults per slug.
 */
function clg_hero_defaults($slug) {
    $map = array(
        'about' => array(
            'breadcrumb' => 'About',
            'title'      => 'A fiancé & fiancée team, all-in on property.',
            'lead'       => "We're Bruno and Kirstie — partners in life and in business. Between us, 10+ years in sales and business taught us how to negotiate, communicate and follow through on what we say. Property is where we're putting all of that to work now.",
            'btn1_text'  => '', 'btn1_url' => '', 'btn2_text' => '', 'btn2_url' => '',
        ),
        'property-management' => array(
            'breadcrumb' => 'Property Management',
            'title'      => "Your property, managed the way we'd manage our own.",
            'lead'       => 'High standards, straight talk, and someone who actually picks up the phone. We look after properties across Birmingham and Staffordshire for owners who want them cared for properly — not just listed and left.',
            'btn1_text'  => 'Talk to us', 'btn1_url' => '/contact/',
            'btn2_text'  => 'What you get', 'btn2_url' => '#what-you-get',
        ),
        'customer-journey' => array(
            'breadcrumb' => 'How we work',
            'title'      => 'The customer journey, in plain English.',
            'lead'       => "No jargon. No hidden steps. Here's exactly what happens from the first conversation to the first deal — and what we'll need from you at each stage.",
            'btn1_text'  => '', 'btn1_url' => '', 'btn2_text' => '', 'btn2_url' => '',
        ),
        'compliance' => array(
            'breadcrumb' => 'Compliance',
            'title'      => 'Compliance, done properly.',
            'lead'       => "Property sourcing is regulated — rightly so. Here's what we have in place on our side, and exactly what we need from you as an investor before we can forward you a deal.",
            'btn1_text'  => '', 'btn1_url' => '', 'btn2_text' => '', 'btn2_url' => '',
        ),
        'contact' => array(
            'breadcrumb' => 'Contact',
            'title'      => 'Start a conversation.',
            'lead'       => 'Drop us a note, send an email, or book a call. We reply to every genuine enquiry — usually within one working day.',
            'btn1_text'  => '', 'btn1_url' => '', 'btn2_text' => '', 'btn2_url' => '',
        ),
        'terms' => array(
            'breadcrumb' => 'Terms of Business',
            'title'      => 'Terms of Business.',
            'lead'       => 'The rules of engagement, in plain language. These terms govern the relationship between Celeste Living Group and our clients, investors and partners.',
            'btn1_text'  => '', 'btn1_url' => '', 'btn2_text' => '', 'btn2_url' => '',
        ),
    );
    return isset($map[$slug]) ? $map[$slug] : array(
        'breadcrumb' => '', 'title' => '', 'lead' => '',
        'btn1_text' => '', 'btn1_url' => '', 'btn2_text' => '', 'btn2_url' => '',
    );
}

/**
 * CTA banner defaults per slug.
 */
function clg_cta_defaults($slug) {
    $map = array(
        'home' => array(
            'eyebrow'   => 'Start a conversation',
            'title'     => 'Ready to see how we work, quietly and properly?',
            'title_max' => '20ch',
            'text'      => "Whether you're an investor, a landlord in transition, or someone exploring their first property partnership — we'd rather have a proper chat than send a brochure.",
            'btn1_text' => 'Get in touch', 'btn1_url' => '/contact/',
            'btn2_text' => 'Book an intro call', 'btn2_url' => '/customer-journey/',
        ),
        'about' => array(
            'eyebrow'   => 'Say hello',
            'title'     => 'Start with a conversation, not a sales pitch.',
            'title_max' => '22ch',
            'text'      => "Thirty minutes. No pressure. See if there's a fit — that's all the first call is for.",
            'btn1_text' => 'Book an intro call', 'btn1_url' => '/customer-journey/#book',
            'btn2_text' => 'Send us a message', 'btn2_url' => '/contact/',
        ),
        'property-management' => array(
            'eyebrow'   => "Let's talk",
            'title'     => "Thinking about handing your property over? Let's have a proper chat first.",
            'title_max' => '24ch',
            'text'      => "No pressure, no hard sell. Tell us about the property and what you're after, and we'll tell you honestly whether we're the right fit.",
            'btn1_text' => 'Get in touch', 'btn1_url' => '/contact/',
            'btn2_text' => 'Book a call', 'btn2_url' => '/customer-journey/#book',
        ),
        'compliance' => array(
            'eyebrow'   => 'Next step',
            'title'     => 'Ready to start your onboarding?',
            'title_max' => '24ch',
            'text'      => "Book a 30-minute intro call and we'll send over the onboarding pack afterwards — only if there's a genuine fit.",
            'btn1_text' => 'Book an intro call', 'btn1_url' => '/customer-journey/',
            'btn2_text' => 'Contact us', 'btn2_url' => '/contact/',
        ),
    );
    return isset($map[$slug]) ? $map[$slug] : $map['home'];
}

/**
 * Homepage defaults.
 */
function clg_home_defaults() {
    return array(
        '_clg_home_hero_eyebrow'   => 'Birmingham · Staffordshire',
        '_clg_home_hero_title'     => "Honest property.\n[it]Considered[/it] partnerships.",
        '_clg_home_hero_lead'      => "We source deals and run property flips with the patience of people playing the long game — because that's exactly what we are. No inflated numbers. No guru language. If a deal doesn't stack, we walk.",
        '_clg_home_hero_btn1_text' => 'See how we work',
        '_clg_home_hero_btn1_url'  => '/customer-journey/',
        '_clg_home_hero_btn2_text' => 'Meet Bruno & Kirstie',
        '_clg_home_hero_btn2_url'  => '/about/',
        '_clg_home_hero_stats'     => array(
            array('number' => '2', 'label' => 'Founders, one vision'),
            array('number' => '100%', 'label' => 'Honest sourcing'),
            array('number' => "B'ham & Staffs", 'label' => 'Focus areas'),
        ),
        '_clg_home_hero_image'     => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
        '_clg_home_hero_image_alt' => 'Interior of a renovated home with considered finishes',
        '_clg_home_hero_badge'     => "Every project\ndone [em]properly[/em]",
        '_clg_home_marquee'        => "Deal sourcing\nProperty flips\nProperty management\nInvestor partnerships\nBirmingham\nStaffordshire\nLong-term thinking",

        '_clg_home_wwd_eyebrow'    => 'What we do',
        '_clg_home_wwd_title'      => 'Three strands. One standard.',
        '_clg_home_wwd_text'       => "We find deals that stack, run flips where a high-end finish is the baseline, and manage properties the way we'd want our own looked after. Three strands, one standard — the bar doesn't move depending on which one you come to us for.",
        '_clg_home_wwd_btn_text'   => 'Explore property management',
        '_clg_home_wwd_btn_url'    => '/property-management/',
        '_clg_home_wwd_cards'      => array(
            array('num' => '01', 'title' => 'Deal sourcing', 'text' => 'Compliant, bespoke sourcing across Birmingham and Staffordshire. We know the streets, the pricing nuance, and when to walk away — which happens more often than most sourcers admit.'),
            array('num' => '02', 'title' => 'Property flips', 'text' => "Select flips with a high-end finish as standard. We plan the exit before we buy the entry, and we'd rather do fewer, better projects than churn through quick-margin compromises."),
            array('num' => '03', 'title' => 'Property management', 'text' => 'High-end, hands-on management for owners across Birmingham and Staffordshire. Direct access, honest reporting, and every property presented to a standard that protects your reviews and your returns.'),
        ),

        '_clg_home_prin_eyebrow'   => 'Our principles',
        '_clg_home_prin_title'     => "Six commitments we won't move on.",
        '_clg_home_prin_text'      => "These aren't tagline words. They're how we'll behave on the boring Tuesdays when nobody's watching.",
        '_clg_home_principles'     => array(
            array('num' => 'i', 'title' => 'Honesty over hype', 'text' => "We don't inflate numbers to make a deal look better than it is. If it doesn't stack up, we walk — and we'll tell you why."),
            array('num' => 'ii', 'title' => 'Relationships first', 'text' => "We're not chasing one-off wins. Investors, sourcers, trades — everyone we work with, we're hoping to work with again. That changes how we treat people."),
            array('num' => 'iii', 'title' => 'Communication, properly', 'text' => "No ghosting, no chasing us for updates, no confusion about where things stand. If we say we'll call, we call."),
            array('num' => 'iv', 'title' => 'High standards, as a baseline', 'text' => "We don't do quick and cheap. Every project is finished properly — that's not an upgrade, it's just the standard."),
            array('num' => 'v', 'title' => 'Local & loyal', 'text' => 'Focused on areas we actually know — Birmingham and Staffordshire — and building a trusted local network of trades and professionals over the long term.'),
            array('num' => 'vi', 'title' => 'Early stage, serious intent', 'text' => "We're at the beginning of this journey — and we're not pretending otherwise. What we bring is preparation, real-world business experience, and the intention to be here in twenty years."),
        ),

        '_clg_home_quote_image'     => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1400&q=80',
        '_clg_home_quote_image_alt' => 'A Birmingham terrace being refurbished',
        '_clg_home_quote_eyebrow'   => 'The Celeste way',
        '_clg_home_quote_text'      => "We won't be the loudest in the room. We'd rather be the people you'd be happy to back on the deal after next.",
        '_clg_home_quote_para'      => "Property is long. So are the relationships that make it work. Our job is to keep both in good shape — which starts with being clear about what we are, what we're not, and what you can expect from us.",
        '_clg_home_quote_btn_text'  => 'Our story',
        '_clg_home_quote_btn_url'   => '/about/',

        '_clg_home_how_eyebrow'    => 'How we work',
        '_clg_home_how_title'      => 'A clear four-step path, without the fluff.',
        '_clg_home_how_text'       => "Every partnership starts with a proper introduction — not a hard sell. Here's the short version. The longer version lives on the [link=/customer-journey/]how we work[/link] page.",
        '_clg_home_how_steps'      => array(
            array('num' => '01', 'label' => 'Intro call', 'title' => 'Get to know each other', 'text' => 'A relaxed 30-minute conversation. What are you looking for, what are we working on, and is there a fit worth exploring.'),
            array('num' => '02', 'label' => 'Discovery', 'title' => 'Your criteria, clearly defined', 'text' => 'Budget, strategy, area, timescale and risk appetite — written down, agreed, and used as the filter for every deal we forward.'),
            array('num' => '03', 'label' => 'Onboarding', 'title' => 'Compliance & paperwork', 'text' => "The proper checks — ID, proof of funds, AML. We'd rather do these properly than cut corners and clean up later."),
            array('num' => '04', 'label' => 'Deals', 'title' => 'Opportunities that stack', 'text' => "You see deals that match your criteria, with real figures, honest risks and clear next steps. If there's nothing worth sending, we don't send anything."),
        ),
        '_clg_home_how_btn_text'   => 'See the full customer journey',
        '_clg_home_how_btn_url'    => '/customer-journey/',
    );
}

/**
 * About page defaults.
 */
function clg_about_defaults() {
    return array(
        '_clg_about_why_image'      => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=1200&q=80',
        '_clg_about_why_image_alt'  => 'Two business partners working together',
        '_clg_about_why_eyebrow'    => 'Why property',
        '_clg_about_why_title'      => 'The long-term play — not a side project.',
        '_clg_about_why_paras'      => "We've built businesses before this. Property is where we see the real long-term opportunity — not a quick win, not a side project. We've left comfortable paths to do this properly, and we're treating it that way from day one.\nWe're early in the journey, and we're not naive about that — we won't pretend otherwise. But early doesn't mean unprepared. We've done the groundwork, we know our market, and we know how to learn fast.\nThe name Celeste comes from our intention — curated living and experiences that feel considered, calm and a little bit elevated. That's the same standard we hold ourselves to in every project, every conversation, and every deal we put our name on.",

        '_clg_about_founders_eyebrow' => 'Founders',
        '_clg_about_founders_title'   => 'Bruno & Kirstie.',
        '_clg_about_founders_text'    => 'Two founders. One standard. Different enough to keep each other sharp, aligned enough to speak with one voice.',
        '_clg_about_f1_image'         => 'https://images.unsplash.com/photo-1566492031773-4f4e44671857?auto=format&fit=crop&w=900&q=80',
        '_clg_about_f1_name'          => 'Bruno',
        '_clg_about_f1_role'          => 'Co-founder',
        '_clg_about_f1_bio'           => 'Bruno leads on deals and numbers — sourcing, negotiation, and making sure every opportunity actually stacks. A background in business gives him the instinct for when something looks too good to be true (and it usually is).',
        '_clg_about_f2_image'         => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=900&q=80',
        '_clg_about_f2_name'          => 'Kirstie',
        '_clg_about_f2_role'          => 'Co-founder',
        '_clg_about_f2_bio'           => "Kirstie leads on design, relationships and the finish. If it's going to look and feel like a Celeste project, it's because she's made sure it does — from the material choices to the way people hear back from us on a Thursday afternoon.",

        '_clg_about_where_eyebrow'   => 'Where we work',
        '_clg_about_where_title'     => 'The West Midlands — on the ground.',
        '_clg_about_where_text'      => "We focus on Birmingham and Staffordshire because we know them. We'd rather be genuinely local — walking the streets, knowing the trades, understanding the pricing nuance — than spread thin across a map we don't understand.",
        '_clg_about_where_stats'     => array(
            array('number' => "B'ham", 'label' => 'Birmingham'),
            array('number' => 'Staffs', 'label' => 'Staffordshire'),
            array('number' => 'West Mids', 'label' => 'Our patch'),
        ),
        '_clg_about_where_image'     => 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&w=1200&q=80',
        '_clg_about_where_image_alt' => 'A West Midlands terraced street',

        '_clg_about_how_eyebrow'  => 'How we work',
        '_clg_about_how_title'    => "Four things we won't move on.",
        '_clg_about_how_text'     => "These aren't tagline words. They're how we behave on the boring Tuesdays when nobody's watching.",
        '_clg_about_values'       => array(
            array('num' => 'i', 'title' => 'Honesty over hype', 'text' => "We don't inflate numbers to make a deal look better than it is. If it doesn't stack up, we walk — and we'll tell you why."),
            array('num' => 'ii', 'title' => 'Relationships first', 'text' => "We're not chasing one-off wins. Investors, sourcers, trades — everyone we work with, we're hoping to work with again. That changes how we treat people."),
            array('num' => 'iii', 'title' => 'Communication, properly', 'text' => "No ghosting, no chasing us for updates, no confusion about where things stand. If we say we'll call, we call."),
            array('num' => 'iv', 'title' => 'High standards, as a baseline', 'text' => "We don't do quick and cheap. Every project is finished properly — that's not an upgrade, it's just the standard."),
        ),

        '_clg_about_build_image'     => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1400&q=80',
        '_clg_about_build_image_alt' => 'A property being refurbished, work in progress',
        '_clg_about_build_eyebrow'   => "What we're building",
        '_clg_about_build_title'     => 'One good project. Then another. Then scale — properly.',
        '_clg_about_build_para'      => "Not quickly. We're documenting the journey honestly as we go, filters off — including the parts that don't go perfectly. If you're an investor, a deal sourcer, or a trade in Birmingham or Staffordshire, we're always happy to talk — even if there's nothing to work on together yet. Connections tend to pay off eventually.",
        '_clg_about_build_quote'     => "If you want hype, we're probably not your people. If you want honesty and someone who'll actually show up — that's us.",
        '_clg_about_build_btn1_text' => 'Property management',
        '_clg_about_build_btn1_url'  => '/property-management/',
        '_clg_about_build_btn2_text' => 'Start a conversation',
        '_clg_about_build_btn2_url'  => '/contact/',

        '_clg_about_social_eyebrow'  => 'Documenting it honestly',
        '_clg_about_social_title'    => 'Behind the scenes, in real time.',
        '_clg_about_social_text'     => 'We share progress as it happens — good days, imperfect days, and everything in between. No polished brand fluff.',
        '_clg_about_social_btn_text' => 'Follow on Instagram',
        '_clg_about_social_btn_url'  => 'https://instagram.com',
        '_clg_about_sc1_image'       => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=800&q=80',
        '_clg_about_sc1_caption'     => 'Kitchen nearing completion — slim shaker, brushed brass, bone marble surround.',
        '_clg_about_sc2_image'       => 'https://images.unsplash.com/photo-1600607688969-a5bfcd646154?auto=format&fit=crop&w=800&q=80',
        '_clg_about_sc2_caption'     => 'Day 14 of the Staffordshire terrace refurb. Every wall tells us something.',
        '_clg_about_sc3_image'       => 'https://images.unsplash.com/photo-1600210491892-03d54c0aaf87?auto=format&fit=crop&w=800&q=80',
        '_clg_about_sc3_caption'     => "Walked every road on this patch so we don't have to guess.",
        '_clg_about_sc4_image'       => 'https://images.unsplash.com/photo-1600585152220-90363fe7e115?auto=format&fit=crop&w=800&q=80',
        '_clg_about_sc4_caption'     => 'Bathroom detail — honest materials, thoughtful layout, no shortcuts.',
        '_clg_about_social_note'     => 'Live feed coming soon — this is a preview of the kind of content we post.',
    );
}

/**
 * Property Management defaults.
 */
function clg_pm_defaults() {
    return array(
        '_clg_pm_wwd_eyebrow'    => 'What we do',
        '_clg_pm_wwd_title'      => 'Managed properly. No surprises.',
        '_clg_pm_wwd_paras'      => "We manage properties the way we'd want ours managed — properly, honestly, with someone who actually picks up the phone.\nThat means clear communication with owners, high standards on every property we look after, and no nasty surprises. If something needs sorting, you'll hear it from us straight away — not find out three weeks later.",
        '_clg_pm_wwd_btn_text'   => 'How we approach it',
        '_clg_pm_wwd_btn_url'    => '#approach',
        '_clg_pm_wwd_image'      => 'https://images.unsplash.com/photo-1617104424032-b9bd6972d0e3?auto=format&fit=crop&w=1200&q=80',
        '_clg_pm_wwd_image_alt'  => 'A well-presented, styled living space',

        '_clg_pm_appr_eyebrow'   => 'Our approach',
        '_clg_pm_appr_title'     => "Four things we won't cut corners on.",
        '_clg_pm_appr_text'      => 'The same standard on a quiet week as on a busy one. This is how every property under our care gets looked after.',
        '_clg_pm_values'         => array(
            array('num' => 'i', 'title' => 'Communication is everything', 'text' => "You'll know what's happening with your property — bookings, maintenance, anything that comes up — without having to chase us for it."),
            array('num' => 'ii', 'title' => 'High-end as standard', 'text' => "We don't do the minimum to get by. Every property under our management is presented and maintained to a proper standard — because that's what protects your investment and keeps guests coming back."),
            array('num' => 'iii', 'title' => 'Local & hands-on', 'text' => 'We focus on Birmingham and Staffordshire, so we know the area, the trades and the market — not managing from a distance through a call centre.'),
            array('num' => 'iv', 'title' => 'Guest experience matters', 'text' => 'From the basics done right — a properly stocked, well-presented property — to thoughtful touches for guests celebrating something, we treat every stay as a reflection of your property. Because it is.'),
        ),

        '_clg_pm_get_eyebrow'   => 'What you get',
        '_clg_pm_get_title'     => 'A proper standard, and the honesty to match.',
        '_clg_pm_get_text'      => "No call centres. No account manager who's never seen the place. Just two people who treat your property like it's theirs — and tell you the truth about how it's doing.",
        '_clg_pm_get_btn_text'  => 'Start a conversation',
        '_clg_pm_get_btn_url'   => '/contact/',
        '_clg_pm_incl_eyebrow'  => 'Included',
        '_clg_pm_incl_title'    => 'Everything, done properly.',
        '_clg_pm_incl_items'    => "Direct access to us — no call centres, no account managers who've never seen your property\nTransparent reporting, so you always know where things stand\nA trusted local network of trades, so problems get sorted quickly and properly\nGuest-facing extras and presentation standards designed to protect your reviews and your booking rate\nHonesty about performance — if something isn't working, we'll tell you, not dress it up",

        '_clg_pm_std_image'     => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80',
        '_clg_pm_std_image_alt' => 'A carefully presented interior detail',
        '_clg_pm_std_eyebrow'   => 'The standard',
        '_clg_pm_std_quote'     => 'Every stay is a reflection of your property — so we treat it like one.',
        '_clg_pm_std_text'      => "Presentation, upkeep, the little touches guests remember — none of it is an upgrade you have to ask for. It's simply how we run every property we manage. That's what protects your reviews, your booking rate, and the value of what you own.",

        '_clg_pm_who_eyebrow'   => 'Who this is for',
        '_clg_pm_who_title'     => 'Owners who want it looked after properly.',
        '_clg_pm_who_paras'     => "Property owners in Birmingham and Staffordshire who want their property cared for — not just listed and left. Whether it's one property or several, you get the same standard and the same directness across all of it.\nIf that sounds like what you're after, get in touch. We're always happy to have a conversation, even before there's anything formal to discuss.",
        '_clg_pm_who_btn_text'  => 'Get in touch',
        '_clg_pm_who_btn_url'   => '/contact/',
        '_clg_pm_who_image'     => 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&w=1200&q=80',
        '_clg_pm_who_image_alt' => 'A well-kept property exterior in the West Midlands',
    );
}

/**
 * Customer Journey defaults.
 */
function clg_cj_defaults() {
    return array(
        '_clg_cj_get_heading' => "What you'll get",
        '_clg_cj_steps'       => array(
            array('num' => '01', 'label' => 'Intro call', 'title' => 'A proper first conversation', 'text' => "Thirty minutes. Relaxed. We learn about your position — what you're looking for, your experience, your timescale — and you learn about us.", 'get_text' => "A clear sense of whether we're a fit, honest thoughts on your strategy, and zero pressure to commit to anything afterwards."),
            array('num' => '02', 'label' => 'Discovery', 'title' => 'Your criteria, pinned down', 'text' => 'A short written brief — budget, strategy, area, deposit, timescale, risk appetite. Agreed by both sides and used as the filter on every deal we forward.', 'get_text' => "A one-page investor brief you keep, and a clear understanding of what we will and won't send your way."),
            array('num' => '03', 'label' => 'Onboarding', 'title' => 'Compliance & paperwork', 'text' => 'Proof of ID, proof of address, proof of funds and a quick AML check. Usually done inside 48 hours. We help you through every part of it.', 'get_text' => "A complete onboarding pack, signed agreements on both sides, and confirmation that you're cleared to receive deals."),
            array('num' => '04', 'label' => 'Deal flow', 'title' => 'Opportunities that actually stack', 'text' => "You receive deal packs that match your criteria — with real figures, honest assumptions, comparables, and a clear exit. If there's nothing worth sending, we send nothing.", 'get_text' => 'Clear deal packs, honest answers to every question, and the space to say no without pressure.'),
            array('num' => '05', 'label' => 'Offer & acquisition', 'title' => 'We hand you to the right people', 'text' => 'If you want to proceed, we introduce you to a solicitor, broker and (if needed) project manager from our trusted network. We stay close throughout.', 'get_text' => 'A smooth handover, a single point of contact, and regular updates without you having to chase.'),
            array('num' => '06', 'label' => 'Ongoing', 'title' => 'The relationship, not just the deal', 'text' => "After completion, we stay in touch. We want to know how it performs, what you'd do differently, and what's next. The first deal is rarely the only deal.", 'get_text' => 'A long-term partner, honest feedback loops, and early sight of future opportunities that fit your portfolio.'),
        ),

        '_clg_cj_book_eyebrow'  => 'Step 01 starts here',
        '_clg_cj_book_title'    => 'Book a 30-minute intro call.',
        '_clg_cj_book_text'     => "Pick a slot that works for you. You'll get a calendar invite and a short form to fill in beforehand — just a few questions so we can make the most of our thirty minutes.",
        '_clg_cj_book_features' => array(
            array('title' => '30 minutes', 'text' => 'No pressure, no hard sell'),
            array('title' => 'Video or phone', 'text' => 'Whichever you prefer'),
            array('title' => 'Zero obligation', 'text' => "Say no afterwards — that's fine"),
        ),
        '_clg_cj_calendly_url'  => 'https://calendly.com/celestelivinggroup/intro-call?hide_gdpr_banner=1&primary_color=1B4332',
        '_clg_cj_book_note'     => 'Powered by Calendly · Your information is handled per our privacy notice.',
        '_clg_cj_noscript'      => "To book a call, please [link=mailto:info@celestelivinggroup.co.uk]email us[/link] and we'll send over some times.",

        '_clg_cj_faq_eyebrow'   => 'A few questions answered',
        '_clg_cj_faq_title'     => 'Before you book — the things people usually ask.',
        '_clg_cj_faqs'          => array(
            array('question' => 'Do I need experience in property to work with you?', 'answer' => 'No. We work with first-time investors as well as experienced portfolio landlords. What matters more than experience is having a clear idea of what you want, realistic expectations, and funds in place (or a plan to get there).'),
            array('question' => 'How do you charge for sourcing?', 'answer' => "Our fees are transparent and set out in writing before onboarding. We'll walk you through our fee structure on the intro call, along with when it's paid and what it covers. No surprises."),
            array('question' => 'What areas do you source in?', 'answer' => 'Primarily Birmingham and Staffordshire. We stay local on purpose — we walk the streets, know the pricing nuance, and can be on-site quickly when something needs checking.'),
            array('question' => 'How long does onboarding take?', 'answer' => "Typically one to two working days once we have your documents. We'll guide you through every part of it, so there's nothing to figure out on your own."),
            array('question' => "What if I'm not ready to invest yet?", 'answer' => "That's fine. Plenty of our conversations start months before someone's ready to move. Book a call, have a chat, and we'll stay in touch until the timing's right."),
        ),
    );
}

/**
 * Compliance defaults.
 */
function clg_comp_defaults() {
    return array(
        '_clg_comp_reg_eyebrow' => 'Our registrations & memberships',
        '_clg_comp_reg_title'   => "What we've put in place.",
        '_clg_comp_reg_text'    => "The baseline isn't optional — but the diligence behind it is where it matters. Here's a clear view of our current registrations and insurances.",
        '_clg_comp_reg_items'   => array(
            array('title' => 'Professional Indemnity Insurance', 'text' => 'We carry active professional indemnity cover appropriate for property sourcing activity — policy details available on request.', 'tag' => 'In place'),
            array('title' => 'ICO Registration', 'text' => "Registered with the Information Commissioner's Office as a data controller. We handle your personal and financial information in line with UK GDPR.", 'tag' => 'Registered'),
            array('title' => 'PRS — Property Redress Scheme', 'text' => "Members of The Property Redress Scheme, providing an independent route for resolving any issues — on the rare occasion it's ever needed.", 'tag' => 'Member'),
            array('title' => 'HMRC AML Supervision', 'text' => 'Supervised by HMRC for Anti-Money Laundering purposes. Every investor onboarding follows a formal AML procedure — no exceptions, no favours.', 'tag' => 'Supervised'),
            array('title' => 'Registered Company (UK)', 'text' => 'Celeste Living Group is a registered limited company in England & Wales. Full company number and registered office available on request or in any deal pack.', 'tag' => 'Companies House'),
            array('title' => 'Data & Document Handling', 'text' => 'Your documents are stored securely, used only for the purpose of investor onboarding and transaction compliance, and never shared with third parties without explicit consent.', 'tag' => 'UK GDPR'),
        ),

        '_clg_comp_inv_eyebrow'  => 'What we need from you',
        '_clg_comp_inv_title'    => 'Investor onboarding — simple, fast, thorough.',
        '_clg_comp_inv_paras'    => "Before we can forward deals, we need to verify who you are and that your funds are legitimate. It's straightforward, it's secure, and it protects both sides. We usually get this done inside a couple of working days.\nIf any of it feels unfamiliar, that's completely normal — we'll walk you through each step personally.",
        '_clg_comp_inv_btn_text' => 'Get started',
        '_clg_comp_inv_btn_url'  => '/contact/',

        '_clg_comp_pack_eyebrow' => 'Your onboarding pack',
        '_clg_comp_pack_title'   => "Three things we'll need.",
        '_clg_comp_pack_text'    => 'Nothing surprising. Nothing onerous.',
        '_clg_comp_pack_groups'  => array(
            array('heading' => '01 · Proof of identity', 'items' => "A clear, in-date passport [em]or[/em] photo driving licence\nOne proof of address from the last 3 months (utility bill, bank statement, council tax)"),
            array('heading' => '02 · Proof of funds', 'items' => "Recent bank statement showing available funds [em]or[/em]\nMortgage agreement in principle / lender decision\nIf funds are a gift or inheritance, a short source-of-funds letter"),
            array('heading' => '03 · Investor criteria', 'items' => "Budget range and deposit available\nPreferred strategy (BTL, BRR, flip, HMO)\nTarget area and timescale"),
        ),

        '_clg_comp_why_eyebrow'   => 'Why it matters',
        '_clg_comp_why_title'     => "Compliance isn't red tape — it's trust, in writing.",
        '_clg_comp_why_paras'     => "Every shortcut in sourcing eventually costs somebody. We'd rather take an extra day on onboarding than skip something that protects you, us, or the next investor we work with.\nIf a sourcer ever tells you the paperwork is \"optional\" or that they can \"start with the deal and do the ID later\" — that's your cue to walk away.",
        '_clg_comp_why_image'     => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1200&q=80',
        '_clg_comp_why_image_alt' => 'Documents and pen on a desk',
    );
}

/**
 * Contact defaults.
 */
function clg_contact_defaults() {
    return array(
        '_clg_contact_methods_eyebrow' => 'Get in touch',
        '_clg_contact_methods_title'   => 'Three ways to reach us.',
        '_clg_contact_methods_text'    => 'Whichever you prefer — pick the one that feels easiest.',

        '_clg_contact_email_label'  => 'Email',
        '_clg_contact_email_value'  => 'info@celestelivinggroup.co.uk',
        '_clg_contact_email_note'   => 'The fastest route for general questions.',
        '_clg_contact_book_label'   => 'Book a call',
        '_clg_contact_book_title'   => '30-minute intro',
        '_clg_contact_book_note'    => 'Pick a slot that works for you — no pressure.',
        '_clg_contact_book_btn_text'=> 'Open booking',
        '_clg_contact_book_btn_url' => '/customer-journey/#book',
        '_clg_contact_area_label'   => 'Area',
        '_clg_contact_area_title'   => 'Birmingham & Staffordshire',
        '_clg_contact_area_note'    => 'Happy to meet in person for serious conversations.',

        '_clg_contact_form_action'  => 'https://formsubmit.co/info@celestelivinggroup.co.uk',
        '_clg_contact_form_eyebrow' => 'Send a message',
        '_clg_contact_form_title'   => "We'll get back to you properly.",
        '_clg_contact_form_intro'   => 'Not a templated reply — an actual answer from either Bruno or Kirstie.',
        '_clg_contact_label_name'   => 'Your name',
        '_clg_contact_label_email'  => 'Email',
        '_clg_contact_label_phone'  => 'Phone (optional)',
        '_clg_contact_label_interest' => 'What brings you here?',
        '_clg_contact_label_message'  => 'Message',
        '_clg_contact_interest_options' => "I'm exploring property investment\nI'm an experienced investor looking for sourced deals\nI'd like to discuss a joint venture\nI'm a landlord considering selling\nJust a general enquiry",
        '_clg_contact_message_placeholder' => 'A sentence or two is plenty.',
        '_clg_contact_form_subject' => 'New enquiry — Celeste Living Group website',
        '_clg_contact_submit_text'  => 'Send message',
        '_clg_contact_form_note'    => 'By submitting, you agree to us getting back to you. No marketing lists, no spam — ever.',

        '_clg_contact_cta_eyebrow'  => 'Prefer a conversation?',
        '_clg_contact_cta_title'    => 'Sometimes a 30-minute call beats ten emails.',
        '_clg_contact_cta_text'     => "Pick a slot, bring your questions, leave with clear answers. We'll never use the call to push you into anything.",
        '_clg_contact_cta_btn_text' => 'Book a call',
        '_clg_contact_cta_btn_url'  => '/customer-journey/#book',
    );
}

/**
 * Terms defaults.
 */
function clg_terms_defaults() {
    $body = <<<'HTML'
<h2>1. About us</h2>
<p>Celeste Living Group ("we", "us", "our") is a property deal sourcing and property flip business operating in England, primarily in Birmingham and Staffordshire. We are a registered limited company in England &amp; Wales. Company registration details are available on request.</p>

<h2>2. Regulatory memberships</h2>
<p>We maintain the following registrations and memberships, which are current at the date of publication:</p>
<ul>
<li>Professional Indemnity Insurance — appropriate cover for property sourcing activity</li>
<li>Information Commissioner's Office (ICO) — registered data controller</li>
<li>The Property Redress Scheme (PRS) — member</li>
<li>HMRC Anti-Money Laundering (AML) Supervision</li>
</ul>
<p>Proof of any of the above is available on request.</p>

<h2>3. Our services</h2>
<p>We offer two principal services:</p>
<h3>3.1 Deal sourcing</h3>
<p>We identify, appraise and present property investment opportunities to investors we have formally onboarded. Each opportunity is presented with supporting figures, assumptions and comparables. We do not give regulated financial advice and nothing in our deal packs constitutes advice of that kind.</p>
<h3>3.2 Property flips</h3>
<p>We acquire, refurbish and re-market residential properties, either alone or in partnership with investors through formal joint-venture arrangements.</p>

<h2>4. Fees</h2>
<p>Our fees are disclosed in writing before any investor onboarding is completed. Fees are agreed specifically for each engagement and are documented in our Engagement Letter. We do not charge hidden or non-disclosed commissions.</p>

<h2>5. Investor onboarding</h2>
<p>Before we can forward a property opportunity to any individual or entity, we are required (and choose) to undertake identity, address and anti-money-laundering verification. By requesting our services, you agree to provide:</p>
<ul>
<li>Valid photographic identification (passport or photo driving licence);</li>
<li>Proof of address dated within the last 3 months;</li>
<li>Proof of funds sufficient to support the proposed transaction;</li>
<li>A brief source-of-funds statement where requested.</li>
</ul>
<p>We may decline to act for any person where we cannot complete these checks to our satisfaction.</p>

<h2>6. No financial advice</h2>
<p>Information provided by Celeste Living Group — whether verbal, written or embedded in deal packs — is for the purpose of evaluating a specific property opportunity and does not constitute regulated financial, investment, tax, legal or mortgage advice. You should obtain independent professional advice before committing to any investment.</p>

<h2>7. Figures, projections and forecasts</h2>
<p>All figures, rental yields, refurbishment costs, end values and projections presented are our honest estimate based on the information available at the time. Markets move, costs vary, and actual outcomes will differ. You accept that all projections are indicative and not guaranteed.</p>

<h2>8. Confidentiality</h2>
<p>Each deal pack is shared in confidence. You agree not to forward, sell, publish or disclose its contents to any third party without our prior written consent, except to your own professional advisors on a need-to-know basis.</p>

<h2>9. Data protection</h2>
<p>We process personal data in accordance with UK GDPR and the Data Protection Act 2018. We use your information only for the purposes of providing our services, meeting our regulatory obligations, and staying in touch about relevant opportunities where you have agreed we may do so. You may withdraw consent or request deletion of your data at any time by writing to <a href="mailto:info@celestelivinggroup.co.uk">info@celestelivinggroup.co.uk</a>.</p>

<h2>10. Complaints</h2>
<p>We aim for complaints to be extremely rare. If something has gone wrong, please email <a href="mailto:info@celestelivinggroup.co.uk">info@celestelivinggroup.co.uk</a> with the subject line "Complaint" and we will respond within 7 working days. If we cannot resolve a complaint between us, you have the right to escalate the matter to The Property Redress Scheme (PRS), of which we are a member.</p>

<h2>11. Limitation of liability</h2>
<p>Nothing in these terms excludes or limits liability for fraud, fraudulent misrepresentation, death or personal injury caused by negligence, or any other liability that cannot lawfully be excluded. Subject to that, our total aggregate liability in connection with any engagement shall not exceed the total fees paid to us under that engagement in the twelve months preceding the claim.</p>

<h2>12. Governing law</h2>
<p>These terms are governed by the laws of England &amp; Wales. The courts of England &amp; Wales have exclusive jurisdiction over any dispute arising from or in connection with these terms.</p>

<h2>13. Changes to these terms</h2>
<p>We may update these Terms of Business from time to time. Material changes will be notified to active clients in writing. The version at the top of this page is the current version.</p>

<h2>14. Contact</h2>
<p>Questions about these terms? Email us at <a href="mailto:info@celestelivinggroup.co.uk">info@celestelivinggroup.co.uk</a>.</p>
HTML;

    return array(
        '_clg_terms_updated' => 'Last updated: April 2026 · Version 1.0',
        '_clg_terms_body'    => $body,
    );
}

/**
 * Coming Soon template defaults.
 */
function clg_cs_defaults() {
    return array(
        '_clg_cs_eyebrow'     => 'Coming soon',
        '_clg_cs_tagline'     => 'Curated Living & Experiences',
        '_clg_cs_message'     => "Our website is being finished properly, not quickly. In the meantime, we'd love to hear from you. Get in touch and we'll reply personally.",
        '_clg_cs_phone_label' => 'Phone',
        '_clg_cs_phone'       => '0121 798 9081',
        '_clg_cs_email_label' => 'Email',
        '_clg_cs_email'       => 'info@celestelivinggroup.co.uk',
        '_clg_cs_footer_text' => 'Celeste Living Group · Birmingham & Staffordshire',
    );
}


// ===== REGISTER META BOXES =====
add_action('add_meta_boxes', 'clg_register_meta_boxes');
function clg_register_meta_boxes() {
    $post_id = isset($_GET['post']) ? intval($_GET['post']) : (isset($_POST['post_ID']) ? intval($_POST['post_ID']) : 0);
    if (!$post_id) return;

    // Coming Soon template pages get their own metabox
    $template = get_post_meta($post_id, '_wp_page_template', true);
    if ($template === 'template-coming-soon.php') {
        add_meta_box('clg_coming_soon', 'Coming Soon Content', 'clg_metabox_coming_soon', 'page', 'normal', 'high');
        return;
    }

    $slug = get_post_field('post_name', $post_id);
    $template_map = array(
        'home'                => 'clg_metabox_home',
        'about'               => 'clg_metabox_about',
        'property-management' => 'clg_metabox_pm',
        'customer-journey'    => 'clg_metabox_cj',
        'compliance'          => 'clg_metabox_compliance',
        'contact'             => 'clg_metabox_contact',
        'terms'               => 'clg_metabox_terms',
    );

    if (!isset($template_map[$slug])) return;

    // Hero meta box for inner pages (not homepage)
    if ($slug !== 'home') {
        add_meta_box('clg_page_hero', 'Page Hero', 'clg_metabox_hero', 'page', 'normal', 'high');
    }

    // Page content meta box
    add_meta_box('clg_page_content', 'Page Content', $template_map[$slug], 'page', 'normal', 'high');

    // CTA banner for pages that have the dark banner
    if (in_array($slug, array('home', 'about', 'property-management', 'compliance'), true)) {
        add_meta_box('clg_cta_banner', 'CTA Banner (dark green, bottom of page)', 'clg_metabox_cta', 'page', 'normal', 'default');
    }
}


// ===== SHARED META BOXES =====

function clg_metabox_hero($post) {
    clg_nonce_once();
    $slug = get_post_field('post_name', $post->ID);
    $d = clg_hero_defaults($slug);

    clg_field_text($post->ID, '_clg_hero_breadcrumb', 'Breadcrumb Label', $d['breadcrumb'], 'Shown as "Home · Label" above the title');
    clg_field_text($post->ID, '_clg_hero_title', 'Hero Title', $d['title']);
    clg_field_textarea($post->ID, '_clg_hero_lead', 'Hero Lead Paragraph', $d['lead']);

    echo '<hr><h3>Hero Buttons (optional — leave text blank to hide)</h3>';
    clg_field_text($post->ID, '_clg_hero_btn1_text', 'Primary Button Text', $d['btn1_text']);
    clg_field_text($post->ID, '_clg_hero_btn1_url', 'Primary Button URL', $d['btn1_url'], 'Relative paths like /contact/ or anchors like #book are fine');
    clg_field_text($post->ID, '_clg_hero_btn2_text', 'Secondary Button Text', $d['btn2_text']);
    clg_field_text($post->ID, '_clg_hero_btn2_url', 'Secondary Button URL', $d['btn2_url']);

    echo '<hr><h3>Header Button (top navigation, this page only)</h3>';
    $nav = clg_nav_cta_slug_defaults($slug);
    clg_field_text($post->ID, '_clg_nav_cta_label', 'Header Button Label', $nav['label']);
    clg_field_text($post->ID, '_clg_nav_cta_url', 'Header Button URL', $nav['url']);
}

/**
 * Per-slug defaults for the header nav CTA (used by hero metabox display).
 */
function clg_nav_cta_slug_defaults($slug) {
    if ($slug === 'customer-journey') return array('label' => 'Book a call', 'url' => '#book');
    if ($slug === 'contact') return array('label' => 'Book a call', 'url' => '/customer-journey/#book');
    return array('label' => 'Work with us', 'url' => '/contact/');
}

function clg_metabox_cta($post) {
    clg_nonce_once();
    $slug = get_post_field('post_name', $post->ID);
    $d = clg_cta_defaults($slug);

    clg_field_text($post->ID, '_clg_cta_eyebrow', 'Eyebrow', $d['eyebrow']);
    clg_field_text($post->ID, '_clg_cta_title', 'Title', $d['title']);
    clg_field_textarea($post->ID, '_clg_cta_text', 'Text', $d['text']);
    clg_field_text($post->ID, '_clg_cta_btn1_text', 'Light Button Text', $d['btn1_text']);
    clg_field_text($post->ID, '_clg_cta_btn1_url', 'Light Button URL', $d['btn1_url']);
    clg_field_text($post->ID, '_clg_cta_btn2_text', 'Ghost Button Text', $d['btn2_text']);
    clg_field_text($post->ID, '_clg_cta_btn2_url', 'Ghost Button URL', $d['btn2_url']);
}


// ===== HOMEPAGE META BOX =====
function clg_metabox_home($post) {
    clg_nonce_once();
    $d = clg_home_defaults();
    $pid = $post->ID;

    echo '<h3>Hero Section</h3>';
    clg_field_text($pid, '_clg_home_hero_eyebrow', 'Eyebrow', $d['_clg_home_hero_eyebrow']);
    clg_field_textarea($pid, '_clg_home_hero_title', 'Hero Title', $d['_clg_home_hero_title'], 2, 'New line = line break. Wrap words in [it]...[/it] for the italic serif style.');
    clg_field_textarea($pid, '_clg_home_hero_lead', 'Hero Lead Paragraph', $d['_clg_home_hero_lead']);
    clg_field_text($pid, '_clg_home_hero_btn1_text', 'Primary Button Text', $d['_clg_home_hero_btn1_text']);
    clg_field_text($pid, '_clg_home_hero_btn1_url', 'Primary Button URL', $d['_clg_home_hero_btn1_url']);
    clg_field_text($pid, '_clg_home_hero_btn2_text', 'Ghost Button Text', $d['_clg_home_hero_btn2_text']);
    clg_field_text($pid, '_clg_home_hero_btn2_url', 'Ghost Button URL', $d['_clg_home_hero_btn2_url']);
    clg_field_repeater($pid, '_clg_home_hero_stats', 'Hero Stats (under the buttons)', $d['_clg_home_hero_stats'], array('number', 'label'));
    clg_field_image($pid, '_clg_home_hero_image', 'Hero Image', $d['_clg_home_hero_image']);
    clg_field_text($pid, '_clg_home_hero_image_alt', 'Hero Image Alt Text', $d['_clg_home_hero_image_alt']);
    clg_field_textarea($pid, '_clg_home_hero_badge', 'Hero Image Badge', $d['_clg_home_hero_badge'], 2, 'New line = line break. Wrap words in [em]...[/em] for italics.');
    clg_field_list($pid, '_clg_home_marquee', 'Scrolling Marquee Items', $d['_clg_home_marquee']);

    echo '<hr><h3>What We Do</h3>';
    clg_field_text($pid, '_clg_home_wwd_eyebrow', 'Eyebrow', $d['_clg_home_wwd_eyebrow']);
    clg_field_text($pid, '_clg_home_wwd_title', 'Title', $d['_clg_home_wwd_title']);
    clg_field_textarea($pid, '_clg_home_wwd_text', 'Text', $d['_clg_home_wwd_text']);
    clg_field_text($pid, '_clg_home_wwd_btn_text', 'Button Text', $d['_clg_home_wwd_btn_text']);
    clg_field_text($pid, '_clg_home_wwd_btn_url', 'Button URL', $d['_clg_home_wwd_btn_url']);
    clg_field_repeater($pid, '_clg_home_wwd_cards', 'Service Cards', $d['_clg_home_wwd_cards'], array('num', 'title', 'text'));

    echo '<hr><h3>Our Principles</h3>';
    clg_field_text($pid, '_clg_home_prin_eyebrow', 'Eyebrow', $d['_clg_home_prin_eyebrow']);
    clg_field_text($pid, '_clg_home_prin_title', 'Title', $d['_clg_home_prin_title']);
    clg_field_textarea($pid, '_clg_home_prin_text', 'Text', $d['_clg_home_prin_text']);
    clg_field_repeater($pid, '_clg_home_principles', 'Principles (roman numerals)', $d['_clg_home_principles'], array('num', 'title', 'text'));

    echo '<hr><h3>Image / Quote Section</h3>';
    clg_field_image($pid, '_clg_home_quote_image', 'Image', $d['_clg_home_quote_image']);
    clg_field_text($pid, '_clg_home_quote_image_alt', 'Image Alt Text', $d['_clg_home_quote_image_alt']);
    clg_field_text($pid, '_clg_home_quote_eyebrow', 'Eyebrow', $d['_clg_home_quote_eyebrow']);
    clg_field_textarea($pid, '_clg_home_quote_text', 'Quote (curly quotes added automatically)', $d['_clg_home_quote_text']);
    clg_field_textarea($pid, '_clg_home_quote_para', 'Paragraph Below Quote', $d['_clg_home_quote_para']);
    clg_field_text($pid, '_clg_home_quote_btn_text', 'Button Text', $d['_clg_home_quote_btn_text']);
    clg_field_text($pid, '_clg_home_quote_btn_url', 'Button URL', $d['_clg_home_quote_btn_url']);

    echo '<hr><h3>How We Work (preview)</h3>';
    clg_field_text($pid, '_clg_home_how_eyebrow', 'Eyebrow', $d['_clg_home_how_eyebrow']);
    clg_field_text($pid, '_clg_home_how_title', 'Title', $d['_clg_home_how_title']);
    clg_field_textarea($pid, '_clg_home_how_text', 'Text', $d['_clg_home_how_text'], 3, 'Use [link=/path/]text[/link] for inline links');
    clg_field_repeater($pid, '_clg_home_how_steps', 'Steps', $d['_clg_home_how_steps'], array('num', 'label', 'title', 'text'));
    clg_field_text($pid, '_clg_home_how_btn_text', 'Bottom Button Text', $d['_clg_home_how_btn_text']);
    clg_field_text($pid, '_clg_home_how_btn_url', 'Bottom Button URL', $d['_clg_home_how_btn_url']);

    echo '<hr><h3>Header Button (top navigation, this page only)</h3>';
    $nav = clg_nav_cta_slug_defaults('home');
    clg_field_text($pid, '_clg_nav_cta_label', 'Header Button Label', $nav['label']);
    clg_field_text($pid, '_clg_nav_cta_url', 'Header Button URL', $nav['url']);
}


// ===== ABOUT META BOX =====
function clg_metabox_about($post) {
    clg_nonce_once();
    $d = clg_about_defaults();
    $pid = $post->ID;

    echo '<h3>Why Property</h3>';
    clg_field_image($pid, '_clg_about_why_image', 'Image (tall, 4:5)', $d['_clg_about_why_image']);
    clg_field_text($pid, '_clg_about_why_image_alt', 'Image Alt Text', $d['_clg_about_why_image_alt']);
    clg_field_text($pid, '_clg_about_why_eyebrow', 'Eyebrow', $d['_clg_about_why_eyebrow']);
    clg_field_text($pid, '_clg_about_why_title', 'Title', $d['_clg_about_why_title']);
    clg_field_textarea($pid, '_clg_about_why_paras', 'Paragraphs (one per line)', $d['_clg_about_why_paras'], 6, 'Each line becomes a paragraph');

    echo '<hr><h3>Founders</h3>';
    clg_field_text($pid, '_clg_about_founders_eyebrow', 'Eyebrow', $d['_clg_about_founders_eyebrow']);
    clg_field_text($pid, '_clg_about_founders_title', 'Title', $d['_clg_about_founders_title']);
    clg_field_textarea($pid, '_clg_about_founders_text', 'Intro Text', $d['_clg_about_founders_text']);
    echo '<h4>Founder 1</h4>';
    clg_field_image($pid, '_clg_about_f1_image', 'Photo', $d['_clg_about_f1_image']);
    clg_field_text($pid, '_clg_about_f1_name', 'Name', $d['_clg_about_f1_name']);
    clg_field_text($pid, '_clg_about_f1_role', 'Role', $d['_clg_about_f1_role']);
    clg_field_textarea($pid, '_clg_about_f1_bio', 'Bio', $d['_clg_about_f1_bio']);
    echo '<h4>Founder 2</h4>';
    clg_field_image($pid, '_clg_about_f2_image', 'Photo', $d['_clg_about_f2_image']);
    clg_field_text($pid, '_clg_about_f2_name', 'Name', $d['_clg_about_f2_name']);
    clg_field_text($pid, '_clg_about_f2_role', 'Role', $d['_clg_about_f2_role']);
    clg_field_textarea($pid, '_clg_about_f2_bio', 'Bio', $d['_clg_about_f2_bio']);

    echo '<hr><h3>Where We Work</h3>';
    clg_field_text($pid, '_clg_about_where_eyebrow', 'Eyebrow', $d['_clg_about_where_eyebrow']);
    clg_field_text($pid, '_clg_about_where_title', 'Title', $d['_clg_about_where_title']);
    clg_field_textarea($pid, '_clg_about_where_text', 'Text', $d['_clg_about_where_text']);
    clg_field_repeater($pid, '_clg_about_where_stats', 'Area Stats', $d['_clg_about_where_stats'], array('number', 'label'));
    clg_field_image($pid, '_clg_about_where_image', 'Image', $d['_clg_about_where_image']);
    clg_field_text($pid, '_clg_about_where_image_alt', 'Image Alt Text', $d['_clg_about_where_image_alt']);

    echo '<hr><h3>How We Work (values)</h3>';
    clg_field_text($pid, '_clg_about_how_eyebrow', 'Eyebrow', $d['_clg_about_how_eyebrow']);
    clg_field_text($pid, '_clg_about_how_title', 'Title', $d['_clg_about_how_title']);
    clg_field_textarea($pid, '_clg_about_how_text', 'Text', $d['_clg_about_how_text']);
    clg_field_repeater($pid, '_clg_about_values', 'Values', $d['_clg_about_values'], array('num', 'title', 'text'));

    echo '<hr><h3>What We\'re Building</h3>';
    clg_field_image($pid, '_clg_about_build_image', 'Image', $d['_clg_about_build_image']);
    clg_field_text($pid, '_clg_about_build_image_alt', 'Image Alt Text', $d['_clg_about_build_image_alt']);
    clg_field_text($pid, '_clg_about_build_eyebrow', 'Eyebrow', $d['_clg_about_build_eyebrow']);
    clg_field_text($pid, '_clg_about_build_title', 'Title', $d['_clg_about_build_title']);
    clg_field_textarea($pid, '_clg_about_build_para', 'Paragraph', $d['_clg_about_build_para']);
    clg_field_textarea($pid, '_clg_about_build_quote', 'Italic Pull-Quote', $d['_clg_about_build_quote']);
    clg_field_text($pid, '_clg_about_build_btn1_text', 'Ghost Button Text', $d['_clg_about_build_btn1_text']);
    clg_field_text($pid, '_clg_about_build_btn1_url', 'Ghost Button URL', $d['_clg_about_build_btn1_url']);
    clg_field_text($pid, '_clg_about_build_btn2_text', 'Primary Button Text', $d['_clg_about_build_btn2_text']);
    clg_field_text($pid, '_clg_about_build_btn2_url', 'Primary Button URL', $d['_clg_about_build_btn2_url']);

    echo '<hr><h3>Social / Behind the Scenes</h3>';
    clg_field_text($pid, '_clg_about_social_eyebrow', 'Eyebrow', $d['_clg_about_social_eyebrow']);
    clg_field_text($pid, '_clg_about_social_title', 'Title', $d['_clg_about_social_title']);
    clg_field_textarea($pid, '_clg_about_social_text', 'Text', $d['_clg_about_social_text']);
    clg_field_text($pid, '_clg_about_social_btn_text', 'Follow Button Text', $d['_clg_about_social_btn_text']);
    clg_field_text($pid, '_clg_about_social_btn_url', 'Follow Button URL', $d['_clg_about_social_btn_url']);
    for ($i = 1; $i <= 4; $i++) {
        echo '<h4>Social Card ' . $i . '</h4>';
        clg_field_image($pid, "_clg_about_sc{$i}_image", 'Image', $d["_clg_about_sc{$i}_image"]);
        clg_field_textarea($pid, "_clg_about_sc{$i}_caption", 'Caption', $d["_clg_about_sc{$i}_caption"], 2);
    }
    clg_field_text($pid, '_clg_about_social_note', 'Note Below Cards', $d['_clg_about_social_note']);
}


// ===== PROPERTY MANAGEMENT META BOX =====
function clg_metabox_pm($post) {
    clg_nonce_once();
    $d = clg_pm_defaults();
    $pid = $post->ID;

    echo '<h3>What We Do</h3>';
    clg_field_text($pid, '_clg_pm_wwd_eyebrow', 'Eyebrow', $d['_clg_pm_wwd_eyebrow']);
    clg_field_text($pid, '_clg_pm_wwd_title', 'Title', $d['_clg_pm_wwd_title']);
    clg_field_textarea($pid, '_clg_pm_wwd_paras', 'Paragraphs (one per line)', $d['_clg_pm_wwd_paras'], 5, 'Each line becomes a paragraph');
    clg_field_text($pid, '_clg_pm_wwd_btn_text', 'Button Text', $d['_clg_pm_wwd_btn_text']);
    clg_field_text($pid, '_clg_pm_wwd_btn_url', 'Button URL', $d['_clg_pm_wwd_btn_url']);
    clg_field_image($pid, '_clg_pm_wwd_image', 'Image', $d['_clg_pm_wwd_image']);
    clg_field_text($pid, '_clg_pm_wwd_image_alt', 'Image Alt Text', $d['_clg_pm_wwd_image_alt']);

    echo '<hr><h3>Our Approach</h3>';
    clg_field_text($pid, '_clg_pm_appr_eyebrow', 'Eyebrow', $d['_clg_pm_appr_eyebrow']);
    clg_field_text($pid, '_clg_pm_appr_title', 'Title', $d['_clg_pm_appr_title']);
    clg_field_textarea($pid, '_clg_pm_appr_text', 'Text', $d['_clg_pm_appr_text']);
    clg_field_repeater($pid, '_clg_pm_values', 'Approach Values', $d['_clg_pm_values'], array('num', 'title', 'text'));

    echo '<hr><h3>What You Get</h3>';
    clg_field_text($pid, '_clg_pm_get_eyebrow', 'Eyebrow', $d['_clg_pm_get_eyebrow']);
    clg_field_text($pid, '_clg_pm_get_title', 'Title', $d['_clg_pm_get_title']);
    clg_field_textarea($pid, '_clg_pm_get_text', 'Text', $d['_clg_pm_get_text']);
    clg_field_text($pid, '_clg_pm_get_btn_text', 'Button Text', $d['_clg_pm_get_btn_text']);
    clg_field_text($pid, '_clg_pm_get_btn_url', 'Button URL', $d['_clg_pm_get_btn_url']);
    echo '<h4>"Included" Checklist Card (dark green)</h4>';
    clg_field_text($pid, '_clg_pm_incl_eyebrow', 'Card Eyebrow', $d['_clg_pm_incl_eyebrow']);
    clg_field_text($pid, '_clg_pm_incl_title', 'Card Title', $d['_clg_pm_incl_title']);
    clg_field_list($pid, '_clg_pm_incl_items', 'Checklist Items', $d['_clg_pm_incl_items']);

    echo '<hr><h3>The Standard (quote section)</h3>';
    clg_field_image($pid, '_clg_pm_std_image', 'Image', $d['_clg_pm_std_image']);
    clg_field_text($pid, '_clg_pm_std_image_alt', 'Image Alt Text', $d['_clg_pm_std_image_alt']);
    clg_field_text($pid, '_clg_pm_std_eyebrow', 'Eyebrow', $d['_clg_pm_std_eyebrow']);
    clg_field_textarea($pid, '_clg_pm_std_quote', 'Quote (curly quotes added automatically)', $d['_clg_pm_std_quote']);
    clg_field_textarea($pid, '_clg_pm_std_text', 'Paragraph Below Quote', $d['_clg_pm_std_text']);

    echo '<hr><h3>Who This Is For</h3>';
    clg_field_text($pid, '_clg_pm_who_eyebrow', 'Eyebrow', $d['_clg_pm_who_eyebrow']);
    clg_field_text($pid, '_clg_pm_who_title', 'Title', $d['_clg_pm_who_title']);
    clg_field_textarea($pid, '_clg_pm_who_paras', 'Paragraphs (one per line)', $d['_clg_pm_who_paras'], 5, 'Each line becomes a paragraph');
    clg_field_text($pid, '_clg_pm_who_btn_text', 'Button Text', $d['_clg_pm_who_btn_text']);
    clg_field_text($pid, '_clg_pm_who_btn_url', 'Button URL', $d['_clg_pm_who_btn_url']);
    clg_field_image($pid, '_clg_pm_who_image', 'Image (tall, 4:5)', $d['_clg_pm_who_image']);
    clg_field_text($pid, '_clg_pm_who_image_alt', 'Image Alt Text', $d['_clg_pm_who_image_alt']);
}


// ===== CUSTOMER JOURNEY META BOX =====
function clg_metabox_cj($post) {
    clg_nonce_once();
    $d = clg_cj_defaults();
    $pid = $post->ID;

    echo '<h3>Journey Steps</h3>';
    clg_field_text($pid, '_clg_cj_get_heading', '"What you\'ll get" Column Heading', $d['_clg_cj_get_heading']);
    clg_field_repeater($pid, '_clg_cj_steps', 'Steps', $d['_clg_cj_steps'], array('num', 'label', 'title', 'text', 'get_text'));

    echo '<hr><h3>Booking Section (Calendly)</h3>';
    clg_field_text($pid, '_clg_cj_book_eyebrow', 'Eyebrow', $d['_clg_cj_book_eyebrow']);
    clg_field_text($pid, '_clg_cj_book_title', 'Title', $d['_clg_cj_book_title']);
    clg_field_textarea($pid, '_clg_cj_book_text', 'Text', $d['_clg_cj_book_text']);
    clg_field_repeater($pid, '_clg_cj_book_features', 'Feature Bullets (clock / call / shield icons)', $d['_clg_cj_book_features'], array('title', 'text'));
    clg_field_text($pid, '_clg_cj_calendly_url', 'Calendly URL', $d['_clg_cj_calendly_url'], 'Full Calendly scheduling link');
    clg_field_text($pid, '_clg_cj_book_note', 'Note Below Widget', $d['_clg_cj_book_note']);
    clg_field_text($pid, '_clg_cj_noscript', 'No-JavaScript Fallback Text', $d['_clg_cj_noscript'], 'Shown only when the visitor has JavaScript disabled. Use [link=mailto:...]text[/link] for the email link.');

    echo '<hr><h3>FAQ</h3>';
    clg_field_text($pid, '_clg_cj_faq_eyebrow', 'Eyebrow', $d['_clg_cj_faq_eyebrow']);
    clg_field_text($pid, '_clg_cj_faq_title', 'Title', $d['_clg_cj_faq_title']);
    clg_field_repeater($pid, '_clg_cj_faqs', 'Questions', $d['_clg_cj_faqs'], array('question', 'answer'));
}


// ===== COMPLIANCE META BOX =====
function clg_metabox_compliance($post) {
    clg_nonce_once();
    $d = clg_comp_defaults();
    $pid = $post->ID;

    echo '<h3>Registrations & Memberships</h3>';
    clg_field_text($pid, '_clg_comp_reg_eyebrow', 'Eyebrow', $d['_clg_comp_reg_eyebrow']);
    clg_field_text($pid, '_clg_comp_reg_title', 'Title', $d['_clg_comp_reg_title']);
    clg_field_textarea($pid, '_clg_comp_reg_text', 'Text', $d['_clg_comp_reg_text']);
    clg_field_repeater($pid, '_clg_comp_reg_items', 'Registration Items', $d['_clg_comp_reg_items'], array('title', 'text', 'tag'));

    echo '<hr><h3>Investor Onboarding</h3>';
    clg_field_text($pid, '_clg_comp_inv_eyebrow', 'Eyebrow', $d['_clg_comp_inv_eyebrow']);
    clg_field_text($pid, '_clg_comp_inv_title', 'Title', $d['_clg_comp_inv_title']);
    clg_field_textarea($pid, '_clg_comp_inv_paras', 'Paragraphs (one per line)', $d['_clg_comp_inv_paras'], 4, 'Each line becomes a paragraph');
    clg_field_text($pid, '_clg_comp_inv_btn_text', 'Button Text', $d['_clg_comp_inv_btn_text']);
    clg_field_text($pid, '_clg_comp_inv_btn_url', 'Button URL', $d['_clg_comp_inv_btn_url']);

    echo '<h4>Onboarding Pack Card (dark green)</h4>';
    clg_field_text($pid, '_clg_comp_pack_eyebrow', 'Card Eyebrow', $d['_clg_comp_pack_eyebrow']);
    clg_field_text($pid, '_clg_comp_pack_title', 'Card Title', $d['_clg_comp_pack_title']);
    clg_field_text($pid, '_clg_comp_pack_text', 'Card Text', $d['_clg_comp_pack_text']);
    clg_field_repeater($pid, '_clg_comp_pack_groups', 'Checklist Groups (items: one per line, [em]...[/em] for italics)', $d['_clg_comp_pack_groups'], array('heading', 'items'));

    echo '<hr><h3>Why It Matters</h3>';
    clg_field_text($pid, '_clg_comp_why_eyebrow', 'Eyebrow', $d['_clg_comp_why_eyebrow']);
    clg_field_text($pid, '_clg_comp_why_title', 'Title', $d['_clg_comp_why_title']);
    clg_field_textarea($pid, '_clg_comp_why_paras', 'Paragraphs (one per line)', $d['_clg_comp_why_paras'], 4, 'Each line becomes a paragraph');
    clg_field_image($pid, '_clg_comp_why_image', 'Image', $d['_clg_comp_why_image']);
    clg_field_text($pid, '_clg_comp_why_image_alt', 'Image Alt Text', $d['_clg_comp_why_image_alt']);
}


// ===== CONTACT META BOX =====
function clg_metabox_contact($post) {
    clg_nonce_once();
    $d = clg_contact_defaults();
    $pid = $post->ID;

    echo '<h3>Contact Methods</h3>';
    clg_field_text($pid, '_clg_contact_methods_eyebrow', 'Eyebrow', $d['_clg_contact_methods_eyebrow']);
    clg_field_text($pid, '_clg_contact_methods_title', 'Title', $d['_clg_contact_methods_title']);
    clg_field_textarea($pid, '_clg_contact_methods_text', 'Text', $d['_clg_contact_methods_text']);
    echo '<h4>Card 1 — Email</h4>';
    clg_field_text($pid, '_clg_contact_email_label', 'Label', $d['_clg_contact_email_label']);
    clg_field_text($pid, '_clg_contact_email_value', 'Email Address', $d['_clg_contact_email_value']);
    clg_field_text($pid, '_clg_contact_email_note', 'Note', $d['_clg_contact_email_note']);
    echo '<h4>Card 2 — Book a Call</h4>';
    clg_field_text($pid, '_clg_contact_book_label', 'Label', $d['_clg_contact_book_label']);
    clg_field_text($pid, '_clg_contact_book_title', 'Title', $d['_clg_contact_book_title']);
    clg_field_text($pid, '_clg_contact_book_note', 'Note', $d['_clg_contact_book_note']);
    clg_field_text($pid, '_clg_contact_book_btn_text', 'Button Text', $d['_clg_contact_book_btn_text']);
    clg_field_text($pid, '_clg_contact_book_btn_url', 'Button URL', $d['_clg_contact_book_btn_url']);
    echo '<h4>Card 3 — Area</h4>';
    clg_field_text($pid, '_clg_contact_area_label', 'Label', $d['_clg_contact_area_label']);
    clg_field_text($pid, '_clg_contact_area_title', 'Title', $d['_clg_contact_area_title']);
    clg_field_text($pid, '_clg_contact_area_note', 'Note', $d['_clg_contact_area_note']);

    echo '<hr><h3>Message Form</h3>';
    clg_field_text($pid, '_clg_contact_form_action', 'Form Action URL', $d['_clg_contact_form_action'], 'FormSubmit endpoint — change the email after the last slash to change the recipient');
    clg_field_text($pid, '_clg_contact_form_eyebrow', 'Form Eyebrow', $d['_clg_contact_form_eyebrow']);
    clg_field_text($pid, '_clg_contact_form_title', 'Form Title', $d['_clg_contact_form_title']);
    clg_field_textarea($pid, '_clg_contact_form_intro', 'Form Intro', $d['_clg_contact_form_intro'], 2);
    clg_field_text($pid, '_clg_contact_label_name', 'Field Label: Name', $d['_clg_contact_label_name']);
    clg_field_text($pid, '_clg_contact_label_email', 'Field Label: Email', $d['_clg_contact_label_email']);
    clg_field_text($pid, '_clg_contact_label_phone', 'Field Label: Phone', $d['_clg_contact_label_phone']);
    clg_field_text($pid, '_clg_contact_label_interest', 'Field Label: Interest', $d['_clg_contact_label_interest']);
    clg_field_text($pid, '_clg_contact_label_message', 'Field Label: Message', $d['_clg_contact_label_message']);
    clg_field_list($pid, '_clg_contact_interest_options', 'Interest Dropdown Options', $d['_clg_contact_interest_options']);
    clg_field_text($pid, '_clg_contact_message_placeholder', 'Message Placeholder', $d['_clg_contact_message_placeholder']);
    clg_field_text($pid, '_clg_contact_form_subject', 'Email Subject Line', $d['_clg_contact_form_subject']);
    clg_field_text($pid, '_clg_contact_submit_text', 'Submit Button Text', $d['_clg_contact_submit_text']);
    clg_field_textarea($pid, '_clg_contact_form_note', 'Note Below Button', $d['_clg_contact_form_note'], 2);

    echo '<hr><h3>Bottom Call-to-Action (light)</h3>';
    clg_field_text($pid, '_clg_contact_cta_eyebrow', 'Eyebrow', $d['_clg_contact_cta_eyebrow']);
    clg_field_text($pid, '_clg_contact_cta_title', 'Title', $d['_clg_contact_cta_title']);
    clg_field_textarea($pid, '_clg_contact_cta_text', 'Text', $d['_clg_contact_cta_text']);
    clg_field_text($pid, '_clg_contact_cta_btn_text', 'Button Text', $d['_clg_contact_cta_btn_text']);
    clg_field_text($pid, '_clg_contact_cta_btn_url', 'Button URL', $d['_clg_contact_cta_btn_url']);
}


// ===== TERMS META BOX =====
function clg_metabox_terms($post) {
    clg_nonce_once();
    $d = clg_terms_defaults();
    $pid = $post->ID;

    clg_field_text($pid, '_clg_terms_updated', 'Last Updated Line', $d['_clg_terms_updated']);
    clg_field_textarea($pid, '_clg_terms_body', 'Terms Body (HTML — h2, h3, p, ul, li, a allowed)', $d['_clg_terms_body'], 30);
}


// ===== COMING SOON META BOX =====
function clg_metabox_coming_soon($post) {
    clg_nonce_once();
    $d = clg_cs_defaults();
    $pid = $post->ID;

    echo '<p class="description">This page uses the standalone Coming Soon template. To gate the whole site with it later, set this page as the front page under Settings → Reading.</p>';
    clg_field_text($pid, '_clg_cs_eyebrow', 'Eyebrow', $d['_clg_cs_eyebrow']);
    clg_field_text($pid, '_clg_cs_tagline', 'Tagline', $d['_clg_cs_tagline']);
    clg_field_textarea($pid, '_clg_cs_message', 'Message', $d['_clg_cs_message']);
    clg_field_text($pid, '_clg_cs_phone_label', 'Phone Card Label', $d['_clg_cs_phone_label']);
    clg_field_text($pid, '_clg_cs_phone', 'Phone Number', $d['_clg_cs_phone']);
    clg_field_text($pid, '_clg_cs_email_label', 'Email Card Label', $d['_clg_cs_email_label']);
    clg_field_text($pid, '_clg_cs_email', 'Email Address', $d['_clg_cs_email']);
    clg_field_text($pid, '_clg_cs_footer_text', 'Footer Text (after the year)', $d['_clg_cs_footer_text']);
}


// ===== SAVE ALL META =====
add_action('save_post_page', 'clg_save_all_meta');
function clg_save_all_meta($post_id) {
    if (!isset($_POST['clg_meta_nonce']) || !wp_verify_nonce($_POST['clg_meta_nonce'], 'clg_save_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // ----- Plain text / textarea / list / image-url fields -----
    $text_fields = array(
        // Shared hero + nav CTA
        '_clg_hero_breadcrumb', '_clg_hero_title', '_clg_hero_lead',
        '_clg_hero_btn1_text', '_clg_hero_btn1_url', '_clg_hero_btn2_text', '_clg_hero_btn2_url',
        '_clg_nav_cta_label', '_clg_nav_cta_url',
        // Shared CTA banner
        '_clg_cta_eyebrow', '_clg_cta_title', '_clg_cta_text',
        '_clg_cta_btn1_text', '_clg_cta_btn1_url', '_clg_cta_btn2_text', '_clg_cta_btn2_url',
        // Home
        '_clg_home_hero_eyebrow', '_clg_home_hero_title', '_clg_home_hero_lead',
        '_clg_home_hero_btn1_text', '_clg_home_hero_btn1_url', '_clg_home_hero_btn2_text', '_clg_home_hero_btn2_url',
        '_clg_home_hero_image', '_clg_home_hero_image_alt', '_clg_home_hero_badge', '_clg_home_marquee',
        '_clg_home_wwd_eyebrow', '_clg_home_wwd_title', '_clg_home_wwd_text', '_clg_home_wwd_btn_text', '_clg_home_wwd_btn_url',
        '_clg_home_prin_eyebrow', '_clg_home_prin_title', '_clg_home_prin_text',
        '_clg_home_quote_image', '_clg_home_quote_image_alt', '_clg_home_quote_eyebrow',
        '_clg_home_quote_text', '_clg_home_quote_para', '_clg_home_quote_btn_text', '_clg_home_quote_btn_url',
        '_clg_home_how_eyebrow', '_clg_home_how_title', '_clg_home_how_text', '_clg_home_how_btn_text', '_clg_home_how_btn_url',
        // About
        '_clg_about_why_image', '_clg_about_why_image_alt', '_clg_about_why_eyebrow', '_clg_about_why_title', '_clg_about_why_paras',
        '_clg_about_founders_eyebrow', '_clg_about_founders_title', '_clg_about_founders_text',
        '_clg_about_f1_image', '_clg_about_f1_name', '_clg_about_f1_role', '_clg_about_f1_bio',
        '_clg_about_f2_image', '_clg_about_f2_name', '_clg_about_f2_role', '_clg_about_f2_bio',
        '_clg_about_where_eyebrow', '_clg_about_where_title', '_clg_about_where_text',
        '_clg_about_where_image', '_clg_about_where_image_alt',
        '_clg_about_how_eyebrow', '_clg_about_how_title', '_clg_about_how_text',
        '_clg_about_build_image', '_clg_about_build_image_alt', '_clg_about_build_eyebrow', '_clg_about_build_title',
        '_clg_about_build_para', '_clg_about_build_quote',
        '_clg_about_build_btn1_text', '_clg_about_build_btn1_url', '_clg_about_build_btn2_text', '_clg_about_build_btn2_url',
        '_clg_about_social_eyebrow', '_clg_about_social_title', '_clg_about_social_text',
        '_clg_about_social_btn_text', '_clg_about_social_btn_url',
        '_clg_about_sc1_image', '_clg_about_sc1_caption', '_clg_about_sc2_image', '_clg_about_sc2_caption',
        '_clg_about_sc3_image', '_clg_about_sc3_caption', '_clg_about_sc4_image', '_clg_about_sc4_caption',
        '_clg_about_social_note',
        // Property Management
        '_clg_pm_wwd_eyebrow', '_clg_pm_wwd_title', '_clg_pm_wwd_paras', '_clg_pm_wwd_btn_text', '_clg_pm_wwd_btn_url',
        '_clg_pm_wwd_image', '_clg_pm_wwd_image_alt',
        '_clg_pm_appr_eyebrow', '_clg_pm_appr_title', '_clg_pm_appr_text',
        '_clg_pm_get_eyebrow', '_clg_pm_get_title', '_clg_pm_get_text', '_clg_pm_get_btn_text', '_clg_pm_get_btn_url',
        '_clg_pm_incl_eyebrow', '_clg_pm_incl_title', '_clg_pm_incl_items',
        '_clg_pm_std_image', '_clg_pm_std_image_alt', '_clg_pm_std_eyebrow', '_clg_pm_std_quote', '_clg_pm_std_text',
        '_clg_pm_who_eyebrow', '_clg_pm_who_title', '_clg_pm_who_paras', '_clg_pm_who_btn_text', '_clg_pm_who_btn_url',
        '_clg_pm_who_image', '_clg_pm_who_image_alt',
        // Customer Journey
        '_clg_cj_get_heading',
        '_clg_cj_book_eyebrow', '_clg_cj_book_title', '_clg_cj_book_text',
        '_clg_cj_calendly_url', '_clg_cj_book_note', '_clg_cj_noscript',
        '_clg_cj_faq_eyebrow', '_clg_cj_faq_title',
        // Compliance
        '_clg_comp_reg_eyebrow', '_clg_comp_reg_title', '_clg_comp_reg_text',
        '_clg_comp_inv_eyebrow', '_clg_comp_inv_title', '_clg_comp_inv_paras', '_clg_comp_inv_btn_text', '_clg_comp_inv_btn_url',
        '_clg_comp_pack_eyebrow', '_clg_comp_pack_title', '_clg_comp_pack_text',
        '_clg_comp_why_eyebrow', '_clg_comp_why_title', '_clg_comp_why_paras',
        '_clg_comp_why_image', '_clg_comp_why_image_alt',
        // Contact
        '_clg_contact_methods_eyebrow', '_clg_contact_methods_title', '_clg_contact_methods_text',
        '_clg_contact_email_label', '_clg_contact_email_value', '_clg_contact_email_note',
        '_clg_contact_book_label', '_clg_contact_book_title', '_clg_contact_book_note',
        '_clg_contact_book_btn_text', '_clg_contact_book_btn_url',
        '_clg_contact_area_label', '_clg_contact_area_title', '_clg_contact_area_note',
        '_clg_contact_form_action', '_clg_contact_form_eyebrow', '_clg_contact_form_title', '_clg_contact_form_intro',
        '_clg_contact_label_name', '_clg_contact_label_email', '_clg_contact_label_phone',
        '_clg_contact_label_interest', '_clg_contact_label_message',
        '_clg_contact_interest_options', '_clg_contact_message_placeholder',
        '_clg_contact_form_subject', '_clg_contact_submit_text', '_clg_contact_form_note',
        '_clg_contact_cta_eyebrow', '_clg_contact_cta_title', '_clg_contact_cta_text',
        '_clg_contact_cta_btn_text', '_clg_contact_cta_btn_url',
        // Terms
        '_clg_terms_updated',
        // Coming Soon
        '_clg_cs_eyebrow', '_clg_cs_tagline', '_clg_cs_message',
        '_clg_cs_phone_label', '_clg_cs_phone', '_clg_cs_email_label', '_clg_cs_email', '_clg_cs_footer_text',
    );

    foreach ($text_fields as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_textarea_field(wp_unslash($_POST[$key])));
        }
    }

    // ----- HTML fields -----
    $html_fields = array('_clg_terms_body');
    foreach ($html_fields as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, wp_kses_post(wp_unslash($_POST[$key])));
        }
    }

    // ----- Repeater fields -----
    $repeater_fields = array(
        '_clg_home_hero_stats', '_clg_home_wwd_cards', '_clg_home_principles', '_clg_home_how_steps',
        '_clg_about_where_stats', '_clg_about_values',
        '_clg_pm_values',
        '_clg_cj_steps', '_clg_cj_book_features', '_clg_cj_faqs',
        '_clg_comp_reg_items', '_clg_comp_pack_groups',
    );

    foreach ($repeater_fields as $key) {
        if (isset($_POST[$key]) && is_array($_POST[$key])) {
            $items = array();
            foreach ($_POST[$key] as $item) {
                if (!is_array($item)) continue;
                $clean = array();
                $has_content = false;
                foreach ($item as $field => $val) {
                    $clean_val = wp_kses_post(wp_unslash($val));
                    $clean[sanitize_key($field)] = $clean_val;
                    if (trim($clean_val) !== '') $has_content = true;
                }
                if ($has_content) $items[] = $clean;
            }
            update_post_meta($post_id, $key, wp_json_encode($items));
        }
    }
}
