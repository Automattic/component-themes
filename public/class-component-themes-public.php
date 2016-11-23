<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://automattic.com
 * @since      1.0.0
 *
 * @package    Component_Themes
 * @subpackage Component_Themes/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Component_Themes
 * @subpackage Component_Themes/public
 * @author     Automattic <payton@a8c.com>
 */
class Component_Themes_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Component_Themes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Component_Themes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/component-themes-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Component_Themes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Component_Themes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/component-themes-public.js', array( 'jquery' ), $this->version, false );

	}

	public function render_page() {
		// TODO: include wp_head except for stylesheets
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title><?php wp_title(); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    </head>
		<body>
<?php
require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'server/ComponentThemes.php' );
$themeConfig = json_decode( file_get_contents( plugin_dir_url( dirname( __FILE__ ) ) . 'themes/kubrick/theme.json' ), true );
$pageConfig = [];
$pageSlug = 'home';
$content = json_decode( '{"myPosts":{"posts":[{"postId":1,"title":"My First Post","date":"February 22, 2013","author":"The Human","link":"http://localhost:3000","content":"This is my very first blog post."}]}}', true );

$renderer = new ComponentThemes();
$pageConfig = ( ! empty( $pageConfig ) ) ? $pageConfig : $renderer->getTemplateForSlug( $themeConfig, $pageSlug );
$rendered_output = $renderer->renderPage( $themeConfig, $pageConfig, $content );
echo $rendered_output;
?>
	</body>
</html>
<?php
		exit;
	}
}
