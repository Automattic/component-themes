<?php

namespace Component_Themes;

class FooterText extends Component {
	public function render() {
		$text = $this->get_prop( 'text', '<a href="/">Create a free website or blog at WordPress.com.</a>' );
		return "<div class='" . $this->get_prop( 'className' ) . "'>{$text}</div>";
	}
}

