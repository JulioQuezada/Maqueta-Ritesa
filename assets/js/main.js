// main.js (refactor)
(function () {
  'use strict';

  const $ = (sel, all = false, root = document) =>
    all ? Array.from(root.querySelectorAll(sel)) : root.querySelector(sel);

  const on = (el, type, fn, opts) => el && el.addEventListener(type, fn, opts);

  // navbar active (throttled)
  const navbarlinks = $('#navbar .scrollto', true);
  const navbarlinksActive = () => {
    const pos = window.scrollY + 200;
    navbarlinks.forEach(link => {
      if (!link.hash) return;
      const sec = $(link.hash);
      if (!sec) return;
      const inView = pos >= sec.offsetTop && pos <= (sec.offsetTop + sec.offsetHeight);
      link.classList.toggle('active', !!inView);
    });
  };
  on(window, 'load', navbarlinksActive);
  on(document, 'scroll', () => { requestAnimationFrame(navbarlinksActive); }, { passive: true });

  // smooth scroll
  const scrollto = (hash) => {
    const el = $(hash);
    if (!el) return;
    window.scrollTo({ top: el.offsetTop, behavior: 'smooth' });
  };
  on(document, 'click', (e) => {
    const a = e.target.closest('.scrollto');
    if (!a || !a.hash) return;
    if ($(a.hash)) {
      e.preventDefault();
      document.body.classList.remove('mobile-nav-active');
      const t = $('.mobile-nav-toggle');
      if (t) { t.classList.toggle('bi-list', true); t.classList.toggle('bi-x', false); }
      scrollto(a.hash);
    }
  });

  on(window, 'load', () => { if (location.hash && $(location.hash)) scrollto(location.hash); });

  // back-to-top
  const backtotop = $('.back-to-top');
  const toggleBacktotop = () => backtotop && backtotop.classList.toggle('active', window.scrollY > 100);
  on(window, 'load', toggleBacktotop);
  on(document, 'scroll', () => requestAnimationFrame(toggleBacktotop), { passive: true });

  // preloader
  const preloader = $('#preloader');
  on(window, 'load', () => { preloader && preloader.remove(); });

  // Typed (si existe)
  const typed = $('.typed');
  if (typed && window.Typed) {
    const typedStrings = (typed.getAttribute('data-typed-items') || '').split(',').map(s => s.trim()).filter(Boolean);
    if (typedStrings.length) {
      new Typed('.typed', {
        strings: typedStrings,
        loop: true,
        typeSpeed: 100,
        backSpeed: 50,
        backDelay: 2000
      });
    }
  }

  // Isotope (si existe)
  on(window, 'load', () => {
    const container = $('.products-container');
    if (container && window.Isotope) {
      const iso = new Isotope(container, { itemSelector: '.products-item' });
      $('#products-flters li', true).forEach(li => {
        on(li, 'click', (e) => {
          e.preventDefault();
          $('#products-flters li', true).forEach(x => x.classList.remove('filter-active'));
          li.classList.add('filter-active');
          iso.arrange({ filter: li.getAttribute('data-filter') });
          if (window.AOS) iso.on('arrangeComplete', () => AOS.refresh());
        });
      });
    }
  });

  // Lightbox (si existe)
  if (window.GLightbox) {
    GLightbox({ selector: '.products-lightbox' });
    GLightbox({ selector: '.products-details-lightbox', width: '90%', height: '90vh' });
  }

  // Swiper (respeta reduced-motion)
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (window.Swiper) {
    new Swiper('.products-details-slider', {
      speed: 400,
      loop: true,
      autoplay: prefersReduced ? false : { delay: 5000, disableOnInteraction: false },
      pagination: { el: '.swiper-pagination', type: 'bullets', clickable: true }
    });
    new Swiper('.sales-slider', {
      speed: 600,
      loop: true,
      autoplay: prefersReduced ? false : { delay: 5000, disableOnInteraction: false },
      slidesPerView: 'auto',
      pagination: { el: '.swiper-pagination', type: 'bullets', clickable: true }
    });
  }

  // AOS
  on(window, 'load', () => { if (window.AOS) AOS.init({ duration: 1000, easing: 'ease-in-out', once: true, mirror: false }); });

  // Contadores
  if (window.PureCounter) new PureCounter();
})();
