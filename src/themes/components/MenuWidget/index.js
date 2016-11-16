import React from 'react';
//import './MenuWidget.css';

const MenuLink = ( { text, url } ) => <li className="MenuWidget__link"><a href={ url }>{ text }</a></li>;

const MenuWidget = ( { title, links, className } ) =>
	<div className={ className }>
		{ !! title && <h2 className="MenuWidget__title">{ title }</h2> }
		<ul>
			{ !! links && links.map( ( { text, url } ) => <MenuLink key={ text } text={ text } url={ url } /> ) }
		</ul>
	</div>;

MenuWidget.description = 'A menu (list of links).';
MenuWidget.editableProps = {
	title: {
		type: 'string',
		label: 'The title of this list (optional)'
	},
	links: {
		type: 'array',
		label: 'A list of { text, url } objects.'
	}
};

export default MenuWidget;

