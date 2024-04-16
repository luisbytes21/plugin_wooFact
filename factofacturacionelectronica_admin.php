<?php 

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/************************** ADMIN PAGE **********************************
 ***********************************************************************/

add_action('admin_menu', 'facto_fe_register_admin_page');
function facto_fe_register_admin_page()
{
    add_submenu_page('woocommerce', 'Configuraciones', 'FACTU NOUS', 'manage_options', 'facto_settings', 'facto_fe_submenu_settings_callback');
    add_action('admin_init', 'facto_fe_register_facto_settings');
    
    
    
}


function facto_fe_submenu_settings_api()
{
 ?>
 <form method="post" action="options.php" id="facto_formulario">
            <?php settings_fields('facto_settings_group_api'); ?>
            <?php do_settings_sections('facto_settings_group_api'); ?>
    
    
    <h2>Ingresar llave de acceso API</h2>
		
		
		<p><b>Por Favor Solicita el Token de acceso y el usuario de aceso a servicios al Administrador de Factu Nous en <href>www.nous.ec</href> con tu número de cedula o  ruc </p>
		
		

		<table class="form-table">

			<tbody>



				<tr valign="top">
					<th scope="row" class="titledesc"><label
						for="facto_webservice_mode">Modo conexión</label></th>
					<td class="forminp forminp-text forminp-text-facto">					
					<select
						name="facto_webservice_mode" id="facto_webservice_mode" onchange="facto_changemode(this.value);">
						<?php 
						
						print "<option value='test'";
						
						if (get_option('facto_webservice_mode') == 'test')
						{
						  print " selected";   
						}
						print ">PRUEBAS - Documentos DEMO para probar tu tienda</option>";
						
						print "<option value='production'";
						
						if (get_option('facto_webservice_mode') == 'production')
						{
						    print " selected";
						}
						print ">PRODUCCIÓN - Integración SRI y generación real de documentos</option>";
						
						
						?></select></td>
				</tr>			

				<tr valign="top" id="facto_tr_user">
					<th scope="row" class="titledesc"><label
						for="facto_webservice_user">Usuario API</label></th>
					<td class="forminp forminp-text forminp-text-facto"><input type="text"
						name="facto_webservice_user" id="facto_webservice_user"
						 <?php 
				
				if (get_option('facto_webservice_mode') == 'test')
				{
				    print " disabled";
				}
				else
				{
				    print " required";   
				}
				?>
						value="<?php echo esc_attr(get_option('facto_webservice_user')); ?>" />
					</td>
				</tr>

				<tr valign="top" id="facto_tr_pass">
					<th scope="row" class="titledesc"><label
						for="facto_webservice_pass">Contraseña API</label></th>
					<td class="forminp forminp-text forminp-text-facto"><input type="text"
						name="facto_webservice_pass" id="facto_webservice_pass"
								 <?php 
				
				if (get_option('facto_webservice_mode') == 'test')
				{
				    print " disabled";
				}
				else
				{
				    print " required";   
				}
				?>						
						value="<?php echo esc_attr(get_option('facto_webservice_pass')); ?>" /></td>
				</tr>

				
			</tbody>
		</table>
		
		<script>
		function facto_changemode(value)
{
	if (value == 'test')
	{
		
		jQuery('#facto_webservice_user').val('');
		jQuery('#facto_webservice_user').removeAttr('required');
		jQuery('#facto_webservice_user').prop('disabled',true);
		
		jQuery('#facto_webservice_pass').val('');
		jQuery('#facto_webservice_pass').removeAttr('required');
		jQuery('#facto_webservice_pass').prop('disabled',true);

	}
	else
	{
		jQuery('#facto_webservice_user').removeAttr('disabled');
		jQuery('#facto_webservice_user').prop('required',true);
		
		jQuery('#facto_webservice_pass').removeAttr('disabled');
		jQuery('#facto_webservice_pass').prop('required',true);
	}
}
		</script>
    <?php 
        
        
        submit_button(); ?>
    
    
    </form>
    <?php 
}

function facto_fe_submenu_settings_docs()
{
 ?>
 
  <form method="post" action="options.php" id="facto_formulario">
            <?php settings_fields('facto_settings_group_docs'); ?>
            <?php do_settings_sections('facto_settings_group_docs'); ?>
    
 
 <!-- DOCUMENTOS -->

		<h2>Documentos tributarios habilitados en tu tienda</h2>		
		
		<p>Recuerda que estos documentos deben estar también habilitados en tu FACTO o no podrás emitirlos. En el caso de los documentos adicionales, debes asegurarte de haberlos contratado.</p>

		<table class="form-table">
			<tbody>

				<tr valign="top">
					<th scope="row" class="titledesc"><label for="facto_checkbox_fe">Factura
							electr&oacute;nica</label></th>
					<td class="forminp forminp-checkbox"><input type="checkbox"
						name="facto_checkbox_fe" id="facto_checkbox_fe" value="on"
						<?php if (esc_attr(get_option('facto_checkbox_fe')) == "on") echo "checked"; ?> />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row" class="titledesc"><label
						for="facto_checkbox_fee">Factura exenta electr&oacute;nica</label></th>
					<td class="forminp forminp-checkbox"><input type="checkbox"
						name="facto_checkbox_fee" id="facto_checkbox_fee" value="on"
						<?php if (esc_attr(get_option('facto_checkbox_fee')) == "on") echo "checked"; ?> />
					</td>
				</tr>

<tr>
<td colspan="2"><b>** Documentos adicionales ** </b></td>
</tr>


				<tr valign="top">
					<th scope="row" class="titledesc"><label for="facto_checkbox_be">Boleta
							electr&oacute;nica</label></th>
					<td class="forminp forminp-checkbox"><input type="checkbox"
						name="facto_checkbox_be" id="facto_checkbox_be" value="on"
						<?php if (esc_attr(get_option('facto_checkbox_be')) == "on") echo "checked"; ?> />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row" class="titledesc"><label for="facto_checkbox_bee">Boleta
							exenta electr&oacute;nica</label></th>
					<td class="forminp forminp-checkbox"><input type="checkbox"
						name="facto_checkbox_bee" id="facto_checkbox_bee" value="on"
						<?php if (esc_attr(get_option('facto_checkbox_bee')) == "on") echo "checked"; ?> />
					</td>
				</tr>
			</tbody>
		</table>
		
		
		<h2>Documento por defecto al momento de comprar</h2>
		
		<table class="form-table">
		<tbody>
		<tr valign="top" id="facto_tr_user">
					<th scope="row" class="titledesc"><label
						for="facto_docs_default">Documento por defecto al comprar</label></th>
					<td class="forminp forminp-text forminp-text-facto">
					<select
						name="facto_docs_default" id="facto_docs_default">
						 <?php 
						 
						 
						 print "<option value='fe'";
						 
						 if (get_option('facto_docs_default') == "fe")
						 {
						     print " selected";
						 }
						 
						 print ">Factura electrónica</option>";
						 
						 print "<option value='fee'";
						 
						 if (get_option('facto_docs_default') == "fee")
						 {
						     print " selected";
						 }
						 
						 print ">Factura exenta electrónica</option>";
				
						 print "<option value='be'";
						 
						 if (get_option('facto_docs_default') == "be")
						 {
						      print " selected";   
						 }
						 
						 print ">Boleta electrónica</option>";
						 
						 print "<option value='bee'";
						 
						 if (get_option('facto_docs_default') == "bee")
						 {
						     print " selected";
						 }
						 
						 print ">Boleta exenta electrónica</option>";
						 
				
				?>
					</select>	
					</td>
				</tr>
				</tbody>
		</table>
 <?php 
        
        
        submit_button(); ?>
 
    </form>
 <?php    
}

