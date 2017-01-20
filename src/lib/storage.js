import Emitter from 'tiny-emitter';

let globalStorage = {};

class Storage extends Emitter {
	get() {
		return globalStorage;
	}
	update( newState ) {
		globalStorage = Object.assign( {}, globalStorage, newState );
		this.emit( 'update' );
	}
}

const storage = new Storage();

export default storage;
