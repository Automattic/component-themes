/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, styled } = ComponentThemes;
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

const Styled = styled( MenuWidget )`
.MenuWidget__title {
	font-size: 0.8em;
	margin: 5px 0 0;
	padding: 0;
}

ul {
	list-style: none;
	margin: 5px 0 0 10px;
	padding: 0;
}

.MenuWidget__link {
	margin: 3px 0 0;
	padding: 0;
	list-style-type: none;
	list-style-image: none;
}
`;

registerComponent( 'MenuWidget', Styled );

