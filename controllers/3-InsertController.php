<?php
# CONNEXIÓ A LA BASE DE DADES AMB PDO
require 'models/connectionWordPress.php';

if (isset($_POST['data'])) {

    $variable = $_POST['data'];

    $wp = new ConexionWordPress();

    echo "<script> console.log('Insert Controller: Connection successful!'); </script>";

    if ((isset($variable['datos']['nombre-carta']))) {

        // Obtenim url del site
        $site_url = $wp->Geturl();

        // Obtenim l'ultim ID de la taula
        $last_id = $wp->GetLastID();

        $nameCarta = $variable['datos']['nombre-carta'];
        // echo $nameCarta;
        $postProduct = ''.$variable['datos']['nombre-carta'].'-menu-'.($last_id['MAX(ID)'] + 1).'';
        // echo $postProduct;

        # Degut a que el Servidor treballa en en GMT +0 i nosaltres estem en GTM +2, la hora a insertar ha de ser la nostra hora -2 hores
        $date = new DateTime();
        $date ->modify('-2 hours');
        $server_date = $date->format('Y-m-d H:i:s');
        echo $server_date;

        # Insertam una nova carta
        $statusCarta = $wp->InsertCarta($server_date, $site_url, $nameCarta, $postProduct);

        # Busquem es ID de sa carta insertada per associar-lo a ses ROW
        $idCarta = $wp->CartaID($nameCarta, $postProduct);

        # Feim un Update de es guid de sa carta


        # Dins sa taula wp_postmeta inserim ses dades de sa nova carta
        $statusCartaMeta = $wp->InsertCartaMeta($idCarta);

        // echo count($variable['grupos']);

        for ($i=0; $i < count($variable['grupos']); $i++) { 

            if ((isset($variable['grupos']['grupo'.$i.'']['nombre'])) ){

                # Assignem es nom de cada grup
                $nameGrupo = $variable['grupos']['grupo'.$i.'']['nombre'];
                // echo $nameGrupo;

                # Assignem la descripció dels grups si existeix
                if (isset($variable['grupos']['grupo'.$i.'']['desc_prec1'])){
                    $precio1_desc = $variable['grupos']['grupo'.$i.'']['desc_prec1'];

                }
                
                if (isset($variable['grupos']['grupo'.$i.'']['desc_prec2'])){
                    $precio2_desc = $variable['grupos']['grupo'.$i.'']['desc_prec2'];
                }

                $grupos[] = $nameGrupo;

                $urlRow = ''.$site_url['option_value'].'/index.php/erm_menu_item/new-row/'.$i.'';
                $urlColumn = ''.$site_url['option_value'].'/index.php/erm_menu_item/new-column/'.$i.'';
                $urlSection = ''.$site_url['option_value'].'/index.php/erm_menu_item/new-section/'.$i.'';

                $newGrupo = $wp->InsertRow($server_date, $nameGrupo, $idCarta, $urlRow);

                /** Obtenim es ID de cada grup/ROW, d'aquesta manera podem associar cada Item que te al seu interior a aquest mateix.
                 * Això ens ajudarà a completar el meta_value corresponent a "_erm_menu_items_pro"
                 */

                $grupoID = $wp->RowID($nameGrupo);
                
                $postColumn = 'new-column-'.$grupoID['ID'].'';

                $postSection = 'new-section-'.$grupoID['ID'].'';

                $postProduct = 'new-product-'.$grupoID['ID'].'';



                $newColumn = $wp->InsertColumn($server_date, $idCarta, $urlColumn, $postColumn);
                $newSection = $wp->InsertSection($server_date, $idCarta, $urlSection, $postSection); # Segurament haurà de tenir un $nameSection


                # Ara toca posar tots es apartats de ses Row escepte es productes dins de sa taula wp_postmeta.
                $rowMetaID = $grupoID['ID'];
                $statusRowMeta = $wp->InsertRowMeta($rowMetaID);

                # Mitjançant les URL, el que farem serà obtenir el ID del Column i Section per posar-lo dins wp_postmeta
                $column = $wp->ColumnID($urlColumn);
                $columnID = $column['ID'];
                $statusColumnMeta = $wp->InsertColumnMeta($columnID);

                $section = $wp->SectionID($urlSection);
                $sectionID = $section['ID'];
                $statusSectionMeta = $wp->InsertSectionMeta($sectionID);


                if ((isset($variable['grupos']['grupo'.$i.'']))) {

                    for ($j=0; $j < count($variable['grupos']['grupo'.$i.'']); $j++){

                        if (isset($variable['grupos']['grupo'.$i.'']['plato'.$j.'']['plato'])){

                            // echo "<hr>";
                        
                            // echo('<pre>');
                            // print_r($variable['grupos']['grupo'.$i.'']['plato'.$j.'']['plato']);
                            // echo('</pre>');
                            
                            $namePlato = $variable['grupos']['grupo'.$i.'']['plato'.$j.'']['plato'];
        
                            // echo('<pre>');
                            // print_r($variable['grupos']['grupo'.$i.'']['plato'.$j.'']['precio1']);
                            // echo('</pre>');
        
                            $precio1 = $variable['grupos']['grupo'.$i.'']['plato'.$j.'']['precio1'];
        
                            // echo('<pre>');
                            // print_r($variable['grupos']['grupo'.$i.'']['plato'.$j.'']['precio2']);
                            // echo('</pre>');

                            $metaPrice = 'a:1:{i:0;a:3:{s:4:"name";s:'.strlen($precio1_desc).':"'.$precio1_desc.'";s:5:"price";s:'.strlen($precio1).':"'.$precio1.'";s:10:"price_sale";s:0:"";}}';
        

                            $precio2 = $variable['grupos']['grupo'.$i.'']['plato'.$j.'']['precio2'];
        
                            // echo('<pre>');
                            // print_r($variable['grupos']['grupo'.$i.'']['plato'.$j.'']['descripcion']);
                            // echo('</pre>');
        
                            $descripcion = $variable['grupos']['grupo'.$i.'']['plato'.$j.'']['descripcion'];
        
                            if ((isset($precio2)) && ($precio2 != null) && ($precio2 != '0.00')){

                                $metaPrice = 'a:2:{i:0;a:3:{s:4:"name";s:'.strlen($precio1_desc).':"'.$precio1_desc.'";s:5:"price";s:'.strlen($precio1).':"'.$precio1.'";s:10:"price_sale";s:0:"";}i:1;a:3:{s:4:"name";s:'.strlen($precio2_desc).':"'.$precio2_desc.'";s:5:"price";s:'.strlen($precio2).':"'.$precio2.'";s:10:"price_sale";s:0:"";}}';

                            } else {

                                $metaPrice = 'a:1:{i:0;a:3:{s:4:"name";s:'.strlen($precio1_desc).':"'.$precio1_desc.'";s:5:"price";s:'.strlen($precio1).':"'.$precio1.'";s:10:"price_sale";s:0:"";}}';

                            }

                            // echo "<hr>";
        
                            $urlProduct = ''.$site_url['option_value'].'/index.php/erm_menu_item/new-product/'.$namePlato.'';
        
                            $statusItems = $wp->InsertProducts($server_date, $namePlato, $descripcion, $idCarta, $urlProduct, $postProduct);

                            $product = $wp->ProductID($idCarta, $urlProduct);
                            $productID = $product['ID'];

                            $productsID [$i][$j] = $productID;
                            
                            $statusProductMeta = $wp->InsertProductMeta($productID, $metaPrice);


                        }

                    }

                }


                // Una vegada tenim ses dades de es array, feim un implode per separar-ho tot amb ","

                if (isset($productsID[$i])){
                    
                    $metaProd = implode(',', $productsID[$i]);
               
                    $metaKey [] = '{"id":'.$grupoID['ID'].',"items":[{"id":'.$columnID.',"items":[{"id":'.$sectionID.',"items":['.$metaProd.']}]}]}';
              
                    //[{"id":3,"items":[{"id":4,"items":[{"id":5,"items":[6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27]}]}]},[{"id":28,"items":[{"id":29,"items":[{"id":30,"items":[31,32,33,34,35]}]}]},[{"id":36,"items":[{"id":37,"items":[{"id":38,"items":[39,40,41,42,43,44,45]}]}]},[{"id":46,"items":[{"id":47,"items":[{"id":48,"items":[49,50,51,52,53,54,55,56,57]}]}]},[{"id":58,"items":[{"id":59,"items":[{"id":60,"items":[61]}]}]}


                }
               
            }

           
        }

        // MONTEM EL VALOR META FINAL
        // print_r($productsID);

        $meta = '['.implode(',', $metaKey).']';

        echo('<pre>');
        print_r($meta);
        echo('</pre>');


        $final = $wp->MetaKey($idCarta, $meta);

            # PASSEM LES DADES A LA VISTA
            require 'views/3-Insert.php';

    }

    $close_wordpress = $wp->DisconnectWp();

    echo "<script> console.log('Disconnected from database'); </script>";


}