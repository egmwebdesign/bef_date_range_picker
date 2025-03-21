<?php

namespace Drupal\bef_date_range_picker\Plugin\better_exposed_filters\filter;

use Drupal\better_exposed_filters\Plugin\better_exposed_filters\filter\FilterWidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\datetime\Plugin\views\filter\Date;
use Drupal\search_api\Plugin\views\filter\SearchApiDate;

/**
 * Select2 widget implementation.
 *
 * @BetterExposedFiltersFilterWidget(
 *   id = "bef_date_range_picker",
 *   label = @Translation("BefDateRangePicker"),
 * )
 */
class BefDateRangePicker extends FilterWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $config = parent::defaultConfiguration();
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function exposedFormAlter(array &$form, FormStateInterface $form_state): void {
    $field_id = $this->getExposedFilterFieldId();
    $wrapperKey = $field_id . '_wrapper';
    if (!empty($form[$wrapperKey])) {
      $form[$wrapperKey]['#type'] = 'bef_date_range_picker';
      $form[$wrapperKey]['#fieldId'] = $field_id;
      $form[$wrapperKey]['#date-format'] = "YYYY.MM.DD.";
    }
  }

  public static function isApplicable($filter = NULL, array $filter_options = []) {
    // Sanity check to ensure we have a filter to work with.
    if (!isset($filter)) {
      return FALSE;
    }

    if (!($filter instanceof Date) && !($filter instanceof SearchApiDate)) {
      return FALSE;
    }

    if ($filter->operator != 'between') {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    return $form;
  }
}
