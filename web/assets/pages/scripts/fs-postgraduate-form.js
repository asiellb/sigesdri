var FSPostgraduateForm = function () {

    var handleCI = function () {

        var avatarPath = '/images/clients/profile-';

        function format(gender) {
            if(gender == 'F')gender = 'female';
            else gender = 'male';

            return avatarPath + gender + ".png";
        }

        var gender = $("#foreingstudentbundle_postgraduate_gender").find('input:checked').val();
        $('.cropper-new.thumbnail').find('img').attr('src',format(gender));

        $("#foreingstudentbundle_postgraduate_gender").find('input').on('ifChecked', function(event){
            gender = $(event.target).val();
            $('.cropper-new.thumbnail').find('img').attr('src',format(gender));
        });

        $('#foreingstudentbundle_postgraduate_birthday').click(function () {
            $(".datepicker.datepicker-dropdown").hide();
        });

        $("#foreingstudentbundle_postgraduate_ci").on("blur" ,function( event ) {
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

                $("#foreingstudentbundle_postgraduate_birthday").datepicker('setDate', moment(ci_date,"DDMMYYYY").format("DD/MM/YYYY"));

                $('#foreingstudentbundle_postgraduate_birthday').click(function () {
                    $(".datepicker.datepicker-dropdown").hide();
                });

                ci_gen = val.substr(9,1);

                if((parseInt(ci_gen) % 2) === 0){
                    $("#foreingstudentbundle_postgraduate_gender").find('[value="M"]').iCheck('check');
                }else {
                    $("#foreingstudentbundle_postgraduate_gender").find('[value="F"]').iCheck('check');
                }

            }

        });
    };

    var handleCourseType = function () {

        $( document ).ready(function() {
            $("#foreingstudentbundle_postgraduate_course").prop("disabled", true);
        });

        $("#foreingstudentbundle_postgraduate_courseType input:radio:checked").each(
            function() {
                var value = $(this).val();
                handleType(value)
            }
        );

        $("#foreingstudentbundle_postgraduate_courseType").find('input').on('ifChecked', function(event){
            var value = $(event.target).val();
            handleType(value)
        });

        function handleType(type) {
            if(type =="CCO"){
                $('#foreingstudentbundle_postgraduate_courseType_0,#foreingstudentbundle_postgraduate_courseType_1,#foreingstudentbundle_postgraduate_courseType_2').iCheck('disable');
                $(".course-select").hide();
                $(".course-text").show();
            } else {
                $('#foreingstudentbundle_postgraduate_courseType_3').iCheck('disable');
                $(".course-text").hide();
                $(".course-select").show();

                getDependenciesList(type);
            }
        }

        function getDependenciesList(type) {
            var typeName = type == 'DOC' ? 'Doctorado' : type == 'MAE' ? 'Maestría' : type == 'ESP' ? 'Especialidad' : '';

            $.ajax({
                type: 'post',
                url: Routing.generate('list_postgraduate_courses_dependencies'),
                dataType: "JSON",
                data: {
                    'type': function() {
                        $(document).ajaxStop($.unblockUI);
                        App.blockUI({
                            target: $('#submit_postgraduate_form, #submit_postgraduate_edit_form'),
                            animate: true
                        });
                        return type;
                    }
                },
                success: function (data) {

                    var courseSelect   = $("#foreingstudentbundle_postgraduate_course");

                    courseSelect.prop("disabled", false);
                    courseSelect.html('');

                    if (data.courses.length){
                        // Remove current options
                        courseSelect.select2({
                            placeholder: 'Cursos de ' + typeName,
                            data: null
                        });

                        // Empty value ...
                        courseSelect.append('<option value> Select a application of ' + typeName + ' ...</option>');

                        $.each(data.courses, function (key, course) {
                            courseSelect.append('<option value="' + course.id + '">' + course.name + '</option>');
                        });
                    }else {
                        courseSelect.select2({
                            placeholder: 'No se encontraron Cursos de ' + typeName,
                            data: null
                        });
                    }

                    App.unblockUI('#submit_postgraduate_form, #submit_postgraduate_edit_form');
                },
                error: function (err) {
                    alert("Ocurrio un error cargando las dependencias ...");
                }
            });
        }
    };

    var handlePostgraduateForm = function () {
        var form = $('#submit_postgraduate_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'foreingstudentbundle_postgraduate[ci]': {
                    minlength: 11,
                    maxlength: 11,
                    required: true,
                    ciCU: true,
                    remote: {
                        type: 'post',
                        url: Routing.generate('postgraduate_ci_is_available'),
                        data: {
                            'ci': function() {
                                $(document).ajaxStop($.unblockUI);
                                $.blockUI({ message: null });
                                return $('#foreingstudentbundle_postgraduate_ci').val();
                            }
                        }
                    }
                },
                'foreingstudentbundle_postgraduate[names]': {
                    required: true
                },
                'foreingstudentbundle_postgraduate[lastNames]': {
                    required:  true
                },
                'foreingstudentbundle_postgraduate[birthday]': {
                    required: true,
                    //birthdayCI:true
                },
                'foreingstudentbundle_postgraduate[gender]': {
                    required: true,
                    //genderCI: true
                },
                'foreingstudentbundle_postgraduate[country]': {
                    required: true
                },
                'foreingstudentbundle_postgraduate[passportNumber]': {
                    required: true
                },
                'foreingstudentbundle_postgraduate[email]': {
                    required: true,
                    emailUC: true,
                    remote: {
                        type: 'post',
                        url: Routing.generate('postgraduate_email_is_available'),
                        data: {
                            'email': function() {
                                $(document).ajaxStop($.unblockUI);
                                $.blockUI({ message: null });
                                return $('#foreingstudentbundle_postgraduate_email').val()
                            }
                        }
                    }
                },
                'foreingstudentbundle_postgraduate[foreingEmail]': {
                    required: false,
                    email: true
                },
                'foreingstudentbundle_postgraduate[cellPhone]': {
                    required: false,
                    minlength: 13,
                    maxlength: 13,
                    mphoneCU: true
                },
                'foreingstudentbundle_postgraduate[entryDate]': {
                    required: true
                },
                'foreingstudentbundle_postgraduate[expiryDate]': {
                    required: true,
                    isAfterStartDate: $('#foreingstudentbundle_postgraduate_entryDate')
                },
                'foreingstudentbundle_postgraduate[courseType]': {
                    required: true
                },
                'foreingstudentbundle_postgraduate[course]': {
                    required: function() {
                        $("#foreingstudentbundle_postgraduate_courseType input:radio:checked").each(
                            function() {
                                var value = $(this).val();
                                handleType(value)
                            }
                        );

                        $("#foreingstudentbundle_postgraduate_courseType").find('input').on('ifChecked', function(event){
                            var value = $(event.target).val();
                            handleType(value)
                        });

                        function handleType(type) {
                            if(type !="CCO")return true;
                            else return false;
                        }
                    }
                },
                'foreingstudentbundle_postgraduate[shortCourse]': {
                    //required: function() {return $("#foreingstudentbundle_postgraduate_courseType input:radio:checked").val() == "CCO";}
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'foreingstudentbundle_postgraduate[ci]': {
                    remote: "El valor de CI ya existe."
                },
                'foreingstudentbundle_postgraduate[gender]': {
                    required: "Seleccione el género."
                },
                'foreingstudentbundle_postgraduate[country]': {
                    required: "Seleccione el país."
                },
                'foreingstudentbundle_postgraduate[email]': {
                    remote: "El email ya existe."
                },
                'foreingstudentbundle_postgraduate[courseType]': {
                    required: "Seleccione un tipo."
                },
                'foreingstudentbundle_postgraduate[course]': {
                    required: "Seleccione un curso."
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "foreingstudentbundle_postgraduate[gender]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_gender_error");
                }else if (element.attr("name") == "foreingstudentbundle_postgraduate[country]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_country_error");
                }else if (element.attr("name") == "foreingstudentbundle_postgraduate[courseType]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_courseType_error");
                }else if (element.attr("name") == "foreingstudentbundle_postgraduate[course]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_course_error");
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
                if (label.attr("for") == "foreingstudentbundle_postgraduate[courseType]" ||
                    label.attr("for") == "foreingstudentbundle_postgraduate[gender]" ||
                    label.attr("for") == "foreingstudentbundle_postgraduate[country]" ||
                    label.attr("for") == "foreingstudentbundle_postgraduate[course]" ) { // for checkboxes and radio buttons, no need to show OK icon
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

    var handlePostgraduateEditForm = function () {
        var form = $('#submit_postgraduate_edit_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                'foreingstudentbundle_postgraduate[ci]': {
                    minlength: 11,
                    maxlength: 11,
                    required: true,
                    ciCU: true
                },
                'foreingstudentbundle_postgraduate[names]': {
                    required: true
                },
                'foreingstudentbundle_postgraduate[lastNames]': {
                    required:  true
                },
                'foreingstudentbundle_postgraduate[birthday]': {
                    required: true,
                    //birthdayCI:true
                },
                'foreingstudentbundle_postgraduate[gender]': {
                    required: true,
                    //genderCI: true
                },
                'foreingstudentbundle_postgraduate[country]': {
                    required: true
                },
                'foreingstudentbundle_postgraduate[passportNumber]': {
                    required: true
                },
                'foreingstudentbundle_postgraduate[email]': {
                    required: true,
                    emailUC: true
                },
                'foreingstudentbundle_postgraduate[foreingEmail]': {
                    required: false,
                    email: true
                },
                'foreingstudentbundle_postgraduate[cellPhone]': {
                    required: false,
                    minlength: 13,
                    maxlength: 13,
                    mphoneCU: true
                },
                'foreingstudentbundle_postgraduate[entryDate]': {
                    required: true
                },
                'foreingstudentbundle_postgraduate[expiryDate]': {
                    required: true,
                    isAfterStartDate: $('#foreingstudentbundle_postgraduate_entryDate')
                },
                'foreingstudentbundle_postgraduate[courseType]': {
                    required: true
                },
                'foreingstudentbundle_postgraduate[course]': {
                    required: function() {
                        return $("#foreingstudentbundle_postgraduate_courseType input:radio:checked").val() != "CCO";
                    }
                },
                'foreingstudentbundle_postgraduate[shortCourse]': {
                    required: function() {
                        return $("#foreingstudentbundle_postgraduate_courseType input:radio:checked").val() == "CCO";
                    }
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'foreingstudentbundle_postgraduate[ci]': {

                },
                'foreingstudentbundle_postgraduate[gender]': {
                    required: "Seleccione el género."
                },
                'foreingstudentbundle_postgraduate[country]': {
                    required: "Seleccione el país."
                },
                'foreingstudentbundle_postgraduate[email]': {

                },
                'foreingstudentbundle_postgraduate[courseType]': {
                    required: "Seleccione un tipo."
                },
                'foreingstudentbundle_postgraduate[course]': {
                    required: "Seleccione un curso."
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "foreingstudentbundle_postgraduate[gender]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_gender_error");
                }else if (element.attr("name") == "foreingstudentbundle_postgraduate[country]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_country_error");
                }else if (element.attr("name") == "foreingstudentbundle_postgraduate[courseType]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_courseType_error");
                }else if (element.attr("name") == "foreingstudentbundle_postgraduate[course]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_course_error");
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
                if (label.attr("for") == "foreingstudentbundle_postgraduate[courseType]" ||
                    label.attr("for") == "foreingstudentbundle_postgraduate[gender]" ||
                    label.attr("for") == "foreingstudentbundle_postgraduate[country]" ||
                    label.attr("for") == "foreingstudentbundle_postgraduate[course]" ) { // for checkboxes and radio buttons, no need to show OK icon
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
        $("#foreingstudentbundle_postgraduate_ci").inputmask({mask:"#{11}"});

        $("#foreingstudentbundle_postgraduate_email").inputmask("email");
        $("#foreingstudentbundle_postgraduate_foreignEmail").inputmask("email");

        $("#foreingstudentbundle_postgraduate_cellPhone").inputmask("phonecum");
    };

    return {
        //main function to initiate the module
        init: function () {
            handleCI();
            handleCourseType();
            handlePostgraduateForm();
            handlePostgraduateEditForm();
            handleInputMask();
        }

    };

}();

jQuery(document).ready(function() {
    FSPostgraduateForm.init();
});