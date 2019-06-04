var InstitutionForm = function () {

    var handleLogoBase = function () {

        var logoBase = '/images/institutions_logos/logo_base.png';

        $('.cropper-new.thumbnail').find('img').attr('src',logoBase);

    };

    var handleInstitutionForm = function () {
        var form = $('#submit_institution_form, #submit_institution_edit_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'foreing_institution[name]': {
                    required: true
                },
                'foreing_institution[acronym]': {
                    required: true
                },
                'foreing_institution[country]': {
                    required: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'foreing_institution[name]': {
                    required: "Inserte el Nombre de la Institución."
                },
                'foreing_institution[acronym]': {
                    required: "Inserte las Siglas de la Institución."
                },
                'foreing_institution[country]': {
                    required: "Seleccione el País."
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "foreing_institution[country]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_country_error");
                }else {
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
                if (label.attr("for") == "foreing_institution[country]" ) { // for checkboxes and radio buttons, no need to show OK icon
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
            handleLogoBase();
            handleInstitutionForm();
        }

    };

}();

jQuery(document).ready(function() {
    InstitutionForm.init();
});