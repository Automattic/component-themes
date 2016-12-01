<?php

namespace Component_Themes;

class PostBody extends Component {
	public function render() {
		return "<div class='" . $this->get_prop( 'className' ) . "'>" . $this->render_children() . '</div>';
	}
}

