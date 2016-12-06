<?php
require( dirname( __DIR__ ) . '/server_requirements/CSS-Parser/parser.php' );

class Component_Themes_Styles {
	public static function get_styler() {
		return new Component_Themes_Styles();
	}

	public static function style_component( $component, $styles ) {
		$styler = Component_Themes_Styles::get_styler();
		$styler->add_styles_to_header( $styles );
		return function( $props, $children ) use ( &$component, &$class_name ) {
			return React::createElement( $component, $props, $children );
		};
	}

	public function add_styles_to_header( $styles ) {
		echo '<style type="text/css" class="component-themes-styles">' . $styles . '</style>';
	}

	public function build_styles_from_theme( $theme_config ) {
		$styles = ct_get_value( $theme_config, 'styles', [] );
		if ( is_string( $styles ) ) {
			return $this->strip_whitespace( $this->prepend_namespace_to_style_string( '.ComponentThemes', $this->expand_style_variants( $this->add_additional_styles( $styles, $theme_config ), $theme_config ) ) );
		}
		$basic_styles = implode( '', array_map( function( $key ) use ( &$styles ) {
			return $this->build_style_block( $key, $styles[ $key ] );
		}, array_keys( $styles ) ) );
		return $this->expand_style_variants( $this->add_additional_styles( $basic_styles, $theme_config ), $theme_config );
	}

	private function strip_whitespace( $styles ) {
		$styles = preg_replace( '/\s*\n\s*/', '', $styles );
		$styles = preg_replace( '/\s*:\s*/', ':', $styles );
		$styles = preg_replace( '/\s*{\s*/', '{', $styles );
		$styles = preg_replace( '/\s*}\s*/', '}', $styles );
		return $styles;
	}

	private function add_additional_styles( $styles, $theme_config ) {
		$additional = ct_get_value( $theme_config, 'additional-styles', [] );
		return $styles . implode( '', array_values( $additional ) );
	}

	private function expand_style_variants( $styles, $theme_config ) {
		if ( ! isset( $theme_config['variant-styles'] ) ) {
			return $styles;
		}
		$variants = $theme_config['variant-styles'];
		$active_variants = array_merge( [ 'defaults' ], ct_get_value( $theme_config, 'active-variant-styles', [] ) );
		$final_variants = array_reduce( $active_variants, function( $prev, $variant_key ) use ( &$variants ) {
			return array_merge( $prev, ct_get_value( $variants, $variant_key, [] ) );
		}, [] );
		return array_reduce( array_keys( $final_variants ), function( $prev, $var_name ) use ( &$final_variants ) {
			return str_replace( '$' . $var_name, $final_variants[ $var_name ], $prev );
		}, $styles );
	}

	private function build_style_block( $key, $style ) {
		return ".ComponentThemes $key{" . $this->get_style_string_from_style_data( $style ) . '}';
	}

	private function get_style_string_from_style_data( $style ) {
		return is_array( $style ) ? implode( '', $style ) : $style;
	}

	private function prepend_namespace_to_node( $namespace, $val ) {
		$new_val = $val;
		if ( is_array( $val ) ) {
			$new_val = [];
			foreach ( $val as $child_key => $child_val ) {
				$new_key = $child_key;
				if ( '.' === $child_key[0] || '#' === $child_key[0] ) {
					$new_key = $namespace . ' ' . $child_key;
				}
				$new_val[ $new_key ] = $this->prepend_namespace_to_node( $namespace, $child_val );
			}
		}
		return $new_val;
	}

	public function prepend_namespace_to_style_string( $namespace, $styles ) {
		$parser = new \CssParser();
		$parser->load_string( $styles );
		$parser->parse();
		$parser->parsed = $this->prepend_namespace_to_node( $namespace, $parser->parsed );
		return $parser->glue();
	}
}
