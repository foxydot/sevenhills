<?php
/**********************************
*
* Integrate WooCommerce with Genesis.
*
* Unhook WooCommerce wrappers and
* Replace with Genesis wrappers.
*
* Reference Genesis file:
* genesis/lib/framework.php
*
* @author AlphaBlossom / Tony Eppright
* @link http://www.alphablossom.com
*
**********************************/

//* Declare WooCommerce Support
add_theme_support( 'woocommerce' );

// Add WooCommerce support for Genesis layouts (sidebar, full-width, etc) - Thank you Kelly Murray/David Wang
add_post_type_support( 'product', array( 'genesis-layouts', 'genesis-seo' ) );

// Unhook WooCommerce Sidebar - use Genesis Sidebars instead
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Unhook WooCommerce wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Hook new functions with Genesis wrappers
add_action( 'woocommerce_before_main_content', 'msdlab_my_theme_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'msdlab_my_theme_wrapper_end', 10 );

// Add opening wrapper before WooCommerce loop
function msdlab_my_theme_wrapper_start() {

    do_action( 'genesis_before_content_sidebar_wrap' );
    genesis_markup( array(
        'html5' => '<div %s>',
        'xhtml' => '<div id="content-sidebar-wrap">',
        'context' => 'content-sidebar-wrap',
    ) );
    
    do_action( 'genesis_before_content' );
    genesis_markup( array(
        'html5' => '<main %s>',
        'xhtml' => '<div id="content" class="hfeed">',
        'context' => 'content',
    ) );
    do_action( 'genesis_before_loop' );
    
}
    
/* Add closing wrapper after WooCommerce loop */
function msdlab_my_theme_wrapper_end() {
    
    do_action( 'genesis_after_loop' );
    genesis_markup( array(
        'html5' => '</main>', //* end .content
        'xhtml' => '</div>', //* end #content
    ) );
    do_action( 'genesis_after_content' );
    
    echo '</div>'; //* end .content-sidebar-wrap or #content-sidebar-wrap
    do_action( 'genesis_after_content_sidebar_wrap' );

}
if (  ! function_exists( 'woocommerce_template_loop_category_title' ) ) {

    /**
     * Show the subcategory title in the product loop.
     */
    function woocommerce_template_loop_category_title( $category ) {
        ?>
        <h3>
            <?php
                echo $category->name;
            ?>
        </h3>
        <?php
    }
}
    
remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
add_action('woocommerce_single_product_summary','the_content',20);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_meta',40);

/**
 * Remove existing tabs from single product pages.
 */
function remove_woocommerce_product_tabs( $tabs ) {
    unset( $tabs['description'] );
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'remove_woocommerce_product_tabs', 98 );

//add_filter( 'woocommerce_product_add_to_cart_text' , 'custom_woocommerce_product_add_to_cart_text' );
function custom_woocommerce_product_add_to_cart_text($var) {
    global $product;    
    $cat_ids = $product->category_ids;
    $product_type = $product->product_type;  
    switch ( $product_type ) {
    case 'variable':
                if(msdlab_is_coffee($cat_ids)){
                return __( 'Choose a Grind Option', 'woocommerce' );
                } else {
                    return $var;
                }
            break;
    default:
        return $var;
        break;
    }
} 

function msdlab_is_coffee($cat_ids){
    if(!is_array($cat_ids)){
        $id = $cat_ids;
        $cat_ids = array();
        $cat_ids[] = $id;
    }
    for($i=9;$i<=13;$i++){
        if(in_array($i, $cat_ids)){
            return true;
        }
    }
    return false;
}

add_filter('woocommerce_dropdown_variation_attribute_options_html','msdlab_woocommerce_dropdown_variation_attribute_options_html',10,2);
function msdlab_woocommerce_dropdown_variation_attribute_options_html($html,$args){
    global $product;
    $cat_ids = $product->category_ids;
    if($args['attribute'] == 'pa_grind'){
        $html = str_replace('Choose an option', 'Choose a Grind Option', $html);
    }
    return $html;
}

// Change number or products per row to 3
add_filter('loop_shop_columns', 'msdlab_loop_columns');
if (!function_exists('msdlab_loop_columns')) {
    function msdlab_loop_columns() {
        return 3; // 3 products per row
    }
}

add_filter('woocommerce_package_rates', 'wf_hide_shipping_method_based_on_shipping_class', 10, 2);
function wf_hide_shipping_method_based_on_shipping_class($available_shipping_methods, $package)
{
    $hide_when_shipping_class_exist = array(
        39 => array(
            'fedex:SMART_POST'
        )
    );

    $hide_when_shipping_class_not_exist = array(
    );

    //error_log(json_encode($available_shipping_methods));
    $shipping_class_in_cart = array();
    foreach(WC()->cart->get_cart_contents() as $key => $values) {
        $shipping_class_in_cart[] = $values['data']->get_shipping_class_id();
    }

    foreach($hide_when_shipping_class_exist as $class_id => $methods) {
        if(in_array($class_id, $shipping_class_in_cart)){
            foreach($methods as & $current_method) {
                unset($available_shipping_methods[$current_method]);
            }
        }
    }
    foreach($hide_when_shipping_class_not_exist as $class_id => $methods) {
        if(!in_array($class_id, $shipping_class_in_cart)){
            foreach($methods as & $current_method) {
                unset($available_shipping_methods[$current_method]);
            }
        }
    }
    return $available_shipping_methods;
}

//add_filter('woocommerce_shortcode_products_query','msdlab_woocommerce_shortcode_products_query',10,3);

function msdlab_woocommerce_shortcode_products_query($query,$attributes,$type){
    ts_data($attributes);
    ts_data($type);
}