import Emitter from 'tiny-emitter';
import assign from 'lodash/assign';

let globalStorage = {};

class Storage extends Emitter {
	get() {
		return globalStorage;
	}
	update( newState ) {
		globalStorage = assign( {}, globalStorage, newState );
		this.emit( 'update' );
	}
}

const storage = new Storage();

export default storage;
