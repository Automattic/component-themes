<?php

namespace Component_Themes;

class PostContent extends Component {
	public function render() {
		$convert_newlines = function( $content ) {
			return preg_replace( '/\n/', '<br/>', $content );
		};
		$content = $this->get_prop_from_parent( 'content', 'No content' );
		return "<div class='PostContent'>" . $convert_newlines( $content ) . '</div>';
	}
}

