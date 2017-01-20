import React from 'react';
import { registerComponent } from 'ComponentThemes/lib';

const FooterText = ( { text, className } ) => {
	return (
		<div className={ className }>
			{ text || <a href="/">Create a free website or blog at WordPress.com.</a> }
		</div>
	);
};

registerComponent( 'FooterText', FooterText, {
	title: 'Footer',
	description: 'The footer text block of a site.',
	editableProps: {
		text: {
			type: 'string',
			label: 'The text to display. Defaults to "Create a free website or blog at WordPress.com"'
		}
	},
} );
