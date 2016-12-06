<?php
class Component_Themes_PageLayout extends Component_Themes_Component {
	public function render() {
		return "<div class='" . $this->get_prop( 'className' ) . "'><div class='PageLayout__content'>" . $this->render_children() . '</div></div>';
	}
}

Component_Themes::register_component( 'PageLayout', 'Component_Themes_PageLayout' );
