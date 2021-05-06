<?php

 /**
 * Plugin Name: Checkout Page Modification
 * Plugin URI: http://woocommerce.com/products/woocommerce-extension/
 * Description: update checkout fields
 * Version: 1.0.0
 * Author: Pulkit Juneja
 * Author URI: http://yourdomain.com/
 * Developer: Pulkit Juneja
 * Developer URI: http://yourdomain.com/
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 *
 * Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
 * WC requires at least: 2.2
 * WC tested up to: 2.3
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */


add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
function custom_override_checkout_fields($fields)
 {
 $fields['billing']['billing_email']['label'] = 'Email';
 $fields['billing']['billing_phone']['label'] = 'Mobile ';
 return $fields;
 }

//  add_filter( 'woocommerce_product_page_heading', 'bbloomer_rename_description_tab_heading' );
 
// function bbloomer_rename_description_tab_heading() {
// return 'Product Features';
// }

// add_action( 'woocommerce_before_single_product', 'bbloomer_rename_description_tab_heading' );
 
// function bbloomer_rename_description_tab_heading() {
// echo '<h2>Product Page</h2>';
// }



 
if ( ! defined( 'WPINC' ) ) {
 
    die;
 
}
 
/*
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
    function ced_shipping_method() {
        if ( ! class_exists( 'Cedcoss_Shipping_Method' ) ) {
            class Cedcoss_Shipping_Method extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct() {
                    $this->id                 = 'cedcoss_shipping'; 
                    $this->method_title       = __( 'Cedcoss Shipping');  
                    $this->method_description = __( 'Custom Shipping Method from Cedcoss ' ); 
                    $this->availability = 'including';
                    $this->countries = array(  'US',  'CA',    'DE',   'GB',  'IT',  'ES',   'HR'  );
 
                    $this->init();
 
                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Cedcoss Shipping');
                }
 
                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // Load the settings API
                    $this->init_form_fields(); 
                    $this->init_settings(); 
 
                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }
 
                /**
                 * Define settings field for this shipping
                 * @return void 
                 */
                function init_form_fields() { 
 
                    $this->form_fields = array(
             
                     'enabled' => array(
                          'title' => __( 'Enable', 'tutsplus' ),
                          'type' => 'checkbox',
                          'description' => __( 'Enable this shipping.', 'tutsplus' ),
                          'default' => 'yes'
                          ),
             
                     'title' => array(
                        'title' => __( 'Title', 'Cedcoss' ),
                          'type' => 'text',
                          'description' => __( 'Title to be display on site', 'Cedcoss' ),
                          'default' => __( 'Cedcoss Shipping' )
                          ),
             
                     );
             
                }
 
                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */

                public function calculate_shipping( $package ) {
                    $weight = 0;
                    $cost = 0;
                    $country = $package["destination"]["country"];
                    foreach ( $package['contents'] as $item_id => $values ) 
                    { 
                        $_product = $values['data']; 
                        $weight = $weight + $_product->get_weight() * $values['quantity']; 
                    }
                  
                    $weight = wc_get_weight( $weight, 'kg' );
                  
                    if( $weight <= 10 ) {   $cost = 0;}
                    elseif( $weight <= 30 ) { $cost = 5;} 
                    elseif( $weight <= 50 ) { $cost = 10;} 
                    else { $cost = 20;}

                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => $cost
                    );

					// Register the rate
					$this->add_rate( $rate );
                     
                 }
                  
                 
            }
        }
    }
}
 
    add_action( 'woocommerce_shipping_init', 'ced_shipping_method' );
 
    function add_ced_shipping_method( $methods ) {
        $methods['cedcoss_shiping'] = 'Cedcoss_Shipping_Method';
        return $methods;
    }
    add_filter( 'woocommerce_shipping_methods', 'add_ced_shipping_method' );
 
  
?>




	