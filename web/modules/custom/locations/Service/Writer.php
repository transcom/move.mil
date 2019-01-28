<?php

namespace Drupal\locations\Service;

use Drupal\node\Entity\Node;
use Drupal\Core\Database\Connection;

/**
 * Class Writer.
 *
 * Parse a given array and saves it in Drupal Locations.
 */
class Writer {

  protected $entity;
  protected $db;

  /**
   * Writer constructor.
   */
  public function __construct(Connection $db) {
    $this->db = $db;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Normalizes data then creates Location nodes.
   */
  public function write(array $rawdata) {
    $location_type = NULL;
    $error = '';
    foreach ($rawdata as $key => $file) {
      switch ($key) {
        case 0:
          $query_term = 'Shipping Office';
          break;

        case 1:
          $query_term = 'Transportation Office';
          break;

        case 2:
          $query_term = 'Weight Scale';
          break;

        default:
          throw new \RuntimeException(sprintf('Unknown file key ["%s"].', $key));
      }

      $location_type = $this->db
        ->select('taxonomy_term_field_data', 't')
        ->fields('t', ['tid'])
        ->condition('name', $query_term, '=')
        ->execute()
        ->fetchField();

      if (!$this->isEmpty($location_type)) {
        $error .= "{$query_term} file is already parsed. Remove all Location nodes and try again." . PHP_EOL;
        continue;
      }

      foreach (json_decode($file) as $obj) {
        $node_ref = (property_exists($obj, 'shipping_office_name')) &&
        ($obj->shipping_office_name != NULL) ?
          $this->db
            ->select('node_field_data', 'n')
            ->fields('n', ['nid'])
            ->condition('title', $obj->shipping_office_name, '=')
            ->execute()
            ->fetchField() : NULL;

        $emails = property_exists($obj, 'email_addresses') ?
            array_map(function ($mail) {
              return $mail->email_address;
            }, $obj->email_addresses) : NULL;

        $urls = NULL;
        if (property_exists($obj, 'urls')) {
          if (is_array($obj->urls)) {
            $urls = array_map(function ($links) {
              return ['uri' => $links->url, 'title' => ''];
            }, $obj->urls);
          }
          else {
            $urls = $obj->urls->url;
          }
        }

        $phone_numbers = NULL;
        if (property_exists($obj, 'phone_numbers')) {
          if (is_array($obj->phone_numbers)) {
            $phone_numbers = array_map(function ($phone) {
                return Paragraph::create([
                  'type' => 'location_telephone',
                  'field_dsn' => $phone->dsn,
                  'field_phonenumber' => $phone->phone_number,
                  'field_type' => $phone->phone_type,
                ]);
            }, $obj->phone_numbers);
          }
          else {
            $phone_numbers = Paragraph::create([
              'type' => 'location_telephone',
              'field_dsn' => $obj->phone_numbers->dsn,
              'field_phonenumber' => $obj->phone_numbers->phone_number,
              'field_type' => $obj->phone_numbers->phone_type,
            ]);
          }
        }

        $hours = property_exists($obj, 'hours') ? $obj->hours : NULL;
        $note = property_exists($obj, 'note') ? $obj->note : NULL;
        $services = property_exists($obj, 'services') ? $obj->services : NULL;

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
          'field_location_hours'      => $hours,
          'field_location_link'       => $urls,
          'field_location_note'       => $note ,
          'field_location_reference'  => [
            'target_id' => $node_ref,
            'target_type' => "node",
          ],
          'field_location_services'   => $services ,
          'field_location_telephone'  => $phone_numbers,
          'field_location_type'       => [
            'target_id' => $location_type,
            'target_type' => "taxonomy_term",
          ],
        ]);
        $node->save();
      }
    }
    if ($error) {
      throw new \RuntimeException($error);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function isEmpty($location_type) {
    $db_objs = $this->db
      ->select('node__field_location_type', 'n')
      ->fields('n')
      ->condition('n.bundle', 'location', '=')
      ->condition('n.field_location_type_target_id', $location_type, '=')
      ->execute()
      ->fetchAll();

    if (count($db_objs) > 0) {
      return FALSE;
    }
    return TRUE;
  }

}
