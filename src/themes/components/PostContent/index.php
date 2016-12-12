<?php
class Component_Themes_PostContent extends Component_Themes_Component {
	public function render() {
		$convert_newlines = function( $content ) {
			return preg_replace( '/\n/', '<br/>', $content );
		};
		$content = $this->get_prop( 'content', 'No content' );
		return "<div class='PostContent'>" . $convert_newlines( $content ) . '</div>';
	}
}

Component_Themes::register_component( 'PostContent', 'Component_Themes_PostContent' );
