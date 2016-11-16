# Theme Components

This directory is for Theme Components, not just regular old React components.

Each theme component is a React Component which will be available to be used in a theme's page layout. The component should follow a few guidelines:

1. It should be as simple as possible. These components will need to be tranformed into PHP code so their logic has to be very basic. A stateless functional component is recommended.
2. It should render its top-level element with `className` set to the prop `className`.
3. It should include a description. You can export a `description` property on the Component object (eg: `TextWidget.description = 'This is a box for text';`).
4. All its data should come from props. There ideally should be no calls to outside libraries, unless it's just for processing data.
5. It should include meta-data about its editable props. Rather than, or in addition to, using React's `propTypes`, you can export an `editableProps` property on the Component object which contains meta-data about each prop. The meta-data should be an object that has two properties of its own: `type` (a string describing the property type, like `'boolean'` or `'string'` or `'array'`), and `label` (a string description for that property).
6. It must be registered in `lib/components`.

Here's an example component:

```js
import React from 'react';

const TextWidget = ( { text, className } ) => {
	return (
		<div className={ className }>
			{ text || 'This is a text widget with no data!' }
		</div>
	);
};

TextWidget.description = 'A block of text or html.';
TextWidget.editableProps = {
	text: {
		type: 'string',
		label: 'The text to display.'
	}
};

export default TextWidget;
```

For more information about how Theme Components are used see the [Theme documentation](../themes/README.md).
