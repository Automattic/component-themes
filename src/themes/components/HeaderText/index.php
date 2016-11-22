<?php
class ComponentThemes_HeaderText extends ComponentThemes_Component {
	static $requiredApiData = [ 'siteInfo' => '/' ];
	public function render( $props ) {
		$site_title = isset( $props['siteTitle'] ) ? $props['siteTitle'] : isset( $props['siteInfo'] ) ? $props['siteInfo']['name'] : 'My Website';
		$site_tagline = isset( $props['siteTagline'] ) ? $props['siteTagline'] : isset( $props['siteInfo'] ) ? $props['siteInfo']['description'] : 'My home on the web';
		return "<div class='" . $this->getProp( 'className' ) . "'>
      <h1 class='HeaderText__title'>$site_title</h1>
      <div class='HeaderText__tagline'>$site_tagline</div>
</div>";
	}
}

