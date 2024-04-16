<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly


/************************* CHECKOUT **********************************
 ***********************************************************************/

//modificamos los campos
add_filter('woocommerce_checkout_fields', 'facto_fe_checkout_fields');
function facto_fe_checkout_fields($fields)
{
    $fields['billing']['billing_state']['placeholder'] = 'Seleccione una Provincia';
    $fields['billing']['billing_state']['label'] = 'Provincia';
    
    $fields['shipping']['shipping_state']['placeholder'] = 'Seleccione una Provincia';
    $fields['shipping']['shipping_state']['label'] = 'Provincia';
    
    //unset($fields['billing']['billing_postcode']);
    //unset($fields['shipping']['shipping_postcode']);
    
    unset($fields['billing']['billing_company']);
    unset($fields['shipping']['shipping_company']);
    
    //unset($fields['billing']['billing_city']);
    //unset($fields['shipping']['shipping_city']);
    
    return $fields;
}


//agregamos los campos
add_action('woocommerce_after_order_notes', 'facto_fe_custom_checkout_field');
function facto_fe_custom_checkout_field($checkout)
{
    
    $opciones = array();
    
    // Default primero
    if ((get_option('facto_checkbox_be') == "on") && (get_option('facto_docs_default') == "be"))
    {
        $opciones['be'] = 'Boleta electronica';
    }
    elseif ((get_option('facto_checkbox_bee') == "on") && (get_option('facto_docs_default') == "bee"))
    {
        $opciones['bee'] = 'Boleta exenta electronica';
    }
    elseif ((get_option('facto_checkbox_fe') == "on") && (get_option('facto_docs_default') == "fe"))
    {
        $opciones['fe'] = 'Factura electr&oacute;nica';
    }
    elseif ((get_option('facto_checkbox_fee') == "on") && (get_option('facto_docs_default') == "fee"))
    {
        $opciones['fee'] = 'Factura exenta electronica';
    }
    
    
    // Luego mostramos el resto
    if ((get_option('facto_checkbox_be') == "on") && (get_option('facto_docs_default') != "be"))
    {
        $opciones['be'] = 'Boleta electronica';
    }
    
    if ((get_option('facto_checkbox_bee') == "on") && (get_option('facto_docs_default') != "bee"))
    {
        $opciones['bee'] = 'Boleta exenta electronica';
    }
    
    if ((get_option('facto_checkbox_fe') == "on") && (get_option('facto_docs_default') != "fe"))
    {
        $opciones['fe'] = 'Factura electronica';
    }
    
    if ((get_option('facto_checkbox_fee') == "on") && (get_option('facto_docs_default') != "fee"))
    {
        $opciones['fee'] = 'Factura exenta electronica';
    }
    
    
    $array_kind_of_legal_invoice = array(
        'type' => 'select',
        'options' => $opciones,
        'class' => array('form-row-wide'),
        'label' => __('Tipo de documento'),
        'placeholder' => __('Seleccione el tipo de documento')
    );
    
    $array_campo_rut = array(
        'type' => 'text',
        'class' => array('form-row-wide'),
        'label' => __('Documento'),
        'required' => true,
        'placeholder' => __('Ingrese el numero de Documento'),
        'id' => 'oml_campo_rut'
    );
    
    $array_campo_razon_social = array(
        'type' => 'text',
        'class' => array('form-row-wide'),
        'label' => __('Raz&oacute;n social'),
        'required' => true,
        'placeholder' => __('Indique la raz&oacute;n social.'),
        'id' => 'campo_razon_social'
        
    );
    
    $array_campo_giro = array(
        'type' => 'text',
        'class' => array('form-row-wide'),
        'label' => __('Giro'),
        'required' => true,
        'placeholder' => __('Indique el giro.'),
        'id' => 'campo_giro'
    );
    
    
    
    $array_campo_receptor_direccion = array(
     'type' => 'text',
     'class' => array('form-row-wide'),
     'label' => __('Direcci&oacute;n del receptor'),
     'required' => true,
     'placeholder' => __('Indique la direcci&oacute;n del receptor.')
     );
    /*
     $array_campo_comuna = array(
     'type' => 'text',
     'class' => array('form-row-wide'),
     'label' => __('Comuna'),
     'placeholder' => __('Indique la comuna del receptor.'),
     'required' => true,
     );
     
     
     $array_campo_telefono = array(
     'type' => 'text',
     'class' => array('form-row-wide'),
     'label' => __('Tel&eacute;fono'),
     'required' => true,
     'placeholder' => __('Indique el n&uacute;mero de tel&eacute;fono del receptor.')
     );*/
    
    
    /*$arreglo_comunas = array();
     $nombre_comunas = comunas_de_chile(array());
     foreach($nombre_comunas["CL"] as $key => $valor){
     $arreglo_comunas[$valor] = $valor;
     }
     $array_campo_comuna = array(
     'type'          => 'select',
     'class'         => array('form-row-wide'),
     'label'         => __('Comuna'),
     'placeholder'   => __('Indique la comuna.'),
     'options'     => $arreglo_comunas
     );*/
    
    //sanitize_text_field
    
    
    ?>



    <div id="div_datos_de_facto"><br>
        <h3>Seleccione tipo de documento</h3>

        <?php

        //woocommerce_form_field('campo_receptor_direccion', $array_campo_receptor_direccion, $checkout->get_value('campo_receptor_direccion'));
        //woocommerce_form_field( 'campo_receptor_comuna', $array_campo_comuna, $checkout->get_value( 'comuna' ));
        //woocommerce_form_field('campo_receptor_comuna', $array_campo_comuna, $checkout->get_value('campo_receptor_comuna'));
        //woocommerce_form_field('campo_receptor_telefono', $array_campo_telefono, $checkout->get_value('campo_receptor_telefono'));
        woocommerce_form_field('kind_of_legal_invoice', $array_kind_of_legal_invoice, $checkout->get_value('kind_of_legal_invoice'));
        ?>
        <div id='factofacturacionelectronica_div_factura'> <?php

            woocommerce_form_field('oml_campo_rut', $array_campo_rut, $checkout->get_value('oml_campo_rut'));
            woocommerce_form_field('campo_razon_social', $array_campo_razon_social, $checkout->get_value('campo_razon_social'));
            woocommerce_form_field('campo_giro', $array_campo_giro, $checkout->get_value('campo_giro'));
            ?>
        </div>
    </div>
    <?php

    //RUT
    //wp_register_script('jquery.script', plugins_url('jquery.script.js', __FILE__));
    //wp_enqueue_script('jquery.script');
    
    //wp_register_script('jquery.Rut', plugins_url('js/factofacturacionelectronica.js', __FILE__));
    //wp_enqueue_script('jquery.Rut');
    
    //script
    wp_register_script('facto_script', plugins_url('js/factofacturacionelectronica.js', __FILE__));
    wp_enqueue_script('facto_script');
}

