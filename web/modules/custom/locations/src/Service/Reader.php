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
  public function parse($xmlFile) {
    if (!is_file($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" does not exist.', $xmlFile));
    }
    if (!is_readable($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" cannot be read.', $xmlFile));
    }
    // Get XML offices to update on Drupal.
    $xml_offices = simplexml_load_file($xmlFile)->xpath('LIST_G_CNSL_ORG_ID/G_CNSL_ORG_ID');
    // Parse from XML to array of
    // unique transportation and shipping offices.
    $allLocations = [];
    foreach ($xml_offices as $xml_office) {
      $nodeData = $this->getNodeData($xml_office, FALSE);
      $allLocations[$nodeData['id']] = $nodeData;
      // If PPSO's been added, skip the parsing.
      $ppsoId = $nodeData['ppsoId'];
      if (!empty($ppsoId) && empty($allLocations[$ppsoId])) {
        $ppsoData = $this->getNodeData($xml_office, TRUE);
        $allLocations[$ppsoId] = $ppsoData;
      }
    }
    // Sort locations.
    uasort($allLocations, function ($a, $b) {
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
    return $allLocations;
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
  private function getNodeData(SimpleXMLElement $xml_office, $isPPSO) {
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
      $type = $isPPSO ? (string) $email->EMAIL_TYPEP : (string) $email->EMAIL_TYPE;
      $address = $isPPSO ? (string) $email->EMAIL_ADDRESSP : (string) $email->EMAIL_ADDRESS;
      $node['emails'][] = $type . '%' . $address;
    }
    // Get XML file phone elements.
    $xpath = "LIST_G_{$locType}PHONE_ORG_ID/G_{$locType}PHONE_ORG_ID/LIST_G_{$locType}PHONE_NOTES/G_{$locType}PHONE_NOTES";
    $xmlPhones = $isPPSO ? $officeInfo->xpath($xpath) : $xml_office->xpath($xpath);
    foreach ($xmlPhones as $phone) {
      $dns = (string) $phone->{$locType . 'COMM_OR_DSN'} == 'D';
      $number = $dns ? (string) $phone->{$locType . 'DSN_NUM'} : (string) $phone->{$locType . 'PHONE_NUM'};
      $voice = (string) $phone->{$locType . 'VOICE_OR_FAX'} == 'V';
      $type = (string) $phone->{$locType . 'PHONE_TYPE'};
      $node['phones'][] = [
        'dns' => $dns,
        'number' => $number,
        'voice' => $voice,
        'type' => empty($type) ? 'Customer Service' : $type,
      ];
    }
    // Get XML file ppso id.
    if (!$isPPSO) {
      $node['ppsoId'] = (string) $officeInfo->PPSO_ORG_ID;
    }
    return $node;
  }

}
