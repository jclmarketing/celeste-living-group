<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
wp_body_open();

$clg_brand_name = get_option('clg_brand_name', 'Celeste');
$clg_brand_sub  = get_option('clg_brand_sub', 'Living Group');
$clg_nav_cta    = clg_nav_cta();
?>

<header class="site-header" id="site-header">
  <div class="container">
    <nav class="nav" id="nav">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="nav__brand" aria-label="<?php echo esc_attr($clg_brand_name . ' ' . $clg_brand_sub); ?> — home">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/mark.png'); ?>" alt="" aria-hidden="true" width="512" height="512">
        <span><?php echo esc_html($clg_brand_name); ?><small><?php echo esc_html($clg_brand_sub); ?></small></span>
      </a>
      <div class="nav__links">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'walker'         => new CLG_Nav_Walker(),
            'fallback_cb'    => false,
            'depth'          => 1,
        ));
        ?>
      </div>
      <a href="<?php echo esc_url(clg_url($clg_nav_cta['url'])); ?>" class="btn btn--primary nav__cta"><?php echo esc_html($clg_nav_cta['label']); ?> <span class="arrow">&rarr;</span></a>
      <button class="nav__burger" id="burger" aria-label="Open menu" aria-expanded="false"><span></span></button>
    </nav>
  </div>
</header>

<div class="mobile-menu" id="mobileMenu" aria-hidden="true">
  <?php
  wp_nav_menu(array(
      'theme_location' => 'mobile',
      'container'      => false,
      'items_wrap'     => '%3$s',
      'walker'         => new CLG_Nav_Walker(),
      'fallback_cb'    => false,
      'depth'          => 1,
  ));
  ?>
</div>
