<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package infinitia
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'infinitia' ); ?></a>

	<header id="masthead" class="site-header has-global-padding">
		<div class="masthead-container">
			
			<div class="main-navigation has-primary-90-background-color has-foreground-inverted-color">	
				<div class="site-branding">
					<?php the_custom_logo(); ?>
				</div><!-- .site-branding -->

				<button class="menu-toggle-mobile btn-icon has-primary-30-background-color" aria-controls="primary-menu" aria-label="Cerrar menú" aria-expanded="false">
					<?php echo file_get_contents(get_template_directory() . '/assets/icons/menu.svg'); ?>
				</button>
				
				<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary-menu',
							'menu_id'        => 'primary-menu',
							
						)
					);
				?>
			</div>
			
			<nav id="mega-menu" aria-label="<?php esc_attr_e( 'Primary Menu', 'zoilo-rios' ); ?>">
				<?php
					// Mostrar menú de empresas
					wp_nav_menu(
						array(
							'theme_location' => 'mega-menu',
							'menu_id'        => 'mega-menu-nav',
							'container_class' => 'menu-main-menu-container',
							'walker'         => new SMN_Walker_Mega_Menu_Groups(),
						)
					);	
				?>
			</nav><!-- #mega-menu -->
			
		</div> <!-- .masthead-container -->
	</header><!-- #masthead -->
	
	
	
