var PassportApplicationForm = function () {

    var handlePassportApplicationState = function () {
        $("#passportbundle_application_state input:radio:checked").each(
            function() {
                var value = $(this).val();

                if(value =="CON"){
                    $('#passportbundle_application_state_2, #passportbundle_application_state_3').iCheck('disable');
                } else if(value =="ENV") {
                    $('#passportbundle_application_state_0').iCheck('disable');
                    $('#passportbundle_application_state_2, #passportbundle_application_state_3').iCheck('enable');
                } else if(value =="CNF"){
                    $('#passportbundle_application_state_0, #passportbundle_application_state_1, #passportbundle_application_state_3').iCheck('disable');
                }else if(value =="REC"){
                    $('#passportbundle_application_state_0, #passportbundle_application_state_1, #passportbundle_application_state_2').iCheck('disable');
                }
            }
        );


        $("#passportbundle_application_state").find('input').on('ifChecked', function(event){
            var value = $(event.target).val();
            if(value =="CON"){
                $('#passportbundle_application_state_2, #passportbundle_application_state_3').iCheck('disable');
            } else if(value =="ENV") {
                $('#passportbundle_application_state_0').iCheck('disable');
                $('#passportbundle_application_state_2, #passportbundle_application_state_3').iCheck('enable');
            } else if(value =="CNF"){
                $('#passportbundle_application_state_0, #passportbundle_application_state_1, #passportbundle_application_state_3').iCheck('disable');
            }else if(value =="REC"){
                $('#passportbundle_application_state_0, #passportbundle_application_state_1, #passportbundle_application_state_2').iCheck('disable');
            }
        });
    };

    var handlePassportApplicationReject = function () {
        $("#passportbundle_application_state").find('input').on('ifChecked', function(event){
            var value = $(event.target).val();
            if(value =="REC"){
                $(".reject-issues").show();
            } else {
                $(".reject-issues").hide();
            }
        });
    };

    var handlePassportApplicationForm = function () {
        var form = $('#submit_passport_application_edit_form, #submit_passport_application_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'passportbundle_application[client]': {
                    required: true
                },
                'passportbundle_application[organ]': {
                    required: true
                },
                'passportbundle_application[travelReason]': {
                    required: true
                },
                'passportbundle_application[reason]': {
                    required: true
                },
                'passportbundle_application[applicationDate]': {
                    required: true
                },
                'passportbundle_application[applicationType]': {
                    required: true
                },
                'passportbundle_application[passportType]': {
                    required: true
                },
                'passportbundle_application[state]': {
                    required: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'passportbundle_application[client]': {
                    required: "Este campo es obligatorio.",
                    minlength: jQuery.validator.format("Seleccione al menos un cliente.")
                },
                'passportbundle_application[reason]': {
                    required: "Seleccione la Razon de la Solicitud.",
                    minlength: jQuery.validator.format("Seleccione al menos un tipo.")
                },
                'passportbundle_application[applicationType]': {
                    required: "Seleccione el Tipo de Solicitud.",
                    minlength: jQuery.validator.format("Seleccione al menos un tipo.")
                },
                'passportbundle_application[passportType]': {
                    required: "Seleccione el Tipo de Pasaporte.",
                    minlength: jQuery.validator.format("Seleccione al menos un tipo.")
                },
                'passportbundle_application[state]': {
                    required: "Seleccione el Estado de la Solicitud.",
                    minlength: jQuery.validator.format("Seleccione al menos un estado.")
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "passportbundle_application[client]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_client_error");
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
                if (label.attr("for") == "passportbundle_application[client]" ||
                    label.attr("for") == "passportbundle_application[reason]" ||
                    label.attr("for") == "passportbundle_application[applicationType]" ||
                    label.attr("for") == "passportbundle_application[passportType]" ||
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
            handlePassportApplicationState();
            handlePassportApplicationReject();
            handlePassportApplicationForm();
        }

    };

}();

jQuery(document).ready(function() {
    PassportApplicationForm.init();
});