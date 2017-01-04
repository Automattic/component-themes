<?php
require( dirname( __DIR__ ) . '/server_requirements/CSS-Parser/parser.php' );

class Component_Themes_Styles {

	public static function get_styler() {
		return new Component_Themes_Styles();
	}

	public function build_styles_from_theme( $theme_config ) {
		$styles = ct_get_value( $theme_config, 'styles', array() );
		return $this->strip_whitespace( $this->prepend_namespace_to_style_string( '.ComponentThemes', $this->expand_style_variants( $this->add_additional_styles( $styles, $theme_config ), $theme_config ) ) );
	}

	private function strip_whitespace( $styles ) {
		$styles = preg_replace( '/\s*\n\s*/', '', $styles );
		$styles = preg_replace( '/\s*:\s*/', ':', $styles );
		$styles = preg_replace( '/\s*{\s*/', '{', $styles );
		$styles = preg_replace( '/\s*}\s*/', '}', $styles );
		return $styles;
	}

	private function add_additional_styles( $styles, $theme_config ) {
		$additional = ct_get_value( $theme_config, 'additional-styles', array() );
		return $styles . implode( '', array_values( $additional ) );
	}

	private function expand_style_variants( $styles, $theme_config ) {
		if ( ! isset( $theme_config['variant-styles'] ) ) {
			return $styles;
		}
		$variants = $theme_config['variant-styles'];
		$active_variants = array_merge( array( 'defaults' ), ct_get_value( $theme_config, 'active-variant-styles', array() ) );

		$active_variants_array = array();
		foreach( $active_variants as $key ) {
			$active_variants_array[] = ct_get_value( $variants, $key, array() );
		}
		$final_variants = call_user_func_array( 'array_merge', $active_variants_array );

		$var_names = array();
		$style_values = array();
		foreach( $final_variants as $key => $value ) {
			$var_names[] = '$' . $key;
			$style_values[] = $value;
		}

		return str_replace( $var_names, $style_values, $styles );
	}

	private function prepend_namespace_to_node( $namespace, $val ) {
		$new_val = $val;
		if ( is_array( $val ) ) {
			$new_val = array();
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
		$parser = new CssParser();
		$parser->load_string( $styles );
		$parser->parse();
		$parser->parsed = $this->prepend_namespace_to_node( $namespace, $parser->parsed );
		return $parser->glue();
	}
}
