var NewClient = function () {

    var handleCI = function () {

        var avatarPath = '/images/clients/profile-';

        function format(gender) {
            if(gender == 'F')gender = 'female';
            else gender = 'male';

            return avatarPath + gender + ".png";
        }

        var gender = $("#clientbundle_client_gender").find('input:checked').val();
        $('.cropper-new.thumbnail').find('img').attr('src',format(gender));

        $("#clientbundle_client_gender").find('input').on('ifChecked', function(event){
            gender = $(event.target).val();
            $('.cropper-new.thumbnail').find('img').attr('src',format(gender));
        });

        $('#clientbundle_client_birthday').click(function () {
            $(".datepicker.datepicker-dropdown").hide();
        });

        $("#clientbundle_client_ci").on("blur" ,function( event ) {
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

                $("#clientbundle_client_birthday").datepicker('setDate', moment(ci_date,"DDMMYYYY").format("DD/MM/YYYY"));

                $('#clientbundle_client_birthday').click(function () {
                    $(".datepicker.datepicker-dropdown").hide();
                });

                ci_gen = val.substr(9,1);

                if((parseInt(ci_gen) % 2) === 0){
                    $("#clientbundle_client_gender").find('[value="M"]').iCheck('check');
                }else {
                    $("#clientbundle_client_gender").find('[value="F"]').iCheck('check');
                }

            }

        });
    };

    var handleClientType = function () {
        $("#clientbundle_client_clientType").find('input').on('ifChecked', function(event){
            var value = $(event.target).val();
            if(value =="est"){
                $(".tab-estudiante").show();
                $(".tab-trabajador").hide();
            } else {
                $(".tab-trabajador").show();
                $(".tab-estudiante").hide();

                if(value == 'nod'){
                    $("#clientbundle_client_workersFaculty").prop("disabled", true);
                }
            }
        });
    };

    var handleDefaultValues = function () {
        $('#clientbundle_client_countryBirth, #clientbundle_client_country').val('CUB');
        $('#clientbundle_client_countryBirth, #clientbundle_client_country').trigger('change');
    };

    var handleStudentsAreaCareer = function () {

        $( document ).ready(function() {
            $("#clientbundle_client_studentsCareer").prop("disabled", true);
        });

        $('#clientbundle_client_studentsFaculty').change(function () {
            var areaSelector  = $("#clientbundle_client_studentsFaculty.select2 option:selected");
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
                            target: $('#submit_client_form'),
                            animate: true
                        });
                        return areaSelector.val();
                    }
                },
                success: function (data) {

                    var careerSelect   = $("#clientbundle_client_studentsCareer");

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

                    App.unblockUI('#submit_client_form');
                },
                error: function (err) {
                    alert("Ocurrio un error cargando las dependencias ...");
                }
            });
        }
    };

    var handleFormWizard = function ( ) {
        if (!jQuery().bootstrapWizard) {
            return;
        }

        var form = $('#submit_client_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'clientbundle_client[ci]': {
                    minlength: 11,
                    maxlength: 11,
                    required: true,
                    ciCU: true,
                    remote: {
                        type: 'post',
                        url: Routing.generate('client__ci_is_available'),
                        data: {
                            'ci': function() {
                                $(document).ajaxStop($.unblockUI);
                                $.blockUI({ message: null });
                                return $('#clientbundle_client_ci').val();
                            }
                        }
                    }
                },
                'clientbundle_client[firstName]': {
                    minlength: 3,
                    fname: true,
                    required: true
                },
                'clientbundle_client[secondName]': {
                    minlength: 3,
                    names: true
                },
                'clientbundle_client[firstLastName]': {
                    minlength: 3,
                    names: true,
                    required: true
                },
                'clientbundle_client[secondLastName]': {
                    minlength: 3,
                    names: true,
                    required: true
                },
                'clientbundle_client[birthday]': {
                    required: true,
                    birthdayCI:true
                },
                'clientbundle_client[gender]': {
                    required: true,
                    genderCI: true
                },
                'clientbundle_client[email]': {
                    required: true,
                    emailUC: true,
                    remote: {
                        type: 'post',
                        url: Routing.generate('client__email_is_available'),
                        data: {
                            'email': function() {
                                $(document).ajaxStop($.unblockUI);
                                $.blockUI({ message: null });
                                return $('#clientbundle_client_email').val()
                            }
                        }
                    }
                },
                'clientbundle_client[foreignEmail]': {
                    email: true
                },
                'clientbundle_client[privatePhone]': {
                    minlength: 12,
                    maxlength: 13,
                    require_from_group: [1,".phone-require"],
                    fphoneCU: true
                },
                'clientbundle_client[cellPhone]': {
                    minlength: 13,
                    maxlength: 13,
                    require_from_group: [1,".phone-require"],
                    mphoneCU: true
                },

                //Passport data
                'clientbundle_client[mothersName]': {

                },
                'clientbundle_client[fathersName]': {

                },
                'clientbundle_client[civilState]': {
                    required: false
                },
                'clientbundle_client[weight]': {
                    number: true
                },
                'clientbundle_client[height]': {
                    number: true
                },
                'clientbundle_client[eyesColor]': {
                    required: false
                },
                'clientbundle_client[skinColor]': {
                    required: false
                },
                'clientbundle_client[hairColor]': {
                    required: false
                },
                'clientbundle_client[pvs]': {

                },
                'clientbundle_client[citizenship]': {

                },
                'clientbundle_client[stateBirth]': {

                },
                'clientbundle_client[cityBirth]': {

                },
                'clientbundle_client[foreignCityBirth]': {

                },
                'clientbundle_client[country]': {

                },
                'clientbundle_client[state]': {

                },
                'clientbundle_client[city]': {

                },
                'clientbundle_client[area]': {

                },
                'clientbundle_client[street]': {

                },
                'clientbundle_client[highway]': {

                },
                'clientbundle_client[firstBetween]': {

                },
                'clientbundle_client[secongBetween]': {

                },
                'clientbundle_client[number]': {

                },
                'clientbundle_client[km]': {

                },
                'clientbundle_client[building]': {

                },
                'clientbundle_client[apartment]': {

                },
                'clientbundle_client[cpa]': {

                },
                'clientbundle_client[farm]': {

                },
                'clientbundle_client[town]': {

                },
                'clientbundle_client[district]': {

                },
                'clientbundle_client[postCode]': {
                    zipcodeCU: true
                },
                //worker data
                'clientbundle_client[workersOccupation]': {

                },
                'clientbundle_client[workersSpecialty]': {

                },
                'clientbundle_client[workersEduCategory]': {
                    required: false
                },
                'clientbundle_client[workersSciGrade]': {
                    required: false
                },
                'clientbundle_client[workersPosition]': {

                },
                'clientbundle_client[workersWorkPlace]': {

                },
                'clientbundle_client[workersAdmissionDate]': {

                },
                'clientbundle_client[workersWorkPhone]': {

                },
                'clientbundle_client[workersPay]': {
                    currency: [""]
                },
                //student data
                'clientbundle_client[studentsYear]': {

                },
                'clientbundle_client[studentsPosition]': {

                },
                'clientbundle_client[studentsCareer]': {

                },
                'clientbundle_client[studentsState]': {

                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'clientbundle_client[ci]': {
                    remote: "El valor de CI ya existe."
                },
                'clientbundle_client[email]': {
                    remote: "El valor de Correo Institucional ya existe."
                },
                'clientbundle_client[gender]': {
                    required: "Seleccione el género.",
                    minlength: jQuery.validator.format("Seleccione al menos una categoría.")
                },
                'clientbundle_client[clientType]': {
                    required: "Seleccione al menos un tipo.",
                    minlength: jQuery.validator.format("Seleccione al menos una categoría.")
                },
                'clientbundle_client[privatePhone]': {
                    require_from_group: "Escriba al menos un teléfono de contacto."
                },
                'clientbundle_client[cellPhone]': {
                    require_from_group: "Escriba al menos un teléfono de contacto."
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "clientbundle_client[gender]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_gender_error");
                } else if (element.attr("name") == "clientbundle_client[clientType]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_clientType_error");
                } else if (element.attr("name") == "clientbundle_client[languages][]") { // for uniform checkboxes, insert the after the given container
                    error.insertAfter("#form_languages_error");
                } else if (element.attr("name") == "clientbundle_client[organizations][]") { // for uniform checkboxes, insert the after the given container
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
                if (label.attr("for") == "clientbundle_client[gender]" ||
                    label.attr("for") == "clientbundle_client[clientType]" ||
                    label.attr("for") == "clientbundle_client[languages][]" ||
                    label.attr("for") == "clientbundle_client[organizations][]") { // for checkboxes and radio buttons, no need to show OK icon
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

        var displayConfirm = function() {
            var fullname,parents,weight,height,birthplace,address;
            $('#tab4 .form-control-static', form).each(function(){
                var input = $('[name="'+$(this).attr("data-display")+'"]', form);
                var value = input.val();
                var type = input.attr('name');

                if (input.is(":radio")) {
                    input = $('[name="'+$(this).attr("data-display")+'"]:checked', form);
                }

                if (value == '') {
                    $(this).remove();
                }else if (input.is(":text") || input.is("textarea") || input.attr('type') == 'email') {
                    $(this).removeClass('disappear').html(input.val());
                    if($(this).hasClass('coma')){$(this).append(', ');}
                    if($(this).hasClass('home-num')){$(this).prepend('#');}
                    if($(this).hasClass('btwn')){$(this).prepend('entre ');}
                    if($(this).hasClass('and-btwn')){$(this).prepend('y ');}
                    if($(this).hasClass('money')){$(this).prepend('$');}
                    if($(this).hasClass('edif')){$(this).prepend('Edif. ');}
                    if($(this).hasClass('apto')){$(this).prepend('Apto. ');}
                    if($(this).hasClass('kmt')){$(this).prepend('Km. ');}
                } else if (input.is("select")) {
                    if(input.attr('multiple')){
                        var selected = '';
                        input.find('option:selected:not(:last)').each(function () {
                            selected = selected + input.find($(this)).text() + ', ';
                        });
                        selected = selected + input.find('option:selected:last').text();
                        $(this).html(selected);
                    }else {
                        $(this).html(input.find('option:selected').text());
                    }
                } else if (input.is(":radio") && input.is(":checked")) {
                    $(this).html(input.attr("data-title"));
                }else if (input.is(":hidden")) {
                    $(this).attr('src',input.val());
                }
            });
        };

        // $("[data-method='getCroppedCanvas']").click(function () {
        //     alert(image_canvas.toDataURL(this.$el.data('mimetype'), this.$el.data('quality')));
        // });

        var handleTitle = function(tab, navigation, index) {
            var total = navigation.find('li').length;

            var current = index + 1;

            // set wizard title
            $('.step-title', $('#form_wizard_client')).text('Paso ' + (index + 1) + ' de ' + total);

            // set done steps
            jQuery('li', $('#form_wizard_client')).removeClass("done").removeClass("error");
            var li_list = navigation.find('li');
            for (var i = 0; i < index; i++) {
                jQuery(li_list[i]).addClass("done");
            }

            if (current == 1) {
                $('#form_wizard_client').find('.button-previous').hide();
            } else {
                $('#form_wizard_client').find('.button-previous').show();
            }

            if (current >= total) {
                $('#form_wizard_client').find('.button-next').hide();
                $('#form_wizard_client').find('.button-submit').show();
                displayConfirm();
            } else {
                $('#form_wizard_client').find('.button-next').show();
                $('#form_wizard_client').find('.button-submit').hide();
            }
            App.scrollTo($('.page-title'));
        };

        var handleError = function(tab, navigation, index) {
            var total = navigation.find('li').length;

            var current = index + 1;

            // set wizard title
            $('.step-title', $('#form_wizard_client')).text('Paso ' + (index + 1) + ' de ' + total);

            // set done steps
            jQuery('li', $('#form_wizard_client')).removeClass("done");
            var li_list = navigation.find('li');
            for (var i = 0; i < index; i++) {
                jQuery(li_list[i]).addClass("error");
            }

            App.scrollTo($('.page-title'));
        };

        // default form wizard
        $('#form_wizard_client').bootstrapWizard({
            'nextSelector': '.button-next',
            'previousSelector': '.button-previous',
            onTabClick: function (tab, navigation, index, clickedIndex) {
                success.hide();
                error.hide();
                if (form.valid() == false) {
                    return false;
                }

                handleTitle(tab, navigation, clickedIndex);
            },
            onNext: function (tab, navigation, index) {
                success.hide();
                error.hide();

                if (form.valid() == false) {
                    handleError(tab, navigation, index);
                    return false;
                }

                handleTitle(tab, navigation, index);
            },
            onPrevious: function (tab, navigation, index) {
                success.hide();
                error.hide();

                handleTitle(tab, navigation, index);
            },
            onTabShow: function (tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                var $percent = (current / total) * 100;
                $('#form_wizard_client').find('.progress-bar').css({
                    width: $percent + '%'
                });
            }
        });

        $('#form_wizard_client').find('.button-previous').hide();
        $('#form_wizard_client .button-submit').click(function () {
            alert('Formulario completo');
        }).hide();



        //Aditional Validations


        var cityExclude     = $(".city-exclude");
        var streetExclude   = $(".street-exclude");
        var numberExclude   = $(".number-exclude");

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

        handleExclude(cityExclude);
        handleExclude(streetExclude);
        handleExclude(numberExclude);

        //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
        $('.country_list', form).change(function () {
            form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
    };

    var handleInputMask = function () {
        $("#clientbundle_client_ci").inputmask({mask:"#{11}"});

        $("#clientbundle_client_email").inputmask("email");
        $("#clientbundle_client_foreignEmail").inputmask("email");

        $("#clientbundle_client_cellPhone").inputmask("phonecum");
        $("#clientbundle_client_privatePhone").inputmask('phonecuf');

        $("#clientbundle_client_weight").inputmask("numeric");
        $("#clientbundle_client_height").inputmask("numeric");

        $("#clientbundle_client_postCode").inputmask("zipcodecu");

        $("#clientbundle_client_workersPay").inputmask("currency");
    };

    return {
        //main function to initiate the module
        init: function () {
            handleCI();
            handleClientType();
            handleDefaultValues();
            handleStudentsAreaCareer();
            handleFormWizard();
            handleInputMask();
        }

    };

}();

jQuery(document).ready(function() {
    NewClient.init();
});