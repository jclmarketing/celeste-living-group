<?php
/**
 * Theme Settings — Appearance → Celeste Settings
 * Site-wide strings: brand, footer, contact details and social links.
 * No plugins, plain options API.
 */

add_action('admin_menu', 'clg_add_settings_page');
function clg_add_settings_page() {
    add_theme_page(
        'Celeste Settings',
        'Celeste Settings',
        'manage_options',
        'clg-settings',
        'clg_settings_page'
    );
}

/**
 * Option keys => array(label, default, type[, description])
 */
function clg_settings_fields() {
    return array(
        'clg_brand_name'       => array('Brand Name (header)', 'Celeste', 'text'),
        'clg_brand_sub'        => array('Brand Subtitle (header)', 'Living Group', 'text'),
        'clg_footer_brand_sub' => array('Brand Subtitle (footer)', 'LIVING GROUP', 'text'),
        'clg_footer_tagline'   => array('Footer Tagline', "Honest property partnerships in Birmingham & Staffordshire.", 'textarea', 'New line = line break'),
        'clg_contact_email'    => array('Contact Email', 'info@celestelivinggroup.co.uk', 'text', 'Used in the footer and mailto links'),
        'clg_phone'            => array('Telephone Number', '0121 7989 081', 'text', 'Used for the click-to-call links in the header and on the contact page'),
        'clg_form_recipient'   => array('Enquiry Recipient', 'info@celestelivinggroup.co.uk', 'text', 'Contact form enquiries are emailed here'),
        'clg_enquiry_webhook'  => array('Enquiry Webhook (n8n)', 'https://n8n.jclmarketing.co.uk/webhook/celeste-enquiry', 'text', 'Where the contact form sends enquiries to be emailed out'),
        'clg_area_line'        => array('Area Line (footer)', 'Birmingham & Staffordshire, UK', 'text'),
        'clg_instagram_url'    => array('Instagram URL', 'https://www.instagram.com/celeste.livinggroup/', 'text', 'Leave blank to hide'),
        'clg_instagram_label'  => array('Instagram Link Label (footer)', 'Instagram', 'text'),
        'clg_linkedin_url'     => array('LinkedIn URL', 'https://uk.linkedin.com/company/celeste-living-group', 'text', 'Leave blank to hide'),
        'clg_linkedin_label'   => array('LinkedIn Link Label (footer)', 'LinkedIn', 'text'),
        'clg_facebook_url'     => array('Facebook URL', 'https://www.facebook.com/p/Celeste-Living-Group-61575384155989/', 'text', 'Leave blank to hide'),
        'clg_facebook_label'   => array('Facebook Link Label (footer)', 'Facebook', 'text'),
        'clg_explore_heading'  => array('Footer Column Heading: Explore', 'Explore', 'text'),
        'clg_contact_heading'  => array('Footer Column Heading: Contact', 'Contact', 'text'),
        'clg_follow_heading'   => array('Footer Column Heading: Follow', 'Follow', 'text'),
        'clg_copyright_text'   => array('Copyright Text (after the year)', 'Celeste Living Group. All rights reserved.', 'text'),
        'clg_terms_link_label' => array('Terms Link Label (footer)', 'Terms of Business', 'text'),
        'clg_breadcrumb_home'  => array('Breadcrumb "Home" Label', 'Home', 'text', 'First word of the breadcrumb on inner pages'),
        'clg_404_title'        => array('404 Page Title', "This page doesn't stack.", 'text'),
        'clg_404_text'         => array('404 Page Text', "The page you're looking for has moved, or never existed. Let's get you back somewhere useful.", 'textarea'),
        'clg_404_btn_text'     => array('404 Button Text', 'Back to the homepage', 'text'),
    );
}

function clg_settings_page() {
    if (!current_user_can('manage_options')) return;

    $fields = clg_settings_fields();

    if (isset($_POST['clg_settings_nonce']) && wp_verify_nonce($_POST['clg_settings_nonce'], 'clg_save_settings')) {
        foreach ($fields as $key => $def) {
            if (isset($_POST[$key])) {
                $val = ($def[2] === 'textarea')
                    ? sanitize_textarea_field(wp_unslash($_POST[$key]))
                    : sanitize_text_field(wp_unslash($_POST[$key]));
                update_option($key, $val);
            }
        }
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Celeste Settings</h1>
        <p>Site-wide content: brand text, footer, contact details and social links. Page content is edited on each page under Pages.</p>
        <form method="post">
            <?php wp_nonce_field('clg_save_settings', 'clg_settings_nonce'); ?>
            <table class="form-table">
                <?php foreach ($fields as $key => $def) :
                    $val = get_option($key, $def[1]);
                ?>
                <tr>
                    <th scope="row"><label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($def[0]); ?></label></th>
                    <td>
                        <?php if ($def[2] === 'textarea') : ?>
                            <textarea id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" rows="3" style="width:100%;max-width:500px;"><?php echo esc_textarea($val); ?></textarea>
                        <?php else : ?>
                            <input type="text" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($val); ?>" style="width:100%;max-width:500px;" />
                        <?php endif; ?>
                        <?php if (!empty($def[3])) : ?>
                            <p class="description"><?php echo esc_html($def[3]); ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}
