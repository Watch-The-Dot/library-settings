<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings\Field;

abstract class Field {

	protected string $key;

	protected $default = false;

	private string $label;

	private string $value;

	/** @var (callable(string, mixed|null, mixed): void)[] */
	private array $on_update_callbacks = [];

	/**
	 * @template T
	 * @var (callable(T, T|null): T)[]
	 */
	private array $filter_value_callbacks = [];

	public function __construct( string $key, ?string $label = null ) {
		if ( is_null( $label ) ) {
			$label = $key;
			$key   = sanitize_key( $label );
		}

		$this->key   = $key;
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

		$option_name = $prefix . '_' . $this->key;
		foreach ( $this->filter_value_callbacks as $callback ) {
			add_action( "pre_update_option_{$option_name}", fn ( $value, $old_value ) => call_user_func( $callback, $value, $old_value ), 10, 2 );
		}

		foreach ( $this->on_update_callbacks as $callback ) {
			add_action( "add_option_{$option_name}", fn ( $_, $value ) => call_user_func( $callback, $this->key, null, $value ), 10, 2 );

			add_action( "update_option_{$option_name}", fn ( $old, $new ) => call_user_func( $callback, $this->key, $old, $new ), 10, 2 );
		}
	}

	private function pre_build( array $args ) {
		$option_name = $args['prefix'] . '_' . $this->key;

		$current_value = get_option( $option_name, $this->default ) ?: $this->default;

		$this->build( $option_name, $current_value );
	}

	abstract public function build( $name, $value );

	abstract public function sanitize( $value );

	public function filter_value( $callback ): self {
		$this->filter_value_callbacks[] = $callback;

		return $this;
	}

	public function on_update( $callback ): self {
		$this->on_update_callbacks[] = $callback;

		return $this;
	}

	public function register_scripts() {
		// NO-OP
	}

	/**
	 * Get the value of key
	 */
	public function get_key() {
		return $this->key;
	}

	public function get_value( string $prefix ) {
		if ( ! isset( $this->value ) ) {
			$this->value = $this->sanitize( get_option( $prefix . '_' . $this->key, $this->default ) );
		}

		return $this->value;
	}
}
