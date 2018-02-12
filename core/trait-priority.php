<?php
namespace EZWPZ\Core;

trait Priority {
  protected static $instance_count = 0;
  public $instance_number;
  public $priority = 10;
}
