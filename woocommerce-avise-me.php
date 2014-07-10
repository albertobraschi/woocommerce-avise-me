<?php
/*
Plugin Name: WooCommerce Avise-me
Plugin URI: http://www.moskatus.com.br/
Description: WooCommerce Avise-me
Author: Moskatus
Author URI: http://www.moskatus.com.br
Version: 1.0.0

*/

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	if ( ! class_exists( 'WC_Avisa' ) ) {
		
		/**
		 * Localisation
		 **/
		load_plugin_textdomain( 'wc_avisa', false, dirname( plugin_basename( __FILE__ ) ) . '/' );

		class WC_Avisa {
			public function __construct() {
				// called only after woocommerce has finished loading
				add_action( 'woocommerce_init', array( &$this, 'woocommerce_loaded' ) );
				
				// called after all plugins have loaded
				add_action( 'plugins_loaded', array( &$this, 'plugins_loaded' ) );
				
				
                                // Load public-facing style sheet and JavaScript.
                                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
                                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
 
				// indicates we are running the admin
				if ( is_admin() ) {
					// ...
				}
				
				
    
				// take care of anything else that needs to be done immediately upon plugin instantiation, here in the constructor
			}
			
			/**
			 * Take care of anything that needs woocommerce to be loaded.  
			 * For instance, if you need access to the $woocommerce global
			 */
			public function woocommerce_loaded() {
                          
                            add_filter( 'woocommerce_get_availability', 'custom_get_availability', 1, 2);
                            
                            function custom_get_availability( $availability, $_product ) {
global $woocommerce, $product;
$stock = $product->get_total_stock();

//Don't do anything if managing stock is not checked
if(!$_product->managing_stock()) return $availability;

//Change default 'In Stock' and 'Out of Stock' text
if ( !$_product->is_in_stock () ) {
    
    do_action( 'woocommerce_before_add_to_cart_form' );
    ?>       
    <form action="<?php echo plugins_url('/woocommerce-avise-me/avise-me.php'); ?>" method="POST" id="avisa">
<?php if($availability['availability'] = __('Sem Estoque', 'woocommerce'));

?>
        <!-- EMAIL -->
		<div id="email-group" class="form-group">
			<label for="email">Email</label>
			<input type="text" class="form-control" name="email" placeholder="Digite seu e-mail">
			<!-- errors will go here -->
		</div>
                
                <input type="hidden" value="<?php echo get_the_ID(); ?>" name="id" id="id">

                <button type="submit" class="btn btn-success">Submit <span class="fa fa-arrow-right"></span></button>
              
    
    
    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
    </form>
	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>


                <?php

return $availability;

}
                            }
                        }
                      
      
			/**
			 * Take care of anything that needs all plugins to be loaded
			 */
			public function plugins_loaded() {

                            }
			
			
                        public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-avise', plugins_url( 'assets/css/avise-me.css', __FILE__ ), array() );
                wp_enqueue_style( $this->plugin_slug . '-avise2', '//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css', array() );

                }

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-avise', plugins_url( 'assets/js/avise-me.js', __FILE__ ), array( 'jquery' ) );
                        
        
        }       

		}

		// finally instantiate our plugin class and add it to the set of globals
		$GLOBALS['wc_avisa'] = new WC_Avisa();
	}
}
