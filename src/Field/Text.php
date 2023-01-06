<?php
namespace Watchthedot\Library\Settings\Field;

class Text extends Field {

	private string $type;

	/**
	 * @var array<string, string>
	 */
	protected array $attributes = [];

	public function __construct(string $key, ?string $label = null, string $type = "text")
	{
		parent::__construct($key, $label);

		$this->type = $type;
	}

	public function set_placeholder( $placeholder ) {
		$this->attributes['placeholder'] = $placeholder;
	}

	public function build( $name, $value ) {
		$attributes = implode(" ", array_map(function ($key, $value) {
			return "{$key}='" . esc_attr( $value ) . "'";
		}, array_keys($this->attributes), array_values($this->attributes)));
		?>
		<input
			type="<?php echo esc_attr( $this->type ); ?>"
			id="<?php echo esc_attr( $name ); ?>"
			name="<?php echo esc_attr( $name ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php echo $attributes; ?>
		>
		<?php
	}

	public function sanitize($value) {
		return sanitize_text_field($value);
	}
}
