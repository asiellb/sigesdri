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
        phonecuf: {
            alias: "abstractphone",
            countrycode: "53",
            phoneCodes: [ {
                mask: "(7) ##-##-##[#]",
                cc: "CU",
                cd: "Cuba",
                city: "Ciudad de La Habana"
            }, {
                mask: "(21) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Guantánamo"
            }, {
                mask: "(22) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Santiago de Cuba"
            }, {
                mask: "(23) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Granma"
            }, {
                mask: "(24) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Holguín"
            }, {
                mask: "(31) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Las Tunas"
            }, {
                mask: "(32) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Camagüey"
            }, {
                mask: "(33) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Ciego de Avila"
            }, {
                mask: "(41) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Sancti Spiritus"
            }, {
                mask: "(42) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Villa Clara"
            }, {
                mask: "(43) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Cienfuegos"
            }, {
                mask: "(45) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Matanzas"
            }, {
                mask: "(46) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Isla de la Juventud"
            }, {
                mask: "(47) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Artemisa y Mayabeque"
            }, {
                mask: "(48) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Pinar del Río"
            } ]
        }
    }), Inputmask;
});