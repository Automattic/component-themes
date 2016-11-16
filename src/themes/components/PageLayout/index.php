<?php
namespace Prometheus;
class PageLayout extends Component {
	public function render() {
		return "<div class='" . $this->getProp( 'className' ) . "'><div class='PageLayout__content'>" . $this->renderChildren() . "</div></div>";
	}
}

