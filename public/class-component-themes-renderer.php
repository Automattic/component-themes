<?php

namespace Component_Themes;

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Component_Themes
 */
class Renderer {

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
				<script src="/wp-content/plugins/component-themes/build/app.js"></script>
				<style> body { padding: 0; margin: 0; font-family: Sans-Serif; } </style>
		</head>
		<body>
			<div id="root">
<?php
require_once( PLUGIN_DIR . 'server/class-component-themes.php' );
$theme_config = json_decode( file_get_contents( PLUGIN_DIR . 'themes/kubrick/theme.json' ), true );
$page_config = null;
$page_slug = ( is_home() || is_front_page() ) ? 'home' : get_post_field( 'post_name', get_post() );

$renderer = new Component_Themes();
$rendered_output = $renderer->render_page( $theme_config, $page_slug, $page_config );
echo $rendered_output;
?>
	<script type="text/javascript">
const themeConfig = <?php echo json_encode( $theme_config ); ?>;
const pageConfig = <?php echo json_encode( $page_config ); ?>;
const pageSlug = '<?php echo $page_slug ?>';
ComponentThemes.renderPage( themeConfig, pageSlug, pageConfig, window.document.getElementById( 'root' ) );
	</script>
	</body>
</html>
<?php
		exit;
	}
}
