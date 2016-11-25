<?php
class ComponentThemes_RowComponent extends ComponentThemes_Component {
	public function render() {
		$styles = "<style>" . $this->getStyles() . "</style>";
		return "<div class='" . $this->getProp( 'className' ) . "'>$styles" . $this->renderChildren() . "</div>";
	}

	private function getStyles() {
		return "
.RowComponent {
  display: flex;
  justify-content: space-between;
}
";
	}
}
