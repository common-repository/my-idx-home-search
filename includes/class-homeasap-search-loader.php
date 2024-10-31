<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://about.homeasap.com
 * @since      1.0.0
 *
 * @package    Homeasap_Search
 * @subpackage Homeasap_Search/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Homeasap_Search
 * @subpackage Homeasap_Search/includes
 * @author     HomeASAP <developers@homeasap.com>
 */
class Homeasap_Search_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * The name of shortcode to register with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $shortcode_name    The name of shortcode registered with WordPress to fire when the plugin loads.
	 */
	protected $shortcode_name;
	protected $shortcode_name2;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();
		$this->shortcode_name = 'homeasap-idx-search';
		$this->shortcode_name2 = 'homeasap-idx-landing';
	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	public static function execute_shortcode( $attrs, $enclosed_content = null ){

		$attrs = array_change_key_case( $attrs, CASE_LOWER );
		$id = $attrs["id"] ?? '';
		$class = $attrs["class"] ?? '';
		$agent = $attrs["agent"] ?? '';
		$mode = $attrs["mode"] ?? '';
		$placeholder = $attrs['placeholder'] ?? '';
		$css = $attrs['css'] ?? '';

		return "<input id=\"$id\" class=\"homeasap-search-input $class\" data-agentid=\"$agent\" data-mode=\"$mode\" placeholder=\"$placeholder\" style=\"$css\" />";
	}

	public static function execute_shortcode2( $attrs, $enclosed_content = null ){
		$frame_class = 'homeasap-alp-frame';

		$attrs = array_change_key_case( $attrs, CASE_LOWER );
		$id = $attrs["id"] ?? '';
		$class = $attrs["class"] ?? '';
		$agent = $attrs["agent"];
		$height = $attrs['height'] ?? '100vh';
		$css = $attrs['css'] ?? '';
		$test = $attrs['test'] ?? '';
		$styles_html = "<style>.$frame_class { width: 100%; height: $height; min-height: $height; $css }</style>";
		$iframe_html = "<iframe id=\"$id\" class=\"$frame_class $class\" src=\"https://${test}homeasap.com/$agent/?ob=1&target=self\" frameborder=\"0\"></iframe>";
		return $styles_html . $iframe_html;
	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		/* Add Shortcode */

		add_shortcode( $this->shortcode_name, array( __class__, 'execute_shortcode' ) );
		add_shortcode( $this->shortcode_name2, array( __class__, 'execute_shortcode2' ) );
	}

}
