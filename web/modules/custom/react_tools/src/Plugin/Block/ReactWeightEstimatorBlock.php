<?php

namespace Drupal\react_tools\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ReactWeightEstimatorBlock' block.
 *
 * @Block(
 *  id = "react_weight_estimator_block",
 *  admin_label = @Translation("React weight estimator block"),
 * )
 */
class ReactWeightEstimatorBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['weight_estimator_block']['#attached']['library'][] = 'react_tools/react-weight-estimator';
    $build['weight_estimator_block']['#markup'] = '<div id="weight-estimator"></div>';

    return $build;
  }

}
