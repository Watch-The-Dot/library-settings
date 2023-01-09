<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings\Field;

class Email extends Text {

	public function __construct( string $key, ?string $label = null ) {
		parent::__construct( $key, $label, 'email' );
	}

	public function sanitize( $value ) {
		return is_email( $value ) ?? '';
	}
}
