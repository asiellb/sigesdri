var AgreementApplicationForm = function () {

    var handleApplicationState = function () {

        $("#agreement_application_state input:radio:checked").each(
            function() {
                var value = $(this).val();
                handleState(value)
            }
        );


        $("#agreement_application_state").find('input').on('ifChecked', function(event){
            var value = $(event.target).val();
            handleState(value)
        });

        function handleState(state) {
            if(state =="APR"){
                $('#agreement_application_state_0, #agreement_application_state_2').iCheck('disable');
                $(".approved").show();
                $(".rejected").hide();
            } else if(state =="REC") {
                $('#agreement_application_state_0, #agreement_application_state_1').iCheck('disable');
                $(".rejected").show();
                $(".approved").hide();
            } else {
                $(".rejected").hide();
                $(".approved").hide();
            }
        }
    };

    var handleApplicationForm = function () {
        var form = $('#submit_agreement_application_form, #submit_agreement_application_edit_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'agreement_application[institution]': {
                    required: true
                },
                'agreement_application[background]': {
                    required: true
                },
                'agreement_application[objetives]': {
                    required: true
                },
                'agreement_application[basement]': {
                    required: true
                },
                'agreement_application[commitments]': {
                    required: true
                },
                'agreement_application[memberForCubanPart]': {
                    required: true
                },
                'agreement_application[memberForForeignPart]': {
                    required: true
                },
                'agreement_application[results]': {
                    required: true
                },
                'agreement_application[expenses]': {
                    required: false
                },
                'agreement_application[state]': {
                    required: true
                },
                'agreement_application[confirmDate]': {
                    required: false
                },
                'agreement_application[rejectDate]': {
                    required: false
                },
                'agreement_application[rejectReasons]': {
                    required: false
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'agreement_application[institution]': {
                    required: "Inserte una Institución Extranjera"
                },
                'agreement_application[state]': {
                    required: "Seleccione al menos un estado.",
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "agreement_application[institution]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_institution_error");
                }else if (element.attr("name") == "agreement_application[state]") { // for uniform radio buttons, insert the after the given container
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
                if (label.attr("for") == "agreement_application[institution]" ||
                    label.attr("for") == "agreement_application[state]" ) { // for checkboxes and radio buttons, no need to show OK icon
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
            handleApplicationForm();
        }

    };

}();

jQuery(document).ready(function() {
    AgreementApplicationForm.init();
});