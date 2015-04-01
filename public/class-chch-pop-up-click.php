<?php
/**
 * Pop-Up CC - Click
 *
 * @package   CcPopUpClick
 * @author    Chop-Chop.org <shop@chop-chop.org>
 * @license   GPL-2.0+
 * @link      https://shop.chop-chop.org
 * @copyright 2014 
 */

if ( ! class_exists( 'ChChPCFTemplate' ) )
    require_once( CHCH_PUCF_PLUGIN_DIR . 'public/includes/chch-pucf-template.php' );
	
/**
 * @package CcPopUpClick
 * @author  Chop-Chop.org <shop@chop-chop.org>
 */
class ChChPopUpClick {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/** 
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'chch-pucf';
	
	/** 
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	private $pop_ups = array();

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		
		// Get all active Pop-Ups
		$this->pop_ups = $this->chch_pucf_get_pop_ups(); 
		
		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'chch_pucf_activate_new_site' ) ); 
  		
		// Include public fancing styles and scripts
		add_action( 'wp_enqueue_scripts', array($this,'chch_pucf_template_scripts') ); 
		
		// Display active Pop-Ups on front-end
		add_action('wp_footer', array( $this, 'chch_pucf_show_popup' )); 
		 
	}
	
	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}


	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();

					restore_current_blog();
				}

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

					restore_current_blog();

				}

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    0.1.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		
	} 
	
	
	/**
	 * Get All Active Pop-Ups IDs
	 *
	 * @since  1.0.0
	 *
	 * @return   array - Active Pop-Ups ids
	 */
	private function chch_pucf_get_pop_ups() {
		$list = array();
		
		$args = array(
			'post_type' => 'chch-pucf',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key'     => '_chch_pucf_status',
					'value'   => 'yes', 
				),
			),
		);
		
		$pop_ups = get_posts( $args);
		
		if ( $pop_ups ) {
			foreach ( $pop_ups as $pop_up ) {
				$list[] = $pop_up->ID;
			}
		} 	 
		return $list;
	}
	
	
	
	/**
	 * Include Templates scripts on Front-End
	 *
	 * @since  1.0.0
	 *
	 * @return   array - Pop-Ups ids
	 */
	function chch_pucf_template_scripts() { 
		
		$pop_ups = $this->pop_ups;
		
		if(!empty($pop_ups)) {
			foreach($pop_ups as $id){
				
				$template_id = get_post_meta( $id, '_chch_pucf_template', true);
				$template_base = get_post_meta( $id, '_chch_pucf_template_base', true);
				
				$template = new ChChPCFTemplate($template_id,$template_base,$id);
				$template->enqueue_template_style();    
			}
		}
			
	} 
	
	 
	/**
	 * Display Pop-Up on Front-End
	 *
	 * @since  1.0.0
	 */
	 public function chch_pucf_show_popup() {
		  
		$pop_ups = $this->pop_ups;
		 
		if(!empty($pop_ups))
		{
			foreach($pop_ups as $id)
			{ 
				
				$user_role = get_post_meta( $id, '_chch_pucf_role', true);
				$user_login = is_user_logged_in();
				
				if($user_role == 'logged' && !$user_login) {
					continue;	
				}
				
				if($user_role == 'unlogged' && $user_login) {
					continue;	
				}
				
				$pages = get_post_meta( $id, '_chch_pucf_page', true);
				if(is_array( $pages)){
					if(is_home()) {
						if(in_array('chch_home', $pages)) {
							continue; 	
						} else {
							$array_key = array_search(get_the_ID(), $pages);
							if($array_key){
								unset($pages[$array_key]);	
							}
						} 	
					}
					if(in_array(get_the_ID(), $pages)){
						continue;		
					}
				}
				
				
				$template_id = get_post_meta( $id, '_chch_pucf_template', true);
				$template_base = get_post_meta( $id, '_chch_pucf_template_base', true);
				
				
				echo '<div style="display:none;" id="modal-'.$id.'" class="free-click '.$template_id.' '.$template_base.'">'; 
				  
				$template = new ChChPCFTemplate($template_id,$template_base,$id);
				$template->build_css();
				$template->get_template();
				$template->build_js();
				
				echo '</div>';   
			}
		}
	} 
	
 }
