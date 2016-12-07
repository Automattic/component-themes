<?php
use function Corretto\describe, Corretto\it, Corretto\expect, Corretto\beforeEach;

require_once( './server/class-component-themes.php' );

describe( 'Component_Themes_Styles', function() {
	beforeEach( function( $c ) {
		$c->styler = Component_Themes_Styles::get_styler();
	} );

	describe( '#build_styles_from_theme()', function() {
		it( 'returns css with ComponentThemes prepended to each rule', function( $c ) {
			$theme = [ 'name' => 'MyTheme', 'slug' => 'mytheme', 'styles' => '.foo{color:green;}.bar{padding:1em;}' ];
			$result = $c->styler->build_styles_from_theme( $theme );
			expect( $result )->toEqual( '.ComponentThemes .foo{color:green;}.ComponentThemes .bar{padding:1em;}' );
		} );

		it( 'returns css with all additional-styles rules appended', function( $c ) {
			$theme = [ 'name' => 'MyTheme', 'slug' => 'mytheme', 'styles' => '.foo{color:green;}.bar{padding:1em;}', 'additional-styles' => [ 'basic' => '.basic{color:#000;}', 'complex' => '.complex{color:red;}' ] ];
			$result = $c->styler->build_styles_from_theme( $theme );
			expect( $result )->toEqual( '.ComponentThemes .foo{color:green;}.ComponentThemes .bar{padding:1em;}.ComponentThemes .basic{color:#000;}.ComponentThemes .complex{color:red;}' );
		} );

		it( 'returns css with default variant-styles applied if none are active', function( $c ) {
			$theme = [ 'name' => 'MyTheme', 'slug' => 'mytheme', 'styles' => '.foo{color:$foo-color;}.bar{padding:1em;}', 'variant-styles' => [ 'defaults' => [ 'foo-color' => 'yellow' ] ] ];
			$result = $c->styler->build_styles_from_theme( $theme );
			expect( $result )->toEqual( '.ComponentThemes .foo{color:yellow;}.ComponentThemes .bar{padding:1em;}' );
		} );

		it( 'returns css with active-variant-styles applied', function( $c ) {
			$theme = [ 'name' => 'MyTheme', 'slug' => 'mytheme', 'styles' => '.foo{color:$foo-color;}.bar{padding:1em;}', 'variant-styles' => [ 'defaults' => [ 'foo-color' => 'yellow' ], 'red' => [ 'foo-color' => 'red' ] ], 'active-variant-styles' => [ 'red' ] ];
			$result = $c->styler->build_styles_from_theme( $theme );
			expect( $result )->toEqual( '.ComponentThemes .foo{color:red;}.ComponentThemes .bar{padding:1em;}' );
		} );
	} );
} );
