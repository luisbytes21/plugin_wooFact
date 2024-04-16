<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly




// Permite llevar todos los datos a XML para ser procesados
function facto_fe_encodingparaxml($origen)
{
    require_once dirname(__FILE__)."/forceutf8/encoding.php";

    $origen = \ForceUTF8\Encoding::toUTF8($origen);
    
    $origen = str_replace("&", "&amp;", $origen);
    $origen = str_replace("<", "&lt;", $origen);
    $origen = str_replace(">", "&gt;", $origen);
    
    return $origen;
}


function facto_fe_getpaymentmethod($order)
{
    if (get_option("facto_" . $order->get_payment_method() . "_fact"))
    {
        
        $fact = get_option("facto_" . $order->get_payment_method() . "_fact");
        
        if ($fact != "auto" && $fact != "manual") {
            
            $fact = "manual";
        }
        
        return $fact;
        
    }
    else
    {
        return "manual";
    }
}

function facto_fe_data_orden($order_id)
{
    $order = new WC_Order($order_id);
    
    $data = array();
    $data['order_id'] = $order_id;
    
    if (get_post_meta($order_id, 'Tipo de documento', true) == "be")
    {
        $data['tipo_dte'] = 39;
    }
    else if (get_post_meta($order_id, 'Tipo de documento', true) == "bee")
    {
        $data['tipo_dte'] = 41;
    }
    else if (get_post_meta($order_id, 'Tipo de documento', true) == "fe")
    {
        $data['tipo_dte'] = 33;
    }
    else if (get_post_meta($order_id, 'Tipo de documento', true) == "fee")
    {
        $data['tipo_dte'] = 34;
    }
    
    
    
    $data['fecha_emision'] = date('Y-m-d');
    
    if ($data['tipo_dte'] == 33 || $data['tipo_dte'] == 34) {
        $data['receptor_rut'] = get_post_meta($order_id, 'RUT', true);
        
        if (get_post_meta($order_id, 'Razon social', true) == "")
        {
            $data['receptor_razon'] = get_post_meta($order_id, 'Raz&oacute;n social', true);
        }
        else
        {
            $data['receptor_razon'] = get_post_meta($order_id, 'Razon social', true);
        }
        
        $data['receptor_giro'] = get_post_meta($order_id, 'Giro', true);
    } else {
        $data['receptor_rut'] = "";
        $data['receptor_razon'] = "";
        $data['receptor_giro'] = "";
    }
    
    
    $data['receptor_direccion'] = get_post_meta($order_id, '_billing_address_1', true);
    $data['receptor_comuna'] = get_post_meta($order_id, '_billing_state', true);
    $data['receptor_ciudad'] = get_post_meta($order_id, '_billing_city', true);
    $data['receptor_telefono'] = get_post_meta($order_id, '_billing_phone', true);
    
    
    /*$data['receptor_direccion'] = get_post_meta($order_id, 'campo_receptor_direccion', true);
    
    $data['receptor_comuna'] = get_post_meta($order_id, 'campo_receptor_comuna', true);
    //$data['receptor_comuna'] = $nombre_comunas['CL'][get_post_meta( $order_id, '_billing_state', true)];
    $data['receptor_ciudad'] = get_post_meta($order_id, '_billing_city', true);
    
    $data['receptor_telefono'] = get_post_meta($order_id, 'campo_receptor_telefono', true);*/
    $data['receptor_email'] = get_post_meta($order_id, '_billing_email', true);
    
    
    $data['condiciones_pago'] = 0;
    
    $data['orden_compra_num'] = $order->get_order_number();
    $data['orden_compra_fecha'] = $data['fecha_emision'];
    
    $data['cantidad'] = $order->get_item_count();
    
    //detalles
    $detalles = array();
    $total_exento = 0;
    $total_afecto = 0;
    $total_afecto_bruto = 0;
    
    
    $todosexentos = true;
    
    if(esc_attr(get_option('facto_checkbox_add_shipping')) != 3) {
        $items = $order->get_items();
    foreach ($items as $item) {


        // Veamos si tenemos variaciones del producto
        if ((isset($item['variation_id'])) && ($item['variation_id'] != "") && ($item['variation_id'] != "0")) {

            $product = new WC_Product_Variation($item['variation_id']);

            if ($product != false) {
                $detalle['sku'] = $product->get_sku();
            } else {

                // Intentemos usar el SKU principal

                $product = wc_get_product($item['product_id']);

                if ($product != false) {
                    $detalle['sku'] = $product->get_sku();
                } else {
                    $detalle['sku'] = "";
                }
            }

        } else {
            $product = wc_get_product($item['product_id']);


            if ($product != false) {
                $detalle['sku'] = $product->get_sku();
            } else {
                $detalle['sku'] = "";
            }
        }

        
        $detalle['cantidad'] = (int)$item['qty'];
        $detalle['unidad'] = ""; //$item['type'];
        $detalle['glosa'] = $item['name'];
        //$detalle['monto_unitario'] = (int)( (int)$item['line_total'] / $detalle['cantidad']  );
        $detalle['descuentorecargo_monto'] = 0;
        $detalle['descuentorecargo_porcentaje'] = 0;
        
        
        
        // Enviamos el total neto si se gestiona el iva mediante woocommerce
        if(get_option('facto_checkbox_totalneto') && wc_tax_enabled())
        {
            // Si hay algún producto afecto a IVA entonces usamos inmediatamente documento afecto
            $taxes = $item->get_taxes();
            
            $tax_items_labels   = array();            
            $tax_label = "";            
            
                //es porque tiene impuesto específico
                if ((isset($item['tax_class'])) && ($item['tax_class'] != "")) {
                    $detalle['tax_class'] = $item['tax_class'];
                    //$detalle['taxes'] = $item['taxes'];
                    $detalle['total_tax'] = $item['total_tax'];
                    if(($item['tax_class'] == "ila-cerveza") || ($item['tax_class'] == "ila-vino")){
                        $detalle['taxes'] = round ($item['total'] * 0.205);
                    }elseif($item['tax_class'] == "ila-destilados"){
                        $detalle['taxes'] = round ($item['total'] * 0.315);
                    }
                }
            foreach ( $order->get_items('tax') as $tax_item ) {
                // Set the tax labels by rate ID in an array
                $tax_items_labels[$tax_item->get_rate_id()] = $tax_item->get_label();
                
            }
            
            foreach( $taxes['subtotal'] as $rate_id => $tax ){
                $tax_label = $tax_items_labels[$rate_id]; // <== Here the line item tax label                
            }
            
            if (count($taxes['subtotal']) != 0) {
            //if ($tax_label == "IVA") {
                $detalle['exento_afecto'] = 1;
                $todosexentos = false;                
                
                $detalle['monto_unitario'] = round($item['line_total'] / $detalle['cantidad'],6);
                
                $total_afecto += round($detalle['cantidad'] * $detalle['monto_unitario']);

                $total_afecto_bruto += round($item['line_total'] * 1.19);
                
            } else {
                $detalle['exento_afecto'] = 0;
                
                $detalle['monto_unitario'] = round($item['line_total'] / $detalle['cantidad'],6);
                
                $total_exento += round($detalle['cantidad'] * $detalle['monto_unitario']);
            }
            
            
        }
        else
        {
            

            if ($data['tipo_dte'] == 34 || $data['tipo_dte'] == 41)
            {
                
                // Documentos exentos
                $detalle['monto_unitario'] = round($item['line_total'] / $detalle['cantidad'],6);
                $detalle['exento_afecto'] = 0;
                $total_exento += round($detalle['cantidad'] * $detalle['monto_unitario']);
                
                
            } else {
                
                // Documentos afectos
                $detalle['monto_unitario'] = round((($item['line_total'] / $detalle['cantidad']) / 1.19),6);
                $detalle['exento_afecto'] = 1;
                $total_afecto += round($detalle['cantidad'] * $detalle['monto_unitario']);

                $total_afecto_bruto += round($item['line_total'] );
                
                $todosexentos = false;
            }
            
            
        }
        
        array_push($detalles, $detalle);
        }
    }
    
    //GASTOS DE ENVIO
    //$gastos_de_envio = $order->get_total_shipping();
    $gastos_de_envio = $order->get_shipping_total();
    if ($gastos_de_envio > 0 && (esc_attr(get_option('facto_checkbox_add_shipping')) == 1 || esc_attr(get_option('facto_checkbox_add_shipping')) == 3 || esc_attr(get_option('facto_checkbox_add_shipping')) == "")) {
        $detalle = array();
        $detalle['sku'] = "";
        $detalle['cantidad'] = 1;
        $detalle['unidad'] = "uni";
        $detalle['glosa'] = "Gastos de envio";
        $detalle['descuentorecargo_monto'] = 0;
        $detalle['descuentorecargo_porcentaje'] = 0;
        
        
        
        // Si el impuesto lo dejamos para manejo por woocommerce, no necesitamos dividir por 1.19 puesto que el valor de shipping total está como neto.
        if(get_option('facto_checkbox_totalneto') && wc_tax_enabled())
        {
            
            $shipping_tax_label = '';
            
            foreach ( $order->get_items('tax') as $tax_item ) {
                
                // Get the tax label used for shipping (if needed)
                if( ! empty($tax_item->get_shipping_tax_total()) ) {
                    $shipping_tax_label = $tax_item->get_label();
                }
            }

            
            if ($shipping_tax_label == "") {
                
                $detalle['monto_unitario'] = round($gastos_de_envio,6);
                $detalle['exento_afecto'] = 0;
                $total_exento += $detalle['monto_unitario'];
                
            }
            else
            {
                
                $detalle['monto_unitario'] = $gastos_de_envio;
                $detalle['exento_afecto'] = 1;
                $total_afecto += $detalle['monto_unitario'];

                $total_afecto_bruto += round($gastos_de_envio * 1.19);
                
                $todosexentos = false;
            }
            
            
        } else {
                
            if ($data['tipo_dte'] == 34 || $data['tipo_dte'] == 41) {
                $detalle['monto_unitario'] = round($gastos_de_envio,6);
                $detalle['exento_afecto'] = 0;
                $total_exento += $detalle['monto_unitario'];
            }
            else
            {

                
                $detalle['monto_unitario'] = round(($gastos_de_envio / 1.19),6);
                $detalle['exento_afecto'] = 1;
                $total_afecto += $detalle['monto_unitario'];

                $total_afecto_bruto += $gastos_de_envio;
                
                $todosexentos = false;
            }
        
        }
        array_push($detalles, $detalle);
    }
    $data['detalles'] = $detalles;
    
    // Advanced tax mode
    if(get_option('facto_checkbox_totalneto') && wc_tax_enabled()) {
    
        // All VAT exempt
        if ($todosexentos == true) {
            if ($data['tipo_dte'] == 39) {
                $data['tipo_dte'] = 41;
            } elseif ($data['tipo_dte'] == 33) {
                $data['tipo_dte'] = 34;
            }
        } else {
            if ($data['tipo_dte'] == 41) {
                $data['tipo_dte'] = 39;
            } elseif ($data['tipo_dte'] == 34) {
                $data['tipo_dte'] = 33;
            }
        }
    
    }
    
    
    $data['descrecglobales'] = array();

    /*
    // cupones
    
    $order_items = $order->get_items('coupon');
        
    // LOOP THROUGH ORDER COUPON ITEMS
    foreach( $order_items as $item_id => $item ){
        
        // Retrieving the coupon ID reference
        $coupon_post_obj = get_page_by_title( $item->get_name(), OBJECT, 'shop_coupon' );
        $coupon_id = $coupon_post_obj->ID;
        
        // Get an instance of WC_Coupon object (necessary to use WC_Coupon methods)
        $coupon = new WC_Coupon($coupon_id);
        
        
        // Los descuentos de tipo porcentaje los saltamos por cuanto aplican automáticamente a los productos
        if (( $coupon->get_discount_type() == 'percentage' ) || $coupon->is_type( 'cash_back_percentage' ) )
        {
            continue;
        }
            
        // Get the Coupon discount amounts in the order
        $coupon_order_discount_amount = wc_get_order_item_meta( $item_id, 'discount_amount', true );
        $coupon_order_discount_tax_amount = wc_get_order_item_meta( $item_id, 'discount_amount_tax', true );
        
        
        // Exento
        if ($data['tipo_dte'] == 34 || $data['tipo_dte'] == 41)
        {
            $data['descuentosglobales'] = array(
                "descrec" => "DE",
                "valor" => $coupon_order_discount_amount,
                "tipovalor" => "$",
                "glosa" => "CUPON ".$item->get_name(),
            );
            
            $total_exento -= $coupon_order_discount_amount;
        }
        // Afecto
        else
        {
            $data['descuentosglobales'] = array(
                "descrec" => "DA",
                "valor" => $coupon_order_discount_amount,
                "tipovalor" => "$",
                "glosa" => "CUPON ".$item->get_name(),
            );
            
            $total_afecto -= $coupon_order_discount_amount;
        }
        
        
        ## Or get the coupon amount object
        //$coupons_amount = $coupons->get_amount();

    }
    */
   
    
    
    //totales
    $total_iva = round($total_afecto*0.19);

    $total =  round($total_afecto) + round($total_iva) + round($total_exento);
    $total_bruto = round($total_afecto_bruto) + round($total_exento);


    if (get_option('facto_select_rounding_type') == "1") {
        $total_iva += $total_bruto-$total;
    }

    $data['total_afecto'] = round($total_afecto);
    $data['total_iva'] = round($total_iva);
    $data['total_exento'] = round($total_exento);
    $data['total_final'] = $data['total_iva'] + $data['total_afecto'] + $data['total_exento'];
    
    
    //     if (get_option('facto_checkbox_totalneto')){
    
    //         $data['total_iva'] = $order->get_total_tax();
    //         $data['total_afecto'] = $order->get_total();
    //         $data['total_final'] = $order->get_total() + $order->get_total_tax();
    
    //     }
    
    
    return $data;
}

