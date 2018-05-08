<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection as Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class LocationsController.
 */
class LocationsController extends ControllerBase {

  private $databaseConnection;
  protected $entityTypeManager;

  /**
   * Constructs a LocationsController.
   *
   * @param \Drupal\Core\Database\Connection $databaseConnection
   *   A Database Connection object.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager interface.
   */
  public function __construct(Connection $databaseConnection, EntityTypeManagerInterface $entity_type_manager) {
    $this->databaseConnection = $databaseConnection;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Get all search results.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return locations as a Json object
   */
  public function locations() {
    $entities = $this->entityTypeManager->getStorage('node')->loadMultiple();
    foreach ($entities as $entity) {
      dump([$entity->label() => $entity->getEntityTypeId()]);
    }
    $json = '  {
      "name": "PPPO Fort Belvoir / Logistics Readiness Center (LRC)",
      "location": {
        "street_address": "9910 Tracy Loop",
        "extended_address": "Bldg 766, 406th Army Field Support Battalion (AFSB) Army Sustainment Command (ASC)",
        "locality": "Fort Belvoir",
        "region": "Virginia",
        "region_code": "VA",
        "postal_code": "22060",
        "country_name": "United States",
        "country_code": "US",
        "latitude": 38.6891689,
        "longitude": -77.1472925
      },
      "email_addresses": [
        {
          "email_address": "usarmy.belvoir.mbx.outboundpcsing@mail.mil",
          "note": "Customer Service"
        }
      ],
      "phone_numbers": [
        {
          "phone_number": "(703) 805-5674",
          "phone_type": "voice",
          "dsn": false
        }
      ],
      "hours": "0800 - 1130 hrs\n1230 - 1500 hrs\nClosed for lunch 1200-1230 hrs.\nClosed Friday Afternoon",
      "services": [
        "Walk-In Help",
        "Appointments"
      ],
      "note": "Walk-in help is available on a case-by-case basis.",
      "shipping_office":   {
        "name": "JPPSO-MA: Customer Service Management Division",
        "location": {
          "street_address": "9325 Gunston Rd",
          "extended_address": "Ste N110 Bldg. 1466",
          "locality": "Fort Belvoir",
          "region": "Virginia",
          "region_code": "VA",
          "postal_code": "22060",
          "country_name": "United States",
          "country_code": "US",
          "latitude": 38.704505,
          "longitude": -77.148212
        },
        "email_addresses": [
          {
            "email_address": "usarmy.belvoir.imcom.mbx.jppsowa@mail.mil",
            "note": "Customer Service"
          }
        ],
        "phone_numbers": [
          {
            "phone_number": "(703) 806-4900",
            "phone_type": "voice",
            "dsn": false
          },
          {
            "phone_number": "312-656-4900",
            "phone_type": "voice",
            "dsn": true
          }
        ],
        "urls": [
          {
            "url": "http://www.belvoir.army.mil/jppsoma/"
          }
        ],
        "hours": "0800-1600",
        "services": [
          "Walk-In Help",
          "Call Center",
          "QA Inspections"
        ]
      }
    }';
    $data = json_decode($json, TRUE);
    $response = JsonResponse::create($data, 200);
    $response->setEncodingOptions(
      $response->getEncodingOptions() |
      JSON_PRETTY_PRINT |
      JSON_FORCE_OBJECT
    );
    if (gettype($response) == 'object') {
      return $response;
    }
    else {
      return JsonResponse::create('Error while creating response.', 500);
    }
  }

}
