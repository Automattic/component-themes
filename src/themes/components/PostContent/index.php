<?php
class Component_Themes_PostContent extends Component_Themes_Component {
	public function render() {
		$content = $this->get_prop( 'content', 'No content' );
		return React::createElement( 'div', array( 'className' => 'PostContent' ), array( nl2br( $content ) ) );
	}
}

Component_Themes::register_component( 'PostContent', 'Component_Themes_PostContent' );
