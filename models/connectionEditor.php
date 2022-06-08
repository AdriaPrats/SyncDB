<?php
// Amb PDO podem unificar la manera de fer QUERY en les BD per generalitzar-ho amb els diferents motors. Poguent utilitzar ORACLE, MySQL, Postgree, Microsoft SQL

class ConexionEditor extends PDO
	{
		private $editor;

		public function __construct()
		{
			// # Conectam a la Base de Dades 1. En aquest cas és la base de dades de l'Editor
			// $hostEditor='localhost, 1433'; // Base de dades local (En un principi aquesta serà l'editor)
			// $dbnameEditor='Editor_platos';
			// $userEditor='AdminAdri';
			// $passEditor='12345678';

			# Conectam a la Base de Dades 1. En aquest cas és la base de dades de l'Editor
			$hostEditor='sql-srv'; // Base de dades local (En un principi aquesta serà l'editor)
			$dbnameEditor='Editor_platos';
			$userEditor='sa';
			$passEditor='P@ssword';

			try{
				$this->editor= new PDO("sqlsrv:server=$hostEditor;TrustServerCertificate=true;database= $dbnameEditor", $userEditor, $passEditor);
				$this->editor->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			
				} catch(PDOException $e){
				echo 'Error: ' . $e->getMessage();
				exit;
			}
		}
		
		/** public function ShowNames()
		 * S'ocupa de sel·leccionar tots els noms que hi ha dins la base de dades, i mostrar-los
		 * en ordre descendent (Les més noves es troben abans).
		 */
		public function ShowNames(){
			try {
				$sql = ("SELECT car_id, car_nombre FROM carta ORDER BY car_id DESC");
				$stmt=$this->editor->prepare($sql);
	
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				header("HTTP/1.1 200 OK -> Hay datos");
				return $stmt->fetchAll(); // -> Aquest return passa al selection
				exit;
			} catch(PDOException $err) {
			
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}

		}

		/** public function RestaurantInfo($id)
		 * Amb l'id obtingut del select de la primera pàgina, obtenim el nom mostrat en el Select.
		 */
		public function RestaurantInfo($id){
			try {
				$sql = ("SELECT car_nombre
						FROM carta
						WHERE car_id = :id");
				$stmt=$this->editor->prepare($sql);
		
				$stmt->bindValue(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		/** public function GrupoInfo($id)
		 * Amb l'id obtingut del select de la primera pàgina, obtenim els grups de la carta del restaurant
		 */
		public function GrupoInfo($id){
			try {
				$sql = ("SELECT gru_id, gru_pos, gru_frase, gru_comentario
						FROM grupo
						WHERE gru_car_id = :id
						ORDER BY gru_pos ASC");
				$stmt=$this->editor->prepare($sql);
		
				$stmt->bindValue(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		/** public function PlatoInfo($id)
		 * En base al id dels grups sel·leccionats trobem els plats que corresponen a cadascun
		 */
		public function PlatoInfo($gru_id){
			try {
				$sql = ("SELECT pla_numero, pla_plato, pla_precio1, pla_precio2, pla_descripcion
						FROM Platos
						WHERE pla_gru_id = :id
						ORDER BY pla_pos ASC");
				$stmt=$this->editor->prepare($sql);
		
				$stmt->bindValue(':id', $gru_id, PDO::PARAM_INT);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				return $stmt->fetchAll();
				exit;
		
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}
		}

		public function DisconnectEdt(){
			try{
				$stmt=$this->editor = null;
				exit;
			} catch(PDOException $err) {
		
				echo "Error: ejecutando consulta SQL.";
				echo "<br>";
				echo $err;
			}

		}

	}