function facto_fe_submenu_settings_issuanceconditions()
{
 ?>
    <form method="post" action="options.php" id="facto_formulario">
        <?php settings_fields('facto_settings_group_paymentgateways'); ?>
        <?php do_settings_sections('facto_settings_group_paymentgateways'); ?>

        <!-- METODOS DE PAGO -->

        <h2>Emitir al completar la compra según M&eacute;todos de pago</h2>
        <p>Selecciona seg&uacute;n el m&eacute;todo de pago si quieres generar
            el documento de manera autom&aacute;tica o manual en pedidos.</p>

        <p><b>Automática:</b> Los documentos se generarán automáticamente para esta forma de pago cuando el pedido llegue al estado EN PROCESO o COMPLETADO según lo que indiques en la pestaña de estados de pedido.</p>

        <p><b>Manual:</b> Podrás generar manualmente los documentos en el administrador de Woocommerce al ingresar a cada pedido</p>

        <?php
        // para cada mp obtenemos su configuraci&oacute;n
        if (! defined('WC')) {

            $mp_arr = WC()->payment_gateways()->get_available_payment_gateways();
        } else {
            $mp_arr = array();
        }

        ?>
        <table class="form-table">
            <tbody>



            <?php
            foreach ($mp_arr as $mp) {
                $code = $mp->id;
                $title = $mp->title;

                $fact = "manual";
                if (get_option("facto_" . $code . "_fact"))
                    $fact = get_option("facto_" . $code . "_fact");

                if ($fact != "auto" && $fact != "manual")
                    $fact = "manual";

                ?>
                <tr valign="top">
                    <th scope="row" class="titledesc"><label
                                for="<?php echo esc_attr("facto_" . $code . "_fact"); ?>"><?php echo esc_html($title); ?></label></th>
                    <td class="forminp forminp-select"><select
                                name="<?php echo esc_attr("facto_" . $code . "_fact"); ?>">
                            <option value="auto"
                                <?php if ($fact == "auto") echo "selected='selected'"; ?>>
                                Automatica</option>
                            <option value="manual"
                                <?php if ($fact == "manual") echo "selected='selected'"; ?>>
                                Manual</option>
                        </select></td>
                </tr>
                <?php
            }
            ?>


            <tbody>

        </table>

        <?php


        submit_button(); ?>

    </form>


    <form method="post" action="options.php" id="facto_formulario">
            <?php settings_fields('facto_settings_group_orderstatus'); ?>
            <?php do_settings_sections('facto_settings_group_orderstatus'); ?>
 
 <!-- ESTADO PARA FACTURAR -->

		<h2>Emitir cuando el estado de pedido cambie</h2>
		<p>Selecciona en qué estado del pedido se generará el documento.</p>
		<p>Recuerda que algunos métodos de pago como transferencia bancaria dejan los pedidos EN PROCESO mientras tú confirmas manualmente el pago,
		mientras que los medios de pago con tarjeta, dejan los pedidos COMPLETADOS una vez se ha recibido el pago.</p>
		<p>Te recomendamos elegir COMPLETADO como primera opción y ajustar estas opciones dependiendo de los plugins de métodos de pago con los que trabajes.</p>

		<table class="form-table">
			<tbody>


				<tr valign="top">
					<th scope="row" class="titledesc"><label for="facto_checkbox_estadoprocesando">Facturar
							cuando el estado del pedido est&aacute; en PROCESO</label></th>
					<td class="forminp forminp-checkbox"><input type="checkbox"
								name="facto_checkbox_estadoprocesando"
								id="facto_checkbox_estadoprocesando" value="on"
								<?php if (esc_attr(get_option('facto_checkbox_estadoprocesando')) == "on") echo "checked"; ?> />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row" class="titledesc"><label for="facto_checkbox_estadocompletado">Facturar
							cuando el estado del pedido est&aacute; en COMPLETADO</label></th>
					<td class="forminp forminp-checkbox"><input type="checkbox"
								name="facto_checkbox_estadocompletado"
								id="facto_checkbox_estadocompletado" value="on"
								<?php if (esc_attr(get_option('facto_checkbox_estadocompletado')) == "on") echo "checked"; ?> />
					</td>
				</tr>

</tbody>
</table>

<?php 
        
        
        submit_button(); ?>
 
 </form>
 <?php    


}


