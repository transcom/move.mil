<?php

namespace Drupal\react_tools\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'LocatorMapBlock' block.
 *
 * @Block(
 *  id = "locator_map_block",
 *  admin_label = @Translation("Locator map block"),
 * )
 */
class LocatorMapBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['locator_map_block']['#attached']['library'][] = 'react_tools/react-locator-map';
    $build['locator_map_block']['#markup'] = '<div id="locator-map"></div>';

    return $build;
  }

}
