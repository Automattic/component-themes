import assign from 'lodash/assign';
import storage from '~/src/lib/storage';

let initialStateSaved = false;

function getHomeURL() {
	try {
		return storage.get().apiData.api['/component-themes/v1/settings'].wpurl;
	} catch(e) {
		return location.protocol + '//' + location.host;
	}
}


/**
 * Check whether a url is internal. Returns true when it points internal resources.
 *
 * @param {String} url URL string
 * @return {Boolean}
 */
function isInternalLink( url ) {
	return ( ! /^https?:/i.test( url ) ||  url.indexOf( this.getHomeURL() ) === 0 );
}

/**
 * Get home-relative path of a url
 *
 * @param {String} url URL string
 * @return {String}
 */
function getRelativeURL( url ) {
	if ( isInternalLink( url ) && url[0] !== '/' ) {
		url = url.substr( this.getHomeURL().length );
		return ( ( url[0] === '/' ) ? '' : '/' ) + url;
	}

	return url;
}

class Route {

	constructor() {
		this.initHistory();
	}

	initHistory() {
		if ( typeof window === 'undefined' ) {
			return;
		}

		window.onpopstate = ( { state } ) => {
			this.updatePageInfo( state );
		};
	}

	/**
	 * Move to the URL 
	 *
	 * @param {String} url A URL to move to.
	 * @param {Object} [postInfo] An object that contains information of a single post.
	 *
	 * @example
	 * route.moveTo( '/' ); // go home. Call goHome() instead.
	 * route.moveTo( '/2016/12/post-title', { id: 1, type: 'post', slug: 'post-title' } );
	 */
	moveTo( url, postInfo ) {
		const data = storage.get();

		if ( ! isInternalLink( url ) ) {
			location.url = url;
			return;
		}

		url = getRelativeURL( url );

		if ( ! initialStateSaved ) {
			initialStateSaved = true;
			history.replaceState( this.getPageInfo(), document.title, location.href );
		}

		// move to the new page
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
