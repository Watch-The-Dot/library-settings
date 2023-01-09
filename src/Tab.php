<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings;

use Watchthedot\Library\Settings\Field\Field;

class Tab {

	private string $name;

	private string $key;

	private string $description = '';

	/**
	 * @var Field[]
	 */
	private array $fields = [];

	public function __construct( string $name ) {
		$this->name = $name;
		$this->key  = sanitize_title( $name );
	}

	public function add_description( string $description ) {
		$this->description = $description;
	}

	public function add_field( Field $field ): self {
		$this->fields[] = $field;

		return $this;
	}

	public function register_fields( string $parent, string $prefix ): void {
		add_settings_section(
			$this->key,
			$this->name,
			function () {
				echo '<p>' . esc_html( $this->description ) . '</p>';
			},
			$parent
		);

		foreach ( $this->fields as $field ) {
			$field->register( $parent, $this->key, $prefix );
		}
	}

	public function register_scripts(): void {
		$field_types_run = [];

		foreach ( $this->fields as $field ) {
			$class_name = get_class( $field );

			if ( in_array( $class_name, $field_types_run, true ) ) {
				continue;
			}

			$field->register_scripts();

			$field_types_run[] = $class_name;
		}
	}

	public function get_name(): string {
		return $this->name;
	}

	public function get_key(): string {
		return $this->key;
	}
}
