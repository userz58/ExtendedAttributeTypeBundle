'use strict';
define([
        'jquery',
        'underscore',
        'pim/field',
        'routing',
        'pim/attribute-manager',
        'u58-extended-attribute-type/templates/product/field/images',
        'u58-extended-attribute-type/templates/product/field/image',
        'pim/dialog',
        'oro/mediator',
        'oro/messenger',
        'pim/media-url-generator',
        'jquery.slimbox'
    ],
    function ($, _, Field, Routing, AttributeManager, fieldTemplate, imageTemplate, Dialog, mediator, messenger, MediaUrlGenerator) {
        return Field.extend({
            fieldTemplate: _.template(fieldTemplate),
            imageTemplate: _.template(imageTemplate),
            events: {
                'change input[type="file"]': 'upload',
                'click .preview': 'preview',
                'click .remove': 'remove',
            },

            renderInput: function (context) {
                var $element = $(this.fieldTemplate(context));
                this.renderList($element.find('ul'));

                return $element;
            },

            renderList: function (ul) {
                ul.children('li').remove();

                var data = this.getCurrentValue().data;
                _.each(data, filePath => {
                    ul.append(this.imageTemplate({
                        filePath: filePath,
                        mediaUrlGenerator: MediaUrlGenerator,
                        editMode: this.getEditMode()
                    }));
                });

                ul.children('li').each(function (i) {
                    $(this).attr('position', i);
                });
            },

            postRender: function () {
                this.$('ul').sortable({
                    cursor: 'move',
                    update: this.sortOrder.bind(this),
                    start: function (e, ui) {
                        ui.placeholder.height(ui.helper.outerHeight());
                    },
                    tolerance: 'pointer',
                    helper: function (e, item) {
                        var originals = item.children();
                        var helper = item.clone();
                        helper.children().each(function (index) {
                            $(this).width(originals.eq(index).outerWidth());
                        });
                        return helper;
                    },
                    forcePlaceholderSize: true
                });
            },

            sortOrder: function (e, ui) {
                var sorted = [];
                var positions = this.$('ul').sortable('toArray', {attribute: 'position'});
                for (var i = 0; i < positions.length; i++) {
                    sorted.push(this.getCurrentValue().data[positions[i]]);
                }

                this.setCurrentValue(sorted);

                this.$('ul > li').each(function (i) {
                    $(this).attr('position', i);
                });
            },

            getItemIndex: function (e) {
                return this.$(e.currentTarget).closest('li').attr('position');
            },

            preview: function (e) {
                var index = this.getItemIndex(e);
                var mediaUrl = MediaUrlGenerator.getMediaShowUrl(this.getCurrentValue().data[index].filePath, 'preview');
                if (mediaUrl) {
                    $.slimbox(mediaUrl, '', {overlayOpacity: 0.3});
                }
            },

            upload: function (e) {
                if (!this.isReady()) {
                    Dialog.alert(_.__(
                        'pim_enrich.entity.product.info.already_in_upload',
                        {'locale': this.context.locale, 'scope': this.context.scope}
                    ));
                }

                var input = this.$('.field-input:first input[type="file"]').get(0);
                if (!input || 0 === input.files.length) {
                    return;
                }

                this.setReady(false);

                var promises = _.map(input.files, function (file) {
                    this.$('ul').append(this.imageTemplate({filePath: null}));

                    var formData = new FormData();
                    formData.append('file', file);

                    return $.ajax({
                        url: Routing.generate('pim_enrich_media_rest_post'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false
                    })
                    .fail(function (xhr) {
                        var message = xhr.responseJSON && xhr.responseJSON.message ?
                            xhr.responseJSON.message :
                            _.__('pim_enrich.entity.product.error.upload');
                        messenger.enqueueMessage('error', message);
                    });
                }.bind(this));

                Promise.all(promises)
                    .then(
                        results => {
                            this.setCurrentValue(this.getCurrentValue().data.concat(_.pluck(results, 'filePath')));
                            this.renderList(this.$('ul'));
                        },
                        errors => {
                            console.log(errors);
                        })
                    .then(
                        results => {
                            this.$('ul').sortable('enable');
                            this.setReady(true);
                        });
            },

            remove: function (e) {
                var index = this.getItemIndex(e);
                var data = this.getCurrentValue().data;
                data.splice(index, 1);

                this.setCurrentValue(data);

                this.renderList(this.$('ul'));
            },
        });
    }
);
