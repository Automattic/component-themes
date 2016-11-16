<?php
namespace Prometheus;
class ColumnComponent extends Component {
	public function render() {
		return "<div class='" . $this->getProp( 'className' ) . "'>" . $this->renderChildren() . "</div>";
	}
}

