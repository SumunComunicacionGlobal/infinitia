<?php
/**
 * The template for displaying all landings posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-landing
 *
 * @package infinitia
 */
?>

<?php $landing_google = get_field( 'landing_google' ); ?>


<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<!-- Google Tag Manager -->
    <?php if ( $landing_google ) : ?>
    <script>
		(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-TBW4D9C');
		</script>
    <!-- End Google Tag Manager -->
    <?php endif; ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class('landing-page'); ?>>

<?php wp_body_open(); ?>

<!-- Google Tag Manager (noscript) -->
 <?php if ( $landing_google ) : ?>
	<noscript>
		<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TBW4D9C"
		height="0" width="0" style="display:none;visibility:hidden"></iframe>
	</noscript>
<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>

<div id="page" class="site">

    <header class="has-primary-80-background-color has-global-padding">
        <div class="site-branding" style="padding: 1.5rem 0 1.5rem 0">
            <img width="127" height="61" src="https://www.infinitiaresearch.com/wp-content/uploads/logo-infinitia.svg" class="custom-logo" alt="INFINITIA Industrial Consulting" decoding="async" fetchpriority="high" loading="false">
        </div><!-- .site-branding -->
    </header>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

	<footer id="colophon" class="site-footer">
        <?php smn_render_reusable_block_by_title( 'Footer Landing' ); ?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>