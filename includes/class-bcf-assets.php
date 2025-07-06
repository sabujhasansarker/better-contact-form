<?php
defined( 'ABSPATH' ) || exit;
/**
 * Class BCF_Assets
 *
 * This class handles the registration of scripts and styles for the Better Contact Form plugin.
 */

class BCF_Assets {
	private $assets = [];
	public function __construct() {
		$this->assets = [ 
			'css' => [ 
				'tailwindcss' => [ 
					'src' => '//cdn.tailwindcss.com/3.4.16',
					'deps' => [],
					'ver' => '1.0.0',
					'media' => 'all'
				],
				'bcf-fonts' => [ 
					'src' => '//fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Noto+Sans+Mono:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
					'deps' => [],
					'ver' => '1.0.0',
					'media' => 'all'
				],
				'bcf-fontend' => [ 
					'src' => BCF_PLUGIN_URL . 'assets/css/frontend.css',
					'deps' => [],
					'ver' => BCF_PLUGIN_VERSION,
					'media' => 'all'
				],
			],
			'js' => [ 
				'bfc-frontend' => [ 
					'src' => plugin_dir_url( __FILE__ ) . 'assets/js/frontend.js',
					'deps' => [ 'jquery' ],
					'ver' => '1.0.0',
					'in_footer' => true
				]
			]
		];
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}
	public function enqueue_assets() {
		/** CSS */
		foreach ( $this->assets['css'] as $handle => $asset ) {
			wp_enqueue_style( $handle, $asset['src'], $asset['deps'], $asset['ver'], $asset['media'] );
		}
		/** JS */
		foreach ( $this->assets['js'] as $handle => $asset ) {
			wp_enqueue_script( $handle, $asset['src'], $asset['deps'], $asset['ver'], $asset['in_footer'] );
		}

		wp_localize_script( 'bcf-frontend', 'bcf_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'bcf_submit_form' )
		) );

	}
	public function enqueue_admin_assets( $hook ) {
		if ( strpos( $hook, 'bcf-dashboard' ) !== false || strpos( $hook, 'bcf-form-builder' ) !== false || get_post_type() === 'bcf_submission' ) {
			wp_enqueue_style( 'bcf-admin', BCF_PLUGIN_URL . 'assets/css/admin.css', [], '1.0.0' );
			wp_enqueue_script( 'bcf-admin', BCF_PLUGIN_URL . 'assets/js/admin.js', [ 'jquery' ], '1.0.0', true );
		}
		/** Enqueue Alpine.js for form builder */
		if ( strpos( $hook, 'bcf-form-builder' ) !== false ) {
			wp_enqueue_style( 'bcf-admin', BCF_PLUGIN_URL . 'assets/css/admin.css', [], '1.0.0' );
			wp_enqueue_script( 'alpine-js', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', array(), '3.0.0', true );
			wp_script_add_data( 'alpine-js', 'defer', true );
		}
	}
}

?>