<?php
class ComponentThemes_PostDateAndAuthor extends ComponentThemes_Component {
	public function render() {
		$date = $this->getPropFromParent( 'date', 'No date' );
		$author = $this->getPropFromParent( 'author', 'No author' );
		return "<div class='PostDateAndAuthor'>$date by $author</div>";
	}
}

