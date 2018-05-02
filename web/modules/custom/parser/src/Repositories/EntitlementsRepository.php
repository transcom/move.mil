<?php
namespace Drupal\parser\Repositories;

/**
 * Class EntitlementsRepository.
 */
class EntitlementsRepository {

  /**
   * Get all entitlements from the database.
   *
   * @return object
   *   An object containing the loaded entries if found.
   *
   * @see db_select()
   * @see http://drupal.org/node/310075
   */
  public function getall() {
    return
    db_select('parser_entitlements')
      ->fields('parser_entitlements')
      ->execute()
      ->fetchAll();
  }

}
