var PassportForm = function () {

    var handleFirstPagePicture = function () {

        var fpPath = '/assets/global/img/passport_cover/pass-';

        function format(type) {
            if(type == 'COR')type = 'ord';
            else type = 'ofi';

            return fpPath + type + ".png";
        }

        var type = $("#passportbundle_passport_type").find('input:checked').val();
        $('.cropper-new.thumbnail').find('img').attr('src',format(type));

        $("#passportbundle_passport_type").find('input').on('ifChecked', function(event){
            type = $(event.target).val();
            $('.cropper-new.thumbnail').find('img').attr('src',format(type));
        });

        $("#passportbundle_passport_number").on("blur" ,function( event ) {
            var val = $(event.target).val(),
                letter;

            letter = val.substr(0,1);

            //alert(letter);

            if(letter == "E" || letter == "X"){
                $("#passportbundle_passport_type").find('[value="OFI"]').iCheck('check');
            }else if(letter == "H" || letter == "I" || letter == "J"){
                $("#passportbundle_passport_type").find('[value="ORD"]').iCheck('check');
            }

            //alert(letter);
        });
    };

    var handlePassportHolder = function () {
        $("#show-client-ci").on('click',function() {
            $("#client-ci").show();
            $("#holder").hide();
        });
        $("#show-holder").on('click',function() {
            $("#holder").show();
            $("#client-ci").hide();
        });
    };

    var handlePassportExtensions = function () {
        $("#passportbundle_passport_firstExtension").change(function() {
            if($(this).prop('checked')){
                $(".first-extension-issues").show();
            }else {
                $(".first-extension-issues").hide();
            }
        });

        $("#passportbundle_passport_secondExtension").change(function() {
            if($(this).prop('checked')){
                $(".second-extension-issues").show();
            }else {
                $(".second-extension-issues").hide();
            }
        });
    };

    var handlePassportDrop = function () {
        $("#passportbundle_passport_drop").change(function() {
            if($(this).prop('checked')){
                $(".drop-issues").show();
            }else {
                $(".drop-issues").hide();
            }
        });
    };

    var handlePassportApplication = function () {
        $( document ).ready(function() {
            $("#passportbundle_passport_application").prop("disabled", true);

            if($('#passport-holder-selected').length){
                if (!($("#passport-holder-selected.select2 option:selected").val() == "")){
                    var holderSelector  = $("#passport-holder-selected.select2 option:selected");
                }

                if($('#passportbundle_passport_application').length){
                    getApplicationList(holderSelector);
                }
            }

        });

        $('#passportbundle_passport_holder').change(function () {
            //var holderSelector = $(this);
            var holderSelector  = $("#passportbundle_passport_holder.select2 option:selected");

            getApplicationList(holderSelector);
        });

        function getApplicationList(holderSelector) {
            $.ajax({
                type: 'post',
                url: Routing.generate('list_client_applications'),
                dataType: "JSON",
                data: {
                    'holder': function() {
                        $(document).ajaxStop($.unblockUI);
                        App.blockUI({
                            target: $('#submit_passport_form, #submit_passport_edit_form'),
                            animate: true
                        });
                        return holderSelector.val();
                    }
                },
                success: function (data) {

                    var applicationSelect   = $("#passportbundle_passport_application");

                    applicationSelect.prop("disabled", false);
                    applicationSelect.html('');

                    if (data.applications.length > 0){
                        // Remove current options
                        applicationSelect.select2({
                            placeholder: 'Solicitudes de ' + holderSelector.text(),
                            data: null
                        });

                        // Empty value ...
                        applicationSelect.append('<option value> Select a application of ' + holderSelector.find("option:selected").text() + ' ...</option>');

                        $.each(data.applications, function (key, application) {
                            applicationSelect.append('<option value="' + application.id + '">' + application.number + '</option>');
                        });
                    }else {
                        applicationSelect.select2({
                            placeholder: 'No se encontraron Solicitudes para ' + holderSelector.text(),
                            data: null
                        });
                    }

                    App.unblockUI('#submit_passport_form, #submit_passport_edit_form');
                },
                error: function (err) {
                    alert("Ocurrio un error cargando las solicitudes ...");
                }
            });
        };
    };

    var handlePassportNewForm = function () {
        var form = $('#submit_passport_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'passportbundle_passport[holder]': {
                    //required: true
                },
                'passportbundle_passport[application]': {
                    //required: true
                },
                'passportbundle_passport[clientCi]': {
                    minlength: 11,
                    maxlength: 11,
                    required: false,
                    ciCU: true
                },
                'passportbundle_passport[number]': {
                    required: true,
                    passCU:true,
                    remote: {
                        type: 'post',
                        url: Routing.generate('passport__number_is_available'),
                        data: {
                            'number': function() {
                                $(document).ajaxStop($.unblockUI);
                                $.blockUI({ message: null });
                                return $('#passport_number').val();
                            }
                        }
                    }
                },
                'passportbundle_passport[type]': {
                    required: true,
                    typePass: true
                },
                'passportbundle_passport[issueDate]': {
                    required: true
                },
                'passportbundle_passport[expiryDate]': {
                    required: true,
                    isAfterStartDate: $('#passportbundle_passport_issueDate')
                },
                'passportbundle_passport[drop]': {
                    required: false
                },
                'passportbundle_passport[dropReason]': {
                    maxlength: 255
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'passportbundle_passport[number]': {
                    remote: "El Número de pasaporte ya existe."
                },
                'passportbundle_passport[holder]': {
                    required: "Este campo es obligatorio.",
                    minlength: jQuery.validator.format("Seleccione al menos un cliente.")
                },
                'passportbundle_passport[type]': {
                    required: "Seleccione el Tipo de Pasaporte.",
                    minlength: jQuery.validator.format("Seleccione al menos un tipo.")
                },
                'passportbundle_passport[expiryDate]': {
                    isAfterStartDate: "La fecha debe ser mayor que la de emisión."
                },
                'passportbundle_passport[state]': {
                    required: "Seleccione el Estado del Pasaporte.",
                    minlength: jQuery.validator.format("Seleccione al menos un estado.")
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "passportbundle_passport[holder]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_holder_error");
                } else if (element.attr("name") == "passportbundle_passport[type]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_type_error");
                } else if (element.attr("name") == "passportbundle_passport[state]") { // for uniform checkboxes, insert the after the given container
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
                if (label.attr("for") == "passportbundle_passport[holder]" ||
                    label.attr("for") == "passportbundle_passport[type]" ||
                    label.attr("for") == "passportbundle_passport[state]" ) { // for checkboxes and radio buttons, no need to show OK icon
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

    var handlePassportEditForm = function () {
        var form = $('#submit_passport_edit_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'passportbundle_passport[holder]': {
                    required: true
                },
                'passportbundle_passport[clientCi]': {
                    minlength: 11,
                    maxlength: 11,
                    required: false,
                    ciCU: true
                },
                'passportbundle_passport[number]': {
                    required: true,
                    passCU:true
                },
                'passportbundle_passport[type]': {
                    required: true,
                    typePass: true
                },
                'passportbundle_passport[issueDate]': {
                    required: true
                },
                'passportbundle_passport[expiryDate]': {
                    required: true,
                    isAfterStartDate: true
                },
                'passportbundle_passport[state]': {
                    required: true
                },
                'passportbundle_passport[dropDate]': {
                    required: function() {
                        $("#passportbundle_passport_drop").change(function() {
                            if($(this).prop('checked')){
                                return true;
                            }else {
                                return false;
                            }
                        });
                    }
                },
                'passportbundle_passport[dropReason]': {
                    required: function() {
                        $("#passportbundle_passport_drop").change(function() {
                            if($(this).prop('checked')){
                                return true;
                            }else {
                                return false;
                            }
                        });
                    },
                    maxlength: 255
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'passportbundle_passport[number]': {
                    remote: "El Número de pasaporte ya existe."
                },
                'passportbundle_passport[holder]': {
                    required: "Este campo es obligatorio.",
                    minlength: jQuery.validator.format("Seleccione al menos un cliente.")
                },
                'passportbundle_passport[type]': {
                    required: "Seleccione el Tipo de Pasaporte.",
                    minlength: jQuery.validator.format("Seleccione al menos un tipo.")
                },
                'passportbundle_passport[expiryDate]': {
                    isAfterStartDate: "La fecha debe ser mayor que la de emisión."
                },
                'passportbundle_passport[state]': {
                    required: "Seleccione el Estado del Pasaporte.",
                    minlength: jQuery.validator.format("Seleccione al menos un estado.")
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "passportbundle_passport[holder]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_holder_error");
                } else if (element.attr("name") == "passportbundle_passport[type]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_type_error");
                } else if (element.attr("name") == "passportbundle_passport[state]") { // for uniform checkboxes, insert the after the given container
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
                if (label.attr("for") == "passportbundle_passport[holder]" ||
                    label.attr("for") == "passportbundle_passport[type]" ||
                    label.attr("for") == "passportbundle_passport[state]" ) { // for checkboxes and radio buttons, no need to show OK icon
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
            handleFirstPagePicture();
            handlePassportHolder();
            handlePassportExtensions();
            handlePassportDrop();
            handlePassportApplication();
            handlePassportNewForm();
            handlePassportEditForm();
        }

    };

}();

jQuery(document).ready(function() {
    PassportForm.init();
});