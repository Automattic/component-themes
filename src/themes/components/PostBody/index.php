<?php
class Component_Themes_PostBody extends Component_Themes_Component {
	public function render() {
		return "<div class='" . $this->get_prop( 'className' ) . "'>" . $this->render_children() . '</div>';
	}
}

