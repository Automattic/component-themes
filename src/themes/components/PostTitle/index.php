<?php
namespace Prometheus;
class PostTitle extends Component {
	public function render() {
		$link = $this->getPropFromParent( 'link' );
		$link_text = $this->getPropFromParent( 'title', 'No title' );
		return "<h1 class='" . $this->getProp( 'className' ) . "'>
			<a class='PostTitle_link' href='$link'>$link_text</a>
		</h1>";
	}
}

