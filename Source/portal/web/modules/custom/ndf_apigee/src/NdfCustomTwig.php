<?php

namespace Drupal\ndf_apigee;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Drupal\user\Entity\User;

/**
 * Class SdaiaCustom.
 */
class NdfCustomTwig extends AbstractExtension {

  /**
   * Custom twig functions.
   */
  public function getFunctions() {
    return [
      new TwigFunction('is_admin', [$this, 'isAdmin']),
      new TwigFunction('current_user_id', [$this, 'currentUserId']),
      new TwigFunction('current_user_full_name', [$this, 'currentUserFullName']),
      new TwigFunction('current_user_picture', [$this, 'currentUserPicture']),
      new TwigFunction('current_user_account_type', [$this, 'currentUserAccountType']),
      new TwigFunction('current_user_account_status', [$this, 'currentUserAccountStatus']),
      new TwigFunction('current_user_profile', [$this, 'currentUserProfile']),
      new TwigFunction('is_company_owner', [$this, 'isCompanyOwner']),
      new TwigFunction('get_app_displayname', [$this, 'getAppDisplayName']),
      new TwigFunction('apidoc_status_current_user', [$this, 'apidocStatusCurrentUser']),
    ];
  }

  /**
   * Custom twig function.
   */
  public function currentUserId() {
    return \Drupal::currentUser()->id();
  }

  /**
   * Custom twig function.
   */
  public function isAdmin() {
    $current_user = User::load(\Drupal::currentUser()->id());
    return $current_user->hasRole('administrator');
  }

  /**
   * Custom twig function.
   */
  public function currentUserFullName() {
    $uid = \Drupal::routeMatch()->getRawParameter('user');
    if (!empty($uid)) {
      $user = User::load($uid);
    } else {
      $user = User::load(\Drupal::currentUser()->id());
    }

    return $user->get('first_name')->value . ' ' . $user->get('field_user_second_name')->value . ' ' . $user->get('field_user_third_name')->value . ' ' . $user->get('last_name')->value;
  }

  /**
   * CurrentUserPic.
   */
  public function currentUserPicture() {
    $uid = \Drupal::routeMatch()->getRawParameter('user');
    if (!empty($uid)) {
      $user = User::load($uid);
    } else {
      $user = User::load(\Drupal::currentUser()->id());
    }

    if (isset($user) && !empty($user->get('user_picture'))) {
      // Check if user has image.
      if ($user->get('user_picture')->entity != null && !empty($user->get('user_picture')->entity->getFileUri())) {
        // $picture = $user->get('user_picture')->entity->url();
        $picture = $user->user_picture->entity->getFileUri();
        $style = \Drupal::entityTypeManager()->getStorage('image_style')->load('profile_image_150x150');
        $picture = $style->buildUrl($picture);
      } else {
        // Load the default image.
        $pic_field = \Drupal\field\Entity\FieldConfig::loadByName('user', 'user', 'user_picture');
        $default_image = $pic_field->getSetting('default_image');
        $file = \Drupal::service('entity.repository')->loadEntityByUuid('file', $default_image['uuid']);
        if (!empty($file)) {
          $picture = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
        } else {
          $themeHandler = \Drupal::service('theme_handler');
          $themePath = $themeHandler->getTheme($themeHandler->getDefault())->getPath();
          $picture = base_path() . $themePath . '/images/account_default.png';
        }
      }
    }
    return $picture;
  }

