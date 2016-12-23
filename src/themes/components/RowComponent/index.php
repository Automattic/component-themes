<?php

class Component_Themes_Row_Component extends Component_Themes_Component {
	public static $styles = "
		.RowComponent {
			display: flex;
			justify-content: space-between;
		}
	";

	public function render() {
		$class_name = $this->get_prop( 'className', '' );
		$new_props = ct_omit( $this->props, array( 'className', 'children' ) );
		$new_children = React::cloneChildren( $this->children, $new_props );
		return React::createElement( 'div', array( 'className' => $class_name ), $new_children );
	}
}

Component_Themes::register_component( 'RowComponent', 'Component_Themes_Row_Component' );
