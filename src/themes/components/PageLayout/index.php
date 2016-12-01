<?php

namespace Component_Themes;

class PageLayout extends Component {
	public function render() {
		return "<div class='" . $this->get_prop( 'className' ) . "'><div class='PageLayout__content'>" . $this->render_children() . '</div></div>';
	}
}

