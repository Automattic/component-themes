<?php
class Component_Themes_RowComponent extends Component_Themes_Component {
	public function render() {
		$styles = '<style>' . $this->getStyles() . '</style>';
		return "<div class='" . $this->get_prop( 'className' ) . "'>$styles" . $this->render_children() . '</div>';
	}

	private function getStyles() {
		return '
.RowComponent {
  display: flex;
  justify-content: space-between;
}
';
	}
}
