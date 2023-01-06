<?php
namespace Watchthedot\Library\Settings;

class SettingsPage {

	public const VERSION = "1.0.0";

	public static $assets_url;

	private string $plugin_file;

	private string $title;

	/**
	 * This is the prefix to all keys in the wp_options table
	 */
	private string $prefix;

	private array $position;

	/**
	 * @var Tab[]
	 */
	private array $tabs = [];

	public function __construct( string $file, string $title, string $prefix ) {
		$this->plugin_file = $file;
		$plugin_directory = dirname($this->plugin_file);

		self::$assets_url = plugins_url(str_replace($plugin_directory, "", dirname(__DIR__)) . '/assets', $file);

		$this->title = $title;
		$this->prefix = $prefix;

		if (str_ends_with($this->prefix, '_')) {
			$this->prefix = substr($this->prefix, 0, strlen($this->prefix) - 1);
		}

		$this->position = [
			'location' => 'options', // Possible settings: options, menu, submenu.
			'parent_slug' => 'options-general.php',
			'page_title' => $this->title,
			'menu_title' => $this->title,
			'capability' => 'manage_options',
			'menu_slug' => $this->prefix . '_settings',
			'function' => fn () => $this->show_page(),
			'icon_url' => '',
			'position' => null,
		];

		add_action( 'admin_init', fn () => $this->register_settings() );

		add_action( 'admin_menu', fn () => $this->add_menu_item() );
	}

	public function add_tab( Tab $tab ) {
		$this->tabs[] = $tab;
	}

	/**
	 * TODO: Move to different functions
	 */
	public function set_position( array $position ) {
		$this->position = $position;
	}

	/* ==== WordPress Actions and Filters ==== */

	private function register_settings(): void {
		if (empty($this->tabs)) return;

		$current_tab = $this->get_current_tab();

		$current_tab->register_fields( $this->position['menu_slug'], $this->prefix );
	}

	private function add_menu_item(): void {
		$args = $this->position;

		// Do nothing if wrong location key is set.
		if ( is_array( $args ) && isset( $args['location'] ) && function_exists( 'add_' . $args['location'] . '_page' ) ) {
			switch ( $args['location'] ) {
				case 'options':
				case 'submenu':
					$page = add_submenu_page( $args['parent_slug'], $args['page_title'], $args['menu_title'], $args['capability'], $args['menu_slug'], $args['function'] );
					break;
				case 'menu':
					$page = add_menu_page( $args['page_title'], $args['menu_title'], $args['capability'], $args['menu_slug'], $args['function'], $args['icon_url'], $args['position'] );
					break;
				default:
					return;
			}
			add_action( 'admin_print_styles-' . $page, fn () => $this->add_scripts() );
		}
	}

	private function add_scripts(): void {
		$this->get_current_tab()->register_scripts();
	}

	private function show_page(): void {
		?>
		<div class="wrap" id="<?php echo esc_attr( $this->prefix ); ?>_settings">
			<h2><?php echo esc_html( $this->title ); ?></h2>
			<?php if (count($this->tabs) === 0) : ?>
				<p>No settings have been defined</p>
			<?php else: ?>
				<?php $current_tab_slug = $this->get_current_tab_slug(); ?>
				<?php $this->show_tabs( $current_tab_slug ); ?>
				<form method="post" action="options.php" enctype="multipart/form-data">
					<?php
						// Get settings fields.
						settings_fields( $this->prefix . '_settings' );
						do_settings_sections( $this->prefix . '_settings' );
					?>

					<p class="submit">
						<input type="hidden" name="tab" value="<?php echo esc_attr( $current_tab_slug ); ?>">
						<input name="Submit" type="submit" class="button-primary" value="<?php echo esc_attr( __( 'Save Changes', 'wordpress' ) ); ?>">
					</p>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	private function show_tabs( string $current_tab_slug ): void {
		if ( count( $this->tabs ) === 1 ) return;
		$c = 0;
		?>
		<h2 class="nav-tab-wrapper">
			<?php foreach ( $this->tabs as $tab ) : ?>
				<?php
				// Set tab class.
				$class = 'nav-tab';
				$class .= ( empty($current_tab_slug) && $c === 0 || $current_tab_slug === $tab->get_key() ) ? ' nav-tab-active' : '';

				// Set tab link.
				$tab_link = add_query_arg( [ 'tab' => $tab->get_key() ] );

				// Only being used as a flag therefore nonce not required.
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( isset( $_GET['settings-updated'] ) ) {
					$tab_link = remove_query_arg( 'settings-updated', $tab_link );
				}

				// Output tab.
				++$c;
				?>
				<a 	href="<?php echo esc_url( $tab_link ); ?>"
					class="<?php echo esc_attr( $class ); ?>">
					<?php echo esc_html( $tab->get_name() ); ?>
				</a>
			<?php endforeach; ?>
		</h2>
		<?php
	}

	private function get_current_tab(): Tab {
		$tab_slugs = array_map(fn (Tab $tab) => $tab->get_key(), $this->tabs);

		$tabs_by_slugs = array_combine($tab_slugs, $this->tabs);

		return $tabs_by_slugs[ $this->get_current_tab_slug() ] ?? $this->tabs[0] ?? null;
	}

	private function get_current_tab_slug(): string {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_tab_slug = sanitize_key( $_POST['tab'] ?? $_GET['tab'] ?? '' );

		$tab_slugs = array_map(fn (Tab $tab) => $tab->get_key(), $this->tabs);

		if ( ! in_array( $current_tab_slug, $tab_slugs, true ) ) {
			$current_tab_slug = '';
		}

		return $current_tab_slug;
	}
}
