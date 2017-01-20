/**
 * External dependencies
 */
import React, { Component, PropTypes } from 'react';

/**
 * Internal dependencies
 */
import Styles from '~/src/components/Styles';
import { buildStylesFromTheme } from '~/src/lib/styles';
import { buildComponentsFromTheme, getTemplateForSlug, mergeThemes } from '~/src/lib/component-builder';
import { apiDataProvider } from '~/src/lib/api';

class ComponentThemePage extends Component {

	render() {
		const theme = mergeThemes( this.props.defaultTheme || {}, this.props.theme );
		const slug = this.props.info.type === 'post' ? 'post' : this.props.info.slug;
		const page = this.props.page || getTemplateForSlug( theme, slug );
		const children = buildComponentsFromTheme(
			theme,
			page,
			this.props.content || {}
		);
		const styles = buildStylesFromTheme( theme, this.props.content );
		return (
			<div className="ComponentThemes">
				<Styles name="ComponentThemePage__styles" styles={ styles } />
				{ children }
			</div>
		);
	}
}

ComponentThemePage.propTypes = {
	theme: PropTypes.object.isRequired,
	defaultTheme: PropTypes.object.isRequired,
	page: PropTypes.object,
	info: PropTypes.object.isRequired,
	content: PropTypes.object,
};

export default apiDataProvider()( ComponentThemePage );
