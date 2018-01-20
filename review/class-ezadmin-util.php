<?php
class EZAdmin_Util {
  /**
   *
   * @param array $args
   */
  public static function control($args) {
    switch ($args['type']) {
      case 'checkbox':
        break;
      case 'radio':
        break;
      case 'select':
        break;
      case 'textarea':
        break;
      case 'dropdown-pages':
        break;
      default:
        echo '<input type="text">';
        break;
    }
  }
}
