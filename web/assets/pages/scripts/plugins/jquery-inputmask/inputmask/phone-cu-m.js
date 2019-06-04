/*!
* phone-codes/phone-be.js
* https://github.com/RobinHerbots/Inputmask
* Copyright (c) 2010 - 2018 Robin Herbots
* Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
* Version: 4.0.1-beta.3
*/

!function(factory) {
    "function" == typeof define && define.amd ? define([ "../inputmask" ], factory) : "object" == typeof exports ? module.exports = factory(require("../inputmask")) : factory(window.Inputmask);
}(function(Inputmask) {
    return Inputmask.extendAliases({
        phonecum: {
            alias: "abstractphone",
            countrycode: "53",
            phoneCodes: [ {
                mask: "(5#) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Telefonia Movil"
            } ]
        }
    }), Inputmask;
});