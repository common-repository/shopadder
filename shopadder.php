<?php
/*
Plugin Name: ShopAdder
Plugin URI: http://www.shopadder.com
Description: Add a shop to your WP site, fully integrate e-commerce into your current design. No hassles. Professional options. Click SETTINGS in the left WP menu-bar and select ShopAdder to configure this plugin.
Version: 0.7.0
Author: ShopAdder
Author URI: http://www.shopadder.com
License:  This plugin is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or (at your option) any later version. ShopAdder is freemium ware. A version is 
available free of charge and a pro version with advanced extras is available for a small charge per year.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
*/

if(!class_exists('WP_ShopAdderclass'))
{
	class WP_ShopAdderclass
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
            
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));            
            
		} // END public function __construct
	    
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init() {
        	
        	// register your plugin's settings
        	register_setting('wp_shopadderclass-group', 'shopadder_shopid');
        	register_setting('wp_shopadderclass-group', 'shopadder_serverid');

        	// add your settings section
        	add_settings_section(
        	    'wp_shopadderclass-section', 
        	    'ShopAdder Settings', 
        	    array(&$this, 'settings_section_wp_shopadderclass'), 
        	    'wp_shopadderclass'
        	);
        	
        	// add your setting's fields - SHOPID
            add_settings_field(
                'wp_shopadderclass-setting_a', 
                'ShopAdder <strong>Shop ID</strong>', 
                array(&$this, 'settings_field_input_text'), 
                'wp_shopadderclass', 
                'wp_shopadderclass-section',
                array(
                    'field' => 'shopadder_shopid'
                )
            );
            // add your setting's fields - SERVERID
            add_settings_field(
                'wp_shopadderclass-setting_b', 
                'ShopAdder <strong>Server ID</strong>', 
                array(&$this, 'settings_field_input_text'), 
                'wp_shopadderclass', 
                'wp_shopadderclass-section',
                array(
                    'field' => 'shopadder_serverid'
                )
            );
            // Possibly do additional admin_init tasks
        } // END public static function activate
 	    
        /**
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'ShopAdder Settings', 
        	    'ShopAdder ', 
        	    'manage_options', 
        	    'wp_shopadderclass', 
        	    array(&$this, 'ShopAdderplugin_settings_page')
        	);
        } // END public function add_menu()
    
        /**
         * Menu Callback
         */		
        public function ShopAdderplugin_settings_page() {
			global $sa_productArray;
        	if(!current_user_can('manage_options')) {
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
						
        	// Render the settings template
        	//include(sprintf("%s/shopadder_rendersettings.php", dirname(__FILE__)));
        	
        	/* set default value to 'demoproduct' */
        	$testoption = get_option("shopadder_shopid");
        	if($testoption === "") {
				update_option("shopadder_shopid","6w6sxsfp");
				update_option("shopadder_serverid","1");
			}
        	
?>

        	<div class="wrap">
				<a href="http://www.shopadder.com" target="_blank"><img src="http://www.shopadder.com/images/shopadder_logo_213x40.png" alt="ShopAdder" style="float:right"></a>
				<script>
					try {
						var Tstyle = document.createElement('style');
						Tstyle.type = 'text/css';
						var csstmp = ".greenbar {background-color:#7DF17E;padding-top:10px;padding-bottom:10px;padding-left: 5px;}\n";
						if(typeof Tstyle.styleSheet == 'undefined') {
							Tstyle.innerHTML = csstmp;
						} else {
							Tstyle.styleSheet.cssText = csstmp;
						}
						document.getElementsByTagName('head')[0].appendChild(Tstyle);
					} catch (exception) { 
					}    
				</script>
				
				<form method="post" action="options.php"> 
					<?php @settings_fields('wp_shopadderclass-group'); ?>
					<?php @do_settings_fields('wp_shopadderclass-group'); ?>
					<?php do_settings_sections('wp_shopadderclass'); ?>
					
					<?php @submit_button(); ?>
					</ol>
					<h3 class="greenbar">STEP 3: What's next?</h3>
					<ol>
					<li><strong>Start adding your own products.</strong><br>
						Switch to ShopAdder and start adding products in the section 'Products'.<br>
						Switch back to WordPress and type one of the corresponding unique SKU within { },
						somewhere in your site or blog. <br>
						A list of the products in your actual shop can be found below.<br>
						<i>For example: type {shopaddertest} anywhere in your site to see a demo product.</i></li>
					<li><strong>Fine-tune your shop.</strong><br>
						Switch to ShopAdder and go to the section 'Settings' to adapt the shop to your specific wishes. 
					</ol>
					<hr>
					<a href="http://www.shopadder.com?do=login" target="_blank">ShopAdder login</a> <a href="http://www.shopadder.com?do=help" target="_blank">ShopAdder help</a> <a href="http://shops.cluster015.ovh.net/wpdemo2/wordpress/" target="_blank">ShopAdder example site</a>
					<hr>
					<b>Valid productID's:</b>
					<div style="height: 100px;overflow:automatic;">
						<?
							ShopAdder_get_products(get_option("shopadder_serverid"),get_option("shopadder_shopid"));
							if($sa_productArray){
								ShopAdder_list_validproductid();
							} else {
								echo "None (yet)....";
							}
						?>
					</div>
				</form>
			
			</div>

<?php
        } // END public function plugin_settings_page()
        
        
        /**
         * Settings intro
         */		        
		public function settings_section_wp_shopadderclass() {
            // Think of this as help text for the section.
            echo '<h3>Congratulations!</h3>';
            echo '<p style="font-size:105%">You just added an advanced e-commerce system to your WordPress site.<br></p>';
			echo '<h3 class="greenbar">STEP 1: Test it</h3>';
            echo '<p>To get a quick impression how it works: just type <strong>{shopaddertest}</strong> now, anywhere in a page or post and click preview.<br>';
            echo 'It is easy to adapt ShopAdder to your wishes, there is no need to change your WP config, PHP or CSS files!</p>';
            echo '<h3 class="greenbar">STEP 2: Create and connect your own free shop:</h3>';
            echo '<ol>';
            echo '<li><strong>Click to open <a href="http://www.shopadder.com/index.php?do=free" target="_blank">ShopAdder.com</a> in a new tab. </strong></li>';
            echo '<li><strong>Get your own Shop ID and Server ID and enter these values ​​in the corresponding fields below.</strong><br><i>Or leave/make empty to use the demo shop.</i></li>';
            //echo '</ol>';
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args) {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)
                
        	    
		/**
		 * Activate the plugin
		 */
		public static function activate() {
			// Do nothing
		} // END public static function activate
	
		/**
		 * Deactivate the plugin
		 */		
		public static function deactivate() {
			// Do nothing
		} // END public static function deactivate
	} // END class WP_ShopAdderclass
} // END if(!class_exists('WP_ShopAdderclass'))

