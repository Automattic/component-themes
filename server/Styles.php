<?php
namespace Prometheus;

require( '../vendor/CSS-Parser/parser.php' );

class Styles {
	public function buildStylesFromTheme( $themeConfig ) {
		$styles = isset( $themeConfig['styles'] ) ? $themeConfig['styles'] : [];
		if ( is_string( $styles ) ) {
			return $this->prependNamespaceToStyleString( '.StrangerThemes', $styles );
		}
		return implode( '', array_map( function( $key ) use ( &$styles ) {
			return $this->buildStyleBlock( $key, $styles[ $key ] );
		}, array_keys( $styles ) ) );
	}

	public function getComponentStyles( $components ) {
		return implode( '', array_reduce( $components, function( $styles, $component ) {
			if ( ! class_exists( __NAMESPACE__ . '\\' . $component ) ) {
				return $styles;
			}
			$css = call_user_func( array( __NAMESPACE__ . '\\' . $component, 'getStyles' ) );
			if ( $css ) {
				return array_merge( $styles, [ $css ] );
			}
			return $styles;
		}, [] ) );
	}

	private function buildStyleBlock( $key, $style ) {
		return ".StrangerThemes $key{" . $this->getStyleStringFromStyleData( $style ) . "}";
	}

	private function getStyleStringFromStyleData( $style ) {
		return is_array( $style ) ? implode( '', $style ) : $style;
	}

	private function prependNamespaceToNode( $namespace, $val ) {
		$new_val = $val;
		if ( is_array( $val ) ) {
			$new_val = [];
			foreach( $val as $child_key => $child_val ) {
				$new_key = $child_key;
				if ( $child_key[0] === '.' || $child_key[0] === '#' ) {
					$new_key = $namespace . ' ' . $child_key;
				}
				$new_val[ $new_key ] = $this->prependNamespaceToNode( $namespace, $child_val );
			}
		}
		return $new_val;
	}

	public function prependNamespaceToStyleString( $namespace, $styles ) {
		$parser = new \CssParser();
		$parser->load_string( $styles );
		$parser->parse();
		$parser->parsed = $this->prependNamespaceToNode( $namespace, $parser->parsed );
		return $parser->glue();
	}
}