function facto_fe_comunicacion_api($data)
{
    
    global $wpdb;
    /*echo('<pre>');
    var_dump($data);
    echo('</pre>');
    exit();*/

    // En caso de que el pedido no tenga tipo de documento, entonces intentaremos hacer una boleta o boleta exenta
    if ($data["tipo_dte"] == "")
    {
        if (get_option('facto_checkbox_be') == "on")
        {
            $tipo_dte = 39;
        }
        elseif (get_option('facto_checkbox_bee') == "on")
        {
            $tipo_dte = 41;
        }
        else
        {
            $tipo_dte = "";
        }
    }
    else
    {
        $tipo_dte = $data["tipo_dte"];
    }
    
    
    
    //$tipo_dte = $data["tipo_dte"];
    $fecha_emision = $data["fecha_emision"];
    $receptor_rut = str_replace(".", "", $data["receptor_rut"]);
    $receptor_razon = $data["receptor_razon"];
    $receptor_direccion = $data["receptor_direccion"];
    $receptor_comuna = $data["receptor_comuna"];
    $receptor_ciudad = $data["receptor_ciudad"];
    $receptor_telefono = $data["receptor_telefono"];
    $receptor_giro = $data["receptor_giro"];
    $condiciones_pago = '0';
    $receptor_email = $data["receptor_email"];
    $orden_compra_num = $data["orden_compra_num"];
    $orden_compra_fecha = $data["orden_compra_fecha"];
    
    $order_id = $data["order_id"];
    
    $descuentorecargo_global_tipo = '0';
    $descuentorecargo_global_valor = '0';
    $total_exento = $data["total_exento"];
    $total_afecto = $data["total_afecto"];
    $total_iva = $data["total_iva"];
    $total_final = $data["total_final"];
    $impuesto_especifico = 0;
    
    
    $no_repetir_numpedido= 1;
    
    
    
    $detalles = $data['detalles'];
    
    if ($total_final == 0) {
        return array(
            "status" => true,
            "msg" => "Total de la orden es cero.");
    }
    
    require_once(dirname(__FILE__) . "/nusoap/nusoap.php");
    
    
    try
    {
        // Si tenemos un intento de facturación de menos de 30 segundos, abortamos
        $consulta = "SELECT order_id FROM ".$wpdb->prefix."facto_log
	    WHERE order_id = '".$order_id."' AND estado_envio = -1
	     AND fecha >= '".date("Y-m-d H:i:s",time()-30)."'";

        $result = $wpdb->get_results($consulta);

        if (count($result) != 0) {
            print "<span style='color:red'>Ya existe un intento de facturación en procesamiento. Por favor espera un momento...</span>";
            return array(
                "status" => false,
                "msg" => "Ya existe un intento de facturación en procesamiento. Por favor espera un momento.");
        }


        // ** Al recargar aparece y el enlace no funciona
        $consulta = "SELECT enlace, estado FROM ".$wpdb->prefix."facto_order_mp
	    WHERE order_id = '".$order_id."' AND enlace <> ''";
        
        $result = $wpdb->get_results($consulta);
        
        if (count($result) != 0)
        {
            $enlace = get_object_vars($result[0])["enlace"];
            return array(
            "status" => true,
            "msg" => "El documento se ha generado correctamente.",
            "doc_link" => $enlace);


            
        }

        // Comencemos el intento de envío
        $datosinsert = array(
            'fecha' => date("Y-m-d H:i:s"),
            'order_id' => $order_id,
            'request' => "",
            'response' => "",
            'estado_envio' => "-1"
        );

        $wpdb->insert($wpdb->prefix."facto_log", $datosinsert);

        
        $client = new nusoap_client("https://conexion.facto.cl/documento.php?wsdl");
        
        if (get_option('facto_webservice_mode') == 'test')
        {
            $client->setCredentials("1.111.111-4/pruebasapi", "90809d7721fe3cdcf1668ccf33fea982", "basic");
        }
        else
        {
            $client->setCredentials(get_option('facto_webservice_user'), get_option('facto_webservice_pass'), "basic");
        }
        
        
        
        $cadena_xml = "
					<documento xsi:type='urn:emitir_dte'>
                        <opciones>
                            <redondeo_tipo>";

        if (get_option("facto_select_rounding_type") == "1") {
            $cadena_xml .= "bruto";
        } else {
            $cadena_xml .= "neto";
        }

        $cadena_xml .= "</redondeo_tipo>
                        </opciones>
						<encabezado xsi:type='urn:encabezado'>
							<tipo_dte xsi:type='xsd:string'>" . facto_fe_encodingparaxml($tipo_dte) . "</tipo_dte>
							<fecha_emision xsi:type='xsd:date'>" . facto_fe_encodingparaxml($fecha_emision) . "</fecha_emision>
							<receptor_rut xsi:type='xsd:string'>" . facto_fe_encodingparaxml($receptor_rut) . "</receptor_rut>
							<receptor_razon xsi:type='xsd:string'><![CDATA[" . facto_fe_encodingparaxml($receptor_razon) . "]]></receptor_razon>
							<receptor_direccion xsi:type='xsd:string'><![CDATA[" . facto_fe_encodingparaxml($receptor_direccion) . "]]></receptor_direccion>
							<receptor_comuna xsi:type='xsd:string'><![CDATA[" . facto_fe_encodingparaxml($receptor_comuna) . "]]></receptor_comuna>
							<receptor_ciudad xsi:type='xsd:string'><![CDATA[" . facto_fe_encodingparaxml($receptor_ciudad) . "]]></receptor_ciudad>
							    
							<receptor_telefono xsi:type='xsd:string'><![CDATA[" . facto_fe_encodingparaxml($receptor_telefono) . "]]></receptor_telefono>
							<receptor_giro xsi:type='xsd:string'><![CDATA[" . facto_fe_encodingparaxml($receptor_giro) . "]]></receptor_giro>
							<condiciones_pago xsi:type='xsd:string'><![CDATA[" . facto_fe_encodingparaxml($condiciones_pago) . "]]></condiciones_pago>
							<receptor_email xsi:type='xsd:string'><![CDATA[" . facto_fe_encodingparaxml($receptor_email) . "]]></receptor_email>
							<orden_compra_num xsi:type='xsd:string'>" . facto_fe_encodingparaxml($orden_compra_num) . "</orden_compra_num>
							<orden_compra_fecha xsi:type='xsd:date'>" . facto_fe_encodingparaxml($orden_compra_fecha) . "</orden_compra_fecha>
						</encabezado>
							    
						<detalles xsi:type='urn:detalles'>";

        foreach ($data['detalles'] as $key => $detalle) {
            $cadena_xml .= "
							<detalle xsi:type='urn:detalle'>
								<cantidad xsi:type='xsd:int'>" . facto_fe_encodingparaxml($detalle['cantidad']) . "</cantidad>
								<unidad xsi:type='xsd:string'>unid</unidad>
								<glosa xsi:type='xsd:string'><![CDATA[" . facto_fe_encodingparaxml($detalle['glosa']) . "]]></glosa>
                                <sku xsi:type='xsd:string'>" . facto_fe_encodingparaxml($detalle['sku']) . "</sku>
								<monto_unitario xsi:type='xsd:decimal'>" . facto_fe_encodingparaxml($detalle['monto_unitario']) . "</monto_unitario>
								<exento_afecto xsi:type='xsd:boolean'>" . facto_fe_encodingparaxml($detalle['exento_afecto']) . "</exento_afecto>";
            if(isset($detalle['taxes'])){
                $impuesto_especifico += $detalle['taxes'];
                if($detalle['tax_class'] == "ila-cerveza"){
                    $cadena_xml .= "<impuesto_codigo xsi:type='xsd:int'>5</impuesto_codigo>";
                }elseif ($detalle['tax_class'] == "ila-destilados"){
                    $cadena_xml .= "<impuesto_codigo xsi:type='xsd:int'>3</impuesto_codigo>";
                }elseif ($detalle['tax_class'] == "ila-vino"){
                    $cadena_xml .= "<impuesto_codigo xsi:type='xsd:int'>4</impuesto_codigo>";
                }
            }
            $cadena_xml .= "</detalle>";
        }
        
        $cadena_xml .= "</detalles>";

            
        $cadena_xml .= "<referencias xsi:type='urn:referencias'>
                            <referencia xsi:type='urn:referencia'>
                                <docreferencia_tipo>802</docreferencia_tipo>
                                <docreferencia_folio>" . facto_fe_encodingparaxml($orden_compra_num) . "</docreferencia_folio>
                                <docreferencia_fecha>" . facto_fe_encodingparaxml($orden_compra_fecha) . "</docreferencia_fecha>
                                <codigo_referencia>5</codigo_referencia>
                                <descripcion>Pedido Online</descripcion>
                            </referencia>
                        </referencias>";
        
        if ((isset($data['descrecglobales'])) && (count($data['descrecglobales'])))
        {
            $cadena_xml .= "<descuentorecargoglobales>";

            foreach ($data['descrecglobales'] as $descrecrow)
            {
                $cadena_xml .= "<descuentorecargoglobal>
<descrec>".$descrecrow['descrec']."</descrec>
<valor>".$descrecrow['valor']."</valor>
<tipovalor>".$descrecrow['tipovalor']."</tipovalor>
<glosa>".$descrecrow['glosa']."</glosa>
</descuentorecargoglobal>";
            }

            $cadena_xml .= "</descuentorecargoglobales>";
            
        }
        
        
                                    
        $cadena_xml .= "<totales xsi:type='urn:totales'>
							<total_exento xsi:type='xsd:int'>" . facto_fe_encodingparaxml($total_exento) . "</total_exento>
							<total_afecto xsi:type='xsd:int'>" . facto_fe_encodingparaxml($total_afecto) . "</total_afecto>
							<total_iva xsi:type='xsd:int'>" . facto_fe_encodingparaxml($total_iva) . "</total_iva>";
        if($impuesto_especifico != 0){
            $cadena_xml .= "<total_otrosimpuestos xsi:type='xsd:int'>".$impuesto_especifico."</total_otrosimpuestos>";
            $total_final += $impuesto_especifico;
        }
        $cadena_xml .=	"<total_final xsi:type='xsd:int'>" . facto_fe_encodingparaxml($total_final) . "</total_final>
						</totales>";
        
        $cadena_xml .= "</documento>";

        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = false;
        //$response = $client->call("emitirDocumento", $cadena_xml);
        //$err = $client->getError();
        
        // buscar numero de orden
        $sql = "SELECT order_id FROM ".$wpdb->prefix."facto_order_mp WHERE order_id = '".$order_id."'
AND enlace <> '' AND enlace IS NOT NULL";
        
        // obtener numero de orden
        $result = $wpdb->get_results($sql);
        
        if(count($result) != 0)
        {
            
            $order_v = get_object_vars($result[0])["order_id"];
            
        }
        else
        {
            $order_v = "";
        }

        // Parámetros de salida
        $resultado = false;
        $mensajesalida = "";
        $doc_link = "";
        $doc_description = "";
        
        // flag
        $fl = false;
        
        // si existe el numero de orden
        if ((isset($order_v)) && ($order_v != ""))
        {
            
            /*
            //buscamos el metodo de pago
            $order = wc_get_order($order_id);
            $fact = facto_fe_getpaymentmethod($order);
            
            // si se utiliza facturacion en modo manual
            if ($fact == "manual")
            {
                $response = $client->call("emitirDocumento", $cadena_xml);
                $err = $client->getError();
            }
            else
            {*/
                
                $fl = true;
                $err = "Error: Esta orden ya tiene un documento asociado, por lo que no se ha generado el documento.";
            /*
            }
            */
        }
        else
        {
            
            
            $response = $client->call("emitirDocumento", $cadena_xml);
            $err = $client->getError();
            
            if (strpos($err, "403 Forbidden") !== false)
            {
                $err = "Error de autentificación en llave API. Por favor comprueba que tu llave esté correctamente ingresada y tenga los permisos necesarios para este tipo de documento y vuelve a intentarlo";
            }
        }
        

        
        
        if ($err != "")
        {
            if($fl)
            {
                $resultado = false;
                $mensajesalida = $err;
            }
            else
            {
                $resultado = false;
                $mensajesalida = "Ha ocurrido un error al generar el documento. " . $err;
                
            }
            
            $msg = $err;
            $enlace = "";
            $estado = 1;
            
            
            $data = array(
                'fecha' => date("Y-m-d H:i:s"),
                'order_id' => $order_id,
                'request' => $client->request,
                'response' => print_r($err.$response, true),
                'estado_envio' => "1"
            );
            
            $wpdb->insert($wpdb->prefix."facto_log", $data);

            $wpdb->delete($wpdb->prefix."facto_log", array(
                "order_id" => $order_id,
                "estado_envio" => -1
            ));
            
        }
        else if ($response["resultado"]["status"] == 0 || $response["resultado"]["status"] == 2)
        {
            $estado = $response["resultado"]["status"];
            $msg = $response["resultado"]["mensaje_error"];
            
            if ($response["enlaces"]["dte_pdf"])
            {
                
                $enlace = $response["enlaces"]["dte_pdf"];

                $resultado = true;
                
                if (((get_option('facto_checkbox_be') == "on") && $tipo_dte == 39) || ((get_option('facto_checkbox_bee') == "on") && $tipo_dte == 41))
                {

                    $mensajesalida = "El documento se ha generado correctamente.";

                    $doc_link = $response["enlaces"]["dte_pdf"];

                    if ($tipo_dte == 39) {

                        $doc_description = "Boleta electrónica ";

                    } elseif ($tipo_dte == 41) {
                        $doc_description = "Boleta electrónica exenta ";
                    }

                    if (isset($response["encabezado"]["folio"])) {
                        $folio = $response["encabezado"]["folio"];

                        update_post_meta($order_id, 'Folio de documento', sanitize_text_field($folio));

                        $doc_description .= $folio;
                    }

                    
                }
                else
                {
                    
                    $mensajesalida = "El documento se ha generado correctamente.";

                    $doc_link = $response["enlaces"]["dte_pdf"];

                    if ($tipo_dte == 33) {

                        $doc_description = "Factura electrónica ";

                    } elseif ($tipo_dte == 34) {
                        $doc_description = "Factura electrónica exenta ";
                    }

                    if (isset($response["encabezado"]["folio"])) {
                        $folio = $response["encabezado"]["folio"];

                        update_post_meta($order_id, 'Folio de documento', sanitize_text_field($folio));

                        $doc_description .= $folio;
                    }


                }

                update_post_meta($order_id,'Fecha de documento',sanitize_text_field($fecha_emision));
                
                /*
                 $headers = 'From: Tienda online <noresponder@facto.cl> \r\n';
                 $message = "Estimado cliente:\r\n\r\n
                 Se ha realizado una orden de compra en nuestro sitio web y se ha emitido el documento correspondiente utilizando FACTO.\r\n\r\n
                 El documento lo puede ver siguiendo el enlace:\r\n\r\n
                 
                 ".$response['enlaces']['dte_pdf']."\r\n\r\n
                 
                 Saldos cordiales.";
                 wp_mail( $receptor_email, 'FACTURA', $message, $headers );
                 */
                //$estado = 3;
                
                
                
                $data = array(
                    'fecha' => date("Y-m-d H:i:s"),
                    'order_id' => $order_id,
                    'request' => $client->request,
                    'response' => print_r($response, true),
                    'estado_envio' => $response["resultado"]["status"]
                );
                
                $wpdb->insert($wpdb->prefix."facto_log", $data);

                $wpdb->delete($wpdb->prefix."facto_log", array(
                    "order_id" => $order_id,
                    "estado_envio" => -1
                ));
                
                
            }
            else
            {
                $mensajesalida = "Documento generado, pero no se cuenta con un pdf.";
                $resultado = false;
                
                $enlace = "";
                
                
                $data = array(
                    'fecha' => date("Y-m-d H:i:s"),
                    'order_id' => $order_id,
                    'request' => $client->request,
                    'response' => print_r($response, true),
                    'estado_envio' => $response["resultado"]["status"]
                );
                
                $wpdb->insert($wpdb->prefix."facto_log", $data);

                $wpdb->delete($wpdb->prefix."facto_log", array(
                    "order_id" => $order_id,
                    "estado_envio" => -1
                ));
            }
            
        }
        else if ($response["resultado"]["status"] == 1)
        {
            $mensajesalida = $response["resultado"]["mensaje_error"];
            $msg = $response["resultado"]["mensaje_error"];
            $estado = 1;
            $enlace = null;
            
            $data = array(
                'fecha' => date("Y-m-d H:i:s"),
                'order_id' => $order_id,
                'request' => $client->request,
                'response' => print_r($response, true),
                'estado_envio' => $response["resultado"]["status"]
            );
            
            $wpdb->insert($wpdb->prefix."facto_log", $data);

            $wpdb->delete($wpdb->prefix."facto_log", array(
                "order_id" => $order_id,
                "estado_envio" => -1
            ));
            
        }
        
        $data = array(
            'order_id' => $order_id,
            'fact' => 'auto',
            'estado' => $estado,
            'enlace' => $enlace,
            'msg' => $msg
        );
        
        $wpdb->replace($wpdb->prefix."facto_order_mp", $data);
        
        return array(
            "status" => $resultado,
            "msg" => $mensajesalida,
            "doc_link" => $doc_link,
            "doc_description" => $doc_description
        );
    }
    catch (Exception $e)
    {
        //var_dump($e);
        
        echo "- Ha ocurrido un error al generar el documento.";
        
        $data = array(
            'order_id' => $order_id,
            'fact' => 'auto',
            'estado' => 1,
            'error' => print_r($e, true),
            'msg' => "Exception"
        );
        $wpdb->replace($wpdb->prefix."facto_order_mp", $data);
        return array(
            "status" => false
        );
    }
    
}