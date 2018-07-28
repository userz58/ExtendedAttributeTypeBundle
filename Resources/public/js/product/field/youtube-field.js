'use strict';
define([
        'underscore',
        'pim/field',
        'u58-extended-attribute-type/templates/product/field/youtube',
    ], function (
        _,
        Field,
        fieldTemplate,
    ) {
        return Field.extend({
            fieldTemplate: _.template(fieldTemplate),
            events: {
                'click .search-video': 'updateModel',
                'click .clear-input': 'clearInput'
            },

            renderInput: function (context) {
                return this.fieldTemplate(context);
            },

            updateModel: function () {
                var data = this.$('.field-input:first input[type="text"]').val();

                if (data === '') {
                    return;
                }

                var match = data.match( /^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\#\?&\"'>]+)/i );

                if(match !== null) {
                    this.setCurrentValue(match[1]);
                    this.render();
                }
            },

            clearInput: function () {
                this.setCurrentValue(this.attribute.empty_value);
                this.render();
            },
        });
    }
);
