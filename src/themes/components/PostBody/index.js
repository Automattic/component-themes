/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent } = ComponentThemes;

const PostBody = ( { content, date, link, author, title, children, className } ) => {
	const newChildren = React.Children.map( children, child => React.cloneElement( child, { title, content, link, date, author } ) );
	return (
		<div className={ className }>
			{ newChildren }
		</div>
	);
};

PostBody.description = 'A wrapper for a post. Use PostContent, PostTitle, PostDate, and PostAuthor for the inside.';
PostBody.hasChildren = true;

registerComponent( 'PostBody', PostBody );
