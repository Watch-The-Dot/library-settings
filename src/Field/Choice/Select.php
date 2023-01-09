<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings\Field\Choice;

use Watchthedot\Library\Settings\Field\Field;

class Select extends Field {

	/**
	 * @var array<string, string>
	 */
	protected array $options = [];

	/**
	 * @todo On PHP 8.1, investigate support for enums
	 *
	 * @var array<string, string>
	 */
	public function set_options( array $options ): self {
		$options = array_combine(
			array_map( 'sanitize_key', array_keys( $options ) ),
			array_map( 'sanitize_text_field', array_values( $options ) )
		);

		$this->options = $options;
		$this->default = array_keys( $options )[0];

		return $this;
	}

	public function build( $name, $value ) {
		?>
		<select
			name="<?php echo esc_attr( $name ); ?>"
			id="<?php echo esc_attr( $name ); ?>">
			<?php foreach ( $this->options as $key => $label ) : ?>
				<option
					value="<?php echo esc_attr( $key ); ?>"
					<?php selected( $key, $value, true ); ?>
					>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	public function sanitize( $value ) {
		return isset( $this->options[ $value ] ) ? $value : $this->default;
	}
}
