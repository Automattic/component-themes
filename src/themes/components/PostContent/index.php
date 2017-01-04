<?php
class Component_Themes_PostContent extends Component_Themes_Component {
	public function render() {
		$content = $this->get_prop( 'content', 'No content' );
		return "<div class='PostContent'>" . nl2br( $content ) . '</div>';
	}
}

Component_Themes::register_component( 'PostContent', 'Component_Themes_PostContent' );
