<?php
namespace EZWPZ\Theme;
use EZWPZ\Core\Singleton;

class Manager {
  use Singleton;

  protected $layout;

  protected function __construct() {
    $this->layout = Layout::get_instance();
  }
}
