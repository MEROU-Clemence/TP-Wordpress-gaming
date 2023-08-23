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

        // COMPETITION
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}clem_competition(
            id INT AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(150) NOT NULL)");

        // on regarde si la table contient des lignes (rows)
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}clem_competition");

        // si la table est vide je vais lui insérer des valeurs par défaut
        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}clem_competiton", [
                "label" => "Super Compète"
            ]);
        }
        // JOUEUR
        // création de la table en BDD
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}clem_player(
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nom VARCHAR(150) NOT NULL,
                    prenom VARCHAR(150) NOT NULL,
                    surnom VARCHAR(150) NOT NULL,
                    email VARCHAR(150) NOT NULL,
                    competition_id INT(10) NOT NULL,
                    FOREIGN KEY (competition_id) REFERENCES {$wpdb->prefix}clem_competition(id))");

        // on regarde si la table contient des lignes (rows)
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}clem_player");

        // si la table est vide je vais lui insérer des valeurs par défaut
        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}clem_player", [
                "nom" => "Doe",
                "prenom" => "John",
                "surnom" => "Jojo le dodo",
                "email" => "john.doe@gmail.com",
                "competition_id" => 1
            ]);
        }

        // GROUPE
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}clem_group(
            id INT AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(150) NOT NULL)");

        // on regarde si la table contient des lignes (rows)
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}clem_group");

        // si la table est vide je vais lui insérer des valeurs par défaut
        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}clem_group", [
                "label" => "Groupe X"
            ]);
        }

        // POULE
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}clem_pool(
            id INT AUTO_INCREMENT PRIMARY KEY,
            competition_id int(10) NOT NULL,
            groupe_id int(10) NOT NULL,
            player_id int(10) NOT NULL,
            FOREIGN KEY (competition_id) REFERENCES {$wpdb->prefix}clem_competition(id),
            FOREIGN KEY (groupe_id) REFERENCES {$wpdb->prefix}clem_group(id),
            FOREIGN KEY (player_id) REFERENCES {$wpdb->prefix}clem_player(id))");

        // on regarde si la table continent des lignes (rows)
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}clem_pool");

        // MATCH
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}clem_match(
            id INT AUTO_INCREMENT PRIMARY KEY,
            joueur1_id int(10) NOT NULL,
            joueur2_id int(10) NOT NULL,
            date_match int(10) NOT NULL,
            is_pool BOOLEAN DEFAULT false,
            FOREIGN KEY (joueur1_id) REFERENCES {$wpdb->prefix}clem_player(id),
            FOREIGN KEY (joueur2_id) REFERENCES {$wpdb->prefix}clem_player(id))");

        // si la table est vide je vais lui insérer des valeurs par défaut
        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}clem_match", [
                "date_match" => "00/00/0000",
                "is_pool" => false
            ]);
        }

        // POINTS
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}clem_points(
            id INT AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(150) NOT NULL,
            points int(10) NOT NULL)");

        // on regarde si la table continet des lignes (rows)
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}clem_points");

        // si la table est vide je vais lui insérer des valeurs par défaut
        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}clem_points", [
                "label" => "defaite",
                "points" => 0
            ]);
        }

        // SCORES
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}clem_scores(
            id INT AUTO_INCREMENT PRIMARY KEY,
            points_id int(10) NOT NULL,
            player_id int(10) NOT NULL,
            match_id int(10) NOT NULL,
            pool_id int(10) NOT NULL,
            FOREIGN KEY (points_id) REFERENCES {$wpdb->prefix}clem_points(id),
            FOREIGN KEY (player_id) REFERENCES {$wpdb->prefix}clem_player(id),
            FOREIGN KEY (match_id) REFERENCES {$wpdb->prefix}clem_match(id),
            FOREIGN KEY (pool_id) REFERENCES {$wpdb->prefix}clem_pool(id))");

        // on regarde si la table continet des lignes (rows)
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}clem_scores");
    }


    // fonction qui va récupérer tous les clients
    public function findAll()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}clem_player");
        return $res;
    }

    // function pour enregistrer un client
    public function save_player()
    {
        global $wpdb;
        // dans une variable, on va récupérer les données du formulaire
        $data = [
            "nom" => $_POST["nom"],
            "prenom" => $_POST["prenom"],
            "surnom" => $_POST["surnom"],
            "email" => $_POST["email"],
            "competition_id" => $_POST["competition"]
        ];
        // on vérifie que le client n'existe pas déjà
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}clem_player WHERE email = '" . $data["email"] . "'");
        if (is_null($row)) {
            // si le client n'existe pas, on l'insère dans la table
            // méthode insert: 1er paramètre: le nom de la table, 2ème paramètre: les données à insérer(array)
            $wpdb->insert("{$wpdb->prefix}clem_player", $data);
        } else {
            // TODO: faire un message d'erreur
            wp_die("Le joueur avec l'adresse e-mail {$data["email"]} existe déjà.");
        }
    }

    // fonction qui supprime un ou plusieurs player
    public function delete_player($ids) // $ids est un tableau d'id
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

    // fonction qui supprime la table lors de la désinstallation du plugin
    public static function delete_db()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}clem_player");
    }
}
