'use strict';
define([
        'jquery',
        'underscore',
        'pim/field',
        'u58-extended-attribute-type/templates/product/field/country',
        'pim/fetcher-registry',
    ], function (
    $,
    _,
    Field,
    fieldTemplate,
    FetcherRegistry,
    ) {
        return Field.extend({
            fieldTemplate: _.template(fieldTemplate),
            events: {
                'change .field-input:first input[type="hidden"].select-field': 'updateModel',
            },

            renderInput: function (context) {
                return this.fieldTemplate(context);
            },

            postRender: function () {
                $.when(
                    FetcherRegistry.getFetcher('country').fetchAll()
                ).then(function (countries) {
                    var data = _.map(countries, function (value, key) {
                        return {
                            id: key,
                            text: value
                        }
                    });

                    this.$('input.select-field').select2({
                        data: data,
                        formatSelection: this.formatSelection, //templateSelection
                        formatResult: this.formatSelection,    //templateResult
                        minimumInputLength: 0,
                        allowClear: true,
                        placeholder: ' ',
                    });
                }.bind(this));
            },

            formatSelection: function (state) {
                if (!state.id) {
                    return state.text;
                }

                return '<i class="flag flag-' + state.id.toLowerCase() + '"></i>&nbsp;' + state.text;
            },

            setFocus: function () {
                this.$('.data:first').focus();
            },

            updateModel: function () {
                var data = this.$('.field-input:first input[type="hidden"].select-field').val();
                data = '' === data ? this.attribute.empty_value : data;

                this.setCurrentValue(data);
            }
        });
    }
);
