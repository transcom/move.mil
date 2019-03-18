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
   if (!empty($node->get('field_document_file')->entity)) {
     $file = $node->get('field_document_file')->entity;
     $size = $file->getSize();
     $variables['file_url'] = file_create_url($file->getFileUri());
     $variables['file_size'] = format_size($size);
     $variables['file_type'] = $file->getMimeType();
   }
 }
