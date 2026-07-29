<?php
/**
 * Anchor nav de soluciones.
 */

$icon_url = esc_url( get_template_directory_uri() . '/assets/icons/caption-title-icon.svg' );
?>

<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
	<input type="hidden" class="smn-anchor-nav-icon-url" value="<?php echo $icon_url; ?>" />
</div>

<div class="btn-icon btn-icon--anchor-nav has-primary-90-background-color has-foreground-inverted-color">
    <a class="" href="#">
        <?php echo file_get_contents(get_template_directory() . '/assets/icons/arrow-up-2.svg'); ?>
        <span class="screen-reader-text"><?php esc_attr_e( 'Subir arriba', 'infinitia' ); ?></span>
    </a>
</div>



