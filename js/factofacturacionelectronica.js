
function facto_fe_factofacturacionelectronica_cambiar()
{

    document.getElementById("factofacturacionelectronica_div_factura").style.display = "none";

    select_kind_invoice = document.getElementById('kind_of_legal_invoice');

    // Si el value es incorrecto, lo ajustamos
    if (select_kind_invoice.options[select_kind_invoice.selectedIndex].value == "Boleta electronica")
	{
        select_kind_invoice.options[select_kind_invoice.selectedIndex].value = "be";
	}
    else if (select_kind_invoice.options[select_kind_invoice.selectedIndex].value == "Boleta exenta electronica")
	{
        select_kind_invoice.options[select_kind_invoice.selectedIndex].value = "bee";
	}
    else if (select_kind_invoice.options[select_kind_invoice.selectedIndex].value == "Factura electronica")
	{
        select_kind_invoice.options[select_kind_invoice.selectedIndex].value = "fe";
	}
    else if (select_kind_invoice.options[select_kind_invoice.selectedIndex].value == "Factura exenta electronica")
	{
        select_kind_invoice.options[select_kind_invoice.selectedIndex].value = "fee";
	}
    
    
    if (select_kind_invoice.options[select_kind_invoice.selectedIndex].value == "fe"
        || select_kind_invoice.options[select_kind_invoice.selectedIndex].value == "fee") {

        document.getElementById("factofacturacionelectronica_div_factura").style.display = "block";
    } else {
        document.getElementById("oml_campo_rut").value = "";
        document.getElementById("campo_razon_social").value = "";
        document.getElementById("campo_giro").value = "";
    }


    /*
    jQuery('#oml_campo_rut').Rut({
        on_error: function () {
            alert('El rut ingresado es incorrecto');
            jQuery('#oml_campo_rut').val('');
            jQuery('#oml_campo_rut').focus();
        },
        format_on: 'keyup'
    });
    */
}

document.addEventListener("DOMContentLoaded", function(event) {

    document.getElementById("kind_of_legal_invoice").onchange = function () {
        facto_fe_factofacturacionelectronica_cambiar();
    };

    facto_fe_factofacturacionelectronica_cambiar();

    document.getElementById("oml_campo_rut").onchange = function () {
        rut = new Rut(this.value);
        if (rut.validate() == false) {
            alert('RUT ingresado invalido');
            this.value = '';
        } else {
            this.value = rut.getNiceRut();
        }
    }


});

/*
(function ($) {
    jQuery.fn.Rut = function (options) {
        var defaults = {
            digito_verificador: null,
            on_error: function () {
            },
            on_success: function () {
            },
            validation: true,
            format: true,
            format_on: 'change'
        };

        var opts = $.extend(defaults, options);

        return this.each(function () {

            if (defaults.format) {
                jQuery(this).bind(defaults.format_on, function () {
                    jQuery(this).val(jQuery.Rut.formatear(jQuery(this).val(), defaults.digito_verificador == null));
                });
            }
            if (defaults.validation) {
                if (defaults.digito_verificador == null) {
                    jQuery(this).bind('blur', function () {
                        var rut = jQuery(this).val();
                        if (jQuery(this).val() != "" && !jQuery.Rut.validar(rut)) {
                            defaults.on_error();
                        } else if (jQuery(this).val() != "") {
                            defaults.on_success();
                        }
                    });
                } else {
                    var id = jQuery(this).attr("id");
                    jQuery(defaults.digito_verificador).bind('blur', function () {
                        var rut = jQuery("#" + id).val() + "-" + jQuery(this).val();
                        if (jQuery(this).val() != "" && !jQuery.Rut.validar(rut)) {
                            defaults.on_error();
                        } else if (jQuery(this).val() != "") {
                            defaults.on_success();
                        }
                    });
                }
            }
        });
    }
})(jQuery);
*/

/**
 Funciones
 */

