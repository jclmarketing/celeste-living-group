<?php
/**
 * Plugin Name: Celeste — Cookie Consent (Consent Mode v2)
 * Description: Google Consent Mode v2 defaults + on-brand consent banner. Lives in mu-plugins so it survives theme and staging pushes.
 * Version: 1.0.0
 * Author: JCL Marketing
 *
 * Notes:
 * - The defaults script must run BEFORE Site Kit's gtag. It is printed on
 *   wp_head priority 0 and tagged data-no-optimize / data-cfasync so
 *   LiteSpeed and Cloudflare Rocket Loader leave it inline and early.
 *   Without that, LiteSpeed rewrites inline JS to type="litespeed/javascript"
 *   and defers it, so the denied-by-default state lands after the tag fires.
 * - All banner styling is inline on the elements for the same reason.
 */

if (!defined('ABSPATH')) exit;

const CLG_CONSENT_COOKIE = 'clg_consent';

/**
 * Consent Mode v2 defaults — denied until the visitor chooses.
 */
add_action('wp_head', 'clg_consent_mode_defaults', 0);
function clg_consent_mode_defaults() {
    ?>
<script data-no-optimize="1" data-cfasync="false" data-no-defer="1">
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('consent', 'default', {
  'ad_storage': 'denied',
  'analytics_storage': 'denied',
  'ad_user_data': 'denied',
  'ad_personalization': 'denied',
  'functionality_storage': 'granted',
  'security_storage': 'granted',
  'wait_for_update': 500
});
(function () {
  var m = document.cookie.match(/(?:^|;\s*)<?php echo CLG_CONSENT_COOKIE; ?>=([^;]+)/);
  if (m && m[1] === 'granted') {
    gtag('consent', 'update', {
      'ad_storage': 'granted',
      'analytics_storage': 'granted',
      'ad_user_data': 'granted',
      'ad_personalization': 'granted'
    });
  }
})();
</script>
    <?php
}

/**
 * The banner itself.
 */
add_action('wp_footer', 'clg_consent_banner', 100);
function clg_consent_banner() {
    $policy = get_privacy_policy_url();
    if (!$policy) {
        $terms  = get_page_by_path('terms');
        $policy = $terms ? get_permalink($terms) : home_url('/');
    }
    ?>
<div id="clg-consent" role="dialog" aria-live="polite" aria-label="Cookie choices" style="display:none;position:fixed;left:20px;bottom:20px;z-index:99999;max-width:400px;width:calc(100% - 40px);background:#1B4332;color:#F7F4ED;border-radius:18px;padding:24px;box-shadow:0 18px 50px rgba(11,34,24,.35);font-family:'Plus Jakarta Sans',system-ui,-apple-system,'Segoe UI',sans-serif;">
  <p style="margin:0 0 6px;font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:#95D5B2;font-weight:600;">Cookies</p>
  <p style="margin:0 0 16px;font-size:14px;line-height:1.55;color:rgba(247,244,237,.86);">
    We use essential cookies to make this site work, and analytics cookies to understand how it is used. You can accept or decline the optional ones.
    <a href="<?php echo esc_url($policy); ?>" style="color:#95D5B2;text-decoration:underline;text-underline-offset:3px;">Read more</a>.
  </p>
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <button type="button" id="clg-consent-accept" style="flex:1 1 auto;cursor:pointer;border:0;border-radius:999px;padding:11px 20px;background:#F7F4ED;color:#1B4332;font-weight:600;font-size:14px;font-family:inherit;">Accept all</button>
    <button type="button" id="clg-consent-reject" style="flex:1 1 auto;cursor:pointer;border:1px solid rgba(247,244,237,.4);border-radius:999px;padding:11px 20px;background:transparent;color:#F7F4ED;font-weight:600;font-size:14px;font-family:inherit;">Reject non-essential</button>
  </div>
</div>
<script data-no-optimize="1" data-cfasync="false" data-no-defer="1">
(function () {
  var NAME = '<?php echo CLG_CONSENT_COOKIE; ?>';
  var el = document.getElementById('clg-consent');
  if (!el) return;

  function read() {
    var m = document.cookie.match(new RegExp('(?:^|;\\s*)' + NAME + '=([^;]+)'));
    return m ? m[1] : null;
  }
  function write(v) {
    var secure = location.protocol === 'https:' ? ';secure' : '';
    document.cookie = NAME + '=' + v + ';path=/;max-age=31536000;samesite=lax' + secure;
  }
  function close() { el.style.display = 'none'; }

  if (!read()) el.style.display = 'block';

  document.getElementById('clg-consent-accept').addEventListener('click', function () {
    write('granted');
    if (typeof gtag === 'function') {
      gtag('consent', 'update', {
        'ad_storage': 'granted',
        'analytics_storage': 'granted',
        'ad_user_data': 'granted',
        'ad_personalization': 'granted'
      });
    }
    close();
  });

  document.getElementById('clg-consent-reject').addEventListener('click', function () {
    write('denied');
    close();
  });

  // Anything with .clg-consent-reopen (e.g. a footer link) brings the banner back.
  document.addEventListener('click', function (e) {
    var t = e.target.closest ? e.target.closest('.clg-consent-reopen') : null;
    if (t) { e.preventDefault(); el.style.display = 'block'; }
  });
})();
</script>
    <?php
}

/**
 * Never cache a page variant based on the consent cookie — the banner decides
 * its own visibility in the browser, so one cached copy serves everyone.
 */
add_filter('litespeed_vary_cookies', function ($cookies) {
    return array_values(array_diff((array) $cookies, array(CLG_CONSENT_COOKIE)));
});
