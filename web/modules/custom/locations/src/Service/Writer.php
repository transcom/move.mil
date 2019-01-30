<?php

namespace Drupal\locations\Service;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\node\Entity\Node;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SimpleXMLElement;

/**
 * Class Writer.
 *
 * Parse a given array and saves it in Drupal Locations.
 */
class Writer {

  protected $entityTypeManager;
  protected $loggerFactory;

  /**
   * Writer constructor.
   *
   * Needed for the EntityTypeManager dependency injection.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager interface.
   * @param \Drupal\Core\Logger\LoggerChannelFactory $loggerFactory
   *   The logger channel factory.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, LoggerChannelFactory $loggerFactory) {
    $this->entityTypeManager = $entityTypeManager;
    $this->loggerFactory = $loggerFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('logger.factory')
    );
  }

  /**
   * Normalizes data then creates Location nodes.
   *
   * @param \SimpleXMLElement $xml_offices
   *   XML location offices.
   *
   * @throws \Exception
   */
  public function writeFrom(SimpleXMLElement $xml_offices) {
    // Report locations parsed.
    $locationsUpdated = [];
    $locationsCreated = [];
    // Update each XML offices that is found in Drupal content.
    foreach ($xml_offices as $xml_office) {
      $xmlId = (string) $xml_office->CNSL_ORG_ID1;
      $location = $this->getDrupalLocationByCnslId($xmlId);
      if (!empty($location)) {
        $this->updateDrupalLocation($location, $xmlId, $xml_office);
        $locationsUpdated[] = $location->toUrl()->setAbsolute()->toString();
      }
      else {
        $location = $this->createDrupalLocation($xmlId, $xml_office);
        $locationsCreated[] = $location->toUrl()->setAbsolute()->toString();
      }
    }
    $this->loggerFactory
      ->get('locations')
      ->notice(count($locationsUpdated) . ' locations parsed and updated: ' . implode(', ', $locationsUpdated));
    $this->loggerFactory
      ->get('locations')
      ->notice(count($locationsCreated) . ' locations parsed and updated: ' . implode(', ', $locationsCreated));
  }

  /**
   * Delete Location nodes that don't exist in the XML file.
   */
  public function deleteFrom(SimpleXMLElement $xml) {
    try {
      $storageHandler = $this->entityTypeManager->getStorage('node');
    }
    catch (InvalidPluginDefinitionException $e) {
    }
    catch (PluginNotFoundException $e) {
    }
    $nodeEntities = $storageHandler->loadMultiple($db_objs);
    $storageHandler->delete($nodeEntities);
  }

  /**
   * Get Drupal location by its CNSL id.
   *
   * @param string $id
   *   The CNSL id to look in Drupal.
   *
   * @return \Drupal\node\Entity\Node
   *   The node found with CNSL id or NULL.
   *
   * @throws \Exception
   */
  public function getDrupalLocationByCnslId($id) {
    // Get all location entity.
    try {
      $locations = $this->entityTypeManager
        ->getStorage('node')
        ->loadByProperties([
          'type' => 'location',
          'field_location_cnsl_id' => $id,
        ]);
    }
    catch (InvalidPluginDefinitionException $ipde) {
      throw new \Exception('Exception on location node, ' . $ipde->getMessage());
    }
    return current($locations);
  }

  /**
   * Update existing Drupal location.
   *
   * @param \Drupal\node\Entity\Node $location
   *   The Drupal location to update.
   * @param string $id
   *   The CNSL id.
   * @param \SimpleXMLElement $xml_office
   *   The source data from XML office.
   *
   * @throws \Exception
   */
  private function updateDrupalLocation(Node $location, $id, SimpleXMLElement $xml_office) {
    $location = [];
    $location['phones'] = $this->updateLocationPhones($location, $xml_office);
    $location['address'] = $this->updateLocationAddress($location, $xml_office);
    $location['email'] = $this->updateLocationEmails($location, $xml_office);
  }

  /**
   * Update existing Drupal location.
   *
   * @param string $id
   *   The CNSL id.
   * @param \SimpleXMLElement $xml_office
   *   The source data from XML office.
   *
   * @return \Drupal\node\Entity\Node
   *   The Drupal location created.
   *
   * @throws \Exception
   */
  private function createDrupalLocation($id, $xml_office) {
  
  }

}
