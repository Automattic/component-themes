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
