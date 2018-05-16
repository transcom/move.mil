<?php

namespace Drupal\react_tools\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ReactPpmToolBlock' block.
 *
 * @Block(
 *  id = "react_ppm_tool_block",
 *  admin_label = @Translation("React PPM Tool Block"),
 * )
 */
class ReactPpmToolBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['react_ppm_tool_block']['#attached']['library'][] = 'react_tools/react-ppm-tool';
    $build['react_ppm_tool_block']['#markup'] = '<div id="ppm-tool"></div>';
    return $build;
  }

}
