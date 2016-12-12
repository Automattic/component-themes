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

const mapApiToProps = ( getApiEndpoint ) => {
	const siteInfo = getApiEndpoint( '/' );
	return {
		siteTitle: siteInfo && siteInfo.name,
		siteTagline: siteInfo && siteInfo.description,
		link: siteInfo && siteInfo.url,
	};
};

registerComponent( 'HeaderText', apiDataWrapper( mapApiToProps )( HeaderText ), {
	description: 'Header containing site title and tagline.',
	editableProps: {
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
	},
} );

