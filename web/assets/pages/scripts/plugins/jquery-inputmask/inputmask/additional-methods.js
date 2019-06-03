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
        },
        phonecuf: {
            alias: "abstractphone",
            //countrycode: "53",
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
        },
        phonecum: {
            alias: "abstractphone",
            //countrycode: "53",
            phoneCodes: [ {
                mask: "(5#) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Telefonia Movil"
            } ]
        },
        phonecu: {
            alias: "abstractphone",
            phoneCodes: [ {
                mask: "(##) ##-##-##",
                cc: "CU",
                cd: "Cuba",
                city: "Resto del Pais"
            },{
                mask: "(7) ##-##-##[#]",
                cc: "CU",
                cd: "Cuba",
                city: "Ciudad de La Habana"
            } ]
        },
        zipcodecu: {
            mask: "#{2} #{3}",
        },
        ipm: {
            mask: "i[i[i]].i[i[i]].i[i[i]].i[i[i]]",
            definitions: {
                i: {
                    validator: function(chrs, maskset, pos, strict, opts) {
                        return pos - 1 > -1 && "." !== maskset.buffer[pos - 1] ? (chrs = maskset.buffer[pos - 1] + chrs,
                            chrs = pos - 2 > -1 && "." !== maskset.buffer[pos - 2] ? maskset.buffer[pos - 2] + chrs : "0" + chrs) : chrs = "00" + chrs,
                            new RegExp("25[0-5]|2[0-4][0-9]|[01][0-9][0-9]").test(chrs);
                    }
                }
            },
            onUnMask: function(maskedValue, unmaskedValue, opts) {
                alert("prueba");
                var regexp = "/2/g";
                return maskedValue.replace(regexp, "6");
            },
            inputmode: "numeric"
        },
        numeriqueDecimal: {
            mask: "~",
            groupSeparator: " ",
            radixPoint: ".",
            integerDigits: 9,
            fractionalDigits: 2,
            autoGroup: true,
            skipRadixDance: true,
            allowPlus: false,
            allowMinus: false,
            regex: {
                number: function (groupSeparator, groupSize, radixPoint, integerDigits, fractionalDigits) {
                    var escapedGroupSeparator = $.inputmask.escapeRegex.call(this, groupSeparator);
                    var escapedRadixPoint = $.inputmask.escapeRegex.call(this, radixPoint);
                    var integerDigitsExpression = isNaN(integerDigits) ? integerDigits : '{1,' + integerDigits + '}'
                    var fractionalDigitsExpression = isNaN(fractionalDigits) ? fractionalDigits : '{0,' + fractionalDigits + '}'

                    var numeriqueRegex;
                    if (escapedGroupSeparator == "") {
                        numeriqueRegex = "^\\d" + integerDigitsExpression + "(" + escapedRadixPoint + "\\d" + fractionalDigitsExpression + ")?$";
                    } else {
                        numeriqueRegex = "^(\\d" + integerDigitsExpression + "|\\d{1," + groupSize + "}((" + escapedGroupSeparator + "\\d{" + groupSize + "})?)+)(" + escapedRadixPoint + "\\d" + fractionalDigitsExpression + ")?$";
                    }

                    return new RegExp(numeriqueRegex);
                }
            },
            definitions: {
                '~': { //real number
                    validator: function (chrs, buffer, pos, strict, opts) {
                        if (chrs == "") return false;
                        if (pos <= 1 && buffer[0] === '0' && new RegExp("[\\d-]").test(chrs)) { //handle first char
                            buffer[0] = "";
                            return { "pos": 0 };
                        }

                        var cbuf = strict ? buffer.slice(0, pos) : buffer.slice();

                        cbuf.splice(pos + 1, 0, chrs);
                        var bufferStr = cbuf.join('');
                        if (opts.autoGroup && !strict) { //strip groupseparator
                            var escapedGroupSeparator = $.inputmask.escapeRegex.call(this, opts.groupSeparator);
                            bufferStr = bufferStr.replace(new RegExp(escapedGroupSeparator, "g"), '');
                        }
                        var isValid = opts.regex.number(opts.groupSeparator, opts.groupSize, opts.radixPoint, opts.integerDigits, opts.fractionalDigits).test(bufferStr);
                        if (!isValid) {
                            //let's help the regex a bit
                            bufferStr += "0";
                            isValid = opts.regex.number(opts.groupSeparator, opts.groupSize, opts.radixPoint, opts.integerDigits, opts.fractionalDigits).test(bufferStr);
                            if (!isValid) {
                                //make a valid group
                                var lastGroupSeparator = bufferStr.lastIndexOf(opts.groupSeparator);
                                for (i = bufferStr.length - lastGroupSeparator; i <= 3; i++) {
                                    bufferStr += "0";
                                }

                                isValid = opts.regex.number(opts.groupSeparator, opts.groupSize, opts.radixPoint, opts.integerDigits, opts.fractionalDigits).test(bufferStr);
                                if (!isValid && !strict) {
                                    if (chrs == opts.radixPoint) {
                                        isValid = opts.regex.number(opts.groupSeparator, opts.groupSize, opts.radixPoint, opts.integerDigits, opts.fractionalDigits).test("0" + bufferStr + "0");
                                        if (isValid) {
                                            buffer[pos] = "0";
                                            pos++;
                                            return { "pos": pos };
                                        }
                                    }
                                }
                            }
                        }

                        if (isValid != false && !strict && chrs != opts.radixPoint) {
                            var newPos = opts.postFormat(buffer, pos + 1, false, opts);
                            return { "pos": newPos };
                        }
                        return isValid;
                    },
                    cardinality: 1,
                    prevalidator: null
                }
            },
            alias: "decimal"
        }
    }), Inputmask;
});