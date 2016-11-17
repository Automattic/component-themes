<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Prometheus</title>
<?php

require( './StrangerThemes.php' );

$kubrick = json_decode( file_get_contents( '../src/themes/kubrick.json' ), true );
$twentyfifteen = json_decode( file_get_contents( '../src/themes/twentyfifteen.json' ), true );
$content = json_decode( file_get_contents( '../src/content/kubrick.json' ), true );

$current_theme_slug = 'kubrick';
if ( isset( $_GET['theme'] ) ) {
	$current_theme_slug = $_GET['theme'];
}
switch ( $current_theme_slug ) {
case 'kubrick':
	$theme = $kubrick;
	break;
case 'twentyfifteen':
	$theme = $twentyfifteen;
	break;
default:
	$theme = $kubrick;
	break;
}

?>
</head><body>
<?php
$renderer = new \Prometheus\StrangerThemes();
$app_css = file_get_contents( '../src/components/App/App.css' );
echo "<style class='app'>$app_css</style>";
echo "<style class='ThemeSelector'>
.ThemeSelector {
	float: right;
}
</style>
";
echo "
<script>
function switchToTwentyfifteen() {
	window.location = '/?theme=twentyfifteen';
}
function switchToKubrick() {
	window.location = '/?theme=kubrick';
}
</script>
";

$rendered_output = $renderer->renderTheme( $theme, $content );

$twentyfifteenDisabled = ( $current_theme_slug === 'twentyfifteen' ) ? 'disabled' : '';
$kubrickDisabled = ( $current_theme_slug === 'kubrick' ) ? 'disabled' : '';

echo "
<div class='App'>
	<div class='App-header'>
		<h2>Welcome to Prometheus</h2>
		<div class='ThemeSelector'>
			<button onClick='switchToKubrick()' $kubrickDisabled>Kubrick</button>
			<button onClick='switchToTwentyfifteen()' $twentyfifteenDisabled>Twentyfifteen</button>
		</div>
	</div>
	$rendered_output
</div>
";
?>
<script class="prometheus" type="text/javascript" src="/build/main.js"></script>
</body></html>
