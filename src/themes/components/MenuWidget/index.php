<?php
class Component_Themes_Menu_Widget extends Component_Themes_Component {
	public static $styles = "
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
	";

	public function render() {
		$title = $this->get_prop( 'title' );
		$title_area = isset( $title ) ? "<h2 class='MenuWidget__title'>$title</h2>" : '';

		$links = $this->get_prop( 'links', array() );
		$links_area = implode( '', array_map( array( $this, 'render_menu_link' ), $links ) );

		return "<div class='" . $this->get_prop( 'className' ) . "'>
		$title_area
		<ul>
			$links_area
		</ul>
		</div>";
	}

	public function render_menu_link( $link ) {
		$url = $link['url'];
		$text = $link['text'];

		return "<li class='MenuWidget__link'><a href='{$url}'>{$text}</a></li>";
	}
}

Component_Themes::register_component( 'MenuWidget', 'Component_Themes_Menu_Widget' );
