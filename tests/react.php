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
		it( 'returns a component for a simple html string', function() {
			$result = React::createElement( 'em' );
			expect( get_class( $result ) )->toEqual( 'Component_Themes_Html_Component' );
		} );

		it( 'renders html for a simple html component', function() {
			$result = React::createElement( 'em' );
			expect( React::render( $result ) )->toEqual( '<em></em>' );
		} );

		it( 'renders html with properties for a simple html component with properties', function() {
			$result = React::createElement( 'a', [ 'href' => 'localhost' ] );
			expect( React::render( $result ) )->toEqual( '<a href="localhost"></a>' );
		} );

		it( 'renders html with properties for a simple html component with multiple properties', function() {
			$result = React::createElement( 'a', [ 'href' => 'localhost', 'foo' => 'bar' ] );
			expect( React::render( $result ) )->toEqual( '<a href="localhost" foo="bar"></a>' );
		} );

		it( 'renders html with "class" for a simple html component with "className" property', function() {
			$result = React::createElement( 'a', [ 'className' => 'great-link' ] );
			expect( React::render( $result ) )->toEqual( '<a class="great-link"></a>' );
		} );

		it( 'renders html which ignores properties which do not have a string value', function() {
			$result = React::createElement( 'a', [ 'className' => 'great-link', 'foo' => [ 'hello', 'world' ] ] );
			expect( React::render( $result ) )->toEqual( '<a class="great-link"></a>' );
		} );

		it( 'renders html with a text child included', function() {
			$result = React::createElement( 'a', [ 'className' => 'great-link' ], 'hello' );
			expect( React::render( $result ) )->toEqual( '<a class="great-link">hello</a>' );
		} );

		it( 'renders html with an element child included', function() {
			$child = React::createElement( 'b', [ 'className' => 'bold' ], 'hello' );
			$result = React::createElement( 'a', [ 'className' => 'great-link' ], $child );
			expect( React::render( $result ) )->toEqual( '<a class="great-link"><b class="bold">hello</b></a>' );
		} );

		it( 'renders html with multiple element children included, separated by spaces', function() {
			$child1 = React::createElement( 'b', [ 'className' => 'bold' ], 'hello' );
			$child2 = React::createElement( 'em', [ 'className' => 'emphasis' ], 'world' );
			$result = React::createElement( 'a', [ 'className' => 'great-link' ], [ $child1, 'there,', $child2 ] );
			expect( React::render( $result ) )->toEqual( '<a class="great-link"><b class="bold">hello</b> there, <em class="emphasis">world</em></a>' );
		} );

		it( 'renders html for a simple function component that returns a string', function() {
			$component = function() {
				return '<b>hello</b>';
			};
			$result = React::createElement( $component );
			expect( React::render( $result ) )->toEqual( '<b>hello</b>' );
		} );

		it( 'renders html for a simple function component that returns a component', function() {
			$component = function() {
				return React::createElement( 'b', [], 'hello' );
			};
			$result = React::createElement( $component );
			expect( React::render( $result ) )->toEqual( '<b>hello</b>' );
		} );

		it( 'renders html for a function component with props', function() {
			$component = function( $props ) {
				return React::createElement( 'b', [], ct_get_value( $props, 'name' ) );
			};
			$result = React::createElement( $component, [ 'name' => 'hello' ] );
			expect( React::render( $result ) )->toEqual( '<b>hello</b>' );
		} );

		it( 'renders html for a function component with children', function() {
			$component = function( $props, $children ) {
				return React::createElement( 'b', [], $children );
			};
			$result = React::createElement( $component, [], 'hello' );
			expect( React::render( $result ) )->toEqual( '<b>hello</b>' );
		} );

		it( 'renders html for a simple class component that returns a string', function() {
			$result = React::createElement( 'My_Component' );
			expect( React::render( $result ) )->toEqual( '<b>hello</b>' );
		} );

		it( 'renders html for a class component with props', function() {
			$result = React::createElement( 'My_Component', [ 'name' => ' world' ] );
			expect( React::render( $result ) )->toEqual( '<b>hello world</b>' );
		} );
	} );

	describe( '::cloneElement()', function() {
		it( 'returns an html component with the same tag as the passed component', function() {
			$component = React::createElement( 'em' );
			$result = React::cloneElement( $component );
			expect( React::render( $result ) )->toEqual( '<em></em>' );
		} );

		it( 'returns an html component which is a different instance than the passed component', function() {
			$component = React::createElement( 'em' );
			$result = React::cloneElement( $component );
			expect( $result )->toNotEqual( $component );
		} );

		it( 'returns an html component with the same props as the passed component', function() {
			$component = React::createElement( 'em', [ 'foo' => 'bar' ] );
			$result = React::cloneElement( $component );
			expect( $result->props['foo'] )->toEqual( 'bar' );
		} );

		it( 'returns an html component with the same children as the passed component', function() {
			$component = React::createElement( 'em', [], 'hello' );
			$result = React::cloneElement( $component );
			expect( React::render( $result ) )->toEqual( '<em>hello</em>' );
		} );

		it( 'returns an html component with its props overridden by the passed props', function() {
			$component = React::createElement( 'em', [ 'foo' => 'bar' ], 'hello' );
			$result = React::cloneElement( $component, [ 'foo' => 'baz' ] );
			expect( React::render( $result ) )->toEqual( '<em foo="baz">hello</em>' );
		} );

		it( 'returns a function component which renders the same string as the passed component', function() {
			$component = React::createElement( function() {
				return '<b>hello</b>';
			} );
			$result = React::cloneElement( $component );
			expect( React::render( $result ) )->toEqual( '<b>hello</b>' );
		} );

		it( 'returns a function component which is a different instance than the passed component', function() {
			$component = React::createElement( function() {
				return '<b>hello</b>';
			} );
			$result = React::cloneElement( $component );
			expect( $result )->toNotEqual( $component );
		} );

		it( 'returns a function component with its props set by the passed props', function() {
			$component = React::createElement( function() {
				return '<b>hello</b>';
			} );
			$result = React::cloneElement( $component, [ 'foo' => 'bar' ] );
			expect( $result->props['foo'] )->toEqual( 'bar' );
		} );

		it( 'returns a class component which renders the same string as the passed component', function() {
			$component = React::createElement( 'My_Component' );
			$result = React::cloneElement( $component );
			expect( React::render( $result ) )->toEqual( '<b>hello</b>' );
		} );

		it( 'returns a class component which is a different instance than the passed component', function() {
			$component = React::createElement( 'My_Component' );
			$result = React::cloneElement( $component );
			expect( $result )->toNotEqual( $component );
		} );

		it( 'returns a class component with its props set by the passed props', function() {
			$component = React::createElement( 'My_Component' );
			$result = React::cloneElement( $component, [ 'foo' => 'bar' ] );
			expect( $result->props['foo'] )->toEqual( 'bar' );
		} );
	} );
} );

