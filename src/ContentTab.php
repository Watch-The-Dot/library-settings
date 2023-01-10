<?php
declare( strict_types=1 );

namespace Watchthedot\Library\Settings;

class ContentTab extends Tab {

	private $callback;

	public function __construct( string $name, callable $callback ) {
		parent::__construct( $name );

		$this->callback = $callback;
	}

	public function display() {
		?>
		<h2><?php echo esc_html( $this->name ); ?></h2>
		<?php
		call_user_func( $this->callback );
	}
}
