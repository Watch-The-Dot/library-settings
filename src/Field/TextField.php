<?php
namespace Watchthedot\Library\Settings\Field;

class TextField extends Field {
	public function build( $name, $value ) {
		?>
		<input
			type="text"
			id="<?php echo $name; ?>"
			name="<?php echo $name; ?>"
			value="<?php echo $value; ?>"
		>
		<?php
	}

	public function sanitize($value) {
		return sanitize_text_field($value);
	}
}
