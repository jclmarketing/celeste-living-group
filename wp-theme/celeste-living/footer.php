<?php
$clg_brand_name       = get_option('clg_brand_name', 'Celeste');
$clg_footer_brand_sub = get_option('clg_footer_brand_sub', 'LIVING GROUP');
$clg_footer_tagline   = get_option('clg_footer_tagline', "Curated living & experiences.\nHonest property partnerships in Birmingham & Staffordshire.");
$clg_contact_email    = get_option('clg_contact_email', 'info@celestelivinggroup.co.uk');
$clg_area_line        = get_option('clg_area_line', 'Birmingham & Staffordshire, UK');
$clg_instagram        = get_option('clg_instagram_url', 'https://instagram.com');
$clg_instagram_label  = get_option('clg_instagram_label', 'Instagram');
$clg_linkedin         = get_option('clg_linkedin_url', 'https://linkedin.com');
$clg_linkedin_label   = get_option('clg_linkedin_label', 'LinkedIn');
$clg_facebook         = get_option('clg_facebook_url', 'https://facebook.com');
$clg_facebook_label   = get_option('clg_facebook_label', 'Facebook');
$clg_explore_heading  = get_option('clg_explore_heading', 'Explore');
$clg_contact_heading  = get_option('clg_contact_heading', 'Contact');
$clg_follow_heading   = get_option('clg_follow_heading', 'Follow');
$clg_copyright        = get_option('clg_copyright_text', 'Celeste Living Group. All rights reserved.');
$clg_terms_label      = get_option('clg_terms_link_label', 'Terms of Business');
$clg_terms_page       = get_page_by_path('terms');
?>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="footer-brand"><?php echo esc_html($clg_brand_name); ?><small><?php echo esc_html($clg_footer_brand_sub); ?></small></div>
        <p><?php echo clg_rich($clg_footer_tagline); ?></p>
      </div>
      <div>
        <h4><?php echo esc_html($clg_explore_heading); ?></h4>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'footer',
            'container'      => false,
            'items_wrap'     => '<ul>%3$s</ul>',
            'fallback_cb'    => false,
            'depth'          => 1,
        ));
        ?>
      </div>
      <div>
        <h4><?php echo esc_html($clg_contact_heading); ?></h4>
        <ul>
          <li><a href="mailto:<?php echo esc_attr($clg_contact_email); ?>"><?php echo esc_html($clg_contact_email); ?></a></li>
          <li><?php echo esc_html($clg_area_line); ?></li>
        </ul>
      </div>
      <div>
        <h4><?php echo esc_html($clg_follow_heading); ?></h4>
        <ul>
          <?php if ($clg_instagram) : ?><li><a href="<?php echo esc_url($clg_instagram); ?>" target="_blank" rel="noopener"><?php echo esc_html($clg_instagram_label); ?></a></li><?php endif; ?>
          <?php if ($clg_linkedin) : ?><li><a href="<?php echo esc_url($clg_linkedin); ?>" target="_blank" rel="noopener"><?php echo esc_html($clg_linkedin_label); ?></a></li><?php endif; ?>
          <?php if ($clg_facebook) : ?><li><a href="<?php echo esc_url($clg_facebook); ?>" target="_blank" rel="noopener"><?php echo esc_html($clg_facebook_label); ?></a></li><?php endif; ?>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <div>&copy; <span id="yr"></span> <?php echo esc_html($clg_copyright); ?></div>
      <div style="display:flex; gap:20px; flex-wrap:wrap;">
        <?php if ($clg_terms_page) : ?>
          <a href="<?php echo esc_url(get_permalink($clg_terms_page)); ?>"><?php echo esc_html($clg_terms_label); ?></a>
        <?php endif; ?>
        <a href="mailto:<?php echo esc_attr($clg_contact_email); ?>"><?php echo esc_html($clg_contact_email); ?></a>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
