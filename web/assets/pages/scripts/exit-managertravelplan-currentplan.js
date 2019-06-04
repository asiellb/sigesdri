var ManagerTravelPlanOldPlan = function () {

    var handleManagerTravelOldPlanConfirmation = function () {
        if (!$().confirmation) {
            return;
        }
        $('.data-confirmation').confirmation({
            title: '¿Estas Seguro(a)?',
            popout: true,
            container: 'body',
            btnOkLabel: 'Si',
            btnOkClass: 'btn btn-sm btn-success',
            btnCancelLabel: 'No',
            btnCancelClass: 'btn btn-sm btn-danger',

        });
    };

    return {
        //main function to initiate the module
        init: function () {
            handleManagerTravelOldPlanConfirmation();
        }

    };

}();

jQuery(document).ready(function() {
    ManagerTravelPlanOldPlan.init();
});