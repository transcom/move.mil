<?php

namespace Drupal\parser\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class XMLReader.
 *
 * Parse an XML file.
 */
class XMLReader {

  protected $entityTypeManager;

  /**
   * XMLReader constructor.
   *
   * Needed for the EntityTypeManager dependency injection.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Parses csv file with php function str_getcsv.
   */
  public function parse($xmlFile) {
    if (!is_file($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" does not exist.', $xmlFile));
    }
    if (!is_readable($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" cannot be read.', $xmlFile));
    }

    $values = [
      'type' => 'location',
    ];
    $nodes = $this->entityTypeManager
      ->getListBuilder('node')
      ->getStorage()
      ->loadByProperties($values);


    foreach (simplexml_load_file($xmlFile)->LIST_G_CNSL_ORG_ID->G_CNSL_ORG_ID as $element) {
      $xmlTitle = !strpos((string) $element->LIST_G_CNSL_INFO->G_CNSL_INFO->CNSL_NAME, ',') ?
                  (string) $element->LIST_G_CNSL_INFO->G_CNSL_INFO->CNSL_NAME :
                  strstr((string) $element->LIST_G_CNSL_INFO->G_CNSL_INFO->CNSL_NAME, ',', TRUE);
      $similarities = [];
      foreach ($nodes as $node) {
        similar_text(strtolower($xmlTitle), strtolower($node->title->getValue()[0]['value']), $perc);
        if ($perc > 70) {
          $similarities[$node->title->getValue()[0]['value']] = $perc;
        }
      }
      $similarity[$xmlTitle] = $similarities;
    }

    dump($similarity);

    die;
    return simplexml_load_file(file($xmlFile));
  }

}