<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_block__DELTA__REGION().
 *
 * This made possible by our custom theme suggestion.
 * @see uswds_theme_suggestions_block_alter().
 */
function move_mil_preprocess_block__system_branding_block(&$variables) {
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof \Drupal\node\NodeInterface) {
    $variables['nid'] = $node->id();
  }
  $variables['#cache']['max-age'] = 0;
}
