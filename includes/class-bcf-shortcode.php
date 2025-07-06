<?php

defined( 'ABSPATH' ) || exit;

class BCF_Shortcode {
	public function __construct() {
		add_shortcode( 'better-contact-form', array( $this, 'render_shortcode' ) );
	}

	public function render_shortcode() {
		ob_start();
		$form_data = $this;
		include BCF_PLUGIN_PATH . 'includes/better-contact-form.php';
		return ob_get_clean();
	}

	private function get_form_fields() {
		return [ 
			[ 
				'type' => 'text',
				'label' => 'Name',
				'name' => 'name',
				'placeholder' => 'Enter your name',
				'required' => true,
			],
			[ 
				'type' => 'email',
				'label' => 'Email',
				'name' => 'email',
				'placeholder' => 'Enter your email',
				'required' => true,
			],
			[ 
				'type' => 'textarea',
				'label' => 'Message',
				'name' => 'message',
				'placeholder' => 'Enter your message',
				'required' => true,
			],
		];
	}
}