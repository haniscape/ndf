<?php

namespace Drupal\ndf_services\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\user\Entity\User;
use Drupal\Core\Database\Connection;

class UserService {

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
   * Gets Current User Roles.
   */
  public function getCurrentUserRoles() {
    return $this->currentUser->getRoles();
  }

  /**
   * Gets Current User Role.
   */
  public function getCurrentUserRole() {
    $roles = $this->getCurrentUserRoles();
    if (!empty($roles) && count($roles) > 1) {
      $roles = array_diff( $roles, ['authenticated']);
    }
    return $roles;
  }

  /**
   * Gets Current User Accessible Products.
   */
  public function getCurrentUserAccessibleProducts() {
    // Get current user roles.
    $user_roles = $this->getCurrentUserRoles();

    $roles_term_id = [];
    foreach ($user_roles as $user_role) {
      // Load role taxonomy term.
      $role_term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
        ->loadByProperties(['field_roles_name' => $user_role, 'vid' => 'roles']);

      if (isset($role_term) && !empty($role_term)) {
        $role_term = reset($role_term);
        $role_term_id = $role_term->id();
        $roles_term_id[] = $role_term_id;
      }
    }

    // Get APIDocs that assign to user role.
    $apidocs = \Drupal::entityTypeManager()->getStorage('node') ->loadByProperties([
      'type' => 'apidoc',
      'field_role' => $roles_term_id
    ]);

    $products = [];
    if (isset($apidocs) && !empty($apidocs)) {
      // Get products from APIDoc node and merge it with new array.
      foreach ($apidocs as $apidoc) {
        // Access multiple products.
        foreach ($apidoc->get('field_api_product')->getValue() as $product) {
          $products[$product['target_id']] = $product['target_id'];
        }
      }
    }
    return array_unique($products);
  }

}