  /**
   * Custom twig function.
   */
  public function currentUserAccountType() {
    $uid = \Drupal::routeMatch()->getRawParameter('user');
    if (!empty($uid)) {
      $user = User::load($uid);
    } else {
      $user = User::load(\Drupal::currentUser()->id());
    }

    $account_type = t('User Account');
    $type_color = '#1FA99A';
    $font_color = '#FFFFFF';
    if ($user->hasRole('role_company_owner') || $user->hasRole('role_company_owner_pending')) {
      $account_type = t('Company Owner');
      $type_color = '#045CE0';
      $font_color = '#FFFFFF';
    } elseif ($user->hasRole('role_company_user')) {
      $account_type = t('Company User');
      $type_color = '#FFE168';
      $font_color = '#000000';
    } elseif ($user->hasRole('administrator')) {
      $account_type = t('Administrator');
    }
    elseif ($user->hasRole('role_product_manager')) {
      $account_type = t('Product Manager');
    }
    elseif ($user->hasRole('role_technical_manager')) {
      $account_type = t('Technical Manager');
    }
    elseif ($user->hasRole('role_operation_manager')) {
      $account_type = t('Operation Manager');
    }
    elseif ($user->hasRole('role_portal_manager')) {
      $account_type = t('Portal Manager');
    }
    return ['type' => $account_type, 'color' => $type_color, 'font_color' => $font_color];
  }

  /**
   * Custom twig function.
   */
  public function currentUserAccountStatus() {
    $uid = \Drupal::routeMatch()->getRawParameter('user');
    if (!empty($uid)) {
      $user = User::load($uid);
    } else {
      $user = User::load(\Drupal::currentUser()->id());
    }

    $user_status = $user->get('field_user_workflow_register')->value;
    $status = t('Account Is Pending');
    $color = '#fce83a';
    if (strpos($user_status, '_review') !== false) {
      $status = t('Account Under Review');
      $color = '#ffb302';
    } elseif (strpos($user_status, '_rejected') !== false) {
      $status = t('Account Rejected');
      $color = '#ff3838';
    } elseif (strpos($user_status, '_not_complete') !== false) {
      $status = t('Account Not Completed');
      $color = '#ffb302';
    } elseif (strpos($user_status, '_accepted') !== false) {
      $status = t('Account Accepted');
      $color = '#56f000';
    } elseif (strpos($user_status, '_blocked') !== false) {
      $status = t('Account Blocked');
      $color = '#ff3838';
    } elseif (strpos($user_status, '_activated') !== false) {
      $status = t('Account Activated');
      $color = '#56f000';
    }

    return ['status' => $status, 'color' => $color];
  }

  /**
   * Custom twig function.
   */
  public function currentUserProfile() {
    $uid = \Drupal::routeMatch()->getRawParameter('user');
    if (!empty($uid)) {
      $user = User::load($uid);
    } else {
      $user = User::load(\Drupal::currentUser()->id());
    }

    $profile_list = \Drupal::entityTypeManager()
      ->getStorage('profile')
      ->loadByProperties([
        'uid' => $user->id(),
        'type' => 'profile_company',
      ]);

    $profile_object = current($profile_list);
    $profile = [
      'org_name_ar' => $profile_object->field_pr_company_org_name_ar->value,
      'org_name_en' => $profile_object->field_pr_company_org_name_en->value,
      'industry_type' => $profile_object->field_pr_company_industry_type->value,
      'org_700_num' => $profile_object->field_pr_company_org_700_num->value,
      'org_phone_num' => $profile_object->field_pr_company_org_phone_num->value,
    ];

    return $profile;
  }

  /**
   * Custom twig function.
   */
  public function isCompanyOwner() {
    $uid = \Drupal::routeMatch()->getRawParameter('user');
    if (!empty($uid)) {
      $user = User::load($uid);
    } else {
      $user = User::load(\Drupal::currentUser()->id());
    }

    return $user->hasRole('role_company_owner') || $user->hasRole('role_company_owner_pending');
  }

  /**
   * Custom twig function.
   */
  public function getAppDisplayName($id) {
    $app = \Drupal::entityTypeManager()->getStorage('developer_app')->loadByProperties(['id' => $id]);
    $current_app = reset($app);
    return $current_app->getDisplayName();
  }

  /**
   * Custom twig function.
   */
  public function apidocStatusCurrentUser($nid) {
    return \Drupal::service('sdaia_subscribe.sdaia_subscribe_service')->getCanUserSubscribtionStatus(\Drupal::currentUser(),\Drupal\node\Entity\Node::load($nid));
  }



}
