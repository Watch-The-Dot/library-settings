<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings\Field;

use Watchthedot\Library\Settings\SettingsPage;

class Color extends Field {

	private const VERSION = 1;

	protected $default = '#ffffff';

	public function build( $name, $value ) {
		?>
		<div class="colorpicker-wrapper">
			<input type="text" name="<?php echo esc_attr( $name ); ?>" class="color" value="<?php echo esc_attr( $value ); ?>" />
			<div class="colorpicker"></div>
		</div>
		<?php
	}

	public function register_scripts() {
		wp_enqueue_script( 'farbtastic' );
		wp_enqueue_script(
			'libsettings-field-color',
			SettingsPage::$assets_url . '/scripts/field/color.js',
			[ 'jquery', 'farbtastic' ],
			self::VERSION,
			true
		);

		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_style(
			'libsettings-field-color',
			SettingsPage::$assets_url . '/styles/field/color.js',
			[ 'farbtastic' ],
			self::VERSION,
			true
		);
	}

	public function sanitize( $value ) {
		return sanitize_hex_color( $value );
	}
}
