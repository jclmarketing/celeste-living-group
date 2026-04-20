(() => {
  const header = document.getElementById('site-header');
  const nav = document.getElementById('nav');
  const burger = document.getElementById('burger');
  const mobileMenu = document.getElementById('mobileMenu');

  const onScroll = () => {
    if (!header) return;
    header.classList.toggle('is-scrolled', window.scrollY > 12);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (burger && mobileMenu) {
    burger.addEventListener('click', () => {
      const open = mobileMenu.classList.toggle('is-open');
      nav.classList.toggle('is-open', open);
      burger.setAttribute('aria-expanded', String(open));
      mobileMenu.setAttribute('aria-hidden', String(!open));
      document.body.style.overflow = open ? 'hidden' : '';
    });
    mobileMenu.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        mobileMenu.classList.remove('is-open');
        nav.classList.remove('is-open');
        burger.setAttribute('aria-expanded', 'false');
        mobileMenu.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      });
    });
  }

  const io = 'IntersectionObserver' in window
    ? new IntersectionObserver((entries) => {
        entries.forEach(e => {
          if (e.isIntersecting) {
            e.target.classList.add('is-in');
            io.unobserve(e.target);
          }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' })
    : null;

  document.querySelectorAll('.reveal').forEach(el => {
    if (io) io.observe(el);
    else el.classList.add('is-in');
  });

  const yr = document.getElementById('yr');
  if (yr) yr.textContent = new Date().getFullYear();

  if (new URLSearchParams(window.location.search).get('sent') === '1') {
    const form = document.querySelector('form');
    if (form) {
      const success = document.createElement('div');
      success.style.cssText = 'padding:20px; background:var(--sage-100); border-radius:12px; color:var(--forest-900); margin-bottom:20px; font-weight:500;';
      success.textContent = 'Thanks — your message is in. We\u2019ll reply within one working day.';
      form.prepend(success);
    }
  }
})();
