<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings\Field;

class Text extends Field {
	/**
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/Input
	 * @var array<string, array{accept?: string[], reject?: string[], boolean?: true}>
	 */
	private static array $valid_attributes = [
		'autocomplete' => [ 'reject' => [ 'checkbox', 'radio', 'button', 'submit', 'reset' ] ],
		'checked'      => [
			'accept'  => [ 'checkbox', 'radio' ],
			'boolean' => true,
		],
		'dirname'      => [ 'accept' => [ 'search', 'text' ] ],
		'disabled'     => [
			'reject'  => [],
			'boolean' => true,
		],
		'list'         => [ 'reject' => [ 'hidden', 'password', 'checkbox', 'radio', 'button', 'submit', 'reset' ] ],
		'max'          => [ 'accept' => [ 'date', 'month', 'week', 'time', 'datetime-local', 'number', 'range' ] ],
		'maxlength'    => [ 'accept' => [ 'text', 'search', 'url', 'tel', 'email', 'password' ] ],
		'min'          => [ 'accept' => [ 'date', 'month', 'week', 'time', 'datetime-local', 'number', 'range' ] ],
		'minlength'    => [ 'accept' => [ 'text', 'search', 'url', 'tel', 'email', 'password' ] ],
		'multiple'     => [
			'accept'  => [ 'email' ],
			'boolean' => true,
		],
		'pattern'      => [ 'accept' => [ 'text', 'search', 'url', 'text', 'email', 'password' ] ],
		'placeholder'  => [ 'accept' => [ 'text', 'search', 'url', 'text', 'email', 'password', 'number' ] ],
		'readonly'     => [ 'reject' => [ 'hidden', 'range', 'color', 'checkbox', 'radio', 'button', 'submit', 'reset' ] ],
		'required'     => [
			'reject'  => [ 'hidden', 'range', 'color', 'button', 'submit', 'reset' ],
			'boolean' => true,
		],
		'step'         => [ 'accept' => [ 'date', 'month', 'week', 'time', 'datetime-local', 'number', 'range' ] ],
	];

	private string $type;

	/**
	 * @var array<string, string>
	 */
	protected array $attributes = [];

	public function __construct( string $key, ?string $label = null, string $type = 'text' ) {
		parent::__construct( $key, $label );

		$this->type = $type;
	}

	public function set_placeholder( $placeholder ) {
		$this->attributes['placeholder'] = $placeholder;

		return $this;
	}

	public function build( $name, $value ) {
		?>
		<input
			type="<?php echo esc_attr( $this->type ); ?>"
			id="<?php echo esc_attr( $name ); ?>"
			name="<?php echo esc_attr( $name ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php
			foreach ( $this->attributes as $key => $value ) {
				// Restricts the keys to the attributes defined above
				if ( ! isset( self::$valid_attributes[ $key ] ) ) {
					continue;
				}

				$attr_data = self::$valid_attributes[ $key ];

				// Check if the attribute is valid for the type of input
				if (
					( isset( $attr_data['accept'] ) && ! in_array( $this->type, $attr_data['accept'], true ) ) ||
					( isset( $attr_data['reject'] ) && in_array( $this->type, $attr_data['reject'], true ) )
				) {
					continue;
				}

				// If attribute is boolean, output only if the value is truey
				if ( isset( $attr_data['boolean'] ) && $attr_data['boolean'] ) {
					if ( (bool) $value ) {
						// This is already sanitized to the values in the self::$valid_attributes property
						echo sprintf(
							'%s',
							esc_attr( $key ),
						);
					}
				} else {
					// This is already sanitized to the values in the self::$valid_attributes property
					echo sprintf(
						"%s='%s'",
						esc_attr( $key ),
						esc_attr( $value ),
					);
				}
			}
			?>
		>
		<?php
	}

	public function sanitize( $value ) {
		return sanitize_text_field( $value );
	}
}
