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
function move_mil_preprocess_field(&$variables) {
  switch ($variables['element']['#field_name']) {
    case 'field_video_url':
      $original_url = $variables['items'][0]['content']['#context']['value'];
      $platform = NULL;
      $video_id = FALSE;

      if ( strpos($original_url, 'vimeo') ) {
        $platform = 'vimeo';
        $start = strpos($original_url, '.com') + 5;
        $end = strpos($original_url, '?');

        if($end){
          $length = $end - $start;
          $video_id = substr($original_url, $start, $length);
        }
        else {
          $video_id = substr($original_url, $start);
        }
      }
      elseif ( strpos($original_url, 'youtu') ) {
        $platform = 'youtube';

        if( strpos($original_url, 'v=') ) {
          $start = strpos($original_url, 'v=') + 2;
          $video_id = substr($original_url, $start);
        }
        elseif ( strpos($original_url, 'youtu.be/') ) {
          $start = strpos($original_url, '.be/') + 4;
          $video_id = substr($original_url, $start);
        }
      }

      $variables['video_id'] = $video_id;
      $variables['platform'] = $platform;
      break;
  }
}
