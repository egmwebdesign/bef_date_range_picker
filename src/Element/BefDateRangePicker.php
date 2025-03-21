<?php

namespace Drupal\bef_date_range_picker\Element;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\OptGroup;
use Drupal\Core\Render\Element\Fieldset;
use Drupal\Core\Render\Element\FormElement;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;

/**
 * Provides an select2 form element.
 *
 * Simple usage example:
 * @code
    $form['date_interval'] = [
      '#type' => 'bef_date_range_picker',
      '#fieldId' => 'date_interval', // same as the key of the render array
      '#date-format' => "YYYY.MM.DD", // NOT php format, but http://www.daterangepicker.com/ format
      '#title' => 'Date',
      'date_interval' => [ // same as the value of "#fieldId"
        '#tree' => TRUE,
        'min' => [
          '#type' => 'textfield',
          '#default_value' => "",
        ],
        'max' => [
          '#type' => 'textfield',
          '#default_value' => "",
        ]
      ],
    ];
 * @endcode
 *
 * @FormElement("bef_date_range_picker")
 */
class BefDateRangePicker extends Fieldset {

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    $class = get_class($this);
    $info = parent::getInfo();

    // Apply default form element properties.
    $info['#type'] = "bef_date_range_picker";
    $info['#attached']['library'][] = 'bef_date_range_picker/bef_date_range_picker';
    $info['#fieldId'] = NULL;
    $info['#date-format'] = "YYYY-MM-DD";
    $info['#attributes']['class'][] = 'bef-date-range-picker-container';
    $info['#process'][] = [$class, 'processSelect'];
    return $info;
  }

  public static function processSelect(&$element, FormStateInterface $form_state, &$complete_form): array {
    $fieldId = $element['#fieldId'];
    $element['#attributes']['date-picker-date-format'] = $element['#date-format'];
    $min = &$element[$fieldId]['min'];
    $max = &$element[$fieldId]['max'];

    $element[$fieldId]['output'] = [
      '#type' => 'textfield',
      '#title' => t(''),
      '#title_display' => 'before',
      '#maxlength' => '100000',
      '#attributes' => [
        'class' => ['bef-date-range-picker-output'],
      ],
    ];

    $min['#attributes']['class'][] = 'bef-date-range-picker-min';
    $min['#attributes']['class'][] = 'hidden';
    $min['#title_display'] = 'hidden';

    $max['#attributes']['class'][] = 'bef-date-range-picker-max';
    $max['#attributes']['class'][] = 'hidden';
    $max['#title_display'] = 'hidden';

    unset($min);
    unset($max);
    return $element;
  }
}
