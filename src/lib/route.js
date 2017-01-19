import assign from 'lodash/assign';
import storage from '~/src/lib/storage';

var initialStateSaved = false;

class Route {

	constructor() {
		this.initHistory();
	}

	initHistory() {
		if ( typeof window === 'undefined' ) return;

		window.onpopstate = ( { state } ) => {
			this.updatePageInfo( state );
		};
	}

	getHomeURL() {
		try {
			return storage.get().apiData.api['/component-themes/v1/settings'].wpurl;
		} catch(e) {
			return location.protocol + '//' + location.host;
		}
	}

	isInternalLink( url ) {
		return ( url[0] === '/' ||  url.indexOf( this.getHomeURL() ) === 0 );
	}

	/**
	 * get
	 */
	getRelativeURL( url ) {
		if ( this.isInternalLink( url ) && url[0] !== '/' ) {
			url = url.substr( this.getHomeURL().length );
			return ( ( url[0] === '/' ) ? '' : '/' ) + url;
		}

		return url;
	}

	/**
	 * Move to the URL 
	 *
	 * @param {String} url A relative URL to the wordpress root.
	 * @param {Object} [postInfo] An object that contains information of a single post.
	 *
	 * @example
	 * route.moveTo( '/' ); // go home. Call goHome() instead.
	 * route.moveTo( '/2016/12/post-title', { id: 1, type: 'post', slug: 'post-title' } );
	 */
	moveTo( url, postInfo ) {
		const data = storage.get();

		if ( !this.isInternalLink( url ) ) {
			location.url = url;
			return;
		}

		url = this.getRelativeURL( url );

		if ( ! initialStateSaved ) {
			history.replaceState( this.getPageInfo(), document.title, location.href );
		}

		// move to new page
		this.updatePageInfo( { type: postInfo.type, postId: postInfo.id, slug: postInfo.slug } );

		history.pushState( this.getPageInfo(), document.title, url );
	}

	goHome() {
		this.moveTo( '/', { id: '', type: 'home', slug: 'home' } );
	}

	getPageInfo() {
		return Object.assign( {}, storage.get().pageInfo );
	}

	updatePageInfo( newPageInfo ) {
		const data = storage.get();
		data.pageInfo = Object.assign( {}, data.pageInfo, newPageInfo );
		storage.update( data );
	}
}

const route = new Route();

export default route;
