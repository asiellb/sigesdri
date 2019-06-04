/*
 * El CI (Número del Carnet de Identidad ) es el número de identidad asignado por las autoridades en Cuba para todos los
 * ciudadanos naturales cubanos.
 *
 */
$.validator.addMethod( "ciCU", function( value, element ) {
    "use strict";

	var check = false, adata;

    if ( /^\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{5}$/.test( value ) ) {

        adata = value.substr(0,6);

        if(!moment(adata,"YYMMDD",true).isValid()){
            check = false;
        }
         else {
            check = true;
        }
    } else {
        check = false;
    }
    return this.optional( element ) || check;

}, "Por favor introduce un Número de Identidad válido." );

/**
 * Número de teléfono en la variedad de FIJO para Cuba .
 */
$.validator.addMethod("fphoneCU", function(value, element) {
    return this.optional(element) || /^(\(7\)\s(\d{2}\-){2}\d{2,3})|\((2[1-4]|3[1-3]|4[1235678])\)\s\d{2}(\-\d{2}){2}$/.test(value); //^(7\-\d{6,7}|(2[1-4]|3[1-3]|4[1235678])\-\d{6})$
}, "Por favor introduce un número de fijo correcto.");

/**
 * Número de teléfono en la variedad de MOVIL para Cuba .
 */
$.validator.addMethod("mphoneCU", function(value, element) {
    return this.optional(element) || /^\(5\d\)\s\d{2}(\-\d{2}){2}$/.test(value);
}, "Por favor introduce un número de movil correcto.");

/**
 * Fecha de nacimiento coincide con el carnet de identidad.
 */
$.validator.addMethod( "birthdayCI", function( value, element ) {
    "use strict";

    var equal = false,
        ci = $(".ci-cu").val(),
        ci_date_u,
        ci_date_f,
        ci_year,
        ci_month,
        ci_day,
        nyear = moment()
    ;

    if(ci){
        ci_year = ci.substr(0,2);
        ci_month = ci.substr(2,2);
        ci_day = ci.substr(4,2);

        if(moment(ci_year, "YY") > nyear){
            ci_date_u = ci_day+ci_month+'19'+ci_year;
        }else {
            ci_date_u = ci_day+ci_month+moment(ci_year, "YY").format("YYYY");
        }
    }

    if(moment(ci_date_u, "DDMMYYYY").isValid()){
        ci_date_f = moment(ci_date_u, "DDMMYYYY").format("DD/MM/YYYY");

        if(value == ci_date_f) equal = true;
        else equal=false;
    }
    else {
        equal = false;
    }

    return this.optional(element) || equal;

}, "La Fecha de Nacimiento no coincide con el CI." );

/**
 * Email en UC.
 */
$.validator.addMethod( "emailUC", function( value, element ) {
    "use strict";

    return this.optional( element ) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@reduc.edu.cu$/.test( value );

}, "El Correo Institucional no es válido." );

/**
 * Genero coincide con el carnet de identidad.
 */
$.validator.addMethod( "genderCI", function( value, element ) {
    "use strict";

    var equal = false,
        ci = $(".ci-cu").val(),
        ci_gender,
        gender;

    if(ci){
        ci_gender = ci.substr(9,1);
    }

    if((parseInt(ci_gender) % 2) === 0) gender = 'M';
    else gender = 'F';

    if(gender === value) equal = true;
    else equal = false;

    return this.optional(element) || equal;

}, "El Género no coincide con el CI." );

/**
 * Código Postal de Cuba.
 */
$.validator.addMethod("zipcodeCU", function(value, element) {
    return this.optional(element) || /^\d{2}( \d{3})$/.test(value);
}, "El Código Postal no es válido.");

/**
 * Nombre.
 */
$.validator.addMethod("fname", function(value, element) {
    return this.optional(element) || /^[A-ZÑÁÉÍÓÚ][a-zñáéíóú]+$/.test(value);
}, "El Nómbre no es válido.");

/**
 * Nombre y Apellidos.
 */
$.validator.addMethod("names", function(value, element) {
    return this.optional(element) || /^([A-ZÑÁÉÍÓÚ]([a-z]|[ñáéíóú])+\s)?(de\sla\s|del\s|de\slas\s|de\slos\s|la\s|el\s|de\s)?(([A-ZÑÁÉÍÓÚ]([a-z]|[ñáéíóú])*)|\-)+$/.test(value);
}, "El Nómbre o Apellido no es válido.");

/**
 * Nombre y Apellidos.
 */
$.validator.addMethod("number", function(value, element) {
    return this.optional( element ) || /^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\,\d+)?$/.test( value );
}, "Por favor, escribe un número válido.");

/**
 * Pasaporte Cubano.
 */
$.validator.addMethod( "passCU", function( value, element ) {
    "use strict";

    var check = false;

    if ( /^[EIJXH]\d{6}$/.test( value ) ) {
            check = true;

    } else {
        check = false;
    }
    return this.optional( element ) || check;

}, "Por favor introduce un Número de Pasaporte válido." );

/**
 * Typo coincide con el Numero de Pasaporte.
 */
$.validator.addMethod( "typePass", function( value, element ) {
    "use strict";

    var equal = false,
        n = $(".number-cu").val(),
        l,
        t;

    if(n){
        l = n.substr(0,1);
    }

    if(l === "E" || l === "X") {
        t = 'OFI';
    }
    else if (l === "H" || l === "I" || l === "J") {
        t = 'COR';
    }

    equal = t === value;

    return this.optional(element) || equal;

}, "El Tipo no coincide con el Número de Pasaporte." );

/**
 * Comparar fechas.
 */
$.validator.addMethod("isAfterStartDate", function(value, element, param) {

    var check = false,
        inDate = parseDMY(param.val()),
        eDate  = parseDMY(value);

    if(inDate < eDate) {
        check = true;
    }

    return this.optional(element) || check;
}, "La fecha debe ser posterior a la de entrada.");

function parseDMY(value) {
    var date = value.split("/");
    var d = parseInt(date[0], 10),
        m = parseInt(date[1], 10),
        y = parseInt(date[2], 10);
    return new Date(y, m - 1, d);
}