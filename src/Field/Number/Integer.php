<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings\Field\Number;

use Watchthedot\Library\Settings\Field\Text;

class Integer extends Text {

	public function __construct( string $key, ?string $label = null ) {
		parent::__construct( $key, $label, 'number' );

		$this->attributes['step'] = 1;
	}

	public function set_min( $min_value ) {
		if ( ! is_numeric( $min_value ) ) {
			return;
		}

		$this->attributes['min'] = $min_value;
	}

	public function set_max( $max_value ) {
		if ( ! is_numeric( $max_value ) ) {
			return;
		}

		$this->attributes['max'] = $max_value;
	}

	public function sanitize( $value ) {
		return is_numeric( $value ) ? intval( $value ) : ( $this->default ?? 0 );
	}
}
