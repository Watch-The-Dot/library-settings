<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings\Field;

use Watchthedot\Library\Settings\SettingsPage;

class Image extends Field {

	private const VERSION = 1;

	private bool $multiple = false;

	public function build( $name, $value ) {
		$images = explode( ',', $value );
		?>
		<div class="image_upload_preview" id="<?php echo esc_attr( $name ); ?>_preview">
			<?php foreach ( $images as $image ) : ?>
				<img src="<?php echo esc_url( wp_get_attachment_thumb_url( $image ) ); ?>" class="image-preview">
			<?php endforeach; ?>
		</div><br>

		<input
			id="<?php echo esc_attr( $name ); ?>_button"
			type="button"
			data-uploader_title="<?php echo esc_attr( 'Choose image' ); ?>"
			data-uploader_button_text="<?php echo esc_attr( 'Use image' ); ?>"
			data-multiple="<?php echo $this->multiple ? '1' : '0'; ?>"
			class="image_upload_button button"
			value="<?php echo esc_attr( 'Choose image' ); ?>"
		>
		<input
			id="<?php echo esc_attr( $name ); ?>"
			class="image_data_field"
			type="hidden"
			name="<?php echo esc_attr( $name ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
		>
		<br>
		<?php
	}

	public function is_multiple(): self {
		$this->multiple = true;

		return $this;
	}

	public function register_scripts() {
		wp_enqueue_media();

		wp_enqueue_script(
			'libsettings-field-image',
			SettingsPage::$assets_url . '/scripts/field/image.js',
			[ 'jquery' ],
			self::VERSION,
			true
		);

		wp_enqueue_style(
			'libsettings-field-image',
			SettingsPage::$assets_url . '/styles/field/image.css',
			[],
			self::VERSION
		);
	}

	public function sanitize( $value ) {
		return implode(
			',',
			array_filter(
				array_map(
					fn( $d ) => is_numeric( $d ) ? intval( $d ) : '',
					explode( ',', $value )
				)
			)
		);
	}
}
