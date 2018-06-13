<?php

namespace Drupal\react_tools\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'EntitlementsBlock' block.
 *
 * @Block(
 *  id = "entitlements_block",
 *  admin_label = @Translation("Entitlements block"),
 * )
 */
class EntitlementsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['entitlements_block']['#attached']['library'][] = 'react_tools/entitlements';
    $build['entitlements_block']['#markup'] = '<div id="entitlements-block"></div>';

    return $build;
  }

}
