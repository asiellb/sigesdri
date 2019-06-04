var InitFormPlugins = function () {

    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: App.isRTL(),
                format: 'dd/mm/yyyy',
                language: "es",
                orientation: "left",
                autoclose: true,
                enableOnReadonly: false
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }

        /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
    };

    var handleBootstrapSelect = function() {
        $('.bs-select').selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-close'
        });
    };

    var handleSelect2 = function () {
        $(".select2, .select2-multiple").select2({
            placeholder: 'Seleccione de la lista ...',
            width: null,
            allowClear: true,
        });
    };

    var handleICheck = function () {
        if($('.radio-list input').length){
            $('.radio-list input').iCheck({
                radioClass: 'iradio_square-grey'
            });
        }
    };

    var handleCountryList = function () {
        var imagePath = '/assets/global/img/countries_flags/';
        function format(state) {
            if (!state.id) return state.text; // optgroup
            return "<img class='flag' src='"+ imagePath + state.id + ".png'/>&nbsp;&nbsp;" + state.text;
        }

        $(".country_list").select2({
            placeholder: "Seleccione un país ...",
            allowClear: true,
            templateResult: format,
            width: 'auto',
            templateSelection: format,
            escapeMarkup: function (m) {
                return m;
            },
            //tags: true,
            tokenSeparators: [',', ' ']
        });

        $("select").on("select2:select", function (evt) {
            var element = evt.params.data.element;
            var $element = $(element);
            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
            //alert('entro');
        });
    };

    var isInitialized = function (selector) {
        var $counter = false;
        var selectorsID = handleSelectorId(selector);
        $(selectorsID).each(function () {
            if($(this).val().length > 0){
                $counter = true;
            }
        });
        return $counter;

    };

    var handleSelectorId = function (selector) {
        return $(selector).filter(function () {
            return $(this).attr("id");
        })
    };

    var handleDependent = function (independent) {
        return $('input[data-dependent]').filter(function () {
            return $(this).attr('data-dependent') == $(independent).attr('id');
        })
    };

    var handleNecessary = function (necessary) {
        if(!isInitialized(necessary)){
            $(necessary).each(function () {
                $(this).rules("add", {required:true});
            });
        }
        $(necessary).on("keyup blur" ,function( event ) {
            var length = $(event.target).val().length;
            if(length > 0){
                $(necessary).each(function () {
                    $(this).rules("remove", "required")
                });
                $(necessary).closest('.form-group').removeClass('has-error').addClass('has-success');
                $(necessary).next('.help-block-error').remove();
            } else {
                $(necessary).each(function () {
                    $(this).rules("add", {required:true})
                });
            }
        });
    };

    var handleExclude = function (exclude) {
        if(isInitialized(exclude)){
            $(exclude).each(function () {
                if($(this).val().length > 0){
                    $(exclude).not($(this)).each(function () {
                        $(this).prop('disabled', true);
                        handleDependent($(this)).prop('disabled', true);
                    })
                }
            });
        }

        $(exclude).on("keyup blur" ,function( event ) {
            var target = $(event.target);
            var length = $(target).val().length;
            if(length > 0){
                $(exclude).not(target).each(function () {
                    $(this).prop('disabled', true);
                    handleDependent($(this)).prop('disabled', true);
                })
            } else {
                $(exclude).each(function () {
                    $(this).removeProp('disabled');
                    handleDependent($(this)).removeProp('disabled');
                });
            }
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            handleDatePickers();
            handleBootstrapSelect();
            handleSelect2();
            handleICheck();
            handleCountryList();
        }

    };

}();

jQuery(document).ready(function() {
    InitFormPlugins.init();
});