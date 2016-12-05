<?php

function ct_get_value( $ary, $key, $default = null ) {
	return ! empty( $ary[ $key ] ) ? $ary[ $key ] : $default;
}

