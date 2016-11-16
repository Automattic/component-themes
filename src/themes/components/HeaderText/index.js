import React from 'react';

const HeaderText = ( { siteTitle, siteTagline, className } ) => {
	return (
		<div className={ className }>
			<h1 className="HeaderText__title">{ siteTitle || 'My Website' }</h1>
			<div className="HeaderText__tagline">{ siteTagline || 'My home on the web' }</div>
		</div>
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
	}
};

export default HeaderText;

