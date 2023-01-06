<?php
namespace Watchthedot\Library\Settings\Field;

abstract class Field {

	protected string $key;

	protected $default = false;

	private string $label;

	public function __construct( string $key, ?string $label = null ) {
		if (is_null($label)) {
			$label = $key;
			$key = sanitize_key($label);
		}

		$this->key = $key;
		$this->label = $label;
	}

	public function set_default( $default_value ): self {
		$this->default = $default_value;

		return $this;
	}

	public function register( string $parent, string $tab, string $prefix ): void {
		register_setting(
			$parent,
			$prefix . '_' . $this->key,
			[ 'sanitize_callback' => [ $this, 'sanitize' ] ]
		);

		add_settings_field(
			$prefix . '_' . $this->key,
			$this->label,
			fn ( $args ) => $this->pre_build( $args ),
			$parent,
			$tab,
			[ 'prefix' => $prefix ]
		);
	}

	private function pre_build( array $args ) {
		$option_name = $args['prefix'] . '_' . $this->key;

		$current_value = get_option( $option_name, $this->default );

		$this->build($option_name, $current_value);
	}

	public abstract function build( $name, $value );

	public abstract function sanitize( $value );

	public function register_scripts() {
		// NO-OP
	}
}
