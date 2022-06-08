<?php
// Amb PDO podem unificar la manera de fer QUERY en les BD per generalitzar-ho amb els diferents motors. Poguent utilitzar ORACLE, MySQL, Postgree, Microsoft SQL
session_start();

echo $_SESSION['HOST']; // Ip WorPress https://adria.on-menu.com/
echo $_SESSION['DB_NAME'];
echo $_SESSION['USER'];
echo $_SESSION['PASSWORD'];

class ConexionWordPress extends PDO
	{
		private $wordpress;
		
		# Remote testing
		// WP REMOT
		// public function __construct()
		// {
		// 	# Conectam a la Base de Dades 2
		// 	$hostWp='51.178.144.4'; // Ip WorPress https://adria.on-menu.com/
		// 	$dbnameWp='adriaon_wp314';
		// 	$userWp='adriaon_wp314';
		// 	$passWp='6CHjnLIQmrNz';

		// 	try{
        //     	$this->wordpress = new PDO("mysql:host=$hostWp;dbname=$dbnameWp;charset=utf8", $userWp, $passWp);
		// 		$this->wordpress->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		// 		} catch(PDOException $e){
		// 		echo 'Error: ' . $e->getMessage();
		// 		exit;
		// 	}
		// }


		# Remote testing form
		// Aqui ens podem connectar per treballar en WP REMOT	
		public function __construct()
		{
			# Conectam a la Base de Dades 2
			$hostWp = $_SESSION['HOST']; // Ip WorPress https://adria.on-menu.com/
			$dbnameWp = $_SESSION['DB_NAME'];
			$userWp = $_SESSION['USER'];
			$passWp = $_SESSION['PASSWORD'];

			try{
            	$this->wordpress = new PDO("mysql:host=$hostWp;dbname=$dbnameWp;charset=utf8", $userWp, $passWp);
				$this->wordpress->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				} catch(PDOException $e){
				echo 'Error: ' . $e->getMessage();
				exit;
			}
		}


		# Localhost testing
		// Aqui ens podem connectar per treballar en WP LOCAL

		// public function __construct()
		// {
		// 	# Conectam a la Base de Dades 2
		// 	$hostWp='localhost'; 
		// 	$dbnameWp='wordpress';
		// 	$userWp='wordpress';
		// 	$passWp='wordpress';

		// 	try{
        //     	$this->wordpress = new PDO("mysql:host=$hostWp;dbname=$dbnameWp;charset=utf8", $userWp, $passWp);
		// 		$this->wordpress->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		// 		} catch(PDOException $e){
		// 		echo 'Error: ' . $e->getMessage();
		// 		exit;
		// 	}
		// }

		/** public function GetUrl()
		 * Dona com output una url per a que es usuari no tengui que escriure directament en un input
		 */
		public function Geturl(){
			try { 
				$sql = "SELECT option_value
						FROM wp_options
						WHERE option_name = 'siteurl'";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->execute();
				return $stmt->fetch();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		/** public function GetLastID()
		 * Obtenim com a resultat l'últim ID de la taula per a poder concatenar-lo dins la variable $post_product
		 */
		public function GetLastID(){
			try { 
				$sql = "SELECT MAX(ID) 
						FROM wp_posts;";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->execute();
				return $stmt->fetch();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}
		
		
		/** public function InsertCarta($urlMenu, $nameGrupo, $postProduct, $postProductRevision, $urlRevision)
		 * Amb les dades rebudes de la pàgina on hi ha la taula per editar els plats, agafem algunes de les dades
		 * del inputs per crear la carta dins la taula wp_posts
		 */
		public function InsertCarta($server_date, $site_url, $nameGrupo, $postProduct){
			try { 
				$sql = "INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_title, post_status, comment_status, ping_status, post_name, post_modified, post_modified_gmt, post_parent, guid, menu_order, post_type, comment_count)
						VALUES (1, :server_date, :server_date, :nameGrupo, 'publish', 'closed', 'closed', :postProduct, :server_date, :server_date, 0, :site_url, 0, 'erm_menu', 0)";
				$stmt = $this->wordpress->prepare($sql);

				$stmt->bindValue(':server_date', $server_date, PDO::PARAM_STR);
				$stmt->bindValue(':site_url', $site_url['option_value'], PDO::PARAM_STR);
				$stmt->bindValue(':nameGrupo', $nameGrupo, PDO::PARAM_STR);
				$stmt->bindValue(':postProduct', $postProduct, PDO::PARAM_STR);
				
				$stmt->execute();
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		/** public function CartaID($nameGrupo, $postProductInsert)
		 * Agafa el ID de la carta insertada per poder relacionar-lo amb els item que tindrà al seu interior
		 */
		public function CartaID($nameGrupo, $postProductInsert){
			try { 
				$sql = "SELECT ID
						FROM wp_posts
						WHERE post_title = :nameGrupo AND post_name = :postProductInsert";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':nameGrupo', $nameGrupo, PDO::PARAM_STR);
				$stmt->bindValue(':postProductInsert', $postProductInsert, PDO::PARAM_STR);
				$stmt->execute();
				return $stmt->fetch();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		
		/** public function InsertCartaMeta($idCarta) 
		 * Funció per insertar dins wp_postmeta sa carta
		*/
		public function InsertCartaMeta($idCarta){
			try {
				$sql = "INSERT INTO wp_postmeta (post_id, meta_key, meta_value)
						VALUES (:idCarta, '_edit_last', 1),
							   (:idCarta, '_erm_footer_menu', ''),
							   (:idCarta, '_erm_menu_thumbnails', 'show'),
							   (:idCarta, '_erm_menu_aligned', 'left'),
							   (:idCarta, '_erm_menu_rows', 'dropdown'),
							   (:idCarta, '_erm_menu_sections', 'expanded'),
							   (:idCarta, '_erm_menu_display_more', 'yes'),
							   (:idCarta, '_edit_lock', 1)"; # Aquest valor 1 és per no deixar un NULL dins la tupla. Hauria de funcionar igual
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':idCarta', $idCarta['ID'], PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		/** public function InsertRow($nameGrupo, $idCarta, $urlGrupo)
		 * Funció per insertar els grups en base al nom i el ID de la carta per relacionar-ho d'alguna forma
		 */
		public function InsertRow($server_date, $nameGrupo, $idCarta, $urlGrupo){
			try {
				$sql = "INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_status, comment_status, ping_status, post_name, post_modified, post_modified_gmt, post_parent, guid, menu_order, post_type, comment_count)
						VALUES (1, :server_date, :server_date, '', :nameGrupo, 'publish', 'closed', 'closed', 'new-row', :server_date, :server_date, :idCarta, :urlGrupo, 0, 'erm_menu_item', 0)";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':server_date', $server_date, PDO::PARAM_STR);
				$stmt->bindValue(':nameGrupo', $nameGrupo, PDO::PARAM_STR);
				$stmt->bindValue(':idCarta', $idCarta['ID'], PDO::PARAM_INT);
				$stmt->bindValue(':urlGrupo', $urlGrupo, PDO::PARAM_STR);

				$stmt->execute();
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		/** public function RowID($nameGrupo)
		 * Obtenció del ID de la row en base al nom del grup com clau principal
		 */
		public function RowID($nameGrupo){
			try { 
				$sql = "SELECT ID
						FROM wp_posts
						WHERE post_title = :nameGrupo AND post_name = 'new-row'";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':nameGrupo', $nameGrupo, PDO::PARAM_STR);
				$stmt->execute();
				return $stmt->fetch();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		/** public function InsertRowMeta($grupoID)
		 * Funció per insertar dins wp_postmeta cadascuna de les ROW en base al seu ID
		 */
		public function InsertRowMeta($rowMetaID){
			try {
				$sql = "INSERT INTO wp_postmeta (post_id, meta_key, meta_value)
						VALUES (:rowMetaID, '_erm_type', 'row')";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':rowMetaID', $rowMetaID, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		/** public function InsertColumn($idCarta, $urlColumn, $postColumn)
		 * Degut a que les columnes no es modificaran mai (segurament), aquestes tenen un valors predeterminats per a
		 * que simplement s'acompleixi l'estructura de la carta dins WordPress.
		 */
		public function InsertColumn($server_date, $idCarta, $urlColumn, $postColumn){
			try {
				$sql = "INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_status, comment_status, ping_status, post_name, post_modified, post_modified_gmt, post_parent, guid, menu_order, post_type, comment_count)
						VALUES (1, :server_date, :server_date, '', ' ', 'publish', 'closed', 'closed', :postColumn, :server_date, :server_date, :idCarta, :urlColumn, 0, 'erm_menu_item', 0)";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':server_date', $server_date, PDO::PARAM_STR);
				$stmt->bindValue(':idCarta', $idCarta['ID'], PDO::PARAM_INT);
				$stmt->bindValue(':urlColumn', $urlColumn, PDO::PARAM_STR);
				$stmt->bindValue(':postColumn', $postColumn, PDO::PARAM_STR);
				$stmt->execute();
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		/** public function ColumnID($urlColumn) 
		 * Funció per seleccionar el ID de de cada columna per posteriorment insertar-lo dins wp_postmeta
		*/
		public function ColumnID($urlColumn){
			try { 
				$sql = "SELECT ID
						FROM wp_posts
						WHERE guid = :urlColumn";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':urlColumn', $urlColumn, PDO::PARAM_STR);
				$stmt->execute();
				return $stmt->fetch();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		/** public function ColumnID($urlColumn) 
		 * Amb el ID anterior insertam dins wp_postmeta les dades mínimes per no comprometre l'estructura
		 */
		public function InsertColumnMeta($columnID){
			try {
				$sql = "INSERT INTO wp_postmeta (post_id, meta_key, meta_value)
						VALUES (:columnID, '_erm_type', 'column')";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':columnID', $columnID, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}


		// **************
		public function InsertSection($server_date, $idCarta, $urlSection, $postSection){
			try {
				$sql = "INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_status, comment_status, ping_status, post_name, post_modified, post_modified_gmt, post_parent, guid, menu_order, post_type, comment_count)
						VALUES (1, :server_date, :server_date, '', ' ', 'publish', 'closed', 'closed', :postSection, :server_date, :server_date, :idCarta, :urlSection, 0, 'erm_menu_item', 0)";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':server_date', $server_date, PDO::PARAM_STR);
				$stmt->bindValue(':idCarta', $idCarta['ID'], PDO::PARAM_INT);
				$stmt->bindValue(':urlSection', $urlSection, PDO::PARAM_STR);
				$stmt->bindValue(':postSection', $postSection, PDO::PARAM_STR);
				$stmt->execute();
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		public function SectionID($urlSection){
			try { 
				$sql = "SELECT ID
						FROM wp_posts
						WHERE guid = :urlSection";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':urlSection', $urlSection, PDO::PARAM_STR);
				$stmt->execute();
				return $stmt->fetch();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		public function InsertSectionMeta($sectionID){
			try {
				$sql = "INSERT INTO wp_postmeta (post_id, meta_key, meta_value)
						VALUES (:sectionID, '_erm_type', 'section')";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':sectionID', $sectionID, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}


		/** public function InsertProducts($namePlato, $descripcion, $idCarta, $urlProduct, $postProduct)
		 * 	Agafa les dades necessàries per posar les dades dins la taula wp_posts
		 */
		public function InsertProducts($server_date, $namePlato, $descripcion, $idCarta, $urlProduct, $postProduct){
			try {
				$sql = "INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_status, comment_status, ping_status, post_name, post_modified, post_modified_gmt, post_parent, guid, menu_order, post_type, comment_count)
						VALUES (1, :server_date, :server_date, :descripcion, :namePlato, 'publish', 'closed', 'closed', :postProduct, :server_date, :server_date, :idCarta, :urlProduct, 0, 'erm_menu_item', 0)";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':server_date', $server_date, PDO::PARAM_STR);
				$stmt->bindValue(':namePlato', $namePlato, PDO::PARAM_STR);
				$stmt->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
				$stmt->bindValue(':idCarta', $idCarta['ID'], PDO::PARAM_INT);
				$stmt->bindValue(':urlProduct', $urlProduct, PDO::PARAM_STR);
				$stmt->bindValue(':postProduct', $postProduct, PDO::PARAM_STR);


				$stmt->execute();
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		public function ProductID($idCarta, $urlProduct){
			try { 
				$sql = "SELECT ID
						FROM wp_posts
						WHERE post_parent = :idCarta AND guid = :urlProduct";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':idCarta', $idCarta['ID'], PDO::PARAM_INT);
				$stmt->bindValue(':urlProduct', $urlProduct, PDO::PARAM_STR);
				$stmt->execute();
				return $stmt->fetch();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

	
		public function InsertProductMeta($productID, $metaPrice){
			try {
				$sql = "INSERT INTO wp_postmeta (post_id, meta_key, meta_value)
						VALUES (:productID, '_erm_type', 'product'),
							   (:productID, '_erm_prices_pro', :metaPrice)";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':productID', $productID, PDO::PARAM_INT);
				$stmt->bindValue(':metaPrice', $metaPrice, PDO::PARAM_STR);

				$stmt->execute();
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		/////////////////////////////////////////////////////////////////////////////////////////
		///////////////////////////////// CREAR METAKEY /////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////////

		/** public function SelectGID($grupoID)
		 * Funció que s'ocupa principalment d'agafar es ID de cadascun dels grups
		 */
		public function MetaKey($idCarta, $meta){
			try {
				$sql = "INSERT INTO wp_postmeta (post_id, meta_key, meta_value)
				VALUES (:idCarta, '_erm_menu_items_pro', :meta)";
				$stmt = $this->wordpress->prepare($sql);
				$stmt->bindValue(':idCarta', $idCarta['ID'], PDO::PARAM_INT);
				$stmt->bindValue(':meta', $meta, PDO::PARAM_STR);


				$stmt->execute();
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		public function DisconnectWp(){
			try{
				$stmt=$this->wordpress = null;
				exit;
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}

		}

	}