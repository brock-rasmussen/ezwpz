<?php

namespace EZWPZ\Core;

trait Singleton
{
	/**
	 * Return a static instance of the class.
	 * @return null|static
	 * @since 1.0.0
	 */
	public static function get_instance()
	{
		static $instance = null;
		if (null === $instance)
			$instance = new static();
		return $instance;
	}

	/**
	 * Singleton constructor.
	 * Protected to prevent creation of a new instance via the `new` operator from outside this class.
	 * @since 1.0.0
	 */
	protected function __construct()
	{
	}

	/**
	 * Private method to prevent cloning of the instance.
	 * @return void
	 * @since 1.0.0
	 */
	private function __clone()
	{
	}

	/**
	 * Private method to prevent unserializing of the instance.
	 * @return void
	 * @since 1.0.0
	 */
	private function __wakeup()
	{
	}

	/**
	 * Private method to prevent serializing of the instance.
	 * @return void
	 * @since 1.0.0
	 */
	private function __sleep()
	{
	}
}
