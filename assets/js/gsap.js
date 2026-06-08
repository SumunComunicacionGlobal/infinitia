document.addEventListener('DOMContentLoaded', function () {
  gsap.registerPlugin(ScrollTrigger);

  gsap.utils.toArray('.site-main .wp-block-cover__inner-container > * > *, .site-main .is-layout-flow > * > *, .wp-block-separator, .single .entry-content > .wp-block-image').forEach(function (element) {
    gsap.from(element, {
      scrollTrigger: {
        trigger: element,
        start: 'top 80%',
        end: 'bottom 20%',
        toggleActions: 'play none none none',
      },
      opacity: 0,
      y: 50,
      duration: 0.3,
    });
  });

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
});