//validamos los campos
add_action('woocommerce_checkout_process', 'facto_fe_custom_checkout_field_process');
function facto_fe_custom_checkout_field_process()
{
    if (sanitize_text_field($_POST['kind_of_legal_invoice'])) {
        if (sanitize_text_field($_POST['kind_of_legal_invoice']) == "fe" || sanitize_text_field($_POST['kind_of_legal_invoice'] == "fee")) {
            if (sanitize_text_field(!$_POST['oml_campo_rut'])) wc_add_notice(__('Debe indicar un RUT.'), 'error');
            if (sanitize_text_field(!$_POST['campo_razon_social'])) wc_add_notice(__('Debe indicar una raz&oacute;n social.'), 'error');
            if (sanitize_text_field(!$_POST['campo_giro'])) wc_add_notice(__('Debe indicar un giro.'), 'error');
        }
    } else wc_add_notice(__('Debe indicar un tipo de documento.'), 'error');

    //if (sanitize_text_field(!$_POST['campo_receptor_direccion'])) wc_add_notice(__('Debe indicar la direcci&oacute;n del receptor.'), 'error');
    //if (sanitize_text_field(!$_POST['campo_receptor_comuna'])) wc_add_notice(__('Debe indicar una comuna.'), 'error');
    //if (sanitize_text_field(!$_POST['campo_receptor_telefono'])) wc_add_notice(__('Debe indicar un n&uacute;mero de tel&eacute;fono del receptor.'), 'error');

}

