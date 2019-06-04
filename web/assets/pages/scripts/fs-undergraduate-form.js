var FSUndergraduateForm = function () {

    var handleCI = function () {

        var avatarPath = '/images/clients/profile-';

        function format(gender) {
            if(gender == 'F')gender = 'female';
            else gender = 'male';

            return avatarPath + gender + ".png";
        }

        var gender = $("#foreingstudentbundle_undergraduate_gender").find('input:checked').val();
        $('.cropper-new.thumbnail').find('img').attr('src',format(gender));

        $("#foreingstudentbundle_undergraduate_gender").find('input').on('ifChecked', function(event){
            gender = $(event.target).val();
            $('.cropper-new.thumbnail').find('img').attr('src',format(gender));
        });

        $('#foreingstudentbundle_undergraduate_birthday').click(function () {
            $(".datepicker.datepicker-dropdown").hide();
        });

        $("#foreingstudentbundle_undergraduate_ci").on("blur" ,function( event ) {
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

                $("#foreingstudentbundle_undergraduate_birthday").datepicker('setDate', moment(ci_date,"DDMMYYYY").format("DD/MM/YYYY"));

                $('#foreingstudentbundle_undergraduate_birthday').click(function () {
                    $(".datepicker.datepicker-dropdown").hide();
                });

                ci_gen = val.substr(9,1);

                if((parseInt(ci_gen) % 2) === 0){
                    $("#foreingstudentbundle_undergraduate_gender").find('[value="M"]').iCheck('check');
                }else {
                    $("#foreingstudentbundle_undergraduate_gender").find('[value="F"]').iCheck('check');
                }

            }

        });
    };

    var handleUndergraduateForm = function () {
        var form = $('#submit_undergraduate_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'foreingstudentbundle_undergraduate[type]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[ci]': {
                    minlength: 11,
                    maxlength: 11,
                    required: true,
                    ciCU: true,
                    remote: {
                        type: 'post',
                        url: Routing.generate('undergraduate_ci_is_available'),
                        data: {
                            'ci': function() {
                                $(document).ajaxStop($.unblockUI);
                                $.blockUI({ message: null });
                                return $('#foreingstudentbundle_undergraduate_ci').val();
                            }
                        }
                    }
                },
                'foreingstudentbundle_undergraduate[names]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[lastNames]': {
                    required:  true
                },
                'foreingstudentbundle_undergraduate[birthday]': {
                    required: true,
                    //birthdayCI:true
                },
                'foreingstudentbundle_undergraduate[gender]': {
                    required: true,
                    genderCI: true
                },
                'foreingstudentbundle_undergraduate[country]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[passportNumber]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[email]': {
                    required: true,
                    emailUC: true,
                    remote: {
                        type: 'post',
                        url: Routing.generate('undergraduate_email_is_available'),
                        data: {
                            'email': function() {
                                $(document).ajaxStop($.unblockUI);
                                $.blockUI({ message: null });
                                return $('#foreingstudentbundle_undergraduate_email').val()
                            }
                        }
                    }
                },
                'foreingstudentbundle_undergraduate[foreingEmail]': {
                    required: false,
                    email: true
                },
                'foreingstudentbundle_undergraduate[cellPhone]': {
                    required: false,
                    minlength: 13,
                    maxlength: 13,
                    mphoneCU: true
                },
                'foreingstudentbundle_undergraduate[entryDate]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[expiryDate]': {
                    required: true,
                    isAfterStartDate: $('#foreingstudentbundle_undergraduate_entryDate')
                },
                'foreingstudentbundle_undergraduate[career]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[year]': {
                    required: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'foreingstudentbundle_undergraduate[type]': {
                    required: "Seleccione un tipo."
                },
                'foreingstudentbundle_undergraduate[ci]': {
                    remote: "El valor de CI ya existe."
                },
                'foreingstudentbundle_undergraduate[gender]': {
                    required: "Seleccione el género."
                },
                'foreingstudentbundle_undergraduate[country]': {
                    required: "Seleccione el país."
                },
                'foreingstudentbundle_undergraduate[email]': {
                    remote: "El email ya existe."
                },
                'foreingstudentbundle_undergraduate[career]': {
                    required: "Seleccione la carrera."
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "foreingstudentbundle_undergraduate[type]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_type_error");
                }else if (element.attr("name") == "foreingstudentbundle_undergraduate[gender]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_gender_error");
                }else if (element.attr("name") == "foreingstudentbundle_undergraduate[country]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_country_error");
                }else if (element.attr("name") == "foreingstudentbundle_undergraduate[career]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_career_error");
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
                if (label.attr("for") == "foreingstudentbundle_undergraduate[type]" ||
                    label.attr("for") == "foreingstudentbundle_undergraduate[gender]" ||
                    label.attr("for") == "foreingstudentbundle_undergraduate[country]" ||
                    label.attr("for") == "foreingstudentbundle_undergraduate[career]" ) { // for checkboxes and radio buttons, no need to show OK icon
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

    var handleUndergraduateEditForm = function () {
        var form = $('#submit_undergraduate_edit_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'foreingstudentbundle_undergraduate[type]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[ci]': {
                    minlength: 11,
                    maxlength: 11,
                    required: true,
                    ciCU: true
                },
                'foreingstudentbundle_undergraduate[names]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[lastNames]': {
                    required:  true
                },
                'foreingstudentbundle_undergraduate[birthday]': {
                    required: true,
                    //birthdayCI:true
                },
                'foreingstudentbundle_undergraduate[gender]': {
                    required: true,
                    genderCI: true
                },
                'foreingstudentbundle_undergraduate[country]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[passportNumber]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[email]': {
                    required: true,
                    emailUC: true
                },
                'foreingstudentbundle_undergraduate[foreingEmail]': {
                    required: false,
                    email: true
                },
                'foreingstudentbundle_undergraduate[cellPhone]': {
                    required: false,
                    minlength: 13,
                    maxlength: 13,
                    mphoneCU: true
                },
                'foreingstudentbundle_undergraduate[entryDate]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[expiryDate]': {
                    required: true,
                    isAfterStartDate: $('#foreingstudentbundle_undergraduate_entryDate')
                },
                'foreingstudentbundle_undergraduate[career]': {
                    required: true
                },
                'foreingstudentbundle_undergraduate[year]': {
                    required: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'foreingstudentbundle_undergraduate[type]': {
                    required: "Seleccione un tipo."
                },
                'foreingstudentbundle_undergraduate[ci]': {

                },
                'foreingstudentbundle_undergraduate[gender]': {
                    required: "Seleccione el género."
                },
                'foreingstudentbundle_undergraduate[country]': {
                    required: "Seleccione el país."
                },
                'foreingstudentbundle_undergraduate[email]': {

                },
                'foreingstudentbundle_undergraduate[career]': {
                    required: "Seleccione la carrera."
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "foreingstudentbundle_undergraduate[type]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_type_error");
                }else if (element.attr("name") == "foreingstudentbundle_undergraduate[gender]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_gender_error");
                }else if (element.attr("name") == "foreingstudentbundle_undergraduate[country]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_country_error");
                }else if (element.attr("name") == "foreingstudentbundle_undergraduate[career]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_career_error");
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
                if (label.attr("for") == "foreingstudentbundle_undergraduate[type]" ||
                    label.attr("for") == "foreingstudentbundle_undergraduate[gender]" ||
                    label.attr("for") == "foreingstudentbundle_undergraduate[country]" ||
                    label.attr("for") == "foreingstudentbundle_undergraduate[career]" ) { // for checkboxes and radio buttons, no need to show OK icon
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

    var handleInputMask = function () {
        $("#foreingstudentbundle_undergraduate_ci").inputmask({mask:"#{11}"});

        $("#foreingstudentbundle_undergraduate_email").inputmask("email");
        $("#foreingstudentbundle_undergraduate_foreignEmail").inputmask("email");

        $("#foreingstudentbundle_undergraduate_cellPhone").inputmask("phonecum");
    };

    return {
        //main function to initiate the module
        init: function () {
            handleCI();
            handleUndergraduateForm();
            handleUndergraduateEditForm();
            handleInputMask();
        }

    };

}();

jQuery(document).ready(function() {
    FSUndergraduateForm.init();
});