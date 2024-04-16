<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

function facto_fe_errorordertable()
{
?>
    <div class="error notice">
        <p><?php _e( 'FACTO: Error al crear tabla de pedidos. Por favor comprueba que el usuario de base de datos que estás utilizando pueda crear tablas y vuelve a instalar el módulo', 'facto-facturacioacuten-electroacutenica' ); ?></p>
    </div>
    <?php
}
        
function facto_fe_errorlogtable()
{
    ?>
    <div class="error notice">
        <p><?php _e( 'FACTO: Error al crear tabla de log. Por favor comprueba que el usuario de base de datos que estás utilizando pueda crear tablas y vuelve a instalar el módulo', 'facto-facturacioacuten-electroacutenica' ); ?></p>
    </div>
    <?php
}

function facto_fe_errornowoocommerce()
{
    ?>
    <div class="error notice">
        <p><?php _e( 'FACTO: El módulo necesita tener instalado WOOCOMMERCE para operar correctamente.', 'facto-facturacioacuten-electroacutenica' ); ?></p>
    </div>
    <?php
}

function facto_fe_errornosetup()
{
    ?>
    <div class="error notice">
        <p><?php _e( 'FACTO: No has configurado aun ningún tipo de documento (factura, factura exenta, boleta, boleta exenta) para realizar compras. Configúralo <a href="'.admin_url( 'admin.php?page=facto_settings' ).'">Aqui</a>', 'facto-facturacioacuten-electroacutenica' ); ?></p>
    </div>
    <?php
}

function facto_fe_errornombstring()
{
    ?>
    <div class="error notice">
        <p><?php _e( 'FACTO: No tienes habilitada la extensión de PHP mbstring. Esto puede causar problemas en acentos al emitir documentos.');?></p>
    </div>
    <?php
}

//crear tablas
function facto_fe_setup()
{
    
    global $wpdb;
    
    
    $charset_collate = $wpdb->get_charset_collate();
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    
    $query_create_order = "
					CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."facto_order_mp (
					  `order_id` INT,
						`msg` VARCHAR(255),
					  `fact` VARCHAR(255),
					  `estado` INT,
					  `enlace` VARCHAR(255),
					  `error` TEXT,
					PRIMARY KEY (`order_id`)
					)";
    //$resultado = $wpdb->query($query_create_order);
    dbDelta($query_create_order);    
    
    //if ($resultado == false)
    //{
    //            
    //    return false;
    //}
    
    // Si teníamos las antiguas tablas, migramos la información y borramos las tablas
    
    
    // Revisemos si es que existe la antigua tabla oml_facto_order_mp y movemos los elementos
    
    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", "oml_facto_order_mp") ) === "oml_facto_order_mp")
    {
        
        $results = $wpdb->get_results( "SELECT * FROM oml_facto_order_mp");
        
        foreach ($results as $result)
        {
            $data = array(
                'order_id' => $result->order_id,
                'fact' => $result->fact,
                'estado' => $result->estado,
                'enlace' => $result->enlace,
                'msg' => $result->msg,
                'error' => $result->error
            );
            
            $wpdb->insert($wpdb->prefix."facto_order_mp", $data);
        }
        
        $sql = "DROP TABLE oml_facto_order_mp";
        $wpdb->query($sql);
        
    }
    
    
    
    
    $query_create_log = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."facto_log (
						  `id_envio` int(11) NOT NULL AUTO_INCREMENT,
                          `order_id` int(11),
						  `fecha` datetime,
						  `request` text,
						  `response` text,
						  `estado_envio` int(11),
						  PRIMARY KEY (`id_envio`)
 						)";
    //$resultado = $wpdb->query($query_create_log);
    dbDelta($query_create_log);
    
    //if ($resultado == false)
    //{
    //    
    //    return false;
    //}
    
    
    // Revisemos si existe la antigua tabla oml_facto_log y movemos los elementos 
    
    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", "oml_facto_log") ) === "oml_facto_log")
    {
        // Si no está el campo order_id en oml_facto_log, lo agregamos
        $result = $wpdb->query("SHOW COLUMNS FROM `oml_facto_log` LIKE 'order_id'");
        
        if ($result == 0)
        {
            $query_create_order = "
    					ALTER TABLE `oml_facto_log`
                        ADD COLUMN order_id INT";
            $resultado = $wpdb->query($query_create_order);
            
            $query_create_order = "ALTER TABLE `oml_facto_log`
                        CHANGE COLUMN fecha fecha DATETIME";
            $resultado = $wpdb->query($query_create_order);
        }
        
        
        $results = $wpdb->get_results( "SELECT * FROM oml_facto_log");
        
        foreach ($results as $result)
        {
            $data = array(
                'id_envio' => $result->id_envio,
                'fecha' => $result->fecha,
                'order_id' => $result->order_id,
                'request' => $result->request,
                'response' => $result->response,
                'estado_envio' => $result->estado_envio
            );
            
            $wpdb->insert($wpdb->prefix."facto_log", $data);
        }
        
        $sql = "DROP TABLE oml_facto_log";
        $wpdb->query($sql);
        
    }
    

    // Veamos si existe el campo del folio de documento
    $result = $wpdb->query("SHOW COLUMNS FROM `oml_facto_log` LIKE 'order_id'");

    if ($result == 0)
    {
        $query_create_order = "
    					ALTER TABLE `oml_facto_log`
                        ADD COLUMN order_id INT";
        $resultado = $wpdb->query($query_create_order);

        $query_create_order = "ALTER TABLE `oml_facto_log`
                        CHANGE COLUMN fecha fecha DATETIME";
        $resultado = $wpdb->query($query_create_order);
    }
    
    
    return true;
    
}



