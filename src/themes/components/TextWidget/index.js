/* globals window */
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
