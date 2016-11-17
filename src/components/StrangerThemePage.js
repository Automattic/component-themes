/**
 * External dependencies
 */
import React, { Component, PropTypes } from 'react';

/**
 * Internal dependencies
 */
import Styles from '~/src/components/Styles';
import { buildStylesFromTheme } from '~/src/lib/styles';
import { buildComponentsFromTheme, getTemplateForSlug } from '~/src/lib/component-builder';

class StrangerThemePage extends Component {
	render() {
		const page = this.props.page || getTemplateForSlug( this.props.theme, this.props.slug );
		const children = buildComponentsFromTheme(
			this.props.theme,
			page,
			this.props.content || {}
		);
		const styles = buildStylesFromTheme( this.props.theme, this.props.content );
		return (
			<div className="StrangerThemes">
				<Styles styles={ styles } />
				{ children }
			</div>
		);
	}
}

StrangerThemePage.propTypes = {
	theme: PropTypes.object.isRequired,
	page: PropTypes.object,
	slug: PropTypes.string.isRequired,
	content: PropTypes.object,
};

export default StrangerThemePage;
