import React from 'react';

import { apiDataWrapper, getApiEndpoint } from '~/src/lib/api';

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

const mapApiToProps = ( api ) => {
	const siteInfo = getApiEndpoint( api, '/' );
	return {
		siteTitle: siteInfo && siteInfo.name,
		siteTagline: siteInfo && siteInfo.description,
	};
};

export default apiDataWrapper( [ '/' ], mapApiToProps )( HeaderText );

