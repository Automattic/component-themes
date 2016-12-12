/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent } = ComponentThemes;

function convertNewlines( content ) {
	return content.replace( /\n/g, '<br />' );
}

// eslint-disable-next-line react/no-danger
const PostContentInner = ( { content } ) => <div className="PostContent__content" dangerouslySetInnerHTML={ { __html: convertNewlines( content ) } } />;

const PostContent = ( { content, className } ) => {
	return (
		<div className={ className }>
			<PostContentInner content={ content || 'No content' } />
		</div>
	);
};

registerComponent( 'PostContent', PostContent, {
	description: 'The content of a post, rendered as html. Use inside a PostBody.',
	editableProps: {
		content: {
			type: 'string',
			label: 'The html content of a post.'
		}
	},
} );
