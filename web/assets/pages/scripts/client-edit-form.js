var EditClient = function () {

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
            if(isInitialized(necessary)){
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

    var handleCI = function () {

        var avatarPath = '/images/clients/profile-';

        function format(gender) {
            if(gender == 'F')gender = 'female';
            else gender = 'male';

            return avatarPath + gender + ".png";
        }

        var gender = $("#clientbundle_general_info_gender").find('input:checked').val();
        $('.cropper-new.thumbnail').find('img').attr('src',format(gender));

        $("#clientbundle_general_info_gender").find('input').on('ifChecked', function(event){
            gender = $(event.target).val();
            $('.cropper-new.thumbnail').find('img').attr('src',format(gender));
        });

        $('#clientbundle_general_info_birthday').click(function () {
            $(".datepicker.datepicker-dropdown").hide();
        });

        $("#clientbundle_general_info_ci").on("blur" ,function( event ) {
            var val = $(event.target).val(),
                nyear = moment(),
                ci_year = val.substr(0,2),
                ci_month = val.substr(2,2),
                ci_day = val.substr(4,2),
                ci_date,
                ci_gen;

            if(moment(ci_year, "YY") > nyear){
                ci_date = ci_day+ci_month+'19'+ci_year;
            }else {
                ci_date = ci_day+ci_month+moment(ci_year, "YY").format("YYYY");
            }

            if(moment(ci_date,"DDMMYYYY",true).isValid()){

                $("#clientbundle_general_info_birthday").datepicker('setDate', moment(ci_date,"DDMMYYYY").format("DD/MM/YYYY"));

                $('#clientbundle_general_info_birthday').click(function () {
                    $(".datepicker.datepicker-dropdown").hide();
                });

                ci_gen = val.substr(9,1);

                if((parseInt(ci_gen) % 2) === 0){
                    $("#clientbundle_general_info_gender").find('[value="M"]').iCheck('check');
                }else {
                    $("#clientbundle_general_info_gender").find('[value="F"]').iCheck('check');
                }

            }

        });
    };

    var handleClientType = function () {
        var clientType = $("#clientbundle_general_info_clientType").find('input:checked').val();

        if(clientType == "est"){
            $(".tab-estudiante").show();
            $(".tab-trabajador").hide();
        } else {
            $(".tab-trabajador").show();
            $(".tab-estudiante").hide();

            if(clientType == 'nod'){
                $( document ).ready(function() {
                    $("#clientbundle_client_data_at_center_workersFaculty").prop("disabled", true);
                    $("#clientbundle_client_data_at_center_workersEduCategory").prop("disabled", true);
                });
            }
        }
    };

    var handleStudentsAreaCareer = function () {

        $( document ).ready(function() {
            $("#clientbundle_client_data_at_center_studentsCareer").prop("disabled", true);
        });

        $('#clientbundle_client_data_at_center_studentsFaculty').change(function () {
            var areaSelector  = $("#clientbundle_client_data_at_center_studentsFaculty.select2 option:selected");
            getDependenciesList(areaSelector);
        });

        function getDependenciesList(areaSelector) {
            $.ajax({
                type: 'post',
                url: Routing.generate('list_area_career_dependencies'),
                dataType: "JSON",
                data: {
                    'area': function() {
                        $(document).ajaxStop($.unblockUI);
                        App.blockUI({
                            target: $('#submit_client_dc_form'),
                            animate: true
                        });
                        return areaSelector.val();
                    }
                },
                success: function (data) {

                    var careerSelect   = $("#clientbundle_client_data_at_center_studentsCareer");

                    careerSelect.prop("disabled", false);
                    careerSelect.html('');

                    if (data.careers.length){
                        // Remove current options
                        careerSelect.select2({
                            placeholder: 'Carreras asociadas a ' + areaSelector.text(),
                            data: null
                        });

                        // Empty value ...
                        careerSelect.append('<option value> Select a career of ' + areaSelector.find("option:selected").text() + ' ...</option>');

                        $.each(data.careers, function (key, career) {
                            careerSelect.append('<option value="' + career.id + '">' + career.name + '</option>');
                        });
                    }else {
                        careerSelect.select2({
                            placeholder: 'No se encontraron Carreras para ' + areaSelector.text(),
                            data: null
                        });
                    }

                    App.unblockUI('#submit_client_dc_form');
                },
                error: function (err) {
                    alert("Ocurrio un error cargando las dependencias ...");
                }
            });
        }
    };

    var handleGeneralInfo = function () {
        var form = $('#submit_client_gi_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'clientbundle_general_info[ci]': {
                    minlength: 11,
                    maxlength: 11,
                    required: true,
                    ciCU: true
                },
                'clientbundle_general_info[firstName]': {
                    minlength: 3,
                    fname: true,
                    required: true
                },
                'clientbundle_general_info[secondName]': {
                    minlength: 3,
                    names: true
                },
                'clientbundle_general_info[firstLastName]': {
                    minlength: 3,
                    required: true,
                    names: true
                },
                'clientbundle_general_info[secondLastName]': {
                    minlength: 3,
                    required: true,
                    names: true
                },
                'clientbundle_general_info[birthday]': {
                    required: true,
                    birthdayCI:true
                },
                'clientbundle_general_info[gender]': {
                    required: true,
                    genderCI: true
                },
                'clientbundle_general_info[email]': {
                    required: true,
                    emailUC: true,
                },
                'clientbundle_general_info[foreignEmail]': {
                    email: true
                },
                'clientbundle_general_info[privatePhone]': {
                    minlength: 12,
                    maxlength: 13,
                    require_from_group: [1,".phone-require"],
                    fphoneCU: true
                },
                'clientbundle_general_info[cellPhone]': {
                    minlength: 13,
                    maxlength: 13,
                    require_from_group: [1,".phone-require"],
                    mphoneCU: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'clientbundle_general_info[gender]': {
                    required: "Seleccione el género.",
                    minlength: jQuery.validator.format("Seleccione al menos una categoría.")
                },
                'clientbundle_general_info[clientType]': {
                    required: "Seleccione al menos un tipo.",
                    minlength: jQuery.validator.format("Seleccione al menos una categoría.")
                },
                'clientbundle_general_info[privatePhone]': {
                    require_from_group: "Escriba al menos un teléfono de contacto."
                },
                'clientbundle_general_info[cellPhone]': {
                    require_from_group: "Escriba al menos un teléfono de contacto."
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "clientbundle_general_info[gender]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_gender_error");
                } else if (element.attr("name") == "clientbundle_general_info[clientType]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_clientType_error");
                } else if (element.attr("name") == "clientbundle_general_info[languages][]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_languages_error");
                } else if (element.attr("name") == "clientbundle_general_info[organizations][]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_organizations_error");
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
                if (label.attr("for") == "clientbundle_general_info[gender]" ||
                    label.attr("for") == "clientbundle_general_info[clientType]" ||
                    label.attr("for") == "clientbundle_general_info[languages][]" ||
                    label.attr("for") == "clientbundle_general_info[organizations][]") { // for checkboxes and radio buttons, no need to show OK icon
                    label
                        .closest('.form-group').removeClass('has-error').addClass('has-success');
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
                $("#clientbundle_general_info_privatePhone").val().unmask();
                form.submit();
                //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
            }

        });

    };

    var handlePassportInfo = function () {
        var form = $('#submit_client_dp_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //Passport data
                'clientbundle_client_data_passport[mothersName]': {
                    minlength: 3,
                },
                'clientbundle_client_data_passport[fathersName]': {
                    minlength: 3,
                },
                'clientbundle_client_data_passport[civilState]': {
                    required: false
                },
                'clientbundle_client_data_passport[weight]': {
                    number: true
                },
                'clientbundle_client_data_passport[height]': {
                    number: true
                },
                'clientbundle_client_data_passport[eyesColor]': {
                    required: false
                },
                'clientbundle_client_data_passport[skinColor]': {
                    required: false
                },
                'clientbundle_client_data_passport[hairColor]': {
                    required: false
                },
                'clientbundle_client_data_passport[pvs]': {

                },
                'clientbundle_client_data_passport[citizenship]': {

                },
                'clientbundle_client_data_passport[stateBirth]': {

                },
                'clientbundle_client_data_passport[cityBirth]': {

                },
                'clientbundle_client_data_passport[foreignCityBirth]': {

                },
                'clientbundle_client_data_passport[country]': {

                },
                'clientbundle_client_data_passport[state]': {

                },
                'clientbundle_client_data_passport[city]': {

                },
                'clientbundle_client_data_passport[area]': {

                },
                'clientbundle_client_data_passport[street]': {

                },
                'clientbundle_client_data_passport[highway]': {

                },
                'clientbundle_client_data_passport[firstBetween]': {

                },
                'clientbundle_client_data_passport[secongBetween]': {

                },
                'clientbundle_client_data_passport[number]': {

                },
                'clientbundle_client_data_passport[km]': {

                },
                'clientbundle_client_data_passport[building]': {

                },
                'clientbundle_client_data_passport[apartment]': {

                },
                'clientbundle_client_data_passport[cpa]': {

                },
                'clientbundle_client_data_passport[farm]': {

                },
                'clientbundle_client_data_passport[town]': {

                },
                'clientbundle_client_data_passport[district]': {

                },
                'clientbundle_client_data_passport[postCode]': {
                    zipcodeCU: true
                },
            },

            messages: { // custom messages for radio buttons and checkboxes
                'clientbundle_client_data_passport[civilState]': {
                    required: "Seleccione el estado civil.",
                    minlength: jQuery.validator.format("Seleccione al menos un estado civil.")
                },
                'clientbundle_client_data_passport[eyesColor]': {
                    required: "Seleccione al menos un color de ojos.",
                    minlength: jQuery.validator.format("Seleccione al menos un color de ojos.")
                },
                'clientbundle_client_data_passport[skinColor]': {
                    required: "Seleccione al menos un color de piel.",
                    minlength: jQuery.validator.format("Seleccione al menos un color de piel.")
                },
                'clientbundle_client_data_passport[hairColor]': {
                    required: "Seleccione al menos un color de cabello.",
                    minlength: jQuery.validator.format("Seleccione al menos un color de cabello.")
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "clientbundle_client_data_passport[civilState]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_civilState_error");
                } else if (element.attr("name") == "clientbundle_client_data_passport[eyesColor]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_eyesColor_error");
                } else if (element.attr("name") == "clientbundle_client_data_passport[skinColor]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_skinColor_error");
                } else if (element.attr("name") == "clientbundle_client_data_passport[hairColor]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_hairColor_error");
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
                if (label.attr("for") == "clientbundle_client_data_passport[civilState]" ||
                    label.attr("for") == "clientbundle_client_data_passport[eyesColor]" ||
                    label.attr("for") == "clientbundle_client_data_passport[skinColor]" ||
                    label.attr("for") == "clientbundle_client_data_passport[hairColor]") { // for checkboxes and radio buttons, no need to show OK icon
                    label
                        .closest('.form-group').removeClass('has-error').addClass('has-success');
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

        //Aditional Validations

        var cityExclude     = $(".city-exclude");
        var streetExclude   = $(".street-exclude");
        var numberExclude   = $(".number-exclude");

        handleExclude(cityExclude);
        handleExclude(streetExclude);
        handleExclude(numberExclude);

        //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
        $('.country_list', form).change(function () {
            form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
    };

    var handleInstitutionalInfo = function ( ) {
        var form = $('#submit_client_dc_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //worker data
                'clientbundle_client_data_at_center[workersOccupation]': {
                    minlength: 3
                },
                'clientbundle_client_data_at_center[workersSpecialty]': {
                    minlength: 3
                },
                'clientbundle_client_data_at_center[workersEduCategory]': {
                    required: false
                },
                'clientbundle_client_data_at_center[workersSciGrade]': {
                    required: false
                },
                'clientbundle_client_data_at_center[workersPosition]': {
                    minlength: 3
                },
                'clientbundle_client_data_at_center[workersWorkPlace]': {
                    minlength: 3
                },
                'clientbundle_client_data_at_center[workersAdmissionDate]': {

                },
                'clientbundle_client_data_at_center[workersWorkPhone]': {
                    minlength: 8,
                    maxlength: 8
                },
                'clientbundle_client_data_at_center[workersPay]': {
                    number: true
                },
                //student data
                'clientbundle_client_data_at_center[studentsYear]': {

                },
                'clientbundle_client_data_at_center[studentsPosition]': {
                    minlength: 3
                },
                'clientbundle_client_data_at_center[studentsCareer]': {
                    minlength: 3
                },
                'clientbundle_client_data_at_center[studentsState]': {

                }
            },

            messages: { // custom messages for radio buttons and checkboxes

            },

            errorPlacement: function (error, element) { // render error placement for each input type

                    error.insertAfter(element); // for other inputs, just perform default behavior
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

                    label
                        .addClass('valid') // mark the current input as valid and display OK icon
                        .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group

            },

            submitHandler: function (form) {
                success.show();
                error.hide();
                form.submit();
                //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
            }

        });
    };

    var handleInputMask = function () {
        $("#clientbundle_general_info_ci").inputmask({mask:"#{11}"});

        $("#clientbundle_general_info_email").inputmask("email");
        $("#clientbundle_general_info_foreignEmail").inputmask("email");


            $("#clientbundle_general_info_cellPhone").inputmask('phonecum');
        // if($("#clientbundle_general_info_cellPhone").val() == ''){
        // }else{
        //     $("#clientbundle_general_info_cellPhone").inputmask('phonecu');
        // }

            $("#clientbundle_general_info_privatePhone").inputmask('phonecuf');
        // if($("#clientbundle_general_info_privatePhone").val() == ''){
        // }else{
        //     $("#clientbundle_general_info_privatePhone").inputmask('phonecu');
        // }

        $("#clientbundle_client_data_passport_weight").inputmask({
            alias: "numeric",placeholder: "0",radixPoint: ",",digits: 2,digitsOptional: !1,
        });
        $("#clientbundle_client_data_passport_height").inputmask({
            alias: "numeric",placeholder: "0",radixPoint: ",",digits: 2,digitsOptional: !1,
        });

        $("#clientbundle_client_data_passport_postCode").inputmask("zipcodecu");

        if($("#clientbundle_client_data_at_center_workersPay").val() == ''){
        }

        $("#clientbundle_client_data_at_center_workersPay").inputmask({
            alias: "numeric",placeholder: "0",radixPoint: ",",digits: 2,digitsOptional: !1,
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            handleCI();
            handleClientType();
            handleStudentsAreaCareer();
            handleGeneralInfo();
            handlePassportInfo();
            handleInstitutionalInfo();
            handleInputMask();
        }

    };

}();

jQuery(document).ready(function() {
    EditClient.init();
});