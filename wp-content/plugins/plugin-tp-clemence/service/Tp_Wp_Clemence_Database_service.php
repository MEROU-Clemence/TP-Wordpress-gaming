<?php

class Tp_Wp_Clemence_Database_Service
{
    public function __construct()
    {
        // pour l'instant rien dedans
    }

    // fonction qui va créer une nouvelle table dans la DB
    public static function create_db()
    {
        // on appelle la variable globale $wpdb
        global $wpdb;
        // création de la table en BDD
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}clem_player(
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(150) NOT NULL,
            prenom VARCHAR(150) NOT NULL,
            surnom VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL)");

        // on regarde si la table continet des lignes (rows)
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}clem_player");

        // si la table est vide je vais lui insérer des valeurs par défaut
        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}clem_player", [
                "nom" => "Doe",
                "prenom" => "John",
                "surnom" => "Jojo le dodo",
                "email" => "john.doe@gmail.com"
            ]);
        }
    }


    // fonction qui va récupérer tous les clients
    public function findAll()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}clem_player");
        return $res;
    }

    // function pour enregistrer un joueur
    public function save_player()
    {
        global $wpdb;
        // dans une variable, on va récupérer les données du formulaire
        $data = [
            "nom" => $_POST["nom"],
            "prenom" => $_POST["prenom"],
            "surnom" => $_POST["surnom"],
            "email" => $_POST["email"]
        ];
        // on vérifie que le client n'existe pas déjà
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}clem_player WHERE email = '" . $data["email"] . "'");
        if (is_null($row)) {
            // si le client n'existe pas, on l'insère dans la table
            // méthode insert: 1er paramètre: le nom de la table, 2ème paramètre: les données à insérer(array)
            $wpdb->insert("{$wpdb->prefix}clem_player", $data);
        } else {
            // TODO: faire un message d'erreur
        }
    }

    // fonction qui supprime un ou plusieurs player
    public function delete_client($ids) // $ids est un tableau d'id
    {
        global $wpdb;
        // on check si $ids est dans un tableau, sinon, on le met dans un tableau
        // pour avoir la possibilité de supprimer plusieurs clients
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        // effectuer la requête de suppression
        // implode = transforme un tableau en chaîne de caractères
        $wpdb->query("DELETE FROM {$wpdb->prefix}clem_player WHERE id IN (" . implode(",", $ids) . ")");
    }
}
