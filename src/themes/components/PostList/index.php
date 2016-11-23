<?php
class ComponentThemes_PostList extends ComponentThemes_Component {
	public function render() {
		$posts = $this->getProp( 'posts', [] );
		if ( count( $posts ) < 1 ) {
			return "<p>No posts</p>";
		}
		$defaultPostConfig = [ "componentType" => "PostBody", "children" => [
			[ "componentType" => "PostTitle" ],
			[ "componentType" => "PostDateAndAuthor" ],
			[ "componentType" => "PostContent" ]
		] ];
		$post_config = null !== $this->getProp( 'post' ) ? $this->getProp( 'post' ) : $defaultPostConfig;
		$builder = new Builder();
		$render_blog_post = function( $post ) use ( &$post_config, &$builder ) {
			$component = $builder->makeComponentWith( $post_config, $post );
			return $component->render();
		};
		return "<div class='" . $this->getProp( 'className' ) . "'>" . implode( '', array_map( $render_blog_post, $posts ) ) . "</div>";
	}
}

