
function facto_fe_seleccionar_tipo(obj) {
	//todas false
	if(jQuery('#facto_checkbox_fe').is(':checked') == false && jQuery('#facto_checkbox_fee').is(':checked') == false && jQuery('#facto_checkbox_be').is(':checked') == false && jQuery('#facto_checkbox_bee').is(':checked') == false) {
		jQuery("#"+obj.id).prop("checked", true);
	}
}

function facto_fe_validar_formulario(){
	var valido = true;
			
	//por lo menos un tipo de documento
	if(jQuery('#facto_checkbox_fe').is(':checked') == false && jQuery('#facto_checkbox_fee').is(':checked') == false && jQuery('#facto_checkbox_be').is(':checked') == false && jQuery('#facto_checkbox_bee').is(':checked') == false) {
		valido = false;
	}
	
	return valido;
}

document.getElementById("facto_checkbox_fe").onclick = function(){facto_fe_seleccionar_tipo(this);};
document.getElementById("facto_checkbox_fee").onclick = function(){facto_fe_seleccionar_tipo(this);};
document.getElementById("facto_checkbox_be").onclick = function(){facto_fe_seleccionar_tipo(this);};
document.getElementById("facto_checkbox_bee").onclick = function(){facto_fe_seleccionar_tipo(this);};


jQuery('#facto_formulario').on('submit',function(){return facto_fe_validar_formulario();}); 


/*
function facto_fe_facturar(){
	 jQuery.ajax({
		 url: "urlparafacturar.php",
		 method: "POST", 
		 data: {
				'oml_order_id': jQuery('#oml_order_id').val(),
				},
		 contentType: "application/x-www-form-urlencoded; charset=UTF-8",

		 success: function(html) {
			 console.log(html);
			 var retorno = JSON.parse(html);

			 console.log(html);
				if(retorno.error == "true") {
					jQuery('#p_oml_estado').html("Se ha producido un error al generar la factura.<br>Error: "+retorno.data);
					jQuery('#btn_oml_estado').html('Generar factura');
					jQuery('#btn_oml_estado').hide();
					//$('#btn_oml_estado').bind('click', true);
				} else {
					jQuery('#btn_oml_estado').hide();
					jQuery('#p_oml_estado').html("<p id='p_oml_estado'>La factura la puede ver siguiendo el <a href='"+retorno.data+"' target='new'>enlace</a></p>");
				}
			},
			beforeSend: function() {
				jQuery('#btn_oml_estado').html('Cargando...');
				jQuery('#btn_oml_estado').bind('click', false);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
	});
}
*/