var ManagerTravelPlanForm = function () {

    var handleManagerTravelPlanState = function () {

        $("#exitbundle_managertravelplan_state input:radio:checked").each(
            function() {
                var value = $(this).val();
                handleState(value)
            }
        );


        $("#exitbundle_managertravelplan_state").find('input').on('ifChecked', function(event){
            var value = $(event.target).val();
            handleState(value)
        });

        function handleState(state) {
            if(state =="APR"){
                $('#exitbundle_managertravelplan_state_0, #exitbundle_managertravelplan_state_2').iCheck('disable');
                $(".approved").show();
                $(".rejected").hide();
            } else if(state =="REC") {
                $('#exitbundle_managertravelplan_state_0, #exitbundle_managertravelplan_state_1').iCheck('disable');
                $(".rejected").show();
                $(".approved").hide();
            } else {
                $(".rejected").hide();
                $(".approved").hide();
            }
        }
    };

    var handleManagerTravelPlanFinancing = function () {
        $('.economics').collection({
            //allow_duplicate: false,
            allow_up: false,
            allow_down: false,
            add: '<a id="collection-add" href="#" class="collection-add btn btn-circle btn-icon-only default" title="Agregar Elemento" style="margin-top: 10px;"><span class="fa fa-plus"></span></a>',
            // here is the magic!
            elements_selector: 'tr.item',
            elements_parent_selector: '%id% tbody',
            fade_in: true,
            fade_out: true,
            add_at_the_end: true,
            after_add: function(collection, element) {
                //alert('entro');
                $('#exitbundle_managertravelplan_financing').find('.select2').select2({
                    placeholder: 'Seleccione de la lista ...',
                    width: null,
                    allowClear: true
                });
                $('#exitbundle_managertravelplan_financing').find('.bs-select').selectpicker({
                    iconBase: 'fa',
                    tickIcon: 'fa-close'
                });
                return true;
            }
        });

        /*$('#collection-add').on('click', '.exitbundle_managertravelplan_financing-collection-add',function () {
            alert('entro');
        });

        var bt_count = 0;
        $("#blog-test-cont .blog-test").bind("click", function(){
            $(this).after("<p class=\"blog-test\">Pulsa para probar " + (++bt_count) + "</p>");
        });

        $("#blog-test-cont").on("click", ".blog-test", function(){
            $(this).after("<p class=\"blog-test\">Pulsa para probar " + (++bt_count) + "</p>");
        });*/
    };

    var handleManagerTravelPlanDepartureDate = function () {
        if (jQuery().datepicker) {
            $('.for-next-year').datepicker({
                rtl: App.isRTL(),
                format: 'dd/mm/yyyy',
                language: "es",
                orientation: "left",
                autoclose: true,
                enableOnReadonly: false,
                placeholder: 'Fecha de salida ...',
                startDate: moment().startOf('year').add(1,'year').format('DD/MM/YYYY'),
                endDate: moment().endOf('year').add(1,'year').format('DD/MM/YYYY')
            });
        }
    };

    var handleManagerTravelPlanApproval = function () {
        $("#exitbundle_managertravelplan_approvalDate").prop("disabled", true);
        $("#exitbundle_managertravelplan_approvalObservations").prop("disabled", true);

        $("#exitbundle_managertravelplan_approval").change(function() {
            if($(this).prop('checked')){
                $("#exitbundle_managertravelplan_approvalDate").prop("disabled", false);
                $("#exitbundle_managertravelplan_approvalObservations").prop("disabled", false);
            }else {
                $("#exitbundle_managertravelplan_approvalDate").prop("disabled", true);
                $("#exitbundle_managertravelplan_approvalObservations").prop("disabled", true);
            }
        });
    };

    var handleManagerTravelPlanReject = function () {
        $("#exitbundle_managertravelplan_rejectDate").prop("disabled", true);
        $("#exitbundle_managertravelplan_rejectReason").prop("disabled", true);

        $("#exitbundle_managertravelplan_reject").change(function() {
            if($(this).prop('checked')){
                $("#exitbundle_managertravelplan_rejectDate").prop("disabled", false);
                $("#exitbundle_managertravelplan_rejectReason").prop("disabled", false);
            }else {
                $("#exitbundle_managertravelplan_rejectDate").prop("disabled", true);
                $("#exitbundle_managertravelplan_rejectReason").prop("disabled", true);
            }
        });
    };

    var handleApplicationForm = function () {
        var form = $('#submit_exit_managertravelplan_form, #submit_exit_managertravelplan_edit_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                //general info
                'exitbundle_managertravelplan[client]': {
                    required: true
                },
                'exitbundle_managertravelplan[countries][]': {
                    required: true
                },
                'exitbundle_managertravelplan[objetives]': {
                    required: true
                },
                'exitbundle_managertravelplan[departureDate]': {
                    required: true
                },
                'exitbundle_managertravelplan[lapsed]': {
                    required: true,
                    number: true
                },
                'exitbundle_managertravelplan[state]': {
                    required: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                'exitbundle_managertravelplan[client]': {
                    required: "Seleccione el Directivo que solicita.",
                    minlength: jQuery.validator.format("Seleccione el cliente que solicita.")
                },
                'exitbundle_managertravelplan[countries][]': {
                    required: "Seleccione el País(es) de destino.",
                    minlength: jQuery.validator.format("Seleccione el País de destino.")
                },
                'exitbundle_managertravelplan[state]': {
                    required: "Seleccione al menos un estado.",
                    minlength: jQuery.validator.format("Seleccione al menos un estado.")
                },
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "exitbundle_managertravelplan[client]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_client_error");
                }else if (element.attr("name") == "exitbundle_managertravelplan[countries][]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_countries_error");
                } else if (element.attr("name") == "exitbundle_managertravelplan[state]") { // for uniform radio buttons, insert the after the given container
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
                if (label.attr("for") == "exitbundle_application[client]" ||
                    label.attr("for") == "exitbundle_managertravelplan[countries][]" ||
                    label.attr("for") == "passportbundle_application[state]" ) { // for checkboxes and radio buttons, no need to show OK icon
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
            handleManagerTravelPlanState();
            handleManagerTravelPlanFinancing();
            handleManagerTravelPlanDepartureDate();
            handleManagerTravelPlanApproval();
            handleManagerTravelPlanReject();
            handleApplicationForm();
        }

    };

}();

jQuery(document).ready(function() {
    ManagerTravelPlanForm.init();
});