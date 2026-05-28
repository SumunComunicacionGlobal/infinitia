// Añade clase a body cuando se hace scroll
window.addEventListener("scroll", function() {
    if (window.scrollY > 180) {
        document.body.classList.add("scrolled");
    } else {
        document.body.classList.remove("scrolled");
    }
});

// Añade botones de scroll a la izquierda y derecha
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".is-style-group-horizontal-scroll-btns").forEach((content) => {
        if (content.children.length > 1) {
            const rightBtn = document.createElement("button");
            rightBtn.classList.add("scrolling-button", "scrolling-button--right");
            rightBtn.innerHTML = "→";
            rightBtn.disabled = false;

            const leftBtn = document.createElement("button");
            leftBtn.classList.add("scrolling-button", "scrolling-button--left");
            leftBtn.innerHTML = "←";
            leftBtn.disabled = true;

            const buttonContainer = document.createElement("div");
            buttonContainer.classList.add("scrolling-button-container");
            buttonContainer.appendChild(leftBtn);
            buttonContainer.appendChild(rightBtn);
            // Agregar el contenedor de botones después del contenido
            content.parentNode.insertBefore(buttonContainer, content.nextSibling);
            // Agregar el contenedor de botones antes del contenido
            //content.parentNode.insertBefore(buttonContainer, content);

            // Desplazamiento fijo para móvil y desktop
            function getScrollStep() {
                return window.innerWidth < 768 ? 400 : 288;
            }

            rightBtn.addEventListener("click", () => {
                const scrollContent = content;
                const scrollStep = getScrollStep();
                scrollContent.scrollLeft += scrollStep;
                leftBtn.disabled = false;

                if (scrollContent.scrollWidth - scrollContent.scrollLeft - scrollContent.clientWidth <= 0) {
                    rightBtn.disabled = true;
                }
            });

            leftBtn.addEventListener("click", () => {
                const scrollContent = content;
                const scrollStep = getScrollStep();
                scrollContent.scrollLeft -= scrollStep;
                rightBtn.disabled = false;

                if (scrollContent.scrollLeft <= 0) {
                    leftBtn.disabled = true;
                }
            });
        }
    });
});

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

document.addEventListener('DOMContentLoaded', setRevealTextLength);


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
    const filterByElement = document.getElementById('filter-by');
    
    if (toggleFilterBy && filterByElement) {
        toggleFilterBy.addEventListener('click', function() {
            filterByElement.classList.toggle('filter-by--is-open');
        });
    }
});