/*
jQuery.Rut = {

    formatear: function (Rut, digitoVerificador) {
        var sRut = new String(Rut);
        var sRutFormateado = '';
        sRut = jQuery.Rut.quitarFormato(sRut);
        if (digitoVerificador) {
            var sDV = sRut.charAt(sRut.length - 1);
            sRut = sRut.substring(0, sRut.length - 1);
        }
        while (sRut.length > 3) {
            sRutFormateado = "." + sRut.substr(sRut.length - 3) + sRutFormateado;
            sRut = sRut.substring(0, sRut.length - 3);
        }
        sRutFormateado = sRut + sRutFormateado;
        if (sRutFormateado != "" && digitoVerificador) {
            sRutFormateado += "-" + sDV;
        } else if (digitoVerificador) {
            sRutFormateado += sDV;
        }

        return sRutFormateado;
    },

    quitarFormato: function (rut) {
        var strRut = new String(rut);
        while (strRut.indexOf(".") != -1) {
            strRut = strRut.replace(".", "");
        }
        while (strRut.indexOf("-") != -1) {
            strRut = strRut.replace("-", "");
        }

        return strRut;
    },

    digitoValido: function (dv) {
        if (dv != '0' && dv != '1' && dv != '2' && dv != '3' && dv != '4'
            && dv != '5' && dv != '6' && dv != '7' && dv != '8' && dv != '9'
            && dv != 'k' && dv != 'K') {
            return false;
        }
        return true;
    },

    digitoCorrecto: function (crut) {
        largo = crut.length;
        if (largo < 2) {
            return false;
        }
        if (largo > 2) {
            rut = crut.substring(0, largo - 1);
        } else {
            rut = crut.charAt(0);
        }
        dv = crut.charAt(largo - 1);
        jQuery.Rut.digitoValido(dv);

        if (rut == null || dv == null) {
            return 0;
        }

        dvr = jQuery.Rut.getDigito(rut);

        if (dvr != dv.toLowerCase()) {
            return false;
        }
        return true;
    },

    getDigito: function (rut) {
        var dvr = '0';
        suma = 0;
        mul = 2;
        for (i = rut.length - 1; i >= 0; i--) {
            suma = suma + rut.charAt(i) * mul;
            if (mul == 7) {
                mul = 2;
            } else {
                mul++;
            }
        }
        res = suma % 11;
        if (res == 1) {
            return 'k';
        } else if (res == 0) {
            return '0';
        } else {
            return 11 - res;
        }
    },

    validar: function (texto) {
        texto = jQuery.Rut.quitarFormato(texto);
        largo = texto.length;

        // rut muy corto
        if (largo < 2) {
            return false;
        }

        // verifica que los numeros correspondan a los de rut
        for (i = 0; i < largo; i++) {
            // numero o letra que no corresponda a los del rut
            if (!jQuery.Rut.digitoValido(texto.charAt(i))) {
                return false;
            }
        }

        var invertido = "";
        for (i = (largo - 1), j = 0; i >= 0; i--, j++) {
            invertido = invertido + texto.charAt(i);
        }
        var dtexto = "";
        dtexto = dtexto + invertido.charAt(0);
        dtexto = dtexto + '-';
        cnt = 0;

        for (i = 1, j = 2; i < largo; i++, j++) {
            if (cnt == 3) {
                dtexto = dtexto + '.';
                j++;
                dtexto = dtexto + invertido.charAt(i);
                cnt = 1;
            } else {
                dtexto = dtexto + invertido.charAt(i);
                cnt++;
            }
        }

        invertido = "";
        for (i = (dtexto.length - 1), j = 0; i >= 0; i--, j++) {
            invertido = invertido + dtexto.charAt(i);
        }

        if (jQuery.Rut.digitoCorrecto(texto)) {
            return true;
        }
        return false;
    }
};
*/


/*!
 * Rut.js v0.1.1
 * http://jeam.github.io/rut
 *
 * Copyright (c) 2013 Jorge Ălvarez <jorge@jalvarez.cl>
 * Released under the MIT license
 */
(function(root, factory) {
    if (typeof define === 'function' && define.amd) {
        define(function() {
            return root.Rut = factory();
        });
    } else if (typeof exports === 'object') {
        module.exports = factory();
    } else {
        root.Rut = factory();
    }
})(this, function() {
    var Rut;
    Rut = (function() {
        var _cleanRut, _formatRut, _getCheckDigit;

        function Rut(rut, withoutCheckDigit) {
            this.setRut(rut, withoutCheckDigit);
        }

        Rut.prototype.setRut = function(rut, withoutCheckDigit) {
            if (withoutCheckDigit == null) {
                withoutCheckDigit = false;
            }
            if (typeof rut !== 'string') {
                throw new Error('rut tiene que ser string');
            }
            this.rut = withoutCheckDigit ? _cleanRut(rut) : _cleanRut(rut.substr(0, rut.length - 1));
            this.checkDigit = withoutCheckDigit ? _getCheckDigit(rut) : rut.substr(rut.length - 1).toUpperCase();
            this.isValid = this.validate();
        };

        Rut.prototype.validate = function() {
            var checkDigit;
            if (!/([0-9]|k)/i.test(this.checkDigit)) {
                return false;
            }
            checkDigit = _getCheckDigit(this.rut);
            return this.checkDigit.toLowerCase() === checkDigit.toLowerCase();
        };

        Rut.prototype.getCleanRut = function() {
            return this.rut + '' + this.checkDigit;
        };

        Rut.prototype.getNiceRut = function(type) {
            if (type == null) {
                type = true;
            }
            if (type) {
                return _formatRut(this.rut) + '-' + this.checkDigit;
            } else {
                return this.rut + '-' + this.checkDigit;
            }
        };

        _cleanRut = function(rut) {
            return rut.replace(/(\.|\-)/g, '');
        };

        _getCheckDigit = function(rut) {
            var i, mul, res, sum;
            sum = 0;
            i = rut.length;
            mul = 2;
            while (--i >= 0) {
                sum += rut.charAt(i) * mul;
                if (++mul === 8) {
                    mul = 2;
                }
            }
            res = sum % 11;
            if (res === 1) {
                return 'K';
            } else if (res === 0) {
                return '0';
            } else {
                return String(11 - res);
            }
        };

        _formatRut = function(rut) {
            return rut.split('').reverse().reduce(function(a, b, i) {
                return a = i % 3 === 0 ? b + '.' + a : b + '' + a;
            });
        };

        return Rut;

    })();
    return Rut;
});