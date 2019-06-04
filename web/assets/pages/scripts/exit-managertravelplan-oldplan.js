var ManagerTravelPlanOldPlan = function () {

    var handleManagerTravelOldPlanYear = function () {
        $('.bs-select').selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-close',
            width: '155px',
            style: 'btn-success',
            doneButton: true,
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            handleManagerTravelOldPlanYear();
        }

    };

}();

jQuery(document).ready(function() {
    ManagerTravelPlanOldPlan.init();
});