<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings;

use Watchthedot\Library\Settings\Field\Field;

class FormTab extends Tab {

	private string $description = '';

	/**
	 * @var Field[]
	 */
	private array $fields = [];

	private string $prefix = '';

	public function add_description( string $description ) {
		$this->description = $description;
	}

	public function add_field( Field $field ): self {
		if ( in_array( $field->get_key(), array_keys( $this->fields ), true ) ) {
			throw new \Exception( "Cannot redefine settings field {$field->get_key()}" );
		}

		$this->fields[ $field->get_key() ] = $field;

		return $this;
	}

	public function display() {
		// Get settings fields.
		settings_fields( $this->prefix . '_settings' );
		do_settings_sections( $this->prefix . '_settings' );
		?>

		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php echo esc_attr( __( 'Save Changes' ) ); ?>">
		</p>
		<?php
	}

	public function register_fields( string $parent ): void {
		add_settings_section(
			$this->key,
			$this->name,
			function () {
				echo '<p>' . esc_html( $this->description ) . '</p>';
			},
			$parent
		);

		foreach ( $this->fields as $field ) {
			$field->register( $parent, $this->key, $this->prefix );
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

	public function get_fields(): array {
		return $this->fields;
	}

	/**
	 * Set the value of prefix
	 *
	 * @return self
	 */
	public function set_prefix( $prefix ): self {
		$this->prefix = $prefix;

		return $this;
	}
}
