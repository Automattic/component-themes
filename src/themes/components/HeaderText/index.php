<?php

namespace Component_Themes;

class HeaderText extends Component {
	public static $requiredApiData = [ 'siteInfo' => '/' ];
	public function render() {
		$props = $this->props;
		$site_title = isset( $props['siteInfo']['name'] ) ? $props['siteInfo']['name'] : ( isset( $props['siteTitle'] ) ? $props['siteTitle'] : 'My Website' );
		$site_tagline = isset( $props['siteInfo']['description'] ) ? $props['siteInfo']['description'] : ( isset( $props['siteTagline'] ) ? $props['siteTagline'] : 'My home on the web' );
		$className = isset( $props['className'] ) ? $props['className'] : '';
		return "<div class='$className'>
      <h1 class='HeaderText__title'>$site_title</h1>
      <div class='HeaderText__tagline'>$site_tagline</div>
</div>";
	}
}
