<?php
/* Subtitle Support */
if(!class_exists('WPAlchemy_MetaBox')){
	include_once WP_CONTENT_DIR.'/wpalchemy/MetaBox.php';
}
add_action('init','add_custom_metaboxes');
add_action('admin_footer','subtitle_footer_hook');
add_action( 'admin_print_scripts', 'my_metabox_styles' );
//add_action( 'genesis_entry_header', 'msdlab_do_post_subtitle' );


function add_custom_metaboxes(){
	global $subtitle_metabox,$footer_metabox;
    $subtitle_metabox = new WPAlchemy_MetaBox(array
    (
        'id' => '_subtitle',
        'title' => 'Subtitle & Intro Text',
        'types' => array('page'),
        'context' => 'normal', // same as above, defaults to "normal"
        'priority' => 'high', // same as above, defaults to "high"
        'template' => get_stylesheet_directory() . '/lib/template/subtitle-meta.php',
        'autosave' => TRUE,
        'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
        'prefix' => '_msdlab_' // defaults to NULL
    ));
    $footer_metabox = new WPAlchemy_MetaBox(array
    (
        'id' => '_footer',
        'title' => 'Footer Text',
        'types' => array('page'),
        'context' => 'normal', // same as above, defaults to "normal"
        'priority' => 'high', // same as above, defaults to "high"
        'template' => get_stylesheet_directory() . '/lib/template/footer-meta.php',
        'autosave' => TRUE,
        'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
        'prefix' => '_msdlab_' // defaults to NULL
    ));
}

function subtitle_footer_hook()
{
	?><script type="text/javascript">
        jQuery('#titlediv').after(jQuery('#_subtitle_metabox'));
        jQuery('#postdivrich').after(jQuery('#_footer_metabox'));
	</script><?php
}

// include css to help style our custom meta boxes
 
function my_metabox_styles()
{
    if ( is_admin() )
    {
        wp_enqueue_style('wpalchemy-metabox', get_stylesheet_directory_uri() . '/lib/template/meta.css');
    }
}

function msdlab_do_post_subtitle() {
    global $subtitle_metabox;
    $subtitle_metabox->the_meta();
    $subtitle = $subtitle_metabox->get_the_value('subtitle');

    if ( strlen( $subtitle ) == 0 )
        return;

    $subtitle = sprintf( '<h2 class="entry-subtitle">%s</h2>', apply_filters( 'genesis_post_title_text', $subtitle ) );
    echo apply_filters( 'genesis_post_title_output', $subtitle ) . "\n";

}

function msdlab_do_post_intro() {
    global $subtitle_metabox;
    $subtitle_metabox->the_meta();
    $intro = $subtitle_metabox->get_the_value('intro');

    if ( strlen( $intro ) == 0 )
        return;

    $intro = sprintf( '<div class="intro-text">%s</div>', apply_filters( 'the_content', $intro ) );
    echo  $intro . "\n";

}

function msdlab_do_post_footer() {
    global $footer_metabox;
    $footer_metabox->the_meta();
    $footer = $footer_metabox->get_the_value('footer');

    if ( strlen( $footer ) == 0 )
        return;

    $footer = sprintf( '<div class="footer-text">%s</div>', apply_filters( 'the_content', $footer ) );
    echo  $footer . "\n";

}