// Añade clase a body cuando se hace scroll
window.addEventListener("scroll", function() {
    if (window.scrollY > 180) {
        document.body.classList.add("scrolled");
    } else {
        document.body.classList.remove("scrolled");
    }
});

// Añade botones de scroll a la izquierda y derecha
function initHorizontalScrollButtons() {
    document.querySelectorAll(".is-style-group-horizontal-scroll-btns").forEach((content) => {
        if (!content || content.dataset.scrollButtonsInit === "1") {
            return;
        }

        if (content.classList.contains("facetwp-facet-sectores")) {
            content.querySelectorAll(".facetwp-radio").forEach((item) => {
                item.style.flex = "0 0 auto";
            });
        }

        if (content.children.length <= 1) {
            return;
        }

        const rightBtn = document.createElement("button");
        rightBtn.classList.add("scrolling-button", "scrolling-button--right");
        rightBtn.innerHTML = "→";

        const leftBtn = document.createElement("button");
        leftBtn.classList.add("scrolling-button", "scrolling-button--left");
        leftBtn.innerHTML = "←";

        const buttonContainer = document.createElement("div");
        buttonContainer.classList.add("scrolling-button-container");
        buttonContainer.appendChild(leftBtn);
        buttonContainer.appendChild(rightBtn);

        content.parentNode.insertBefore(buttonContainer, content.nextSibling);

        function getScrollStep() {
            return window.innerWidth < 768 ? 400 : 288;
        }

        function updateButtonsState() {
            const maxScroll = Math.max(content.scrollWidth - content.clientWidth, 0);
            leftBtn.disabled = content.scrollLeft <= 1;
            rightBtn.disabled = content.scrollLeft >= maxScroll - 1;
            buttonContainer.style.display = maxScroll > 0 ? "flex" : "none";
        }

        rightBtn.addEventListener("click", () => {
            content.scrollBy({ left: getScrollStep(), behavior: "smooth" });
        });

        leftBtn.addEventListener("click", () => {
            content.scrollBy({ left: -getScrollStep(), behavior: "smooth" });
        });

        content.addEventListener("scroll", updateButtonsState, { passive: true });
        window.addEventListener("resize", updateButtonsState);

        content.dataset.scrollButtonsInit = "1";
        updateButtonsState();
    });
}

document.addEventListener("DOMContentLoaded", initHorizontalScrollButtons);
document.addEventListener("facetwp-loaded", initHorizontalScrollButtons);

//botón para mostrar todos los logos de clientes

function initClientesLogosToggle() {
    document.querySelectorAll(".smn-clientes-shortcode").forEach((wrapper) => {
        const logos = wrapper.querySelector(".smn-clientes-logos.facetwp-template");
        const toggleBtn = wrapper.querySelector(".smn-clientes-logos__toggle");
        const wrapperClassExpanded = "smn-clientes-shortcode--show-all";
        const wrapperClassHideButton = "smn-clientes-shortcode--hide-toggle";

        if (!logos) {
            return;
        }

        if (toggleBtn && toggleBtn.dataset.toggleInit !== "1") {
            toggleBtn.addEventListener("click", () => {
                logos.classList.toggle("show-all");
            });

            toggleBtn.dataset.toggleInit = "1";
        }

        if (wrapper.dataset.facetRadioInit === "1") {
            return;
        }

        wrapper.addEventListener("click", (event) => {
            const facetRadio = event.target.closest(".facetwp-radio");

            if (!facetRadio || !wrapper.contains(facetRadio)) {
                return;
            }

            const radioValue = (facetRadio.getAttribute("data-value") || "").trim();

            // Excepcion: el radio "mostrar todos" (sin data-value) restaura estado original.
            if (!radioValue) {
                wrapper.classList.remove(wrapperClassExpanded, wrapperClassHideButton);
                logos.classList.remove("show-all");
                return;
            }

            const isExpanded = wrapper.classList.contains(wrapperClassExpanded);

            if (isExpanded) {
                wrapper.classList.remove(wrapperClassExpanded, wrapperClassHideButton);
                logos.classList.remove("show-all");
                return;
            }

            wrapper.classList.add(wrapperClassExpanded, wrapperClassHideButton);
        });

        wrapper.dataset.facetRadioInit = "1";
    });
}

