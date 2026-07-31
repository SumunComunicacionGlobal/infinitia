document.addEventListener('DOMContentLoaded', function () {
  function initGsapAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      return;
    }

    gsap.registerPlugin(ScrollTrigger);

    const revealElements = gsap.utils.toArray('.site-main .wp-block-cover__inner-container > * > *, .site-main .is-layout-flow > * > *:not(strong), .wp-block-separator, .single .entry-content > .wp-block-image, .colophon .wp-block-column');

    if (revealElements.length > 0) {
      ScrollTrigger.batch(revealElements, {
        start: 'top 80%',
        end: 'bottom 20%',
        once: true,
        onEnter: function (batch) {
          gsap.from(batch, {
            opacity: 0,
            y: 50,
            duration: 0.3,
            stagger: 0.06,
            overwrite: 'auto',
          });
        },
      });
    }

    const triggerSection = document.querySelector('#kpis-section');
    const elements = document.querySelectorAll('.animated-number');

    if (triggerSection && elements.length > 0) {
      gsap.from(elements, {
        textContent: 0,
        duration: 2,
        snap: { textContent: 1 },
        scrollTrigger: {
          trigger: triggerSection,
          start: 'top center',
          once: true,
        },
      });
    }

    // Efecto parallax + scale suave para fondo de covers.
    gsap.utils.toArray('.bg-scroll-animated .wp-block-cover').forEach(function (cover) {
      const bgImage = cover.querySelector('.wp-block-cover__image-background');

      if (!bgImage) {
        return;
      }

      gsap.set(bgImage, {
        scale: 1,
        yPercent: -4,
        transformOrigin: '50% 50%',
        willChange: 'transform',
      });

      gsap.to(bgImage, {
        scale: 1.20,
        yPercent: 7,
        ease: 'none',
        scrollTrigger: {
          trigger: cover,
          start: 'top bottom',
          end: 'bottom top',
          scrub: 1.2,
          invalidateOnRefresh: true,
        },
      });
    });

    ScrollTrigger.refresh();
  }

  requestAnimationFrame(function () {
    requestAnimationFrame(initGsapAnimations);
  });
});