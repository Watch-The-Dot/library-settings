<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings\Field;

class Password extends TextSecret {

	public function __construct( string $key, ?string $label = null ) {
		parent::__construct( $key, $label, 'password' );
	}

	public function get_shroud() {
		return 'password';
	}

	public function sanitize( $value ) {
		return $value;
	}
}
