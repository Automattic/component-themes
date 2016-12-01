<?php

namespace Component_Themes;

class PostTitle extends Component {
	public function render() {
		$link = $this->get_prop_from_parent( 'link' );
		$link_text = $this->get_prop_from_parent( 'title', 'No title' );
		return "<h1 class='" . $this->get_prop( 'className' ) . "'>
			<a class='PostTitle_link' href='$link'>$link_text</a>
		</h1>";
	}
}