document.addEventListener("DOMContentLoaded", initClientesLogosToggle);
document.addEventListener("facetwp-loaded", initClientesLogosToggle);

// Añade drag para los elementos con scroll horizontal
document.addEventListener('DOMContentLoaded', (event) => {
    const sliders = document.querySelectorAll('.is-style-group-horizontal-scroll');
    let isDown = false;
    let startX;
    let scrollLeft;
  
    // Añade el evento a cada slider
    sliders.forEach(slider => {
        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mousemove', (e) => {
            if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 3; //scroll-fast
            slider.scrollLeft = scrollLeft - walk;
            console.log(walk);
        });
    });
  
  });

//Rank Math FAQ Dropdown
document.addEventListener('DOMContentLoaded', (event) => {
    const faqs = document.querySelectorAll('.rank-math-list-item');
    faqs.forEach(faq => {
        const question = faq.querySelector('.rank-math-question');
        question.addEventListener('click', () => {
            faq.classList.toggle('active');
        });
    });
});

// Asigna --n automáticamente según la longitud de texto para el efecto reveal
document.addEventListener('DOMContentLoaded', setRevealTextLength);
function setRevealTextLength() {
    const revealTexts = document.querySelectorAll('.scroll--reveal-text');

    revealTexts.forEach((element) => {
        // Fuerza un único <span> contenedor para todo el contenido del párrafo
        const wrapper = document.createElement('span');
        const childNodes = Array.from(element.childNodes);

        childNodes.forEach((node) => {
            if (node.nodeType === Node.ELEMENT_NODE && node.tagName === 'SPAN') {
                while (node.firstChild) {
                    wrapper.appendChild(node.firstChild);
                }
                node.remove();
            } else {
                wrapper.appendChild(node);
            }
        });

        element.innerHTML = '';
        element.appendChild(wrapper);

        const normalizedText = (element.textContent || '')
            .replace(/\s+/g, ' ')
            .trim();

        const textLength = Math.max(normalizedText.length, 1);
        element.style.setProperty('--n', String(textLength));
    });
}