if( !class_exists( 'WP_Http' ) ) {
	//src: http://planetozh.com/blog/2009/08/how-to-make-http-requests-with-wordpress/
    include_once( ABSPATH . WPINC. '/class-http.php' );
}

if(class_exists('WP_ShopAdderclass')) {
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('WP_ShopAdderclass', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_ShopAdderclass', 'deactivate'));

	// instantiate the plugin class
	$ShopAdder = new WP_ShopAdderclass();
	$sa_productArray = ARRAY();

	function ShopAdder_get_products($serverid,$shopid){
		global $sa_productArray;
		$prdtel = -1;
		
		if(count($sa_productArray)==0){
			$url = 'https://ssl' . $serverid . '.shops.io/d/shpa_' . $shopid . '000.js';
			$response = wp_remote_get( $url );
			if( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				echo "Something went wrong: $error_message";
			} else {
				$sa_lines = explode("\n", $response[body]); 
				foreach ($sa_lines as $tmp) {
					if ((stripos($tmp, "prd[") === false) && (stripos($tmp, "var qc =") === false) && (stripos($tmp, "var prd =") === false)) {
					} else if (stripos($tmp, "prd[") !== false) {
						$tmp2 = explode(",", $tmp);
						if(trim($tmp2[2])!="''"){
							$sa_productArray[$prdtel] = trim($tmp2[1],"'");$prdtel++;
						}
					}
				}
			}
		}
	}
	function ShopAdder_check_validproductid($prdid){
		global $sa_productArray;
		for ($z = 0; $z < count($sa_productArray); $z++) {
			if(strtoupper($sa_productArray[$z]) == strtoupper($prdid)){
				return true;
			} else {
			}
		}
		return false;
	}
	function ShopAdder_list_validproductid(){
		global $sa_productArray;
		for ($z = 0; $z < count($sa_productArray); $z++) {
			
			if(strtoupper($sa_productArray[$z]) !== ""){
				echo "{" . $sa_productArray[$z] . "} ";
			}
		}
	}
	
	$shopid = get_option("shopadder_shopid");
	if(strlen($shopid)<1) { $shopid = "6w6sxsfp"; }
	$serverid = get_option("shopadder_serverid");
	if(intval($serverid)<1) { $serverid = "1"; }
	
	if($shopid && $serverid){
		
		
		function ShopAdder_buy_buttons($text){
			global $shopid, $serverid, $sa_productArray;
			
			ShopAdder_get_products($serverid,$shopid);
						
			preg_match_all('^\{.*?\}^', $text, $matches, PREG_SET_ORDER);
			foreach($matches as $match) {
				$prdid = str_replace(array("{","}"),"",$match[0]);
				if(ShopAdder_check_validproductid($prdid)){
					$prd_button =  '<a href="https://ssl' . $serverid . '.shops.io/x/buy_1.php?shpaid=' . $shopid . '&prdid=' . $prdid .'" id="'. $shopid . $prdid .'" class="buybtn" target="popup">Buy</a><script type="text/javascript">// <!\n';
					$prd_button .= "[CDATA[\n";
					$prd_button .= "if (typeof(shpaid) == 'undefined'){var shpaid = '" . $shopid . "';document.getElementsByTagName('head')[0].appendChild(document.createElement('script')).src='https://ssl" . $serverid . ".shops.io/s/shopldr.js';}\n";
					$prd_button .= '// ]]></script>';
					$prd_button .= '<!-- ' . date('l jS \of F Y h:i:s A') . '-->';							
					$text = str_replace($match[0], $prd_button, $text);
				} else {		//leave as is
					
				}
			}	
			return $text . $info;
		}

		add_filter('the_content', 'ShopAdder_buy_buttons');
		add_filter('the_title', 'ShopAdder_buy_buttons');
	
	} else {
		
		// find something to alert user
		
	}
		
    // Add a link to the settings page onto the plugin page
    
    if(isset($ShopAdder)) {
        // reserved
    }
    
}