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
  public function defaultConfiguration(): array {
    $config = parent::defaultConfiguration();
    $config['date_format'] = "YYYY-MM-DD";
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
      $form[$wrapperKey]['#date-format'] = $this->configuration['date_format'] ?? "YYYY-MM-DD";

      // drupal 9 deprecation warning fix
      $form[$wrapperKey][$field_id]['min']['#attributes']['type'] = "date";
      $form[$wrapperKey][$field_id]['max']['#attributes']['type'] = "date";
    }
  }

  public static function isApplicable($filter = NULL, array $filter_options = []): bool {
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
  public function buildConfigurationForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['date_format'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Date format'),
      '#description' => $this->t('The date format to use for the date picker. See the <a href="http://momentjs.com/docs/#/displaying/format/">Moment.js documentation</a> for more information.'),
      '#default_value' => $this->configuration['date_format'] ?? "",
    ];
    return $form;
  }
}