function facto_fe_submenu_settings_advanced()
{
    ?>
   <form method="post" action="options.php" id="facto_formulario">
            <?php settings_fields('facto_settings_group_advanced'); ?>
            <?php do_settings_sections('facto_settings_group_advanced'); ?>
            
            
            <!-- OPCIONES ADICIONALES -->
<h2>Avanzadas</h2>


		<table class="form-table">
			<tbody>

            <tr valign="top">
                <th scope="row" class="titledesc"><label for="facto_select_rounding_type">Tipo de redondeo</label>
                </th>
                <td class="forminp forminp-select" scope="row">
                    <select name="facto_select_rounding_type" id="facto_select_rounding_type">
                        <?php
                        $options = array(
                            0 => "Netos",
                            1 => "Brutos",
                        );
                        foreach ($options as $key => $option) {
                            if(esc_attr(get_option('facto_select_rounding_type')) == $key) {
                                print '<option value="' . esc_attr($key) . '" selected>' . esc_html($option) . '</option>';
                            } else {
                                print '<option value="' . esc_attr($key) . '">' . esc_html($option) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
                <td scope="row">
                    <p>* El redondeo por netos calcula todos los valores netos y luego el IVA sobre dicho neto. El redondeo por NETOS puede generar diferencias entre el valor del pedido y de la boleta/factura, pero respetará más firmemente el impuesto por producto. El redondeo por brutos calcula los valores finales y luego el IVA sobre el valor final, por lo que el valor del documento será el mismo del pedido.</p>
                </td>
            </tr>


				<tr valign="top">
                <th scope="row" class="titledesc"><label for="facto_checkbox_add_shipping">Agregar envio al documento emitido</label>
                </th>
                <td class="forminp forminp-select" scope="row">
                    <select name="facto_checkbox_add_shipping" id="facto_checkbox_add_shipping">
                        <?php
                        $options = array(
                            1 => "Facturar productos + Envío",
                            2 => "Facturar sólo productos",
                            3 => "Facturar sólo envío",
                        );
                        foreach ($options as $key => $option) {
                            if(esc_attr(get_option('facto_checkbox_add_shipping')) == $key) {
                                print '<option value="' . esc_attr($key) . '" selected>' . esc_html($option) . '</option>';
                            } else {
                                print '<option value="' . esc_attr($key) . '">' . esc_html($option) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
                <td scope="row">
                    <p>*Al cambiar esta opcion, puedes seleccionar entre emitir un documento con productos y envio, solo productos o solo envio.</p>
                </td>
            </tr>
				<tr valign="top">
					<th scope="row" class="titledesc"><label for="facto_checkbox_totalneto">Gestionar
							el IVA mediante Woocommerce</label>
							</th>
							
							
					<td class="forminp forminp-checkbox"><input type="checkbox"
								name="facto_checkbox_totalneto" id="facto_checkbox_totalneto"
								value="on"
								<?php if (esc_attr(get_option('facto_checkbox_totalneto')) == "on") echo "checked"; ?> />
					</td>
					
					<td scope="row">
					
					
					<p>*Si marcas esta opción se considerará que el valor ingresado en woocommerce es NETO y FACTU NOUS agregará el IVA sobre este valor. En caso contrario se considerará que el valor es el TOTAL BRUTO y Facto calculará el neto correspondiente. Te recomendamos NO marcar esta opción e ingresar tus valores totales en woocommerce.</p>
					</td>
				</tr>
				<tr>
				<td colspan="3">
				<b>Cómo configurar esta opción</b>
				<ul>				
				<li>1. Luego de instalado el plugin, ingresar a los ajustes del mismo y habilitar en la pestaña AVANZADO la opción "Gestionar el IVA mediante woocommerce"</li>
                <li>2. En woocommerce, ingresar a Configuración (Settings), en la pestaña General, habilitar la opción "Activar tasas de impuestos y cálculos" (Enable tax rates and calculations)</li>
                <li>3. En la pestaña impuestos (Tax) de woocommerce, ingresar a "Tasas estándar" (Standard rates) y agregar una nueva fila con código de país "CL" y nombre de impuesto "IVA" (mantener mayúsculas) y tasa en 15%.</li>
                <li>4. Marcar la opción para elegir que ese impuesto sea aplicado a los envíos.</li>
                <li>5. En los productos existentes y nuevos, revisar que tengan marcada en "Estado de impuesto" (Tax status) la opción de "Afecto" (Taxable) si son afectos o "Ninguno" (None) si son exentos.</li>
                <li>6. Realizar pruebas usando el modo PRUEBAS del plugin para comprobar que todo esté operando correctamente</li>
                <li>7. Al usar esta modalidad, el plugin cambiará automáticamente entre boleta afecta/exenta y factura afecta/exenta según corresponda a los productos en el carrito, independiente del tipo de documento que elija la persona al comprar.</li>
                </ul>
                <p>NOTA: Esta es una modalidad AVANZADA. Para el general de nuestros usuarios se recomienda no utilizar el sistema de manejo de impuestos de woocommerce. Por ahora sólo soporta IVA, no impuestos específicos. Por ahora sólo soporta productos digitales para el caso boleta</p>
				</td>
                </tr>
			</tbody>

		</table>
		
		<?php 
        
        
        submit_button(); ?>
            
    </form>
    <?php 
}


function facto_fe_submenu_settings_logs()
{
    global $wpdb;
    
    ?>
    
    <div style="display:none;position:fixed;background-color:white;border:1px solid black;width:50%;max-height:80%;text-align:center;top:100px;" id="detallesolicitud">
    <button style="float:right;color:white;background-color:red;" onclick="jQuery('#detallesolicitud').hide();">X</button>
    <b>Detalle</b><br/>
    <textarea id="detallesolicitud_texto" style="width:100%;height:500px;"></textarea>    
    <div style="clear:both;"></div>
    </div>
    
    <h2>Log de comunicación</h2>
    
    <p><B>Log de últimas 100 solicitudes</B></p>
    
    <form method="POST" action="#" id="buscarpedidos" name="buscarpedidos" class="form-inline" >
        <div class="form-group row">
            <label class="col-lg-2 control-label">Desde</label>
            <input type="datetime-local" class="form-control" name="fechaInicio">
            <label class="col-lg-2 control-label">Hasta</label>
            <input type="datetime-local" class="form-control" name="fechaFin">
            <button class="btn btn-success col-xs-12 col-lg-1" type="submit">Buscar</button>
        </div>
    </form>
    <?php

    $fechainicio = "";
    $fechafin = "";

    if (isset($_REQUEST['fechaInicio']) && ($_REQUEST['fechaInicio'] != "")) {
        $fechainicio = sanitize_text_field($_REQUEST['fechaInicio']);
    }
    if (isset($_REQUEST['fechaFin']) && ($_REQUEST['fechaFin'] != "")) {
        $fechafin = sanitize_text_field($_REQUEST['fechaFin']);
    }



    $query = "SELECT fecha,order_id,request,response,estado_envio FROM ".$wpdb->prefix."facto_log WHERE 1=1";
    if ($fechainicio != "") {
        $query .= " AND fecha >= '".esc_sql($fechainicio)."'";
    }
    if ($fechafin != "") {
        $query .= " AND fecha <= '".esc_sql($fechafin)."'";
    }
    $query .= " ORDER BY fecha desc";
    // Obtengamos las últimas 100 líneas de log
    // Query to fetch data from database table and storing in $results
    $results = $wpdb->get_results($query);
    
    if(!empty($results))                        // Checking if $results have some values or not
    {  
        print "<div class='table-responsive' style='padding-top: 50px'>";
        print "<table width='100%'>";
        print "<thead>";
        print "<tr>";
        print "<th>Fecha</th>";
        print "<th>N° Pedido</th>";
        print "<th>Solicitud</th>";
        print "<th>Respuesta</th>";
        print "<th>Estado envío</th>";
        print "</tr>";
        print "</thead>";
        
        print "<tbody>";
        
        foreach($results as $row)
        {
            print "<tr>";
            print "<td style='text-align:center'>".esc_html($row->fecha)."</td>";
            print "<td style='text-align:center'><a href=\"".esc_url("post.php?post=".$row->order_id."&action=edit")."\" target=\"_blank\">".esc_html($row->order_id)."</a></td>";

            if ($row->estado_envio == "-1") {
                print "<td style='text-align:center'>&nbsp;</td>";
                print "<td style='text-align:center'>&nbsp;</td>";
            } else {
                print "<td style='text-align:center'><button class='button button-primary' onclick=\"jQuery('#detallesolicitud_texto').val('".str_replace("\r","",str_replace("\n",'\n',str_replace('"','&quot;',str_replace("'",'\x27',$row->request))))."');jQuery('#detallesolicitud').show();\">Ver</button></td>";
                print "<td style='text-align:center'><button class='button button-primary' onclick=\"jQuery('#detallesolicitud_texto').val('".str_replace("\r","",str_replace("\n",'\n',str_replace('"','&quot;',str_replace("'",'\x27',$row->response))))."');jQuery('#detallesolicitud').show();\">Ver</button></td>";
            }


            if ($row->estado_envio == "-1")
            {
                print "<td style='text-align:center;'>Iniciando solicitud</td>";
            }
            elseif ($row->estado_envio == "0")
            {
                print "<td style='text-align:center;color:green'>Exito</td>";
            }
            elseif ($row->estado_envio == "1")
            {
                print "<td style='text-align:center;color:red'>Error</td>";
            }
            else
            {
                print "<td style='text-align:center;color:orange'>Advertencia</td>";
            }
            
            print "</tr>";
        }
        
        print "</tbody>";
        
        print "</table>";
        print "</div>";
    }
    ?>
    
    <?php 
    
}

function facto_fe_submenu_settings_help()
{
    ?>
    
    <h2>Ayuda</h2>
    
    <h3>¿Qué hace este módulo?</h3>
    
    <p>Te permite integrar tu Woocommerce a FACTO para pedir a los clientes sus datos de facturación y emitir automáticamente facturas y boletas electrónicas</p>
    
    <h3>¿Cuál es el costo de habilitación?</h3>
    
    <p>Factura y factura exenta electrónica es gratuito al utilizar FACTO. Puedes consultar el costo de habilitar boleta y boleta exenta electrónica <a href="https://www.facto.cl/complementos/documentos-adicionales/" target="_blank">Aquí</a></p>
    
    <h3>¿Cuál es el costo mensual?</h3>
    
    <p>El costo del módulo depende de tu nivel de facturación. Para facturación bajo $1.000.000 es GRATIS y no pagas ninguna mensualidad. Consulta la tabla completa <a href="https://www.facto.cl/producto/integracion-facto-api/" target="_blank">AQUI</a></p>

	<h3>¿Qué es una llave API?</h3>
	
	<p>Corresponde a una llave que te permite integrar FACTO con tu sistema, en este caso con tu e-commerce. La obtienes ingresando a tu cuenta Facto, en el menú Administración, en la opción API</p>
	
	<h3>¿Cómo obtengo una llave API?</h3>
	
	<p>Ingresando a tu cuenta FACTO, en el menú Administración, en la opción API. Luego en la pestaña de llaves presionas el botón CREAR LLAVE API</p>
	
	
	<h3>¿Qué pasa si me da errores al emitir la boleta/factura?</h3>
	
	<p>Revisa que tengas los folios CAF suficientes en FACTO, que tu certificado sea válido, y que la llave API esté correctamente configurada. Puedes revisar el detalle del error en la pestaña "Logs"</p>
	
	<h3>¿Cómo corrijo o anulo un documento emitido por el módulo?</h3>
	
	<p>Debes ingresar a tu cuenta FACTO y emitir la nota de crédito para anular o modificar lo que corresponda.</p>
	
	<h3>¿Mis productos quedan sincronizados con FACTO?</h3>
	
	<p>El módulo te permitirá rebajar automáticamente el inventario de los productos que vendas en tu tienda. Para eso debes asegurarte que el SKU de tu tienda corresponda con el de FACTO. Si necesitas integración bidireccional para crear nuevos productos o actualizar los cambios de stock de Facto en tu tienda, por favor contáctanos.</p>
	
    <h3>¿Se puede aplicar impuestos específicos?</h3>
    <p>Por ahora el módulo sólo soporta impuestos específicos a la Ley de Alcoholes.</p>
    <p>Para crear impuestos específicos debe dirigirse a woocommerce >> Ajustes >> Impuestos y escribir el tipo de  impuesto en el campo "Clases de impuestos adicionales" (ILA cerveza, ILA vino, IlA destilados). Al guardar los cambios se agregará una pestaña correspondiente al tipo de impuesto específico.</p>
    <a href="<?php echo plugin_dir_url( __FILE__ ) ?>images/config_imp_especificos.png" target="_blank">
        <img src="<?php echo plugin_dir_url( __FILE__ ) ?>images/config_imp_especificos.png" width="500"/>
    </a>
    <p>Para configurar el impuesto específico debe agregar dos filas, una para el IVA y otra para el impuesto específico, con los siguientes datos:</p>
    <ul>
        <li>Código país: CL (Chile).</li>
        <li>Tarifa %: Número decimal (Debes ingresar 20,5% para ILA cerveza e ILA vino y 31,5% para ILA destilados)</li>
        <li>Nombre del impuesto: "IVA" (para la primera fila). "ILA cerveza", "ILA vino," "ILA destilados" (para la segunda fila). <b>Debes escribirlos de EXACTAMENTE la misma manera, en caso contrario FACTO no podrá identificar el impuesto y la emisión NO funcionará.</b></li>
        <li>Prioridad: Para IVA es prioridad 1 (primera fila) e impuestos específicos es prioridad 2 (segunda fila).</li>
        <li>Compuesto: Si la tasa de impuestos se agrega encima del IVA (NO marcar).</li>
                <li>Envío: Si la tasa de impuestos se aplica también al envío. (NO marcar)</li>
    </ul>
    <a href="<?php echo plugin_dir_url( __FILE__ ) ?>images/config_imp_especificos_detalle.PNG" target="_blank">
        <img src="<?php echo plugin_dir_url( __FILE__ ) ?>images/config_imp_especificos_detalle.png" width="500"/>
    </a>
	<h3>Tengo otras preguntas</h3>
	
	<p>Ingresa a <a href="https://www.facto.cl" target="_blank">www.facto.cl</a> o escríbenos a <a href="mailto:soporte@facto.cl">soporte@facto.cl</a></p>
    
    <?php 
}


function facto_fe_submenu_settings_callback()
{
    if (isset($_REQUEST["settings-updated"]) && sanitize_text_field($_REQUEST["settings-updated"] == true)) {
        echo "<script>alert('Se han guardado la nuevas opciones.');</script>";
    }
    
        
    
    //RUT
    
    //wp_register_script('jquery.script', plugins_url('jquery.script.js', __FILE__));
    //wp_enqueue_script('jquery.script');
    
    //wp_register_script('jquery.Rut', plugins_url('jquery.Rut.js', __FILE__));
    //wp_enqueue_script('jquery.Rut');
    
    //script
    wp_register_script('facto_script_admin', plugins_url('js/factofacturacionelectronica_admin.js', __FILE__));
    wp_enqueue_script('facto_script_admin');
    
    
 
    ?>
        
    
    
    <?php //wp_enqueue_style( 'style', plugins_url( 'assets/css/style.css', __FILE__ ) );?>
<style>
input[type=text], select {
    width: 400px;
    margin: 0;
    padding: 6px !important;
    box-sizing: border-box;
    vertical-align: top;
	height: auto;
	    line-height: 2;
    min-height: 30px;
}
    </style>

<div class="wrap woocommerce" id="facto-conf">

<div style="background-color:none;">
<a href="https://www.facto.cl" target="_blank">
<img src="<?php echo plugin_dir_url( __FILE__ ) ?>images/logo_nous.png" width="160"/>
</a>
</div>
<h1>Integración de Facturación Eléctrónica en WooComerce</h1>
	
	<hr>

	

<!-- LLAVE -->


<h2 class="nav-tab-wrapper">
    <a href="?page=facto_settings&tab=api" class="nav-tab <?php if ((!isset($_REQUEST['tab'])) || ($_REQUEST['tab'] == "api"))
{
    print " nav-tab-active";
}?>">TOKEN DE ACCESO A SERVICIOS</a>
    <a href="?page=facto_settings&tab=docs" class="nav-tab <?php if ($_REQUEST['tab'] == "docs")
{
    print " nav-tab-active";
}?>">TIPO DE DOCUMENTO A GENERAR</a>
    <a href="?page=facto_settings&tab=issuanceconditions" class="nav-tab <?php if ($_REQUEST['tab'] == "issuanceconditions")
{
    print " nav-tab-active";
}?>">CONDICIONES PARA EMITIR - ESTADO DE PEDIDO</a>
    <a href="?page=facto_settings&tab=advanced" class="nav-tab <?php if ($_REQUEST['tab'] == "advanced")
{
    print " nav-tab-active";
}?>">AVANZADO CALCULO DE IMPUESTO</a>
    <a href="?page=facto_settings&tab=logs" class="nav-tab <?php if ($_REQUEST['tab'] == "logs")
{
    print " nav-tab-active";
}?>">LOG'S DE FACTURACION</a>
    <a href="?page=facto_settings&tab=help" class="nav-tab <?php if ($_REQUEST['tab'] == "help")
{
    print " nav-tab-active";
}?>">AYUDA</a>
</h2>


<?php 

if ((!isset($_REQUEST['tab'])) || ($_REQUEST['tab'] == "api"))
{
    facto_fe_submenu_settings_api();
}
elseif ($_REQUEST['tab'] == "docs")
{
    facto_fe_submenu_settings_docs();
}
elseif ($_REQUEST['tab'] == "issuanceconditions")
{
    facto_fe_submenu_settings_issuanceconditions();
}
elseif ($_REQUEST['tab'] == "advanced")
{
    facto_fe_submenu_settings_advanced();
}
elseif ($_REQUEST['tab'] == "logs")
{
    facto_fe_submenu_settings_logs();
}
elseif ($_REQUEST['tab'] == "help")
{
    facto_fe_submenu_settings_help();
}

?>


</div>


<?php
}

function facto_fe_register_facto_settings()
{
    // Comprobar que existan las tablas necesarias
    global $wpdb;
    
    $sql = 'show tables like "'.$wpdb->prefix.'facto_order_mp"';
    $resultado = $wpdb->query($sql);
    
    if ($resultado == 0)
    {    
        add_action( 'admin_notices', 'facto_fe_errorordertable' );    
    }
    
    $sql = 'show tables like "'.$wpdb->prefix.'facto_order_mp"';
    $resultado = $wpdb->query($sql);
    
    if ($resultado == 0)
    {
        add_action( 'admin_notices', 'facto_fe_errorlogtable' );
    }
    
    
    register_setting('facto_settings_group_api', 'facto_webservice_user');
    register_setting('facto_settings_group_api', 'facto_webservice_pass');
    register_setting('facto_settings_group_api', 'facto_webservice_mode');
    
    
    // Si el modo no está seteado el modo, veamos si tenemos user o no
    if (get_option('facto_webservice_mode') == "")
    {
        if (get_option('facto_webservice_user') == "")
        {
            add_option('facto_webservice_mode', 'test');
        }
        else
        {
            add_option('facto_webservice_mode', 'production');
        }
    }
    
    
    register_setting('facto_settings_group_docs', 'facto_docs_default');

    register_setting('facto_settings_group_docs', 'facto_checkbox_fe');
    register_setting('facto_settings_group_docs', 'facto_checkbox_fee');
    register_setting('facto_settings_group_docs', 'facto_checkbox_be');
    register_setting('facto_settings_group_docs', 'facto_checkbox_bee');
    
    
    // Veamos si no tuvieramos ningún tipo de documento activo
    if (
    (get_option('facto_checkbox_fe') == "") &&
    (get_option('facto_checkbox_fee') == "") &&
    (get_option('facto_checkbox_be') == "") &&
    (get_option('facto_checkbox_bee') == "")    
    )
    {
        add_action( 'admin_notices', 'facto_fe_errornosetup' );
    }
    
    if (!function_exists("mb_detect_encoding")) {
    
        add_action( 'admin_notices', 'facto_fe_errornombstring' );
    }

    register_setting('facto_settings_group_orderstatus', 'facto_checkbox_estadoprocesando');
    register_setting('facto_settings_group_orderstatus', 'facto_checkbox_estadocompletado');
    
    register_setting('facto_settings_group_advanced', 'facto_checkbox_totalneto');
    register_setting('facto_settings_group_advanced', 'facto_checkbox_add_shipping', array("default" => 1));
    register_setting('facto_settings_group_advanced', 'facto_select_rounding_type', array("default" => 1));

    //para cada mp obtenemos su configuraci&oacute;n
    if (class_exists( 'woocommerce' ))
    {
        $mp_arr = WC()->payment_gateways->get_available_payment_gateways();
    }
    else
    {
        
        add_action( 'admin_notices', 'facto_fe_errornowoocommerce' );
        
        $mp_arr = array();
    }

    foreach ($mp_arr as $mp) {
        $code = $mp->id;
        register_setting('facto_settings_group_paymentgateways', "facto_" . $code . "_fact");
    }
}


/************************* COMUNAS ********************************
 ***********************************************************************/
/*function facto_fe_comunas_de_chile($states) {
	$states['CL'] = array(
			'100' => 'Algarrobo',
			'101' => 'Alhu&eacute;',
			'102' => 'Alto Biob&iacute;o',
			'103' => 'Alto del Carmen',
			'104' => 'Alto Hospicio',
			'105' => 'Ancud',
			'106' => 'Andacollo',
			'107' => 'Angol',
			'108' => 'Ant&aacute;rtica',
			'109' => 'Antofagasta',
			'110' => 'Antuco',
			'111' => 'Arauco',
			'112' => 'Arica',
			'113' => 'Ays&eacute;n',
			'114' => 'Buin',
			'115' => 'Bulnes',
			'116' => 'Cabildo',
			'117' => 'Cabo de Hornos',
			'118' => 'Cabrero',
			'119' => 'Calama',
			'120' => 'Calbuco',
			'121' => 'Caldera',
			'122' => 'Calera de Tango',
			'123' => 'Calle Larga',
			'124' => 'Camarones',
			'125' => 'CamiÃƒÂ±a',
			'126' => 'Canela',
			'127' => 'CaÃƒÂ±ete',
			'128' => 'Carahue',
			'129' => 'Cartagena',
			'130' => 'Casablanca',
			'131' => 'Castro',
			'132' => 'Catemu',
			'133' => 'Cauquenes',
			'134' => 'Cerrillos',
			'135' => 'Cerro Navia',
			'136' => 'Chait&eacute;n',
			'137' => 'Chanco',
			'138' => 'ChaÃƒÂ±aral',
			'139' => 'Ch&eacute;pica',
			'140' => 'Chiguayante',
			'141' => 'Chile Chico',
			'142' => 'Chill&aacute;n',
			'143' => 'Chill&aacute;n Viejo',
			'144' => 'Chimbarongo',
			'145' => 'Cholchol',
			'146' => 'Chonchi',
			'147' => 'Cisnes',
			'148' => 'Cobquecura',
			'149' => 'Cocham&oacute;',
			'150' => 'Cochrane',
			'151' => 'Codegua',
			'152' => 'Coelemu',
			'153' => 'Coihueco',
			'154' => 'Coinco',
			'155' => 'Colb&uacute;n',
			'156' => 'Colchane',
			'157' => 'Colina',
			'158' => 'Collipulli',
			'159' => 'Coltauco',
			'160' => 'Combarbal&aacute;',
			'161' => 'Concepci&oacute;n',
			'162' => 'Conchal&iacute;',
			'163' => 'Conc&oacute;n',
			'164' => 'Constituci&oacute;n',
			'165' => 'Contulmo',
			'166' => 'Copiap&oacute;',
			'167' => 'Coquimbo',
			'168' => 'Coronel',
			'169' => 'Corral',
			'170' => 'Coyhaique',
			'171' => 'Cunco',
			'172' => 'Curacaut&iacute;n',
			'173' => 'Curacav&iacute;',
			'174' => 'Curaco de V&eacute;lez',
			'175' => 'Curanilahue',
			'176' => 'Curarrehue',
			'177' => 'Curepto',
			'178' => 'Curic&oacute;',
			'179' => 'Dalcahue',
			'180' => 'Diego de Almagro',
			'181' => 'DoÃƒÂ±ihue',
			'182' => 'El Bosque',
			'183' => 'El Carmen',
			'184' => 'El Monte',
			'185' => 'El Quisco',
			'186' => 'El Tabo',
			'187' => 'Empedrado',
			'188' => 'Ercilla',
			'189' => 'Estaci&oacute;n Central',
			'190' => 'Florida',
			'191' => 'Freire',
			'192' => 'Freirina',
			'193' => 'Fresia',
			'194' => 'Frutillar',
			'195' => 'Futaleuf&uacute;',
			'196' => 'Futrono',
			'197' => 'Galvarino',
			'198' => 'General Lagos',
			'199' => 'Gorbea',
			'200' => 'Graneros',
			'201' => 'Guaitecas',
			'202' => 'Hijuelas',
			'203' => 'Hualaihu&eacute;',
			'204' => 'HualaÃƒÂ±&eacute;',
			'205' => 'Hualp&eacute;n',
			'206' => 'Hualqui',
			'207' => 'Huara',
			'208' => 'Huasco',
			'209' => 'Huechuraba',
			'210' => 'Illapel',
			'211' => 'Independencia',
			'212' => 'Iquique',
			'213' => 'Isla de Maipo',
			'214' => 'Isla de Pascua',
			'215' => 'Juan Fern&aacute;ndez',
			'216' => 'La Calera',
			'217' => 'La Cisterna',
			'218' => 'La Cruz',
			'219' => 'La Estrella',
			'220' => 'La Florida',
			'221' => 'La Granja',
			'222' => 'La Higuera',
			'223' => 'La Ligua',
			'224' => 'La Pintana',
			'225' => 'La Reina',
			'226' => 'La Serena',
			'227' => 'La Uni&oacute;n',
			'228' => 'Lago Ranco',
			'229' => 'Lago Verde',
			'230' => 'Laguna Blanca',
			'231' => 'Laja',
			'232' => 'Lampa',
			'233' => 'Lanco',
			'234' => 'Las Cabras',
			'235' => 'Las Condes',
			'236' => 'Lautaro',
			'237' => 'Lebu',
			'238' => 'Licant&eacute;n',
			'239' => 'Limache',
			'240' => 'Linares',
			'241' => 'Litueche',
			'242' => 'Llanquihue',
			'243' => 'Llay Llay',
			'244' => 'Lo Barnechea',
			'245' => 'Lo Espejo',
			'246' => 'Lo Prado',
			'247' => 'Lolol',
			'248' => 'Loncoche',
			'249' => 'Longav&iacute;',
			'250' => 'Lonquimay',
			'251' => 'Los &aacute;lamos',
			'252' => 'Los Andes',
			'253' => 'Los &aacute;ngeles',
			'254' => 'Los Lagos',
			'255' => 'Los Muermos',
			'256' => 'Los Sauces',
			'257' => 'Los Vilos',
			'258' => 'Lota',
			'259' => 'Lumaco',
			'260' => 'Machal&iacute;',
			'261' => 'Macul',
			'262' => 'M&aacute;fil',
			'263' => 'Maip&uacute;',
			'264' => 'Malloa',
			'265' => 'Marchihue',
			'266' => 'Mar&iacute;a Elena',
			'267' => 'Mar&iacute;a Pinto',
			'268' => 'Mariquina',
			'269' => 'Maule',
			'270' => 'Maull&iacute;n',
			'271' => 'Mejillones',
			'272' => 'Melipeuco',
			'273' => 'Melipilla',
			'274' => 'Molina',
			'275' => 'Monte Patria',
			'276' => 'Mostazal',
			'277' => 'Mulch&eacute;n',
			'278' => 'Nacimiento',
			'279' => 'Nancagua',
			'280' => 'Natales',
			'281' => 'Navidad',
			'282' => 'Negrete',
			'283' => 'Ninhue',
			'284' => 'Nogales',
			'285' => 'Nueva Imperial',
			'286' => 'Ãƒâ€˜iqu&eacute;n',
			'287' => 'Ãƒâ€˜uÃƒÂ±oa',
			'288' => 'O\'Higgins',
			'289' => 'Olivar',
			'290' => 'OllagÃƒÂ¼e',
			'291' => 'Olmu&eacute;',
			'292' => 'Osorno',
			'293' => 'Ovalle',
			'294' => 'Padre Hurtado',
			'295' => 'Padre las Casas',
			'296' => 'Paihuano',
			'297' => 'Paillaco',
			'298' => 'Paine',
			'299' => 'Palena',
			'300' => 'Palmilla',
			'301' => 'Panguipulli',
			'302' => 'Panquehue',
			'303' => 'Papudo',
			'304' => 'Paredones',
			'305' => 'Parral',
			'306' => 'Pedro Aguirre Cerda',
			'307' => 'Pelarco',
			'308' => 'Pelluhue',
			'309' => 'Pemuco',
			'310' => 'Pencahue',
			'311' => 'Penco',
			'312' => 'PeÃƒÂ±aflor',
			'313' => 'PeÃƒÂ±alol&eacute;n',
			'314' => 'Peralillo',
			'315' => 'Perquenco',
			'316' => 'Petorca',
			'317' => 'Peumo',
			'318' => 'Pica',
			'319' => 'Pichidegua',
			'320' => 'Pichilemu',
			'321' => 'Pinto',
			'322' => 'Pirque',
			'323' => 'Pitrufqu&eacute;n',
			'324' => 'Placilla',
			'325' => 'Portezuelo',
			'326' => 'Porvenir',
			'327' => 'Pozo Almonte',
			'328' => 'Primavera',
			'329' => 'Providencia',
			'330' => 'Puchuncav&iacute;',
			'331' => 'Puc&oacute;n',
			'332' => 'Pudahuel',
			'333' => 'Puente Alto',
			'334' => 'Puerto Montt',
			'335' => 'Puerto Octay',
			'336' => 'Puerto Varas',
			'337' => 'Pumanque',
			'338' => 'Punitaqui',
			'339' => 'Punta Arenas',
			'340' => 'Puqueld&oacute;n',
			'341' => 'Pur&eacute;n',
			'342' => 'Purranque',
			'343' => 'Putaendo',
			'344' => 'Putre',
			'345' => 'Puyehue',
			'346' => 'Queil&eacute;n',
			'347' => 'Quell&oacute;n',
			'348' => 'Quemchi',
			'349' => 'Quilaco',
			'350' => 'Quilicura',
			'351' => 'Quilleco',
			'352' => 'Quill&oacute;n',
			'353' => 'Quillota',
			'354' => 'Quilpu&eacute;',
			'355' => 'Quinchao',
			'356' => 'Quinta de Tilcoco',
			'357' => 'Quinta Normal',
			'358' => 'Quintero',
			'359' => 'Quirihue',
			'360' => 'Rancagua',
			'361' => 'R&aacute;nquil',
			'362' => 'Rauco',
			'363' => 'Recoleta',
			'364' => 'Renaico',
			'365' => 'Renca',
			'366' => 'Rengo',
			'367' => 'Requ&iacute;noa',
			'368' => 'Retiro',
			'369' => 'Rinconada',
			'370' => 'R&iacute;o Bueno',
			'371' => 'R&iacute;o Claro',
			'372' => 'R&iacute;o Hurtado',
			'373' => 'R&iacute;o Ib&aacute;ÃƒÂ±ez',
			'374' => 'R&iacute;o Negro',
			'375' => 'R&iacute;o Verde',
			'376' => 'Romeral',
			'377' => 'Saavedra',
			'378' => 'Sagrada Familia',
			'379' => 'Salamanca',
			'380' => 'San Antonio',
			'381' => 'San Bernardo',
			'382' => 'San Carlos',
			'383' => 'San Clemente',
			'384' => 'San Esteban',
			'385' => 'San Fabi&aacute;n',
			'386' => 'San Felipe',
			'387' => 'San Fernando',
			'388' => 'San Gregorio',
			'389' => 'San Ignacio',
			'390' => 'San Javier',
			'391' => 'San Joaqu&iacute;n',
			'392' => 'San Jos&eacute; de Maipo',
			'393' => 'San Juan de la Costa',
			'394' => 'San Miguel',
			'395' => 'San Nicol&aacute;s',
			'396' => 'San Pablo',
			'397' => 'San Pedro',
			'398' => 'San Pedro de Atacama',
			'399' => 'San Pedro de la Paz',
			'400' => 'San Rafael',
			'401' => 'San Ram&oacute;n',
			'402' => 'San Rosendo',
			'403' => 'San Vicente',
			'404' => 'Santa B&aacute;rbara',
			'405' => 'Santa Cruz',
			'406' => 'Santa Juana',
			'407' => 'Santa Mar&iacute;a',
			'408' => 'Santiago',
			'409' => 'Santo Domingo',
			'410' => 'Sierra Gorda',
			'411' => 'Talagante',
			'412' => 'Talca',
			'413' => 'Talcahuano',
			'414' => 'Taltal',
			'415' => 'Temuco',
			'416' => 'Teno',
			'417' => 'Teodoro Schmidt',
			'418' => 'Tierra Amarilla',
			'419' => 'Tiltil',
			'420' => 'Timaukel',
			'421' => 'Tir&uacute;a',
			'422' => 'Tocopilla',
			'423' => 'Tolt&eacute;n',
			'424' => 'Tom&eacute;',
			'425' => 'Torres del Paine',
			'426' => 'Tortel',
			'427' => 'Traigu&eacute;n',
			'428' => 'Treguaco',
			'429' => 'Tucapel',
			'430' => 'Valdivia',
			'431' => 'Vallenar',
			'432' => 'Valpara&iacute;so',
			'433' => 'Vichuqu&eacute;n',
			'434' => 'Victoria',
			'435' => 'VicuÃƒÂ±a',
			'436' => 'Vilc&uacute;n',
			'437' => 'Villa Alegre',
			'438' => 'Villa Alemana',
			'439' => 'Villarrica',
			'440' => 'ViÃƒÂ±a del Mar',
			'441' => 'Vitacura',
			'442' => 'Yerbas Buenas',
			'443' => 'Yumbel',
			'444' => 'Yungay',
			'445' => 'Zapallar'
	); 
	return $states;
}*/
//add_filter('woocommerce_states', 'comunas_de_chile');


/******************** FACTURA DETALLES DE PEDIDO / ADMIN ***************
 ***********************************************************************/

//mostramos la factura
add_action('woocommerce_admin_order_data_after_billing_address', 'facto_fe_facto_factura_admin');
function facto_fe_facto_factura_admin($order)
{
    /*
        $oml_estado == 0 => factura emitida y enviada
        $oml_estado == 1 => factura no generada
        $oml_estado == 2 => factura generada pero no enviada
    */

    global $wpdb;


    if (isset($_REQUEST['facto_fe_facturar'])) {
        $data = facto_fe_data_orden($order->get_id());

        facto_fe_comunicacion_api($data);
    }


    

        ?>
        
        <input type="hidden" name="oml_order_id" id="oml_order_id" value="<?php echo esc_attr($order->get_id()); ?>" />

				<h4><img src="<?php echo plugin_dir_url( __FILE__ ) ?>images/logo_nous.png" style="width:15px;">FACTU NOUS Integración facturación electrónica para Woocomerce</h4>
        
        <?php

        $sql = "SELECT fact, estado, msg, enlace FROM ".$wpdb->prefix."facto_order_mp
        WHERE order_id = '" . esc_sql($order->get_id())."'";

        $results = $wpdb->get_results($sql);


        if (empty($results))
        {
            print "<p><button type='button' class='button button-primary' onclick=\"this.innerHTML = 'Generando...por favor espera';this.disabled=true;window.location.href += '&facto_fe_facturar=1';\">Generar documento tributario</button></p>";
            
        }
        else
        {
            $resultado = $results[0];
        
            $estado = $resultado->estado;
            $msg = $resultado->msg;
            $enlace = $resultado->enlace;
    
    
            if ($enlace == '')
            {
                
                //elegir tipo documento manualmente en pedidos (admin check)
                
                //link generar boleta --> generar automaticamente.
                
                //link generar factura --> mostrar el form --> generar factura.
                
                
                
                //print "<a href='javascript:facturar(".$this->getOrder()->getId().")' id='btn_oml_estado'>Generar la factura</a>";
                //$aer = plugin_dir_path( __FILE__ );
                //var_dump ($aer);
                print "<p><button type='button' class='button button-primary' onclick=\"this.innerHTML = 'Generando...por favor espera';this.disabled=true;window.location.href += '&facto_fe_facturar=1';\">Generar documento tributario</button></p>";
                
                //echo " <a href=\"post.php?post=".$_REQUEST['post']."&action=edit&facto_fe_facturar=1';\" id='btn_oml_estado'>Generar factura</a>";
                //print "plugin_dir_path( __FILE__ )";
                //print "<span id='ajax_loader' style='display:none'><img src='".$this->getSkinUrl('images/opc-ajax-loader.gif')."'/></span>";
    
                if ($msg != "")
                {
                    echo "<p id='p_oml_estado'><b style='color:red'>Documento no generado:</b> " . esc_html($msg) . "</p>";
                }
            }
            else
            {
                
                print "<p><b style='color:green'>Documento generado exitosamente:</b> Puedes ver el documento tributario en el siguiente <a href='" . esc_attr($enlace) . "' target='_blank'>enlace</a></p>";
            }
        
        }
        ?>

        <?php

        //wp_register_script('jquery.script', plugins_url('jquery.script.js', __FILE__));
        //wp_enqueue_script('jquery.script');

        //wp_register_script('oml_facto_admin_order', plugins_url('oml_facto_admin_order.js', __FILE__));
        //wp_enqueue_script('oml_facto_admin_order');

}


add_action('woocommerce_order_details_after_order_table', 'facto_fe_facto_factura');
function facto_fe_facto_factura($order)
{

    /*
        $oml_estado == 0 => factura no emitida
        $oml_estado == 1 => factura emitida correctamente
        $oml_estado == 2 => factura emitida con error
    */

    global $wpdb;

    $data = facto_fe_data_orden($order->get_id());

    if ($data == false) {
        echo "Hubo un problema para obtener los datos de la orden. Revisa que no se hayan eliminado los productos o realizado algún otro cambio que impida recuperar la información.";
    } else {

        $sql = "SELECT fact, estado, msg, enlace FROM ".$wpdb->prefix."facto_order_mp
        WHERE order_id = '" . esc_sql($order->get_id())."'";

        $results = $wpdb->get_results($sql);


    if (!empty($results))
    {

        $resultado = $results[0];
        
        //** Al recargar aparece en el checkout
        
        ?>
 
        <div>
					<h3>Facturaci&oacute;n</h3>
            <?php
            if ($data['total_final'] == 0) {
                echo "Esta venta no generar&aacute; documento tributario ya que su monto total es igual a cero.";
            } elseif ($resultado->estado == 0) {
                echo "Documento generado y enviado al SII. <a href='" . esc_url($resultado->enlace) . "' target='_blank'>enlace</a>";
            } else if ($resultado->estado == 1) {
                echo "Se ha producido un error al generar el documento tributario.";
            } else if ($resultado->estado == 2) {
                echo "El documento se gener&oacute; como borrador pero no se ha podido enviar al SII. Ingrese a FACTO para resolver esta situaci&oacute;n.";

                if ($resultado->enlace != "") {
                    print "<a href='" . esc_url($resultado->enlace) . "' target='_blank'>enlace</a>";
                }

            } else {
                echo "Ha ocurrido un error inesperado.";
            }
            ?>
        </div>
        <?php
    }
    
    }
}




//mostramos los valores en la orden edit page
add_action('woocommerce_admin_order_data_after_billing_address', 'facto_fe_custom_checkout_field_display_admin_order_meta', 10, 4);
add_action('woocommerce_order_details_after_order_table', 'facto_fe_custom_checkout_field_display_admin_order_meta', 10, 4);
function facto_fe_custom_checkout_field_display_admin_order_meta($order)
{
    
    /*echo '<p><strong>' . __('Direcci&oacute;n del receptor') . ':</strong> ' . get_post_meta($order->get_id(), 'campo_receptor_direccion', true) . '</p>';
     echo '<p><strong>' . __('Comuna del receptor') . ':</strong> ' . get_post_meta($order->get_id(), 'campo_receptor_comuna', true) . '</p>';
     echo '<p><strong>' . __('Tel&eacute;fono del receptor') . ':</strong> ' . get_post_meta($order->get_id(), 'campo_receptor_telefono', true) . '</p>';*/
    
    
    echo '<p><strong>' . __('Direcci&oacute;n del receptor') . ':</strong> ' . get_post_meta($order->get_id(), '_billing_address_1', true) . '</p>';
    echo '<p><strong>' . __('Comuna del receptor') . ':</strong> ' . get_post_meta($order->get_id(), '_billing_state', true) . '</p>';
    echo '<p><strong>' . __('Tel&eacute;fono del receptor') . ':</strong> ' . get_post_meta($order->get_id(), '_billing_phone', true) . '</p>';
    
    
    if (get_post_meta($order->get_id(), 'Tipo de documento', true) == "be")
    {
        echo '<p><strong>' . __('Tipo de documento') . ':</strong> Boleta electr&oacute;nica</p>';
    }
    
    if (get_post_meta($order->get_id(), 'Tipo de documento', true) == "bee")
    {
        echo '<p><strong>' . __('Tipo de documento') . ':</strong> Boleta exenta electr&oacute;nica</p>';
    }
    
    if (get_post_meta($order->get_id(), 'Tipo de documento', true) == "fe")
    {
        echo '<p><strong>' . __('Tipo de documento') . ':</strong> Factura electr&oacute;nica</p>';
    }
    
    if (get_post_meta($order->get_id(), 'Tipo de documento', true) == "fee")
    {
        echo '<p><strong>' . __('Tipo de documento') . ':</strong> Factura exenta electr&oacute;nica</p>';
    }
                    
    if (get_post_meta($order->get_id(), 'Tipo de documento', true) == "fe" || get_post_meta($order->get_id(), 'Tipo de documento', true) == "fee")
    {
        echo '<p><strong>' . __('RUT') . ':</strong> ' . esc_html(get_post_meta($order->get_id(), 'RUT', true)) . '</p>';
        echo '<p><strong>' . __('Giro') . ':</strong> ' . esc_html(get_post_meta($order->get_id(), 'Giro', true)) . '</p>';
        
        // Compatibilidad con versión anterior que usaba acento
        if (get_post_meta($order->get_id(), 'Raz&oacute;n social', true) != "")
        {
            echo '<p><strong>' . __('Raz&oacute;n social') . ':</strong> ' . esc_html(get_post_meta($order->get_id(), 'Raz&oacute;n social', true)) . '</p>';
        }                        
        else
        {
            echo '<p><strong>' . __('Raz&oacute;n social') . ':</strong> ' . esc_html(get_post_meta($order->get_id(), 'Razon social', true)) . '</p>';
        }
        
    }

    if (get_post_meta($order->get_id(), 'Folio de documento', true) != "") {
        echo '<p><strong>' . __('Folio documento') . ':</strong> ' . esc_html(get_post_meta(
                $order->get_id(),
                'Folio de documento',
                true
            )) . '</p>';
    }

    if (get_post_meta($order->get_id(), 'Fecha de documento', true) != "") {

        list($ano,$mes,$dia) = explode("-",get_post_meta(
            $order->get_id(),
            'Fecha de documento',
            true
        ));

        echo '<p><strong>' . __('Fecha de documento') . ':</strong> ' . esc_html($dia."-".$mes."-".$ano) . '</p>';
    }
}

