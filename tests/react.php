<?php
use function Corretto\describe, Corretto\it, Corretto\expect, Corretto\beforeEach;

require_once( './server/class-component-themes.php' );

class My_Component extends Component_Themes_Component {
	public function render() {
		return '<b>hello' . ct_get_value( $this->props, 'name', '' ) . '</b>';
	}
}

describe( 'React', function() {
	describe( '::createElement()', function() {
		it( 'returns html for a simple html component', function() {
			$result = React::createElement( 'em' );
			expect( $result )->toEqual( '<em></em>' );
		} );

		it( 'returns html with properties for a simple html component with properties', function() {
			$result = React::createElement( 'a', [ 'href' => 'localhost' ] );
			expect( $result )->toEqual( '<a href="localhost"></a>' );
		} );

		it( 'returns html with properties for a simple html component with multiple properties', function() {
			$result = React::createElement( 'a', [ 'href' => 'localhost', 'foo' => 'bar' ] );
			expect( $result )->toEqual( '<a href="localhost" foo="bar"></a>' );
		} );

		it( 'returns html with "class" for a simple html component with "className" property', function() {
			$result = React::createElement( 'a', [ 'className' => 'great-link' ] );
			expect( $result )->toEqual( '<a class="great-link"></a>' );
		} );

		it( 'returns html which ignores properties which do not have a string value', function() {
			$result = React::createElement( 'a', [ 'className' => 'great-link', 'foo' => [ 'hello', 'world' ] ] );
			expect( $result )->toEqual( '<a class="great-link"></a>' );
		} );

		it( 'returns html with a text child included', function() {
			$result = React::createElement( 'a', [ 'className' => 'great-link' ], 'hello' );
			expect( $result )->toEqual( '<a class="great-link">hello</a>' );
		} );

		it( 'returns html with an element child included', function() {
			$child = React::createElement( 'b', [ 'className' => 'bold' ], 'hello' );
			$result = React::createElement( 'a', [ 'className' => 'great-link' ], $child );
			expect( $result )->toEqual( '<a class="great-link"><b class="bold">hello</b></a>' );
		} );

		it( 'returns html with multiple element children included, separated by spaces', function() {
			$child1 = React::createElement( 'b', [ 'className' => 'bold' ], 'hello' );
			$child2 = React::createElement( 'em', [ 'className' => 'emphasis' ], 'world' );
			$result = React::createElement( 'a', [ 'className' => 'great-link' ], [ $child1, 'there,', $child2 ] );
			expect( $result )->toEqual( '<a class="great-link"><b class="bold">hello</b> there, <em class="emphasis">world</em></a>' );
		} );

		it( 'returns html for a simple function component that returns a string', function() {
			$component = function() {
				return '<b>hello</b>';
			};
			$result = React::createElement( $component );
			expect( $result )->toEqual( '<b>hello</b>' );
		} );

		it( 'returns html for a simple function component that returns a component', function() {
			$component = function() {
				return React::createElement( 'b', [], 'hello' );
			};
			$result = React::createElement( $component );
			expect( $result )->toEqual( '<b>hello</b>' );
		} );

		it( 'returns html for a function component with props', function() {
			$component = function( $props ) {
				return React::createElement( 'b', [], ct_get_value( $props, 'name' ) );
			};
			$result = React::createElement( $component, [ 'name' => 'hello' ] );
			expect( $result )->toEqual( '<b>hello</b>' );
		} );

		it( 'returns html for a function component with children', function() {
			$component = function( $props, $children ) {
				return React::createElement( 'b', [], $children );
			};
			$result = React::createElement( $component, [], 'hello' );
			expect( $result )->toEqual( '<b>hello</b>' );
		} );

		it( 'returns html for a simple class component that returns a string', function() {
			$result = React::createElement( 'My_Component' );
			expect( $result )->toEqual( '<b>hello</b>' );
		} );

		it( 'returns html for a class component with props', function() {
			$result = React::createElement( 'My_Component', [ 'name' => ' world' ] );
			expect( $result )->toEqual( '<b>hello world</b>' );
		} );
	} );
} );

