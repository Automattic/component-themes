<?php
class ComponentThemes_RowComponent extends ComponentThemes_Component {
	public function render() {
		return "<div class='" . $this->getProp( 'className' ) . "'>" . $this->renderChildren() . "</div>";
	}

	public static function getStyles() {
		return "
.RowComponent {
  display: flex;
  justify-content: space-between;
}
";
	}
}
