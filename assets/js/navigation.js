/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	const siteNavigation = document.getElementById( 'mega-menu' );

	// Return early if the navigation doesn't exist.
	if ( ! siteNavigation ) {
		return;
	}

	// Create overlay element
	const overlay = document.createElement( 'div' );
	overlay.className = 'menu-overlay';
	overlay.style.cssText = `
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.7);
		backdrop-filter: blur(4px);
		z-index: 98;
		opacity: 0;
		visibility: hidden;
		transition: opacity 0.8s ease, visibility 0.8s ease;
	`;
	document.body.appendChild( overlay );

	const button = document.querySelector( '.menu-toggle' );
	const mobileButton = document.querySelector( '.menu-toggle-mobile' );
	const primaryMenu = document.getElementById( 'primary-menu' );

	// Return early if the button doesn't exist.
	if ( ! button ) {
		return;
	}

	const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	if ( ! menu.classList.contains( 'nav-menu' ) ) {
		menu.classList.add( 'nav-menu' );
	}

	function isMegaMenuOpen() {
		return siteNavigation.classList.contains( 'mega-menu--open' );
	}

	function isMobileMenuOpen() {
		return !! ( primaryMenu && primaryMenu.classList.contains( 'primary-menu--open' ) );
	}

	function updateOverlayState() {
		const shouldShowOverlay = isMegaMenuOpen() || isMobileMenuOpen();

		overlay.style.opacity = shouldShowOverlay ? '1' : '0';
		overlay.style.visibility = shouldShowOverlay ? 'visible' : 'hidden';
		document.body.style.overflow = shouldShowOverlay ? 'hidden' : '';
	}

	function closeMobileMenu() {
		if ( ! primaryMenu || ! mobileButton ) {
			return;
		}

		primaryMenu.classList.remove( 'primary-menu--open' );
		mobileButton.setAttribute( 'aria-expanded', 'false' );
	}

	if ( mobileButton && primaryMenu ) {
		mobileButton.setAttribute( 'aria-expanded', 'false' );

		mobileButton.addEventListener( 'click', function() {
			const isOpen = primaryMenu.classList.toggle( 'primary-menu--open' );
			mobileButton.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			updateOverlayState();
		} );
	}

	// Toggle the .mega-menu--open class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function() {
		const isOpen = siteNavigation.classList.contains( 'mega-menu--open' );
		
		if ( isOpen ) {
			closeMenu();
		} else {
			openMenu();
		}
	} );

	// Remove the .mega-menu--open class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener( 'click', function( event ) {
		const isClickInside = siteNavigation.contains( event.target ) || button.contains( event.target );

		if ( ! isClickInside && siteNavigation.classList.contains( 'mega-menu--open' ) ) {
			closeMenu();
		}
	} );

	// Close menu when clicking on overlay
	overlay.addEventListener( 'click', function() {
		closeMenu();
		closeMobileMenu();
		updateOverlayState();
	} );

	// Add close button functionality
	const closeButton = document.querySelector( '.menu-toggle-close' );
	if ( closeButton ) {
		closeButton.addEventListener( 'click', function() {
			closeMenu();
		} );
	}

	/**
	 * Open menu with overlay and disable body scroll
	 */
	function openMenu() {
		siteNavigation.classList.add( 'mega-menu--open' );
		button.setAttribute( 'aria-expanded', 'true' );
		updateOverlayState();
	}

	/**
	 * Close menu, hide overlay and restore body scroll
	 */
	function closeMenu() {
		siteNavigation.classList.remove( 'mega-menu--open' );
		button.setAttribute( 'aria-expanded', 'false' );
		updateOverlayState();
		closeAllDropdowns();
	}

	// Get all the link elements within the menu.
	const links = menu.getElementsByTagName( 'a' );

	// Get all the link elements with children within the menu.
	const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

	// Toggle focus each time a menu link is focused or blurred.
	for ( const link of links ) {
		link.addEventListener( 'focus', toggleFocus, true );
		link.addEventListener( 'blur', toggleFocus, true );
	}

	// Handle dropdown submenus
	const menuItemsWithChildren = menu.querySelectorAll( '.menu-item-has-children' );

	function closeDropdownBranch( menuItem ) {
		if ( ! menuItem ) {
			return;
		}

		const nestedOpenDropdowns = menuItem.querySelectorAll( '.menu-item-has-children.dropdown-open' );
		for ( const nestedDropdown of nestedOpenDropdowns ) {
			nestedDropdown.classList.remove( 'dropdown-open' );
			const nestedButton = nestedDropdown.querySelector( '.dropdown-toggle' );
			if ( nestedButton ) {
				nestedButton.setAttribute( 'aria-expanded', 'false' );
			}
		}

		menuItem.classList.remove( 'dropdown-open' );
		const menuItemButton = menuItem.querySelector( '.dropdown-toggle' );
		if ( menuItemButton ) {
			menuItemButton.setAttribute( 'aria-expanded', 'false' );
		}
	}

	function closeSiblingDropdowns( currentMenuItem ) {
		const parentList = currentMenuItem.parentElement;
		if ( ! parentList ) {
			return;
		}

		for ( const sibling of parentList.children ) {
			if ( sibling === currentMenuItem || ! sibling.classList || ! sibling.classList.contains( 'menu-item-has-children' ) ) {
				continue;
			}

			if ( sibling.classList.contains( 'dropdown-open' ) || sibling.querySelector( '.menu-item-has-children.dropdown-open' ) ) {
				closeDropdownBranch( sibling );
			}
		}
	}

	// Add dropdown buttons to menu items with children
	for ( const menuItem of menuItemsWithChildren ) {
		const link = menuItem.querySelector( 'a' );
		const subMenu = menuItem.querySelector( '.sub-menu' );
		
		if ( link && subMenu ) {
			// Create dropdown button
			const dropdownButton = document.createElement( 'button' );
			dropdownButton.className = 'dropdown-toggle btn-icon';
			dropdownButton.setAttribute( 'aria-expanded', 'false' );
			dropdownButton.setAttribute( 'aria-label', 'Expandir submenú' );
			dropdownButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M6 9L12 15L18 9" stroke="#E0452B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
			
			// Insert button after the link
			link.insertAdjacentElement( 'afterend', dropdownButton );
			
			// Handle dropdown toggle (both click and touch)
			function toggleDropdown( event ) {
				event.preventDefault();
				event.stopPropagation();
				
				const isExpanded = dropdownButton.getAttribute( 'aria-expanded' ) === 'true';
				
				// Toggle current dropdown
				if ( isExpanded ) {
					closeDropdownBranch( menuItem );
				} else {
					closeSiblingDropdowns( menuItem );
					menuItem.classList.add( 'dropdown-open' );
					dropdownButton.setAttribute( 'aria-expanded', 'true' );
				}
			}
			
			dropdownButton.addEventListener( 'click', toggleDropdown );
			dropdownButton.addEventListener( 'touchstart', toggleDropdown );
		}
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		if ( event.type === 'focus' || event.type === 'blur' ) {
			let self = this;
			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( ! self.classList.contains( 'nav-menu' ) ) {
				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					self.classList.toggle( 'focus' );
				}
				self = self.parentNode;
			}
		}
	}

	/**
	 * Close all open dropdowns
	 */
	function closeAllDropdowns() {
		const openDropdowns = menu.querySelectorAll( '.menu-item-has-children.dropdown-open' );
		for ( const dropdown of openDropdowns ) {
			dropdown.classList.remove( 'dropdown-open' );
			const dropdownButton = dropdown.querySelector( '.dropdown-toggle' );
			if ( dropdownButton ) {
				dropdownButton.setAttribute( 'aria-expanded', 'false' );
			}
		}
	}
}() );
