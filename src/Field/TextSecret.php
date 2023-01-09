<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings\Field;

/**
 * TODO: If the value hasn't been set, the value shouldn't be overridden
 */
class TextSecret extends Text {

	public function build( $name, $value ) {
		parent::build( $name, empty( $value ) ? '' : $this->get_shroud() );
	}

	public function get_shroud() {
		return '';
	}

	public function sanitize( $value ) {
		return sanitize_text_field( $value );
	}
}
