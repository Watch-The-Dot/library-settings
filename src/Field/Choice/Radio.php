<?php
namespace Watchthedot\Library\Settings\Field\Choice;

class Radio extends Select {

	public function build( $name, $value ) {
		?>
		<?php foreach ($this->options as $key => $label) : ?>
			<p>
				<label for="<?php echo esc_attr( $name . '_' . $key ); ?>">
					<input
						type="radio"
						name="<?php echo esc_attr( $name ); ?>"
						id="<?php echo esc_attr( $name . '_' . $key); ?>"
						value="<?php echo esc_attr($key); ?>"
						<?php checked($key, $value, true); ?>
					>
					<?php echo esc_html($label); ?>
				</label>
			</p>
		<?php endforeach; ?>
		<?php
	}
}
