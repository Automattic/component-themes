<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Component_Themes
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
	 * @SuppressWarnings(PHPMD.ExitExpression)
	 */
	public function render_page() {
		global $wp_query;
		// TODO: include wp_head except for stylesheets
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
		<head>
				<meta charset="<?php bloginfo( 'charset' ); ?>" />
				<title><?php wp_title(); ?></title>
				<link rel="profile" href="http://gmpg.org/xfn/11" />
				<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
				<style> body { padding: 0; margin: 0; font-family: Sans-Serif; } </style>
		</head>
		<body>
			<div id="root">
<?php
require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'server/class-component-themes.php' );
require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'server/core-components.php' );
$theme_config = json_decode( file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'themes/kubrick/theme.json' ), true );
$page_config = null;
$page_slug = ( is_home() || is_front_page() ) ? 'home' : get_post_field( 'post_name', get_post() );
$page_type = ( is_home() || is_front_page() ) ? 'home' : ( is_single() ? 'post' : ( is_archive() ? 'archive' : 'page' ) );
$page_id = ( 'post' === $page_type || 'page' === $page_type ) ? $wp_query->post->ID : '';
$page_info = array(
	'slug' => $page_slug,
	'type' => $page_type,
	'postId' => $page_id,
);

$renderer = new Component_Themes();
$rendered_output = $renderer->render_page( $theme_config, $page_info, $page_config );
echo $rendered_output;
?>
		<script src="/wp-content/plugins/component-themes/build/app.js"></script>
		<script src="/wp-content/plugins/component-themes/build/core-components.js"></script>
		<script type="text/javascript">
const themeConfig = <?php echo json_encode( $theme_config ); ?>;
const pageConfig = <?php echo json_encode( $page_config ); ?>;
const pageInfo = <?php echo json_encode( $page_info ); ?>;
ComponentThemes.renderPage( themeConfig, pageInfo, pageConfig, window.document.getElementById( 'root' ) );
		</script>
	</body>
</html>
<?php
		exit;
	}
}
