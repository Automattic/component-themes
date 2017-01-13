<?php
class Component_Themes_PageLayout extends Component_Themes_Component {
	public function render() {
		$new_props = ct_omit( $this->props, array( 'className', 'children' ) );
		$content = React::createElement( 'div', array( 'className' => 'PageLayout__content' ), $this->clone_children( $new_props ) );
		return React::createElement( 'div', array( 'className' => $this->get_prop( 'className' ) ), array( $content ) );
	}
}

Component_Themes::register_component( 'PageLayout', 'Component_Themes_PageLayout' );
