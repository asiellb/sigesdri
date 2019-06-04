var ManagerExitReports = function () {

    var handleExitReportsYear = function () {
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
            handleExitReportsYear();
        }

    };

}();

jQuery(document).ready(function() {
    ManagerExitReports.init();
});