<?php

namespace Drupal\parser\Service;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SimpleXMLElement;

/**
 * Class XMLReader.
 *
 * Parse an XML file.
 */
class XMLReader {

  protected $entityTypeManager;
  protected $paragraphStorage;

  /**
   * XMLReader constructor.
   *
   * Needed for the EntityTypeManager dependency injection.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager interface.
   *
   * @throws \Exception
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
    try {
      $this->paragraphStorage = $this->entityTypeManager->getStorage('paragraph');
    }
    catch (InvalidPluginDefinitionException $ipde) {
      throw new \Exception('Exception on location_telephone paragraph,  ' . $ipde->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Parses xml file with php function SimpleXML.
   *
   * @throws \Exception
   */
  public function parse($xmlFile) {
    if (!is_file($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" does not exist.', $xmlFile));
    }
    if (!is_readable($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" cannot be read.', $xmlFile));
    }
    // Get all telephone paragraphs.
    $phone_paragraphs = $this->paragraphStorage
      ->loadByProperties(['type' => 'location_telephone']);
    $phones = [];
    foreach ($phone_paragraphs as $phone) {
      $phones[$phone->id()] = $phone;
    }
    // Get all locations entities.
    try {
      $locations = $this->entityTypeManager
        ->getStorage('node')
        ->loadByProperties(['type' => 'location']);
    }
    catch (InvalidPluginDefinitionException $ipde) {
      throw new \Exception('Exception on location node, ' . $ipde->getMessage());
    }
    // Get XML offices to update on Drupal.
    $xml_offices = simplexml_load_file($xmlFile)->LIST_G_CNSL_ORG_ID->G_CNSL_ORG_ID;
    // Update each XML offices that is found in Drupal content.
    foreach ($xml_offices as $xml_office) {
      $xmlId = (string) $xml_office->CNSL_ORG_ID1;
      foreach ($locations as $location) {
        // Search by CNSL org id.
        $cnslField = $location->get('field_location_cnsl_id')->getValue();
        if (empty($cnslField)) {
          continue;
        }
        // Loop until the CNSL id is found.
        if ($xmlId != $cnslField[0]['value']) {
          continue;
        }
        // If the CNSL id is found, update the node and go to the next XML id.
        try {
          $this->updateLocationPhones($location, $xml_office, $phones);
          $this->updateLocationAddress($location, $xml_office);
          $this->updateLocationEmails($location, $xml_office);
        }
        catch (EntityStorageException $e) {
          throw new \Exception(
            'An error occurred while trying to update the location entity, ' . $e->getMessage()
          );
        }
        break;
      }
    }
  }

  /**
   * Update Drupal Location with the XML phone content.
   *
   * @param \Drupal\node\Entity\Node $location
   *   Drupal Location entity.
   * @param \SimpleXMLElement $element
   *   XML file element.
   * @param array $phones
   *   All phone paragraphs.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function updateLocationPhones(Node $location, SimpleXMLElement $element, array $phones) {
    // Get XML file phone elements.
    $xmlPhones = $element
      ->LIST_G_CNSL_PHONE_ORG_ID
      ->G_CNSL_PHONE_ORG_ID
      ->LIST_G_CNSL_PHONE_NOTES
      ->G_CNSL_PHONE_NOTES;
    $xml_phone_numbers = [];
    // Parse XML file phone elements.
    foreach ($xmlPhones as $phone) {
      $dns = (string) $phone->CNSL_COMM_OR_DSN == 'D';
      $number = $dns ? (string) $phone->CNSL_DSN_NUM : (string) $phone->CNSL_PHONE_NUM;
      $type = (string) $phone->CNSL_VOICE_OR_FAX == 'V' ? 'voice' : 'fax';
      $xml_phone_numbers[] = [
        'dns' => $dns,
        'number' => $number,
        'type' => $type,
      ];
    }
    // Get this location phones references.
    $phone_references = $location
      ->get('field_location_telephone')
      ->getValue();
    $updatesCount = count($phone_references);
    // Update existing location telephone paragraphs.
    $updates = array_slice($xml_phone_numbers, 0, $updatesCount);
    foreach ($updates as $key => $update) {
      $ref = $phone_references[$key];
      $id = $ref['target_id'];
      $paragraph = $phones[$id];
      $paragraph->set('field_dsn', $update['dns']);
      $paragraph->set('field_phonenumber', $update['number']);
      $paragraph->set('field_type', $update['type']);
      $paragraph->save();
    }
    // Create the new location telephone paragraphs.
    $newones = array_slice($xml_phone_numbers, $updatesCount);
    foreach ($newones as $new) {
      $paragraph = $this->paragraphStorage->create([
        'type' => 'location_telephone',
        'field_dsn' => $new['dns'],
        'field_phonenumber' => $new['number'],
        'field_type' => $new['type'],
      ]);
      $paragraph->save();
      $phone_references[] = [
        'target_id' => $paragraph->id(),
      ];
    }
    $location->set('field_location_telephone', $phone_references);
    $location->save();
  }

  /**
   * Update Drupal Location with the XML address content.
   *
   * @param \Drupal\node\Entity\Node $location
   *   Drupal Location entity.
   * @param \SimpleXMLElement $element
   *   XML file element.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function updateLocationAddress(Node $location, SimpleXMLElement $element) {
    $officeInfo = $element->LIST_G_CNSL_INFO->G_CNSL_INFO;
    $location->set('field_location_address', [
      'country_code' => $officeInfo->CNSL_COUNTRY,
      'address_line1' => $officeInfo->CNSL_ADDR1,
      'address_line2' => $officeInfo->CNSL_ADDR2,
      'locality' => $officeInfo->CNSL_CITY,
      'administrative_area' => $officeInfo->CNSL_STATE,
      'postal_code' => $officeInfo->CNSL_ZIP,
    ]);
    $location->save();
  }

  /**
   * Update Drupal Location with the XML e-mail content.
   *
   * @param \Drupal\node\Entity\Node $location
   *   Drupal Location entity.
   * @param \SimpleXMLElement $element
   *   XML file element.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function updateLocationEmails(Node $location, SimpleXMLElement $element) {
    // Get XML file phone elements.
    $xmlEmails = $element
      ->LIST_G_CNSL_EMAIL_ORG_ID
      ->G_CNSL_EMAIL_ORG_ID
      ->LIST_G_CNSL_EMAIL
      ->G_CNSL_EMAIL;
    // Parse XML file phone elements.
    $xml_emails = [];
    foreach ($xmlEmails as $email) {
      $xml_emails[] = $email->EMAIL_ADDRESS;
    }
    $location->set('field_location_email', $xml_emails);
    $location->save();
  }

}
