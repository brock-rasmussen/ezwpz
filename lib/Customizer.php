<?php

namespace EZWPZ;

use EZWPZ\Core\Singleton;

class Customizer {
	use Singleton;

	/**
	 * Control types.
	 * @since 1.0.0
	 * @var array
	 */
	protected $control_types = [];

	/**
	 * Constructor.
	 * @since 1.0.0
	 */
	public function __construct() {
		require_once 'Customizer/functions.php';

		$this->control_types = apply_filters( 'ezwpz_customizer_control_types', [
			'code' => \WP_Customize_Code_Editor_Control::class,
			'date-time' => \WP_Customize_Date_Time_Control::class,
			'background-position' => \WP_Customize_Background_Position_Control::class,
			'media' => \WP_Customize_Media_Control::class,
			'color' => \WP_Customize_Color_Control::class,
		] );
	}

	public function get_control_types() {
		return $this->control_types;
	}
}
