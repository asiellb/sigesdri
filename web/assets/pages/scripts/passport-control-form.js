var PassportControlForm = function () {

    var handlePassportControlForm = function () {
        var form = $('#submit_passport_control_form, #submit_passport_control_edit_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'passportbundle_control[passport]': {
                    required: true
                },
                'passportbundle_control[deliveryDate]': {
                    required: true
                },
                'passportbundle_control[receivesSpecialist]': {
                    required: true
                },
                'passportbundle_control[pickUpDate]': {
                    required: false,
                    isAfterStartDate: $('#passportbundle_control_deliveryDate')
                },
                'passportbundle_control[deliversSpecialist]': {
                    required: false
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'passportbundle_control[passport]': {
                    required: "Seleccione un pasaporte."
                },
                'passportbundle_control[deliveryDate]': {
                    required: "Inserte la fecha de entrega."
                },
                'passportbundle_control[receivesSpecialist]': {
                    required: "Seleccione la persona que recibe el pasaporte."
                },
                'passportbundle_control[pickUpDate]': {
                    isAfterStartDate: "La fecha debe ser mayor que la de entrega."
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "passportbundle_control[passport]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_passport_error");
                }else if (element.attr("name") == "passportbundle_control[receivesSpecialist]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_receivesSpecialist_error");
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
                if (label.attr("for") == "passportbundle_control[passport]" ||
                    label.attr("for") == "passportbundle_control[deliveryDate]" ||
                    label.attr("for") == "passportbundle_control[receivesSpecialist]" ) { // for checkboxes and radio buttons, no need to show OK icon
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
            handlePassportControlForm();
        }

    };

}();

jQuery(document).ready(function() {
    PassportControlForm.init();
});