//recuperamos los valores de los campos
add_action('woocommerce_checkout_update_order_meta', 'facto_fe_custom_checkout_field_update_order_meta');
function facto_fe_custom_checkout_field_update_order_meta($order_id)
{
    require_once dirname(__FILE__)."/forceutf8/encoding.php";
    if (!empty($_POST['kind_of_legal_invoice'])) {
        update_post_meta($order_id, 'Tipo de documento', sanitize_text_field($_POST['kind_of_legal_invoice']));
    }

    if (!empty($_POST['oml_campo_rut'])) {
        update_post_meta($order_id, 'RUT', sanitize_text_field($_POST['oml_campo_rut']));
    }

    if (!empty($_POST['campo_razon_social'])) {
        update_post_meta($order_id, 'Razon social', sanitize_text_field($_POST['campo_razon_social']));
    }

    if (!empty($_POST['campo_giro'])) {
        update_post_meta($order_id, 'Giro', sanitize_text_field($_POST['campo_giro']));
    }


    /*if (!empty($_POST['campo_receptor_direccion'])) {
        update_post_meta($order_id, 'campo_receptor_direccion', sanitize_text_field($_POST['campo_receptor_direccion']));
    }

    if (!empty($_POST['campo_receptor_comuna'])) {
        update_post_meta($order_id, 'campo_receptor_comuna', sanitize_text_field($_POST['campo_receptor_comuna']));
    }

    if (!empty($_POST['campo_receptor_telefono'])) {
        update_post_meta($order_id, 'campo_receptor_telefono', sanitize_text_field($_POST['campo_receptor_telefono']));
    }*/
}



//genera la pg con la orden aca
add_action('woocommerce_thankyou', 'facto_fe_display_order_data', 20);
add_action('woocommerce_order_status_processing', 'facto_fe_estado_procesando', 20);
add_action('woocommerce_order_status_completed', 'facto_fe_estado_completado', 20);


function facto_fe_estado_procesando($order_id)
{
    if (get_option('facto_checkbox_estadoprocesando') == 'on') {
        return facto_fe_issue_document($order_id);
    }
}

function facto_fe_estado_completado($order_id)
{
    if (get_option('facto_checkbox_estadocompletado') == 'on') {
        return facto_fe_issue_document($order_id);
    }
}

function facto_fe_issue_document($order_id) {
    $data = facto_fe_data_orden($order_id);

    if ($data == false) {
        echo "Error al recuperar los datos de la orden para emitir la factura/boleta.";
        return false;
    }

    $order = new WC_Order($order_id);

    if ($order->get_status() != "failed")
    {
        $respuesta = facto_fe_comunicacion_api($data);

        if (isset($respuesta['msg'])) {
            print esc_html($respuesta['msg']);

            if (isset($respuesta['doc_link'])) {
                print "<input type='button' class ='button alt' style='margin-top: 10px;' onclick=\"window.open('" . esc_url($respuesta['doc_link']) . "','_blank');\"";

                if (isset($respuesta['doc_description'])) {
                    print " value=\"".esc_attr($respuesta['doc_description'])."\"";
                } else {
                    print "Descarga tu boleta o factura aqu&iacute;";
                }

                print "\">";

            }

        }

    }

}

function facto_fe_display_order_data($order_id, $mostrarlinkfactura = true)
{

    $data = facto_fe_data_orden($order_id);

    if ($data == false) {
        echo "Error al recuperar los datos de la orden para emitir la factura/boleta.";
         return false;
    }
    
    $order = new WC_Order($order_id);
    $fact = facto_fe_getpaymentmethod($order);


    //creamos una tabla para guardar el resultado
    global $wpdb;
    /*
        $oml_estado == 0 => factura no generada
        $oml_estado == 1 => factura generada correctamente
        $oml_estado == 2 => factura generada con error
    */


    if ($fact == "auto")
    {

        // Si estamos en modo autom&aacute;tico, veamos que el estado del pedido sea pagado

        if ($order->get_status() != "failed")
        {

            $respuesta = facto_fe_comunicacion_api($data);


            if ($mostrarlinkfactura == true)
            {

                if (isset($respuesta['msg'])) {
                    print esc_html($respuesta['msg']);

                    if (isset($respuesta['doc_link'])) {
                        print "<input type='button' class ='button alt' style='margin: 10px; border-radius: 30px;' onclick=\"window.open('" . esc_url($respuesta['doc_link']) . "','_blank');\"";

                        if (isset($respuesta['doc_description'])) {
                            print " value=\"".esc_attr($respuesta['doc_description'])."\"";
                        } else {
                            print "Descarga tu boleta o factura aqu&iacute;";
                        }

                        print "\">";

                    }

                }

            }

        }
    }
    else
    {

        if ($mostrarlinkfactura == true)
        {

            echo "El documento tributario (boleta o factura) ser&aacute; emitido por un administrador";

        }
        

        //agregamos que la orden es manual

        $data = array(
            'order_id' => $order_id,
            'fact' => 'manual',
            'estado' => 1
        );


        $wpdb->replace($wpdb->prefix."facto_order_mp", $data);
    }
}


