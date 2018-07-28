'use strict';
define([
        'jquery',
        'underscore',
        'pim/field',
        'routing',
        'pim/attribute-manager',
        'u58-extended-attribute-type/templates/product/field/features',
        'u58-extended-attribute-type/templates/product/field/feature',
        'oro/messenger',
        'pim/media-url-generator',
        'jquery.slimbox'
    ],
    function ($, _, Field, Routing, AttributeManager, fieldTemplate, featureTemplate, messenger, MediaUrlGenerator) {
        return Field.extend({
            fieldTemplate: _.template(fieldTemplate),
            featureTemplate: _.template(featureTemplate),
            events: {
                'click .item-add': 'addItem',
                'click .item-remove': 'removeItem',
                'click img': 'previewImage',
                'click .clear-image': 'clearImage',
                'change input[type="file"]': 'uploadImage',
                'change input[type="text"]': 'updateValue',
                'change textarea': 'updateValue'
            },

            renderInput: function (context) {
                var $element = $(this.fieldTemplate(context));

                this.renderList($element.find('ul'));

                return $element;
            },

            renderList: function (ul) {
                ul.children('li').remove();

                var data = this.getCurrentValue().data;
                _.each(data, function (item, index) {
                    ul.append(this.featureTemplate({
                        value: item,
                        mediaUrlGenerator: MediaUrlGenerator,
                        editMode: this.getEditMode()
                    }));
                }, this);

                ul.children('li').each(function (i) {
                    $(this).attr('position', i);
                });
            },

            postRender: function () {
                this.$('ul').sortable({
                    axis: 'y',
                    cursor: 'move',
                    handle: '.icon-reorder',
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

            previewImage: function (e) {
                var index = this.getItemIndex(e);
                var mediaUrl = MediaUrlGenerator.getMediaShowUrl(this.getCurrentValue().data[index].filePath, 'preview');
                if (mediaUrl) {
                    $.slimbox(mediaUrl, '', {overlayOpacity: 0.3});
                }
            },

            clearImage: function (e) {
                var index = this.getItemIndex(e);
                var data = this.getCurrentValue().data;
                data[index].filePath = null;

                this.setCurrentValue(data);

                this.renderList(this.$('ul'));
            },

            uploadImage: function (e) {
                if (!this.isReady()) {
                    return;
                }

                var input = this.$(e.currentTarget).closest('li').find('input[type="file"]').get(0);
                if (!input || 0 === input.files.length) {
                    return;
                }

                var index = this.getItemIndex(e);

                var formData = new FormData();
                formData.append('file', input.files[0]);

                this.setReady(false);

                this.$('ul').sortable('disable');

                $.ajax({
                    url: Routing.generate('pim_enrich_media_rest_post'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false
                })
                .done(function (response) {
                    var data = this.getCurrentValue().data;
                    data[index].filePath = response['filePath'];

                    this.setCurrentValue(data);

                    this.renderList(this.$('ul'));
                }.bind(this))
                .fail(function (xhr) {
                    var message = xhr.responseJSON && xhr.responseJSON.message ?
                        xhr.responseJSON.message :
                        _.__('pim_enrich.entity.product.error.upload');
                    messenger.enqueueMessage('error', message);
                })
                .always(function () {
                    this.setReady(true);
                    this.$('ul').sortable('enable');
                }.bind(this));
            },

            updateValue: function (e) {
                var index = this.getItemIndex(e);
                var data = this.getCurrentValue().data;
                data[index].title = this.$(e.currentTarget).closest('li').find('input[type="text"]').val();
                data[index].description = this.$(e.currentTarget).closest('li').find('textarea').val();
                this.setCurrentValue(data);
            },

            removeItem: function (e) {
                var index = this.getItemIndex(e);
                var data = this.getCurrentValue().data;
                data.splice(index, 1);

                this.setCurrentValue(data);

                this.renderList(this.$('ul'));
            },

            addItem: function () {
                var item = {
                    filePath: null,
                    title: null,
                    description: null
                };
                var data = this.getCurrentValue().data;
                data.push(item);

                this.setCurrentValue(data);

                this.renderList(this.$('ul'));
                this.$('ul > li:last-child input[type="text"]').focus();
            }
        });
    }
);
