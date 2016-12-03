/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, getPropsFromParent } = ComponentThemes;

const PostAuthor = ( { author, className } ) => {
	return (
		<span className={ className }>
			by { author || 'No author' }
		</span>
	);
};

PostAuthor.description = 'The author block for a post. Use inside a PostBody.';
PostAuthor.editableProps = {
	author: {
		type: 'string',
		label: 'The author string for the post'
	},
};

const mapProps = ( { author } ) => ( { author } );
registerComponent( 'PostAuthor', getPropsFromParent( mapProps )( PostAuthor ) );
