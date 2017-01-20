import React from 'react';
import { registerComponent } from 'ComponentThemes/lib';

const TextWidget = ( { text, className } ) => {
	return (
		<div className={ className }>
			{ text || 'This is a text widget with no data!' }
		</div>
	);
};

registerComponent( 'TextWidget', TextWidget, {
	title: 'Text Widget',
	description: 'A block of text or html.',
	editableProps: {
		text: {
			type: 'string',
			label: 'The text to display.'
		}
	},
} );
