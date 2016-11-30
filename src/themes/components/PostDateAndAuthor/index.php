<?php
class Component_Themes_PostDateAndAuthor extends Component_Themes_Component {
	public function render() {
		$date = $this->get_prop_from_parent( 'date', 'No date' );
		$author = $this->get_prop_from_parent( 'author', 'No author' );
		return "<div class='PostDateAndAuthor'>$date by $author</div>";
	}
}

