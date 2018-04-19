<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_breadcrumb().
 */
use Drupal\Core\Template\Attribute;

function move_mil_preprocess_breadcrumb(&$variables){
  if(($node = \Drupal::routeMatch()->getParameter('node')) && $variables['breadcrumb']){
    $variables['breadcrumb'][] = [
      'text' => $node->getTitle(),
      'url'   => \Drupal::service('path.current')->getPath(),
    ];
    $variables['#cache']['contexts'][] = 'url';
  }
}
