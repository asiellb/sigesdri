/* global FileReader */
(function(w, $){

    'use strict';

    /**
     * @param {jQuery} $el
     * @constructor
     */
    var Cropper = function($el) {
        this.$el = $el;
        this.options = $.extend({}, $el.data('cropper-options'));

        this
            .initElements()
            .initLocalEvents()
            .initRemoteEvents()
            .initCroppingEvents()
        ;
    };

    /**
     * @returns {Cropper}
     */
    Cropper.prototype.initElements = function() {
        this.$modal = this.$el.find('.modal');
        this.$aspectRatio = this.$modal.find('input[name="cropperAspectRatio"]');
        this.$input = this.$el.find('input.cropper-base64');
        this.$clear = this.$el.find('a.clear');
        this.$delete = this.$el.find('[type="checkbox"]');

        this.$container = {
            $preview: this.$modal.find('.cropper-preview'),
            $canvas: this.$el.find('.cropper-canvas-container')
        };

        this.$local = {
            $btnUpload: this.$el.find('.cropper-local .upload'),
            $input: this.$el.find('.cropper-local input[type="file"]')
        };

        this.$remote = {
            $btnUpload: this.$el.find('.cropper-remote button.upload'),
            $uploadLoader: this.$el.find('.cropper-remote .remote-loader'),
            $input: this.$el.find('.cropper-remote input[type="url"]')
        };

        this.options = $.extend(this.options, {
            aspectRatio: this.$aspectRatio.val()
        });

        this.$delete.parents('.form-group.field-checkbox').addClass('disappear');

        this.$clear.on('click', $.proxy(this.clear, this));

        return this;
    };

    /**
     * @returns {Cropper}
     */
    Cropper.prototype.initLocalEvents = function() {
        var self = this;

        // map virtual upload button to native input file element
        this.$local.$btnUpload.on('click', function () {
            self.$local.$input.trigger('click');
        });

        // start cropping process on input file "change"
        this.$local.$input.on('change', function () {
            var reader = new FileReader();

            // show a croppable preview image in a modal
            reader.onload = function (e) {
                self.prepareCropping(e.target.result);

                // clear input file so that user can select the same image twice and the "change" event keeps being triggered
                self.$local.$input.val('');
            };

            // trigger "reader.onload" with uploaded file
            reader.readAsDataURL(this.files[0]);
        });

        return this;
    };

    /**
     * @returns {Cropper}
     */
    Cropper.prototype.initRemoteEvents = function() {
        var self = this;

        var $btnUpload = this.$remote.$btnUpload;
        var $uploadLoader = this.$remote.$uploadLoader;

        // handle distant image upload button state
        this.$remote.$input.on('change, input', function () {
            var url = $(this).val();

            self.$remote.$btnUpload.prop('disabled', url.length <= 0 || url.indexOf('http') === -1);
        });

        // start cropping process get image's base64 representation from local server to avoid cross-domain issues
        this.$remote.$btnUpload.on('click', function () {
            $btnUpload.hide();
            $uploadLoader.removeClass('hidden');
            $.ajax({
                url: $btnUpload.data('url'),
                data: {
                    url: self.$remote.$input.val()
                },
                method: 'post'
            }).done(function (data) {
                self.prepareCropping(data.base64);
                $btnUpload.show();
                $uploadLoader.addClass('hidden');
            });
        });

        return this;
    };

    /**
     * @returns {Cropper}
     */
    Cropper.prototype.initCroppingEvents = function() {
        var self = this;

        // handle image cropping
        this.$modal.find('[data-method="getCroppedCanvas"]').on('click', function() {
            self.crop();
        });

        // handle "aspectRatio" switch
        self.$aspectRatio.on('change', function() {
            self.$container.$preview.children('img').cropper('setAspectRatio', $(this).val());
        });

        return this;
    };

    /**
     * Open cropper "editor" in a modal with the base64 uploaded image.
     *
     * @param {string} base64
     */
    Cropper.prototype.prepareCropping = function(base64) {
        var self = this;

        // clean previous croppable image
        this.$container.$preview.children('img').cropper('destroy').remove();

        // reset "aspectRatio" buttons
        this.$aspectRatio.each(function() {
            var $this = $(this);

            if ($this.val().length <= 0) {
                $this.trigger('click');
            }
        });

        this.$modal
            .one('shown.bs.modal', function() {
                // (re)build croppable image once the modal is shown (required to get proper image width)
                $('<img>')
                    .attr('src', base64)
                    .on('load', function() {
                        $(this).cropper(self.options);
                    })
                    .appendTo(self.$container.$preview);
            })
            .modal('show');
    };

    /**
     * Clear cropper
     *
     */
    Cropper.prototype.clear = function(e){
        if (e) e.preventDefault();

        this.$input.val('');
        this.$container.$canvas.children('canvas').cropper('destroy').remove();
        this.$container.$canvas.children('img').cropper('destroy').remove();
        this.$el.addClass('cropper-new').removeClass('cropper-exists');
        this.$delete.prop('checked', true);

    };

    /**
     * Create canvas from cropped image and fill in the hidden input with canvas base64 data.
     */
    Cropper.prototype.crop = function() {
        var self = this,
            data = this.$container.$preview.children('img').cropper('getData'),
            image_width = Math.min(this.$el.data('max-width'), data.width),
            image_height = Math.min(this.$el.data('max-height'), data.height),
            preview_width = Math.min(this.$container.$canvas.data('preview-width'), data.width),
            preview_height = Math.min(this.$container.$canvas.data('preview-height'), data.height),

            // TODO: getCroppedCanvas seams to only consider one dimension when calculating the maximum size
            // in respect to the aspect ratio and always considers width first, so height is basically ignored!
            // To set a maximum height, no width parameter should be set.
            // Example of current wrong behavior:
            // source of 200x300 with resize to 150x200 results in 150x225 => WRONG (should be: 133x200)
            // source of 200x300 with resize to 200x150 results in 200x300 => WRONG (should be: 100x150)
            // This is an issue with cropper, not this library
            preview_canvas = this.$container.$preview.children('img').cropper('getCroppedCanvas', {
                width: preview_width,
                height: preview_height
            }),
            image_canvas = this.$container.$preview.children('img').cropper('getCroppedCanvas', {
                width: image_width,
                height: image_height
            });

        // fill canvas preview container with cropped image
        this.$container.$canvas.html(preview_canvas).addClass('prueba');

        this.$el.addClass('cropper-exists').removeClass('cropper-new');

        this.$delete.removeProp('checked');

        // fill input with base64 cropped image
        this.$input.val(image_canvas.toDataURL(this.$el.data('mimetype'), this.$el.data('quality')));

        //$("#cacatua").attr("src", this.$input.val());
        //alert(this.$input.val());

        // hide the modal
        this.$modal.modal('hide');
    };

    if (typeof module !== 'undefined' && 'exports' in module) {
        module.exports = Cropper;
    } else {
        window.Cropper = Cropper;
    }

})(window, jQuery);
