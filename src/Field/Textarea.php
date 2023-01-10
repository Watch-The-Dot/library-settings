<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings\Field;

class Textarea extends Field {

	private int $cols = 50;

	private int $rows = 5;

	public function build( $name, $value ) {
		?>
		<textarea
			name="<?php echo esc_attr( $name ); ?>"
			id="<?php echo esc_attr( $name ); ?>"
			cols="<?php echo esc_attr( (string) $this->cols ); ?>"
			rows="<?php echo esc_attr( (string) $this->rows ); ?>"
		><?php echo esc_html( $value ); ?></textarea>
		<?php
	}

	public function set_size( int $cols, int $rows ) {
		$this->cols = $cols;
		$this->rows = $rows;
	}

	public function sanitize( $value ) {
		return sanitize_textarea_field( $value );
	}
}
