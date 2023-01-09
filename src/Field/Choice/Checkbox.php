<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings\Field\Choice;

use Watchthedot\Library\Settings\Field\Choice\Select;

class Checkbox extends Select {

	public function build( $name, $value ) {
		?>
		<?php foreach ( $this->options as $key => $label ) : ?>
			<p>
				<label for="<?php echo esc_attr( $name . '_' . $key ); ?>">
					<input
						type="checkbox"
						name="<?php echo esc_attr( $name ); ?>[]"
						id="<?php echo esc_attr( $name . '_' . $key ); ?>"
						value="<?php echo esc_attr( $key ); ?>"
						<?php echo in_array( $key, (array) $value, true ) ? 'checked' : ''; ?>
					>
					<?php echo esc_html( $label ); ?>
				</label>
			</p>
		<?php endforeach; ?>
		<?php
	}

	public function sanitize( $value ) {
		return array_intersect(
			$value,
			array_keys( $this->options )
		);
	}
}
