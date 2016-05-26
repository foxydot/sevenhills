<?php
//* ---------------------------------------------------------------------- */
/* Check the current post for the existence of a short code
/* ---------------------------------------------------------------------- */

if ( !function_exists('msdlab_has_shortcode') ) {

    function msdlab_has_shortcode($shortcode = '') {
    
        global $post;
        $post_obj = get_post( $post->ID );
        $found = false;
        
        if ( !$shortcode )
            return $found;
        if ( stripos( $post_obj->post_content, '[' . $shortcode ) !== false )
            $found = true;
        
        // return our results
        return $found;
    
    }
}

/* ---------------------------------------------------------------------- */
/* Filter out annoying empty p tags generated by wpautop
/* ---------------------------------------------------------------------- */
add_filter( 'the_content', 'msdlab_remove_empty_p', 20);
if ( !function_exists('msdlab_remove_empty_p') ) {
    function msdlab_remove_empty_p( $content ){
        // clean up p tags around block elements
        $content = preg_replace( array(
            '#<p(.*?)>\s*<(div|aside|section|article|header|footer)#',
            '#</(div|aside|section|article|header|footer)>\s*</p>#',
            '#</(div|aside|section|article|header|footer)>\s*<br ?/?>#',
            '#<(div|aside|section|article|header|footer)(.*?)>\s*</p>#',
            '#<p(.*?)>\s*</(div|aside|section|article|header|footer)#',
        ), array(
            '<$2',
            '</$1>',
            '</$1>',
            '<$1$2>',
            '</$2',
        ), $content );
        return preg_replace('#<p(.*?)>(\s|&nbsp;)*+(<br\s*/*>)*(\s|&nbsp;)*</p>#i', '', $content);
    }
}
/*
* A useful troubleshooting function. Displays arrays in an easy to follow format in a textarea.
*/
if ( ! function_exists( 'ts_data' ) ) :
function ts_data($data){
    $ret = '<textarea class="troubleshoot" cols="100" rows="20">';
    $ret .= print_r($data,true);
    $ret .= '</textarea>';
    print $ret;
}
endif;
/*
* A useful troubleshooting function. Dumps variable info in an easy to follow format in a textarea.
*/
if ( ! function_exists( 'ts_var' ) && function_exists( 'ts_data' ) ) :
function ts_var($var){
    ts_data(var_export( $var , true ));
}
endif;