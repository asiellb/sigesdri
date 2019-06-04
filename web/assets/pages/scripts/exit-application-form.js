var ApplicationForm = function () {

    var handleApplicationState = function () {

        $("#exitbundle_application_state input:radio:checked").each(
            function() {
                var value = $(this).val();
                handleState(value)
            }
        );


        $("#exitbundle_application_state").find('input').on('ifChecked', function(event){
            var value = $(event.target).val();
            handleState(value)
        });

        function handleState(state) {
            if(state =="APR"){
                $('#exitbundle_application_state_0, #exitbundle_application_state_2').iCheck('disable');
                $(".approved").show();
                $(".rejected").hide();
            } else if(state =="REC") {
                $('#exitbundle_application_state_0, #exitbundle_application_state_1').iCheck('disable');
                $(".rejected").show();
                $(".approved").hide();
            } else {
                $(".rejected").hide();
                $(".approved").hide();
            }
        }
    };

    var handleApplicationMissions = function () {
        $('.collection-missions').collection({
            up: '<a href="#" class="btn btn-info"><span class="glyphicon glyphicon-arrow-up"></span> Mover Arriba</a>',
            down: '<a href="#" class="btn btn-info"><span class="glyphicon glyphicon-arrow-down"></span> Mover Abajo</a>',
            add: '<a href="#" class="btn btn-info"><span class="glyphicon glyphicon-plus-sign"></span> Agregar Misión</a>',
            remove: '<a href="#" class="btn btn-info"><span class="glyphicon glyphicon-trash"></span> Eliminar Misión</a>',
            duplicate: '<a href="#" class="btn btn-info"><span class="glyphicon glyphicon-th-large"></span> Duplicar Misión</a>',
            prefix: 'parent',
            allow_duplicate: false,
            children: [{
                selector: '.collection-economics',
                add: '<a href="#" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span></a>',
                remove: '<a href="#" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></a>',
                fade_in: true,
                fade_out: true,
                allow_up: false,
                allow_down: false,
                allow_duplicate: false,
                after_add: function(collection, element) {
                    $(element).find('.select2').select2({
                        placeholder: 'Seleccione de la lista ...',
                        width: null,
                        allowClear: true
                    });
                    $(element).find('.bs-select').selectpicker({
                        iconBase: 'fa',
                        tickIcon: 'fa-close'
                    });
                    $(element).find('.bootstrap-toggle').bootstrapToggle({

                    });

                    return true;
                }
            }],
            after_add: function(collection, element) {
                $(element).find('.select2').select2({
                    placeholder: 'Seleccione de la lista ...',
                    width: null,
                    allowClear: true
                });
                $(element).find('.bs-select').selectpicker({
                    iconBase: 'fa',
                    tickIcon: 'fa-close'
                });
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
                var imagePath = '/assets/global/img/countries_flags/';
                function format(state) {
                    if (!state.id) return state.text; // optgroup
                    return "<img class='flag' src='"+ imagePath + state.id + ".png'/>&nbsp;&nbsp;" + state.text;
                }

                $(element).find('select.country_list_mission').select2({
                    placeholder: "Seleccione un país ...",
                    allowClear: true,
                    templateResult: format,
                    width: 'auto',
                    templateSelection: format,
                    escapeMarkup: function (m) {
                        return m;
                    }
                });

                var fromDate = $(element).find('.fromDate');
                //var dates = $(element).find('.fromDate, .untilDate');
                var untilDate = $(element).find('.untilDate');
                var lapsed = $(element).find('.timeAmount');

                $(element).find('.fromDate, .untilDate').on('changeDate',function(event){
                    var exitDate    = $(fromDate).val(),
                        arrivalDate = $(untilDate).val();
                        lapsed = $(lapsed);

                    if( moment(exitDate,'DD/MM/YYYY').isValid() && moment(arrivalDate,'DD/MM/YYYY').isValid()){
                        var exit    = moment(exitDate,'DD/MM/YYYY');
                        var arrival = moment(arrivalDate,'DD/MM/YYYY');

                        days = arrival.diff(exit, 'days');

                        lapsed.val(days);
                    }
                });

                return true;
            }
        });
    };

    var handleApplicationLapsed = function () {
        $('#exitbundle_application_exitDate, #exitbundle_application_arrivalDate').on('changeDate',function(event){
            var exitDate    = $('#exitbundle_application_exitDate').val(),
                arrivalDate = $('#exitbundle_application_arrivalDate').val(),
                lapsed = $('#exitbundle_application_lapsed');

            if( moment(exitDate,'DD/MM/YYYY').isValid() && moment(arrivalDate,'DD/MM/YYYY').isValid()){
                var exit    = moment(exitDate,'DD/MM/YYYY');
                var arrival = moment(arrivalDate,'DD/MM/YYYY');

                days = arrival.diff(exit, 'days');

                lapsed.val(days);
            }
        });
    };

    var handleCKEditor = function () {
            //CKEDICKEDITOR.replace('.ckeditor');
    };


    var handleApplicationPCCApproval = function () {
        $("#exitbundle_application_pccApprovalDate").prop("disabled", true);

        $("#exitbundle_application_pccApproval").change(function() {
            if($(this).prop('checked')){
                $("#exitbundle_application_pccApprovalDate").prop("disabled", false);
            }else {
                $("#exitbundle_application_pccApprovalDate").prop("disabled", true);
            }
        });
    };

    var handleApplicationIVApproval = function () {
        $("#exitbundle_application_vriApprovalDate").prop("disabled", true);

        $("#exitbundle_application_vriApproval").change(function() {
            if($(this).prop('checked')){
                $("#exitbundle_application_vriApprovalDate").prop("disabled", false);
            }else {
                $("#exitbundle_application_vriApprovalDate").prop("disabled", true);
            }
        });
    };

    var handleApplicationDCApproval = function () {
        $("#exitbundle_application_rsApprovalDate").prop("disabled", true);

        $("#exitbundle_application_rsApproval").change(function() {
            if($(this).prop('checked')){
                $("#exitbundle_application_rsApprovalDate").prop("disabled", false);
            }else {
                $("#exitbundle_application_rsApprovalDate").prop("disabled", true);
            }
        });
    };

    var handleApplicationSecretOffice = function () {
        $("#exitbundle_application_osApprovalDate").prop("disabled", true);

        $("#exitbundle_application_osApproval").change(function() {
            if($(this).prop('checked')){
                $("#exitbundle_application_osApprovalDate").prop("disabled", false);
            }else {
                $("#exitbundle_application_osApprovalDate").prop("disabled", true);
            }
        });
    };

    var handleApplicationForm = function () {
        var form = $('#submit_exit_application_form, #submit_exit_application_edit_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'exitbundle_application[client]': {
                    required: true
                },
                'exitbundle_application[country]': {
                    required: true
                },
                'exitbundle_application[lapsed]': {
                    required: true
                },
                'exitbundle_application[exitDate]': {
                    required: true
                },
                'exitbundle_application[arrivalDate]': {
                    required: true,
                    isAfterStartDate: $('#exitbundle_application_exitDate')

                },
                'exitbundle_application[proposedBy]': {
                    required: true
                },
                'exitbundle_application[state]': {
                    required: true
                },
                'exitbundle_application[missions]': {
                    required: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'exitbundle_application[client]': {
                    required: "Seleccione el Cliente que solicita.",
                    minlength: jQuery.validator.format("Seleccione el cliente que solicita.")
                },
                'exitbundle_application[proposedBy]': {
                    required: "Seleccione quién propone la Salida.",
                    minlength: jQuery.validator.format("Seleccione quién propone la salida.")
                },
                'exitbundle_application[state]': {
                    required: "Seleccione al menos un estado.",
                    minlength: jQuery.validator.format("Seleccione al menos un estado.")
                },
                'exitbundle_application[arrivalDate]': {
                    isAfterStartDate: "La fecha de retorno debe ser posterior a la de salida."
                },
                'exitbundle_application[missions]': {
                    required: "Agregue al menos una misiónn.",
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "exitbundle_application[client]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_client_error");
                }else if (element.attr("name") == "exitbundle_application[missions]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_missions_error");
                } else if (element.attr("name") == "exitbundle_application[proposedBy]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_proposedby_error");
                } else if (element.attr("name") == "exitbundle_application[state]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_state_error");
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                success.hide();
                error.show();
                App.scrollTo(error, -200);
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                if (label.attr("for") == "exitbundle_application[client]" ||
                    label.attr("for") == "exitbundle_application[missions]" ||
                    label.attr("for") == "exitbundle_application[proposedBy]" ||
                    label.attr("for") == "passportbundle_application[state]" ) { // for checkboxes and radio buttons, no need to show OK icon
                    label.closest('.form-group').removeClass('has-error').addClass('has-success');
                    label.remove(); // remove error label here
                } else { // display success icon for other inputs
                    label
                        .addClass('valid') // mark the current input as valid and display OK icon
                        .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                }
            },

            submitHandler: function (form) {
                success.show();
                error.hide();
                form.submit();
                //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
            }

        });
    };

    return {
        //main function to initiate the module
        init: function () {
            handleApplicationState();
            handleApplicationMissions();
            handleApplicationLapsed();
            handleCKEditor();
            handleApplicationPCCApproval();
            handleApplicationIVApproval();
            handleApplicationDCApproval();
            handleApplicationSecretOffice();
            handleApplicationForm();
        }

    };

}();

jQuery(document).ready(function() {
    ApplicationForm.init();
});