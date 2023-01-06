<?php
namespace Watchthedot\Library\Settings\Field\Choice;

use Watchthedot\Library\Settings\Field\Field;

class TrueFalse extends Field {
	public function build( $name, $value ) {
		?>
		<input
			type="checkbox"
			name="<?php echo esc_attr($name); ?>"
			id="<?php echo esc_attr($name); ?>"
			<?php echo $value === 'on' ? 'checked' : ''; ?>
		>
		<?php
	}

	public function set_default($default_value): self {
		if ($default_value === true || $default_value === 'yes' || $default_value === 'on') {
			$this->default = 'on';
		} else {
			$this->default = '';
		}
		return $this;
	}

	public function sanitize($value) {
		return $value === 'on' ? 'on' : '';
	}
}
