<?php

namespace Drupal\ndf_services\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\user\Entity\User;

/**
 * Service description.
 */
class ProductService {

  /**
   * @var \Drupal\user\Entity\User
   */
  protected $currentUser;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * User service constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   *   The current user service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, AccountProxyInterface $currentUser)
  {
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = User::load($currentUser->id());
  }

  /**
   * Get api products based on api type.
   *
   * @param string $type
   *    Type of api_doc [Sandbox,Production].
   * @return array $products.
   *    Return list of unique api products.
   */
  public function getApiProducts(string $type): array {
    // Get APIDocs that assign to user role.
    $api_docs = \Drupal::entityTypeManager()->getStorage('node') ->loadByProperties([
      'type' => 'apidoc',
      'field_type' => $type
    ]);

    $products = [];
    if (isset($api_docs) && !empty($api_docs)) {
      // Get products from APIDoc node and merge it with new array.
      foreach ($api_docs as $api_doc) {
        // Access multiple products.
        foreach ($api_doc->get('field_api_product')->getValue() as $product) {
          $products[$product['target_id']] = $product['target_id'];
        }
      }
    }
    return array_unique($products);
  }

}
