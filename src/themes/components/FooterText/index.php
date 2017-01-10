<?php
class Component_Themes_FooterText extends Component_Themes_Component {
	public function render() {
		$text = $this->get_prop( 'text', '<a href="/">Create a free website or blog at WordPress.com.</a>' );
		return React::createElement( 'div', array( 'className' => $this->get_prop( 'className' ) ), array( $text ) );
	}
}

Component_Themes::register_component( 'FooterText', 'Component_Themes_FooterText' );
