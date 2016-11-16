<?php
namespace Prometheus;
class PostBody extends Component {
	public function render() {
		return "<div class='" . $this->getProp( 'className' ) . "'>" . $this->renderChildren() . "</div>";
	}
}

