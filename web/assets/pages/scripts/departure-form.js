var DepartureForm = function () {

    var handleDepartureApplication = function () {
        $( document ).ready(function() {
            $("#exitbundle_departure_application").prop("disabled", true);
            $("#exitbundle_departure_passport").prop("disabled", true);

            if($('#departure-client-selected').length){
                if (!($("#departure-client-selected.select2 option:selected").val() == "")){
                    var clientSelector  = $("#departure-client-selected.select2 option:selected");
                }

                if($('#exitbundle_departure_application').length || $('#exitbundle_departure_passport').length){
                    getDependenciesList(clientSelector);
                }
            }

        });

        $('#exitbundle_departure_client').change(function () {
            //var holderSelector = $(this);
            var clientSelector  = $("#exitbundle_departure_client.select2 option:selected");

            getDependenciesList(clientSelector);
        });

        function getDependenciesList(clientSelector) {
            $.ajax({
                type: 'post',
                url: Routing.generate('list_client_dependencies'),
                dataType: "JSON",
                data: {
                    'client': function() {
                        $(document).ajaxStop($.unblockUI);
                        App.blockUI({
                            target: $('#submit_departure_form, #submit_departure_edit_form'),
                            animate: true
                        });
                        return clientSelector.val();
                    }
                },
                success: function (data) {

                    var applicationSelect   = $("#exitbundle_departure_application");
                    var passportSelect      = $("#exitbundle_departure_passport");

                    applicationSelect.prop("disabled", false);
                    applicationSelect.html('');

                    passportSelect.prop("disabled", false);
                    passportSelect.html('');

                    if (data.applications.length){
                        // Remove current options
                        applicationSelect.select2({
                            placeholder: 'Solicitudes de ' + clientSelector.text(),
                            data: null
                        });

                        // Empty value ...
                        applicationSelect.append('<option value> Select a application of ' + clientSelector.find("option:selected").text() + ' ...</option>');

                        $.each(data.applications, function (key, application) {
                            applicationSelect.append('<option value="' + application.id + '">' + application.number + '</option>');
                        });
                    }else {
                        applicationSelect.select2({
                            placeholder: 'No se encontraron Solicitudes para ' + clientSelector.text(),
                            data: null
                        });
                    }

                    if (data.passports.length){
                        // Remove current options
                        passportSelect.select2({
                            placeholder: 'Pasaportes de ' + clientSelector.text(),
                            data: null
                        });

                        // Empty value ...
                        passportSelect.append('<option value> Select a application of ' + clientSelector.find("option:selected").text() + ' ...</option>');

                        $.each(data.passports, function (key, passport) {
                            passportSelect.append('<option value="' + passport.id + '">' + passport.number + '</option>');
                        });
                    }else {
                        passportSelect.select2({
                            placeholder: 'No se encontraron Pasaportes para ' + clientSelector.text(),
                            data: null
                        });
                    }

                    App.unblockUI('#submit_departure_form, #submit_departure_edit_form');
                },
                error: function (err) {
                    alert("Ocurrio un error cargando las dependencias ...");
                }
            });
        };
    };

    var handleDepartureForm = function () {
        var form = $('#submit_departure_form, #submit_departure_edit_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'exitbundle_departure[client]': {
                    required: true
                },
                'exitbundle_departure[application]': {
                    required: true
                },
                'exitbundle_departure[passport]': {
                    required: true
                },
                'exitbundle_departure[departureDate]': {
                    required: true
                },
                'exitbundle_departure[passportDelivery]': {
                    required: true
                },
                'exitbundle_departure[returnDate]': {
                    isAfterStartDate: $('#exitbundle_departure_departureDate')
                },
                'exitbundle_departure[passportCollection]': {
                    isAfterStartDate: $('#exitbundle_departure_passportDelivery')
                },
                'exitbundle_departure[observations]': {

                },
                'exitbundle_departure[resultsFile]': {

                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'exitbundle_departure[client]': {
                    required: "Seleccione el Cliente que va a salir."
                },
                'exitbundle_departure[application]': {
                    required: "Seleccione la solicitud asociada a la salida.",
                    minlength: jQuery.validator.format("Seleccione la solicitud asociada a la salida.")
                },
                'exitbundle_departure[passport]': {
                    required: "Seleccione el Pasaporte que se va a utilizar.",
                    minlength: jQuery.validator.format("Seleccione el Pasaporte que se va a utilizar.")
                },
                'exitbundle_departure[returnDate]': {
                    isAfterStartDate: "La fecha de retorno debe ser posterior a la de salida."
                },
                'exitbundle_departure[passportCollection]': {
                    isAfterStartDate: "La fecha de recogida del Pasaporte debe ser posterior a la de entrega."
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "exitbundle_departure[client]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_client_error");
                } else if (element.attr("name") == "exitbundle_departure[application]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_application_error");
                } else if (element.attr("name") == "exitbundle_departure[passport]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_passport_error");
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
                if (label.attr("for") == "exitbundle_departure[client]" ||
                    label.attr("for") == "exitbundle_departure[application]" ||
                    label.attr("for") == "exitbundle_departure[passport]" ) { // for checkboxes and radio buttons, no need to show OK icon
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
            handleDepartureApplication();
            handleDepartureForm();
        }

    };

}();

jQuery(document).ready(function() {
    DepartureForm.init();
});