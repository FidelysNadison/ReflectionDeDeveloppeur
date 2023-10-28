<?php

function GenereClasses($db, $host, $user, $pwd) 
{
    $dsn = 'mysql:dbname=' . $db . ';host=' . $host; 

// connection BDD
    try {

        $conn = new PDO('mysql:host=' . $host . ';dbname=' . $db . ';charset=utf8', $user, $pwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
        return;
    }

    $tablesQuery = "SHOW TABLES";
    $tablesResult = $conn->query($tablesQuery); //requete pour récuperer les table 

    while ($tableRow = $tablesResult->fetch(PDO::FETCH_ASSOC)) {
        $tableName = $tableRow['Tables_in_' . $db]; 


        $columnsQuery = "SHOW COLUMNS FROM $tableName";
        $columnsResult = $conn->query($columnsQuery);

        $classContent = "<?php\n\nclass $tableName {\n"; //Script des classe Générer
        while ($columnRow = $columnsResult->fetch(PDO::FETCH_ASSOC)) {
            $columnName = $columnRow['Field'];
            $columnType = $columnRow['Type'];
            $classContent .= "    public $$columnName;\n";
        }

        $classContent .= "\n";
        $classContent .= "    public function Ajout() {\n";
        $classContent .= "        // script pour ajouter des données\n";
        $classContent .= "    }\n\n";

        $classContent .= "    public function Supp() {\n";
        $classContent .= "        // script pour supprimer des données\n";
        $classContent .= "    }\n\n";

        $classContent .= "    public function Get() {\n";
        $classContent .= "        // script pour récupérer des données\n";
        $classContent .= "    }\n\n";

        $classContent .= "    public function Rechercher() {\n";
        $classContent .= "        // script pour effectuer une recherche\n";
        $classContent .= "    }\n";

        $classContent .= "}\n\n";

        $classFilename = $tableName . '.php';
        file_put_contents($classFilename, $classContent);

        echo "Classe générée pour la table $tableName : $classFilename\n";
    }
    $conn = null;
}
//Test -> GenereClasses('testgenereclasse', 'localhost', 'root', '');