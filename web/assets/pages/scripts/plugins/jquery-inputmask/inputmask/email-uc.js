!function(factory) {
    "function" == typeof define && define.amd ? define([ "jquery", "inputmask" ], factory) : "object" == typeof exports ? module.exports = factory(require("jquery"), require("./inputmask")) : factory(jQuery, window.Inputmask);
}(function($, Inputmask) {
    return Inputmask.extendAliases({
        emailuc: {
            mask: "*{1,64}[.*{1,64}][.*{1,64}][.*{1,63}]@reduc.edu.cu",
            greedy: !1,
            casing: "lower",
            onBeforePaste: function(pastedValue, opts) {
                return (pastedValue = pastedValue.toLowerCase()).replace("mailto:", "");
            },
            definitions: {
                "*": {
                    validator: "[0-9１-９A-Za-zА-яЁёÀ-ÿµ!#$%&'*+/=?^_`{|}~-]"
                },
                "-": {
                    validator: "[0-9A-Za-z-]"
                }
            },
            onUnMask: function(maskedValue, unmaskedValue, opts) {
                return maskedValue;
            },
            inputmode: "email"
        }
    }), Inputmask;
});