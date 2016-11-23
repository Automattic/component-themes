<?php
class ComponentThemes_PostBody extends ComponentThemes_Component {
	public function render() {
		return "<div class='" . $this->getProp( 'className' ) . "'>" . $this->renderChildren() . "</div>";
	}
}

