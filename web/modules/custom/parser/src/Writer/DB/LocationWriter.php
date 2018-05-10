<?php

namespace Drupal\parser\Writer\DB;

use Drupal\parser\Writer\WriterInterface;
use Drupal\Console\Core\Style\DrupalStyle;
use Drupal\node\Entity\Node;

/**
 * Class LocationWriter.
 *
 * Parse a given array and saves it in a custom table.
 */
class LocationWriter implements WriterInterface {
  use DBWriter;

  /**
   * Normalizes data then creates Location nodes.
   */
  public function write(array $rawdata, $truncate, DrupalStyle $io) {
    foreach ($rawdata as $key => $file) {
      foreach (json_decode($file) as $obj) {
        if ($obj->shipping_office_name) {
          $query = $this->getDatabaseConnection()
            ->select('node_field_data', 'n')
            ->condition('n.title', $obj->shipping_office_name, '=')
            ->fields('n', ['nid'])
            ->execute();
          $ref = $query->fetchField();
        }

          $emails = property_exists($obj, 'email_addresses')?
            array_map(function ($mail) {
              return $mail->email_address;
            }, $obj->email_addresses) : null;

        $urls = property_exists($obj, 'email_addresses') ?
          array_map(function ($links) {
            return [ 'uri' => $links->url, 'title' => '',];
          }, $obj->urls) : null;

        $phone_numbers = property_exists($obj, 'phone_numbers') ?
          array_map(function ($phone) {
            return $phone->phone_number;
          }, $obj->phone_numbers) : null;

        $node = Node::create([
          'title'                     => $obj->name,
          'type'                      => 'location',
          'field_geolocation'         => [
            'lat' => $obj->location->latitude,
            'lng' => $obj->location->longitude,
          ],
          'field_location_address'    => [
            'country_code' => $obj->location->country_code,
            'address_line1' => $obj->location->street_address,
            'address_line2' => $obj->location->extended_address,
            'locality' => $obj->location->locality,
            'administrative_area' => $obj->location->region_code,
            'postal_code' => $obj->location->postal_code,
          ],
          'field_location_email'      => $emails,
          'field_location_hours'      => property_exists($obj->hours) ? $obj->hours : null ,,
          'field_location_link'       => $urls,
          'field_location_note'       => property_exists($obj->note) ? $obj->note : null ,
          'field_location_reference'  => [
            'target_id' => $ref ? $ref : null,
            'target_type' => "node",
          ],
          'field_location_services'   => property_exists($obj->services) ? $obj->services : null ,
          'field_location_telephone'  => $phone_numbers,
          'field_location_type'       => [
            'target_id' => 11 - $key,
            'target_type' => "taxonomy_term",
          ],
        ]);
        $node->save();
      }
    }
  }

}