// Toggle para menús de filtros
document.addEventListener('DOMContentLoaded', function() {
    const menuTitles = document.querySelectorAll('.menu-sibling--title');
    const backBtn = document.getElementById('back-btn');
    const filterBy = document.getElementById('filter-by');
    const toggleFilterBtn = document.getElementById('toggle-filter-box');
    const filterBox = document.getElementById('filter-box');
    const closeBtn = document.getElementById('close-btn');
    
    // Toggle para mostrar/ocultar filter-box
    if (toggleFilterBtn && filterBox) {
        const overlay = document.querySelector('.menu-overlay');
        
        function openFilterBox() {
            filterBox.classList.add('filter-box--open');
            if (overlay) {
                overlay.style.opacity = '1';
                overlay.style.visibility = 'visible';
            }
            document.body.style.overflow = 'hidden';
        }
        
        function closeFilterBox() {
            filterBox.classList.remove('filter-box--open');
            if (overlay) {
                overlay.style.opacity = '0';
                overlay.style.visibility = 'hidden';
            }
            document.body.style.overflow = '';
        }
        
        toggleFilterBtn.addEventListener('click', function() {
            const isOpen = filterBox.classList.contains('filter-box--open');
            
            if (isOpen) {
                closeFilterBox();
            } else {
                openFilterBox();
            }
        });
        
        // Cerrar filter-box con botón close
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeFilterBox();
            });
        }
        
        // Cerrar filter-box al hacer click en overlay
        if (overlay) {
            overlay.addEventListener('click', function() {
                if (filterBox.classList.contains('filter-box--open')) {
                    closeFilterBox();
                }
            });
        }
    }
    
    // Función para actualizar visibilidad del botón back y filter-by
    function updateVisibility() {
        const hasOpenMenu = document.querySelector('.menu--siblings.menu-open');
        if (hasOpenMenu) {
            backBtn.classList.add('show');
            filterBy.classList.add('hide');
        } else {
            backBtn.classList.remove('show');
            filterBy.classList.remove('hide');
        }
    }
    
    // Click en títulos de menú para abrir submenús
    menuTitles.forEach(title => {
        title.addEventListener('click', function() {
            // Encontrar el contenedor .menu--siblings hermano
            const siblingsContainer = this.nextElementSibling;
            
            if (siblingsContainer && siblingsContainer.classList.contains('menu--siblings')) {
                // Cerrar otros menús abiertos
                document.querySelectorAll('.menu--siblings.menu-open').forEach(openMenu => {
                    if (openMenu !== siblingsContainer) {
                        openMenu.classList.remove('menu-open');
                        // Actualizar aria-expanded del botón correspondiente
                        const prevTitle = openMenu.previousElementSibling;
                        const prevButton = prevTitle?.querySelector('.btn-icon');
                        if (prevButton) {
                            prevButton.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
                
                // Toggle de la clase menu-open
                siblingsContainer.classList.toggle('menu-open');
                
                // Actualizar atributos ARIA si existen
                const button = this.querySelector('.btn-icon');
                if (button) {
                    const isExpanded = siblingsContainer.classList.contains('menu-open');
                    button.setAttribute('aria-expanded', isExpanded);
                }
                
                // Actualizar visibilidad
                updateVisibility();
            }
        });
    });
    
    // Click en botón back para cerrar menús
    if (backBtn) {
        backBtn.addEventListener('click', function() {
            // Cerrar todos los menús abiertos
            document.querySelectorAll('.menu--siblings.menu-open').forEach(openMenu => {
                openMenu.classList.remove('menu-open');
                // Actualizar aria-expanded del botón correspondiente
                const prevTitle = openMenu.previousElementSibling;
                const prevButton = prevTitle?.querySelector('.btn-icon');
                if (prevButton) {
                    prevButton.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Actualizar visibilidad
            updateVisibility();
        });
    }
    
    // Toggle para filter-by
    const toggleFilterBy = document.querySelector('.toggle-filter-by');
    const filterByCloseBtn = document.querySelector('#filter-by .filter-by__close');
    const filterByElement = document.getElementById('filter-by');
    const filterByTabs = document.querySelectorAll('#filter-by .filter-by__tab');
    const filterByPanels = document.querySelectorAll('#filter-by .filter-by__panel');
    
    if (toggleFilterBy && filterByElement) {
        toggleFilterBy.addEventListener('click', function() {
            filterByElement.classList.toggle('filter-by--is-open');
            const isOpen = filterByElement.classList.contains('filter-by--is-open');
            toggleFilterBy.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    if (filterByTabs.length && filterByPanels.length) {
        const closeAllFilterByPanels = function() {
            filterByTabs.forEach(function(tab) {
                tab.classList.remove('is-active');
                tab.setAttribute('aria-selected', 'false');
            });

            filterByPanels.forEach(function(panel) {
                panel.classList.remove('is-active');
                panel.setAttribute('aria-hidden', 'true');
            });

            if (filterByElement) {
                filterByElement.classList.remove('filter-by--is-open');
            }

            if (toggleFilterBy) {
                toggleFilterBy.setAttribute('aria-expanded', 'false');
            }
        };

        filterByTabs.forEach(function(tab) {
            tab.classList.remove('is-active');
            tab.setAttribute('aria-selected', 'false');
        });

        filterByPanels.forEach(function(panel) {
            panel.classList.remove('is-active');
            panel.removeAttribute('hidden');
            panel.setAttribute('aria-hidden', 'true');
        });

        const activateFilterByTab = function(tab) {
            const panelId = tab.getAttribute('aria-controls');

            filterByTabs.forEach(function(item) {
                const isActive = item === tab;
                item.classList.toggle('is-active', isActive);
                item.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            filterByPanels.forEach(function(panel) {
                const isActive = panel.id === panelId;
                panel.classList.toggle('is-active', isActive);
                panel.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });
        };

        filterByTabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                activateFilterByTab(tab);
            });
        });

        if (filterByCloseBtn) {
            filterByCloseBtn.addEventListener('click', function() {
                closeAllFilterByPanels();
            });
        }
    }
});

// Añade clase cuando el anchor sticky está pegado al top.
document.addEventListener('DOMContentLoaded', function() {
    const anchorNav = document.getElementById('anchor-nav-soluciones');

    if (!anchorNav) {
        return;
    }

    const buttonsWrap = anchorNav.querySelector('.wp-block-buttons');

    if (buttonsWrap) {
        const iconInput = buttonsWrap.querySelector('.smn-anchor-nav-icon-url');
        const iconUrl = iconInput ? iconInput.value : '';
        const usedIds = new Set();
        const sectionCaptions = Array.from(document.querySelectorAll('.section-anchor')).filter((node) => !node.closest('#anchor-nav-soluciones'));

        buttonsWrap.innerHTML = '';

        const slugify = (text) => String(text || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');

        sectionCaptions.forEach((caption) => {
            const paragraph = caption.querySelector('p');

            if (!paragraph) {
                return;
            }

            const label = (paragraph.textContent || '').replace(/\s+/g, ' ').trim();

            if (!label) {
                return;
            }

            const id = slugify(label);

            if (!id || id === 'anchor-nav-soluciones' || usedIds.has(id)) {
                return;
            }

            usedIds.add(id);

            const button = document.createElement('div');
            button.className = 'wp-block-button is-style-fill';

            const link = document.createElement('a');
            link.className = 'wp-block-button__link has-foreground-inverted-background-color has-background wp-element-button';
            link.href = '#' + id;
            link.style.paddingTop = '0.5rem';
            link.style.paddingRight = 'var(--wp--preset--spacing--10)';
            link.style.paddingBottom = '0.5rem';
            link.style.paddingLeft = 'var(--wp--preset--spacing--10)';

            if (iconUrl) {
                const icon = document.createElement('img');
                icon.decoding = 'async';
                icon.width = 16;
                icon.height = 8;
                icon.style.width = '16px';
                icon.src = iconUrl;
                icon.alt = '';
                icon.setAttribute('aria-hidden', 'true');
                link.appendChild(icon);
            }

            link.appendChild(document.createTextNode(label));
            button.appendChild(link);
            buttonsWrap.appendChild(button);
        });
    }

    const anchorItems = Array.from(anchorNav.querySelectorAll('a[href^="#"]'))
        .map((link) => {
            const targetId = decodeURIComponent(link.getAttribute('href').slice(1));

            if (!targetId) {
                return null;
            }

            const section = document.getElementById(targetId);

            if (!section) {
                return null;
            }

            return {
                link,
                section,
                button: link.closest('.wp-block-button'),
            };
        })
        .filter(Boolean);

    function setCurrentAnchor(activeLink) {
        anchorItems.forEach((item) => {
            const isCurrent = item.link === activeLink;
            item.link.classList.toggle('current-anchor', isCurrent);

            if (item.button) {
                item.button.classList.toggle('current-anchor', isCurrent);
            }
        });
    }

    function updateAnchorStickyState() {
        const rect = anchorNav.getBoundingClientRect();
        const stickyTop = parseFloat(window.getComputedStyle(anchorNav).top) || 0;
        const isFixed = rect.top <= stickyTop + 0.5;

        anchorNav.classList.toggle('anchor-nav-soluciones--is-fixed', isFixed);
    }

    function updateCurrentAnchorByScroll() {
        if (!anchorItems.length) {
            return;
        }

        const stickyTop = parseFloat(window.getComputedStyle(anchorNav).top) || 0;
        const markerY = window.scrollY + stickyTop + anchorNav.offsetHeight + 16;
        let activeItem = null;
        let lastPassedItem = null;

        anchorItems.forEach((item) => {
            const sectionTop = item.section.offsetTop;
            const sectionBottom = sectionTop + Math.max(item.section.offsetHeight, 1);

            if (markerY >= sectionTop) {
                lastPassedItem = item;
            }

            if (markerY >= sectionTop && markerY < sectionBottom) {
                activeItem = item;
            }
        });

        const currentItem = activeItem || lastPassedItem;
        setCurrentAnchor(currentItem ? currentItem.link : null);
    }

    anchorItems.forEach((item) => {
        item.link.addEventListener('click', function() {
            setCurrentAnchor(item.link);
        });
    });

    updateAnchorStickyState();
    updateCurrentAnchorByScroll();
    window.addEventListener('scroll', updateAnchorStickyState, { passive: true });
    window.addEventListener('scroll', updateCurrentAnchorByScroll, { passive: true });
    window.addEventListener('resize', updateAnchorStickyState);
    window.addEventListener('resize', updateCurrentAnchorByScroll);
});



