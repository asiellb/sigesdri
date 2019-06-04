!function(factory) {
    "function" == typeof define && define.amd ? define([ "jquery", "inputmask" ], factory) : "object" == typeof exports ? module.exports = factory(require("jquery"), require("./inputmask")) : factory(jQuery, window.Inputmask);
}(function($, Inputmask) {
    return Inputmask.extendAliases({
        phone: {
            url: "http://sigesdri.lh/assets/global/plugins/jquery-inputmask/inputmask/phone-codes/phone-codes.js",
            countrycode: "",
            mask: function(opts) {
                opts.definitions["#"] = opts.definitions[9];
                var maskList = [];
                return $.ajax({
                    url: opts.url,
                    async: !1,
                    dataType: "json",
                    success: function(response) {
                        maskList = response;
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + " - " + opts.url);
                    }
                }), maskList = maskList.sort(function(a, b) {
                    return (a.mask || a) < (b.mask || b) ? -1 : 1;
                });
            },
            keepStatic: !1,
            nojumps: !0,
            nojumpsThreshold: 1,
            onBeforeMask: function(value, opts) {
                var processedValue = value.replace(/^0/g, "");
                return (processedValue.indexOf(opts.countrycode) > 1 || -1 === processedValue.indexOf(opts.countrycode)) && (processedValue = "+" + opts.countrycode + processedValue),
                    processedValue;
            }
        },
        phonecu_m: {
            alias: "phone",
            countrycode: "53",
            nojumpsThreshold: 4,
            mask: "5#-#{6}"
        },
        phonecu_f: {
            alias: "phone",
            countrycode: "53",
            nojumpsThreshold: 4,
            mask: "(21|2|3|4-#{6})|(31|2|3-#{6})|(41|2|3|5|6|7|8-#{6})|(7-#{6}[#])"
        }
    }), Inputmask;
});