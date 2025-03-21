
(function(Drupal, once, $) {
  const MIN_ELEMENT_SELECTOR = '.bef-date-range-picker-min';
  const MAX_ELEMENT_SELECTOR = '.bef-date-range-picker-max';
  const OUTPUT_ELEMENT_SELECTOR = '.bef-date-range-picker-output';

  Drupal.behaviors.bef_date_range_picker = {
    attach: function(context, settings) {
      let ranges = {};
      ranges[Drupal.t('Today')] = [moment(), moment()];
      ranges[Drupal.t('Yesterday')] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
      ranges[Drupal.t('Last 7 Days')] = [moment().subtract(6, 'days'), moment()];
      ranges[Drupal.t('Last 30 Days')] = [moment().subtract(29, 'days'), moment()];
      ranges[Drupal.t('This Month')] = [moment().startOf('month'), moment().endOf('month')];
      ranges[Drupal.t('Last Month')] = [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')];

      const elements = once('bef-date-range-picker-container', '.bef-date-range-picker-container', context);
      elements.forEach(function(element) {

        const output = $(element).find(OUTPUT_ELEMENT_SELECTOR);
        let min = $(element).find(MIN_ELEMENT_SELECTOR);
        let max = $(element).find(MAX_ELEMENT_SELECTOR);

        min = moment(min.val());
        // We add 1 day to the max date, because drupal is not inclusive by default.
        // So we correct this by subtracting 1 day from the max date.
        max = moment(max.val()).subtract(1, 'days');

        let dateFormat = $(element).attr('date-picker-date-format');
        if (!dateFormat) {
          dateFormat = 'YYYY-MM-DD';
        }

        let config = {};
        let isPreLoaded = min.isValid() && max.isValid();
        if (isPreLoaded) {
          config.startDate = min;
          config.endDate = max;
        }
        config.autoUpdateInput = isPreLoaded;
        config.ranges = ranges;
        config.locale = {
          format: dateFormat,
          applyLabel: Drupal.t('Apply'),
          cancelLabel: Drupal.t('Cancel'),
          customRangeLabel: Drupal.t('Custom Range'),
          daysOfWeek: [
            Drupal.t('Su'),
            Drupal.t('Mo'),
            Drupal.t('Tu'),
            Drupal.t('We'),
            Drupal.t('Th'),
            Drupal.t('Fr'),
            Drupal.t('Sa')
          ],
          monthNames: [
            Drupal.t('January'),
            Drupal.t('February'),
            Drupal.t('March'),
            Drupal.t('April'),
            Drupal.t('May'),
            Drupal.t('June'),
            Drupal.t('July'),
            Drupal.t('August'),
            Drupal.t('September'),
            Drupal.t('October'),
            Drupal.t('November'),
            Drupal.t('December')
          ],
          firstDay: 1,
          opens: "right",
        };

        $(output).daterangepicker(config, function(start, end, label) {
          let formatted = start.format(dateFormat) + ' - ' + end.format(dateFormat);
          $(element).find(OUTPUT_ELEMENT_SELECTOR).text(formatted);
          $(element).find(OUTPUT_ELEMENT_SELECTOR).val(formatted);
          $(element).find(MIN_ELEMENT_SELECTOR).val(start.format("YYYY-MM-DD"));
          // We add 1 day to the max date, because drupal is not inclusive by default.
          $(element).find(MAX_ELEMENT_SELECTOR).val(end.add(1, 'days').format("YYYY-MM-DD"));
        });;
      });
    }
  };
})(Drupal, once, jQuery);
