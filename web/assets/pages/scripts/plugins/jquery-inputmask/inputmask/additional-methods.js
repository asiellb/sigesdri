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
        ci = $("#clientbundle_client_ci").val(),
        cidate_u = ci.substr(4,2)+'/'+ci.substr(2,2)+'/'+ci.substr(0,2),
        cidate_f;

    if(moment(cidate_u,"DD/MM/YY",true).isValid()){
        cidate_f = moment(cidate_u,"DD/MM/YY").format("DD/MM/YYYY");

        if(value == cidate_f) equal = true;
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
        ci = $("#clientbundle_client_ci").val(),
        ci_gender = ci.substr(9,1),
        gender;

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


