<?php

namespace Drupal\locations\Service;

use SimpleXMLElement;

/**
 * Class Reader.
 *
 * Parses the given XML file and returns an array of arrays.
 */
class Reader {

  /**
   * Reads and parses XML location file provided by DoD.
   */
  public static function parse($xmlFile, &$context) {
    \Drupal::messenger()->addMessage('Reading XML file.');
    if (!is_file($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" does not exist.', $xmlFile));
    }
    if (!is_readable($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" cannot be read.', $xmlFile));
    }
    // Get XML offices to update on Drupal.
    $xml_offices = simplexml_load_file($xmlFile)->xpath('LIST_G_CNSL_ORG_ID/G_CNSL_ORG_ID');
    // Initialize batch context sandbox.
    if (empty($context['sandbox'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['offices_count'] = count($xml_offices);
    }
    // Process the next 100 if there are at least 100 left. Otherwise,
    // we process the remaining number.
    $batchSize = 100;
    // Start where we left off last time.
    $nextOffices = array_slice($xml_offices, $context['sandbox']['progress'], $batchSize, TRUE);
    // Parse from XML to array of
    // unique transportation and shipping offices.
    foreach ($nextOffices as $xml_office) {
      $nodeData = Reader::getNodeData($xml_office, FALSE);
      $context['results'][$nodeData['id']] = $nodeData;
      // If PPSO's been added, skip the parsing.
      $ppsoId = $nodeData['ppsoId'];
      if (!empty($ppsoId) && empty($context['results'][$ppsoId])) {
        $ppsoData = Reader::getNodeData($xml_office, TRUE);
        $context['results'][$ppsoId] = $ppsoData;
      }
      // Update our progress!
      $context['sandbox']['progress']++;
    }
    // Inform the batch engine that we are not finished,
    // and provide an estimation of the completion level we reached.
    if ($context['sandbox']['progress'] != $context['sandbox']['offices_count']) {
      $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['offices_count'];
    }
    else {
      $context['finished'] = 1;
      // Sort locations.
      uasort($context['results'], function ($a, $b) {
        // Shipping Offices (PPSOs) go first.
        if ($a['isPPSO'] && !$b['isPPSO']) {
          return -1;
        }
        elseif (!$a['isPPSO'] && $b['isPPSO']) {
          return 1;
        }
        else {
          // If both are from the same type, sort by id.
          return strcmp($a['id'], $b['id']);
        }
      });
    }
  }

  /**
   * Get data needed to fill in a location node.
   *
   * @param \SimpleXMLElement $xml_office
   *   The source data from XML office.
   * @param bool $isPPSO
   *   If it is a PPSO (Shipping Office).
   *
   * @return array
   *   The node data.
   */
  private static function getNodeData(SimpleXMLElement $xml_office, $isPPSO) {
    $officeInfo = $xml_office->LIST_G_CNSL_INFO->G_CNSL_INFO;
    $locType = $isPPSO ? 'PPSO_' : 'CNSL_';
    $node = [];
    $node['isPPSO'] = $isPPSO;
    // Get XML file id element.
    $node['id'] = $isPPSO ? (string) $officeInfo->PPSO_ORG_ID : (string) $xml_office->CNSL_ORG_ID1;
    // Get XML file name element.
    $node['name'] = (string) $officeInfo->{$locType . 'NAME'};
    // Get XML file address element.
    $node['address'] = [
      'country_code' => (string) $officeInfo->{$locType . 'COUNTRY'},
      'address_line1' => (string) $officeInfo->{$locType . 'ADDR1'},
      'address_line2' => (string) $officeInfo->{$locType . 'ADDR2'},
      'locality' => (string) $officeInfo->{$locType . 'CITY'},
      'administrative_area' => (string) $officeInfo->{$locType . 'STATE'},
      'postal_code' => (string) $officeInfo->{$locType . 'ZIP'},
    ];
    // Get XML file email elements.
    $xpath = "LIST_G_{$locType}EMAIL_ORG_ID/G_{$locType}EMAIL_ORG_ID/LIST_G_{$locType}EMAIL/G_";
    $xpath = $xpath . ($isPPSO ? 'ppso_email' : 'CNSL_EMAIL');
    $xmlEmails = $isPPSO ? $officeInfo->xpath($xpath) : $xml_office->xpath($xpath);
    foreach ($xmlEmails as $email) {
      if (!$isPPSO || $email->EMAIL_TYPEP == 'Customer Service') {
        $node['emails'][] = $isPPSO ? (string) $email->EMAIL_ADDRESSP : (string) $email->EMAIL_ADDRESS;
      }
    }
    // Get XML file phone elements.
    $xpath = "LIST_G_{$locType}PHONE_ORG_ID/G_{$locType}PHONE_ORG_ID/LIST_G_{$locType}PHONE_NOTES/G_{$locType}PHONE_NOTES";
    $xmlPhones = $isPPSO ? $officeInfo->xpath($xpath) : $xml_office->xpath($xpath);
    foreach ($xmlPhones as $phone) {
      if (!$isPPSO || $phone->PPSO_PHONE_TYPE == 'Customer Service') {
        $dns = (string) $phone->{$locType . 'COMM_OR_DSN'} == 'D';
        $number = $dns ? (string) $phone->{$locType . 'DSN_NUM'} : (string) $phone->{$locType . 'PHONE_NUM'};
        $type = (string) $phone->{$locType . 'VOICE_OR_FAX'} == 'V' ? 'voice' : 'fax';
        $node['phones'][] = [
          'dns' => $dns,
          'number' => $number,
          'type' => $type,
        ];
      }
    }
    // Get XML file ppso id.
    if (!$isPPSO) {
      $node['ppsoId'] = (string) $officeInfo->PPSO_ORG_ID;
    }
    return $node;
  }

}
