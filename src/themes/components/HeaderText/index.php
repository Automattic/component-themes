<?php
class ComponentThemes_HeaderText extends ComponentThemes_Component {
	public function render() {
		$site_title = $this->getProp( 'siteTitle', 'My Website' );
		$site_tagline = $this->getProp( 'siteTagline', 'My home on the web' );
		return "<div class='" . $this->getProp( 'className' ) . "'>
      <h1 class='HeaderText__title'>$site_title</h1>
      <div class='HeaderText__tagline'>$site_tagline</div>
</div>";
	}
}

