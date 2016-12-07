<?php
class Component_Themes_PostTitle extends Component_Themes_Component {
	public function render() {
		$link = $this->get_prop_from_parent( 'link' );
		$link_text = $this->get_prop_from_parent( 'title', 'No title' );
		return "<h1 class='" . $this->get_prop( 'className' ) . "'>
			<a class='PostTitle_link' href='$link'>$link_text</a>
		</h1>";
	}
}

Component_Themes::register_component( 'PostTitle', 'Component_Themes_PostTitle' );
