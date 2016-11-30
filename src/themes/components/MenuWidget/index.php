<?php
class Component_Themes_MenuWidget extends Component_Themes_Component {
	public function render() {
		$title = $this->get_prop( 'title' );
		$title_area = isset( $title ) ? "<h2 class='MenuWidget__title'>$title</h2>" : '';
		$links = $this->get_prop( 'links', [] );
		$links_area = implode( '', array_map( function( $link ) {
			$url = $link['url'];
			$text = $link['text'];
			return "<li class='MenuWidget__link'><a href=$url>$text</a></li>";
		}, $links ) );
		$styles = '<style>' . $this->getStyles() . '</style>';
		return "<div class='" . $this->get_prop( 'className' ) . "'>
		$styles
		$title_area
		<ul>
			$links_area
		</ul>
		</div>";
	}

	private function getStyles() {
		return '
.MenuWidget .MenuWidget__title {
  font-size: 0.8em;
  margin: 5px 0 0;
  padding: 0;
}

.MenuWidget ul {
  list-style: none;
  margin: 5px 0 0 10px;
  padding: 0;
}

.MenuWidget__link {
  margin: 3px 0 0;
  padding: 0;
  list-style-type: none;
  list-style-image: none;
}
';
	}
}
