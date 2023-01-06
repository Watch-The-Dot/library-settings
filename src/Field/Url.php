<?php
namespace Watchthedot\Library\Settings\Field;

class Url extends Text {

	private array $valid_protocols;

	public function __construct(string $key, ?string $label = null)
	{
		parent::__construct($key, $label, 'url');

		$this->valid_protocols = wp_allowed_protocols();
	}

	public function set_valid_protocols(array $valid_protocols) {
		$this->valid_protocols = $valid_protocols;
	}

	public function sanitize($value) {
		return sanitize_url($value, $this->valid_protocols);
	}
}
