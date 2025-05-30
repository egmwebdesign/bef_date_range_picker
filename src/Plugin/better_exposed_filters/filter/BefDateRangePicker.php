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
    $config['future_ranges'] = FALSE;
    $config['custom_ranges'] = "";
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
      $form[$wrapperKey]['#future_ranges'] = $this->configuration['future_ranges'] ?? FALSE;
      $form[$wrapperKey]['#custom_ranges'] = $this->configuration['custom_ranges'] ?? "";

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

    $allowedFilters = [
      Date::class,
    ];

    if (class_exists(SearchApiDate::class)) {
      $allowedFilters[] = SearchApiDate::class;
    }

    if (class_exists(\Drupal\smart_date\Plugin\views\filter\Date::class)) {
      $allowedFilters[] = \Drupal\smart_date\Plugin\views\filter\Date::class;
    }

    if (!in_array(get_class($filter), $allowedFilters)) {
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

    $form['future_ranges'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Future ranges'),
      '#description' => $this->t('Allow future date ranges.'),
      '#default_value' => $this->configuration['future_ranges'] ?? FALSE,
    ];

    $form['custom_ranges'] = [
      '#type' => 'textarea',
      '#title' => t('Custom ranges'),
      '#description' => t('Label,+-interval_start_day,+-interval_end_day. One range per line. Example: "Last 7 days,-7,0"'),
      '#default_value' => $this->configuration['custom_ranges'] ?? "",
    ];
    return $form;
  }
}
