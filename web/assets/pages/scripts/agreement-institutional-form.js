var AgreementInstitutionalForm = function () {

    var handleLogoBase = function () {

        var logoBase = '/images/agreements_univ_logos/logo_base.png';

        $('.cropper-new.thumbnail').find('img').attr('src',logoBase);

    };

    var handleInstitutionalApplication = function () {

        $( document ).ready(function() {
            $("#agreementbundle_institutional_institution").prop("disabled", true);
        });

        $('#agreementbundle_institutional_application').change(function () {
            //var holderSelector = $(this);
            var applicationSelector  = $("#agreementbundle_institutional_application.select2 option:selected");
            //alert(applicationSelector);
            getDependenciesList(applicationSelector);
        });

        function getDependenciesList(applicationSelector) {
            $.ajax({
                type: 'post',
                url: Routing.generate('list_agreement_application_dependencies'),
                dataType: "JSON",
                data: {
                    'application': function() {
                        $(document).ajaxStop($.unblockUI);
                        App.blockUI({
                            target: $('#submit_agreement_institutional_form, #submit_agreement_institutional_edit_form'),
                            animate: true
                        });
                        return applicationSelector.val();
                    }
                },
                success: function (data) {

                    var institutionSelect   = $("#agreementbundle_institutional_institution");

                    institutionSelect.prop("disabled", false);
                    institutionSelect.html('');

                    if (data.institutions.length){
                        // Remove current options
                        institutionSelect.select2({
                            placeholder: 'Institucion asociada a ' + applicationSelector.text(),
                            data: null
                        });

                        // Empty value ...
                        institutionSelect.append('<option value> Select a application of ' + applicationSelector.find("option:selected").text() + ' ...</option>');

                        $.each(data.institutions, function (key, institution) {
                            institutionSelect.append('<option value="' + institution.id + '">' + institution.name + '</option>');
                        });
                    }else {
                        institutionSelect.select2({
                            placeholder: 'No se encontraron Instituciones para ' + applicationSelector.text(),
                            data: null
                        });
                    }

                    App.unblockUI('#submit_agreement_institutional_form, #submit_agreement_institutional_edit_form');
                },
                error: function (err) {
                    alert("Ocurrio un error cargando las dependencias ...");
                }
            });
        }
    };

    var handleActionType = function () {

        $("#agreementbundle_institutional_actionType input:radio:checked").each(
            function() {
                var value = $(this).val();
                handleType(value)
            }
        );


        $("#agreementbundle_institutional_actionType").find('input').on('ifChecked', function(event){
            var value = $(event.target).val();
            handleType(value)
        });

        function handleType(type) {
            if(type =="FIR"){
                $('#agreementbundle_institutional_actionType_1').iCheck('disable');
                $(".issue-parent").hide();
            } else if(type =="REA") {
                $('#agreementbundle_institutional_actionType_0').iCheck('disable');
                $(".issue-parent").show();
            } else {
                $(".issue-parent").hide();
            }
        }
    };

    var handleAgreementInstitutionalForm = function () {
        var form = $('#submit_agreement_institutional_form, #submit_agreement_institutional_edit_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'agreementbundle_institutional[application]': {
                    required: true
                },
                'agreementbundle_institutional[institution]': {
                    required: true
                },
                'agreementbundle_institutional[actionType]': {
                    required: true
                },
                'agreementbundle_institutional[parent]': {
                    required:  function() {
                        return $("#agreementbundle_institutional_actionType input:radio:checked").val() == "REA";
                    }
                },
                'agreementbundle_institutional[number]': {
                    required: true
                },
                'agreementbundle_institutional[startDate]': {
                    required: true
                },
                'agreementbundle_institutional[endDate]': {
                    required: true,
                    isAfterStartDate: $('#agreementbundle_institutional_startDate')
                },
                'agreementbundle_institutional[benefitedAreas][]': {
                    required: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'agreementbundle_institutional[application]': {
                    required: "Seleccione la Ficha Asociada."
                },
                'agreementbundle_institutional[institution]': {
                    required: "Seleccione el nombre de la Institución Extranjera."
                },
                'agreementbundle_institutional[actionType]': {
                    required: "Seleccione una de las acciones."
                },
                'agreementbundle_institutional[parent]': {
                    required: "Seleccione Convenio anterior."
                },
                'agreementbundle_institutional[number]': {
                    required: "Inserte el número del convenio."
                },
                'agreementbundle_institutional[startDate]': {
                    required: "Inserte la fecha de inicio del convenio."
                },
                'agreementbundle_institutional[endDate]': {
                    required: "Inserte la fecha de fin del convenio.",
                    isAfterStartDate: "La fecha debe ser mayor que la de inicio."
                },
                'agreementbundle_institutional[benefitedAreas][]': {
                    required: "Inserte al menos un Área Beneficiada."
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "agreementbundle_institutional[application]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_application_error");
                }else if (element.attr("name") == "agreementbundle_institutional[institution]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_institution_error");
                }else if (element.attr("name") == "agreementbundle_institutional[actionType]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_actionType_error");
                }else if (element.attr("name") == "agreementbundle_institutional[parent]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_parent_error");
                }else if (element.attr("name") == "agreementbundle_institutional[benefitedAreas][]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_benefitedAreas_error");
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
                if (label.attr("for") == "agreementbundle_institutional[application]" ||
                    label.attr("for") == "agreementbundle_institutional[institution]" ||
                    label.attr("for") == "agreementbundle_institutional[actionType]" ||
                    label.attr("for") == "agreementbundle_institutional[parent]" ||
                    label.attr("for") == "agreementbundle_institutional[startDate]" ||
                    label.attr("for") == "agreementbundle_institutional[endDate]" ||
                    label.attr("for") == "agreementbundle_institutional[benefitedAreas][]" ) { // for checkboxes and radio buttons, no need to show OK icon
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
            handleInstitutionalApplication();
            handleActionType();
            handleAgreementInstitutionalForm();
        }

    };

}();

jQuery(document).ready(function() {
    AgreementInstitutionalForm.init();
});