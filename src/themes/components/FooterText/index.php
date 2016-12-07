<?php
class Component_Themes_FooterText extends Component_Themes_Component {
	public function render() {
		$text = $this->get_prop( 'text', '<a href="/">Create a free website or blog at WordPress.com.</a>' );
		return "<div class='" . $this->get_prop( 'className' ) . "'>{$text}</div>";
	}
}

Component_Themes::register_component( 'FooterText', 'Component_Themes_FooterText' );
