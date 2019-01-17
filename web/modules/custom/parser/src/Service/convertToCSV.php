<?php

$xmlFile = "web/modules/custom/parser/src/Service/locations.xml";
$max_number_phones = 0;
if (!is_file($xmlFile)) {
  throw new \RuntimeException(sprintf('File "%s" does not exist.', $xmlFile));
}
if (!is_readable($xmlFile)) {
  throw new \RuntimeException(sprintf('File "%s" cannot be read.', $xmlFile));
}
$ids = [];
// Report locations parsed.
$locationsParsed = [];
// Get XML offices to update on Drupal.
$xml_offices = simplexml_load_file($xmlFile)->LIST_G_CNSL_ORG_ID->G_CNSL_ORG_ID;
$ppso_ids = [];
// Update each XML offices that is found in Drupal content.
foreach ($xml_offices as $xml_office) {
  $xmlId = (string) $xml_office->CNSL_ORG_ID1;
  if (!empty($ids[$xmlId])) {
    echo 'WARNING overriding...: ' . $xmlId . PHP_EOL;
  }
  $ids[$xmlId] = $location;
  $phones = update_location_phones($xml_office);
  $location = "{$xmlId}, {$phones}" . PHP_EOL;
  $locationsParsed[] = $location;
  echo $location;
  $cnsl = $xml_office->LIST_G_CNSL_INFO->G_CNSL_INFO;
  // Get PPSO info.
  $ppso_id = (string) $cnsl->PPSO_ORG_ID;
  if (empty($ppso_ids[$ppso_id])) {
    echo 'New PPSO ' . PHP_EOL;
    $ppso_phones = ppso_phones($cnsl);
    $ppso = "PPSO [{$ppso_id}, {$ppso_phones}]" . PHP_EOL;
    $ppso_ids[$ppso_id] = $ppso;
    echo $ppso;
  }
}
echo PHP_EOL . 'Total Locations: ' . count($locationsParsed) . PHP_EOL;
echo PHP_EOL . 'Total overrides: ' . (count($locationsParsed) - count($ids)) . PHP_EOL;
echo PHP_EOL . 'Total Locations: ' . count($ppso_ids) . PHP_EOL;

/**
 * Update Drupal Location with the XML phone content.
 *
 * @param \SimpleXMLElement $element
 *   XML file element.
 *
 * @return string
 *   the phone numbers.
 */
function update_location_phones(SimpleXMLElement $element) {
  // Get XML file phone elements.
  $xmlPhoneGroup = $element
    ->LIST_G_CNSL_PHONE_ORG_ID
    ->G_CNSL_PHONE_ORG_ID
    ->LIST_G_CNSL_PHONE_NOTES
    ->G_CNSL_PHONE_NOTES;
  // Exit if xmlPhoneGroup is empty.
  if (empty($xmlPhoneGroup)) {
    return '';
  }
  // Parse XML file phone elements.
  $xml_phone_numbers = '';
  foreach ($xmlPhoneGroup as $phone) {
    $dns = (string) $phone->CNSL_COMM_OR_DSN == 'D' ? 'DSN' : 'COMM';
    $number = $dns == 'DSN' ? (string) $phone->CNSL_DSN_NUM : (string) $phone->CNSL_PHONE_NUM;
    $type = (string) $phone->CNSL_VOICE_OR_FAX == 'V' ? 'voice' : 'fax';
    $parsed_phone = "{$number} ({$dns}) ({$type})";
    $xml_phone_numbers = empty($xml_phone_numbers) ? $parsed_phone : "{$xml_phone_numbers} / {$parsed_phone}";
  }
  return $xml_phone_numbers;
}

/**
 * Update Drupal Location with the XML phone content.
 *
 * @param \SimpleXMLElement $element
 *   XML file element.
 *
 * @return string
 *   the phone numbers.
 */
function ppso_phones(SimpleXMLElement $element) {
  // Get XML file phone elements.
  $xmlPhoneGroup = $element
    ->LIST_G_PPSO_PHONE_ORG_ID
    ->G_PPSO_PHONE_ORG_ID
    ->LIST_G_PPSO_PHONE_NOTES
    ->G_PPSO_PHONE_NOTES;
  // Exit if xmlPhoneGroup is empty.
  if (empty($xmlPhoneGroup)) {
    return '';
  }
  // Parse XML file phone elements.
  $xml_phone_numbers = '';
  foreach ($xmlPhoneGroup as $phone) {
    $dns = (string) $phone->PPSO_COMM_OR_DSN == 'D' ? 'DSN' : 'COMM';
    $number = $dns == 'DSN' ? (string) $phone->PPSO_DSN_NUM : (string) $phone->PPSO_PHONE_NUM;
    $type = (string) $phone->PPSO_VOICE_OR_FAX == 'V' ? 'voice' : 'fax';
    $parsed_phone = "{$number} ({$dns}) ({$type})";
    $xml_phone_numbers = empty($xml_phone_numbers) ? $parsed_phone : "{$xml_phone_numbers} / {$parsed_phone}";
  }
  return $xml_phone_numbers;
}
