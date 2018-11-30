<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_node().
 */

 function move_mil_preprocess_node__sme_document__teaser(&$variables){
   $node = $variables['node'];
   $file = $node->get('field_document_file')->entity;
   $size = $file->getSize();
   $variables['filesize'] = format_size($size);
 }
