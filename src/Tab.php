<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings;

abstract class Tab {

	protected string $name;

	protected string $key;

	public function __construct( string $name, string $key = null ) {
		if ( is_null( $key ) ) {
			$key = sanitize_title( $name );
		}

		$this->name = $name;
		$this->key  = $key;
	}

	abstract public function display();

	public function register_scripts() {
		// NO-OP
	}

	public function get_name(): string {
		return $this->name;
	}

	public function get_key(): string {
		return $this->key;
	}
}
