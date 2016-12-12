# Theme Components

This directory is for Theme Components, not just regular old React components.

Each theme component is a React Component which will be available to be used in a theme's page layout. The component should follow a few guidelines:

1. It should be as simple as possible. These components will need to be tranformed into PHP code so their logic has to be very basic. A stateless functional component is recommended.
2. It should render its top-level element with `className` set to the prop `className`.
3. It should include a title, description, if it can have children, and optionally information about its props. This data is passed as a third argument to `registerComponent()`.
4. All its data should come from props. There ideally should be no calls to outside libraries, unless it's just for processing data.
5. It should include meta-data about its editable props. Rather than, or in addition to, using React's `propTypes`, you can pass an `editableProps` property when calling `registerComponent()` which contains meta-data about each prop. Each prop's meta-data should be an object that has two properties of its own: `type` (a string describing the property type, like `'boolean'` or `'string'` or `'array'`), and `label` (a string description for that property).
6. It must be registered in JS by calling `ComponentThemes.registerComponent()` before the theme is rendered.
7. It must be registered in PHP by including the component file before the theme is rendered and calling `Component_Themes::register_component()`.

Here's an example component:

```js
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent } = ComponentThemes;

const TextWidget = ( { text, className } ) => {
	return (
		<div className={ className }>
			{ text || 'This is a text widget with no data!' }
		</div>
	);
};

registerComponent( 'TextWidget', TextWidget, {
	description: 'A block of text or html.',
	editableProps: {
		text: {
			type: 'string',
			label: 'The text to display.'
		}
	},
} );
```

The PHP version of the component can be slightly simpler because it does not require the description and editableProps properties, but it can use `React::createElement()` which is what the JSX in the React component will be transpiled into.

```php
<?php
function Component_Themes_TextWidget( $props ) {
	$text = ct_get_value( $props, 'text', 'This is a text widget with no data!' );
	$class_name = ct_get_value( $props, 'className', '' );
	return React::createElement( 'div', [ 'className' => $class_name ], $text );
}

Component_Themes::register_component( 'TextWidget', 'Component_Themes_TextWidget' );
```

## Required API data

Many components will require WordPress data from the server. To specify that a component requires this data, you can add two pieces of data to the component:

1. The list of REST API endpoints needed for the data to be available.
2. A function to translate the REST API responses into props for the component.

In JavaScript we use a Higher Order Component function called 'apiDataWrapper'. The function accepts a mapping function to map the api data (the same pieces of data as noted above). The api data can be accessed using the special function `getApiEndpoint()` which is passed as the first argument to the mapping function. If the data is not available yet, it will then fetch that data.

```js
/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, apiDataWrapper } = ComponentThemes;

const HeaderText = ( { link, siteTitle, siteTagline, className } ) => {
	return (
		<div className={ className }><a href={ link }>
			<h1 className="HeaderText__title">{ siteTitle || 'My Website' }</h1>
			<div className="HeaderText__tagline">{ siteTagline || 'My home on the web' }</div>
		</a></div>
	);
};

HeaderText.description = 'Header containing site title and tagline.';
HeaderText.editableProps = {
	siteTitle: {
		type: 'string',
		label: 'The site title'
	},
	siteTagline: {
		type: 'string',
		label: 'The site sub-title or tagline'
	},
	link: {
		type: 'string',
		label: 'The header link'
	}
};

const mapApiToProps = ( getApiEndpoint ) => {
	const siteInfo = getApiEndpoint( '/' );
	return {
		siteTitle: siteInfo && siteInfo.name,
		siteTagline: siteInfo && siteInfo.description,
		link: siteInfo && siteInfo.url,
	};
};

registerComponent( 'HeaderText', apiDataWrapper( mapApiToProps )( HeaderText ) );
```

In PHP we use a similar technique. The component must be wrapped in the Higher-Order-Component function `Component_Themes::api_data_wrapper()` which accepts a mapping function as above. Also as above, the first argument to the mapping function is a special function called `get_api_endpoint()` which fetches and returns the endpoint data requested.

*NB: `ct_get_value()` is a helper function that just returns a property in an array if it exists, otherwise it returns a default value.*

```php
<?php
class Component_Themes_HeaderText extends Component_Themes_Component {
	public function render() {
		$site_title = ct_get_value( $this->props, 'siteTitle', 'My Website' );
		$site_tagline = ct_get_value( $this->props, 'siteTagline', 'My home on the web' );
		$class_name = ct_get_value( $this->props, 'className', '' );
		$link = ct_get_value( $this->props, 'link', '' );
		return "<div class='$class_name'><a href='$link'>
      <h1 class='HeaderText__title'>$site_title</h1>
      <div class='HeaderText__tagline'>$site_tagline</div>
</a></div>";
	}
}

$wrapped = Component_Themes::api_data_wrapper( 'Component_Themes_HeaderText', function( $get_api_endpoint ) {
	$site_info = call_user_func( $get_api_endpoint, '/' );
	return [
		'siteTitle' => ct_get_value( $site_info, 'name' ),
		'siteTagline' => ct_get_value( $site_info, 'description' ),
		'link' => ct_get_value( $site_info, 'url' ),
	];
} );

Component_Themes::register_component( 'HeaderText', $wrapped );
```

## Component Styles

Some components need default styles applied that are independent of the theme. These can be applied in the component definition.

In JavaScript we use the `styled()` Higher-Order-Component function. Note that in the JS version we **must** skip a className for the top-level tag, as it will be added automatically. See the documentation for [Styled Components](https://github.com/styled-components/styled-components) for more information.

```js
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, styled } = ComponentThemes;

const RowComponent = ( { children, className } ) => {
	return (
		<div className={ className }>
			{ children }
		</div>
	);
};

const Styled = styled( RowComponent )`
	display: flex;
	justify-content: space-between;
`;

registerComponent( 'RowComponent', Styled );
```

In PHP we use `Component_Themes::style_component()`. Note that in the PHP version, we **must** specify classNames explicity. Bare style rules will not be automatically wrapped in a selector as they are in JavaScript.

```php
<?php
$row_component = function( $props, $children ) {
	$class_name = ct_get_value( $props, 'className', '' );
	return React::createElement( 'div', [ 'className' => $class_name ], $children );
};

$styled = Component_Themes::style_component( $row_component, '
.RowComponent {
	display: flex;
	justify-content: space-between;
}' );

Component_Themes::register_component( 'RowComponent', $styled );
```

For more information about how Theme Components are used see the [Theme documentation](../themes/README.md).
