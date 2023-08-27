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


        // POULE
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}clem_pool(
            id INT AUTO_INCREMENT PRIMARY KEY,
            competition_id int(10) NOT NULL,
            group_id int(10) NOT NULL,
            player_id int(10) NOT NULL,
            FOREIGN KEY (competition_id) REFERENCES {$wpdb->prefix}clem_competition(id),
            FOREIGN KEY (group_id) REFERENCES {$wpdb->prefix}clem_group(id),
            FOREIGN KEY (player_id) REFERENCES {$wpdb->prefix}clem_player(id))");

        // on regarde si la table continent des lignes (rows)
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}clem_pool");



        // MATCH
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}clem_match(
            id INT AUTO_INCREMENT PRIMARY KEY,
            player1_id int(10) NOT NULL,
            player2_id int(10) NOT NULL,
            date_match VARCHAR(150) NOT NULL,
            is_pool BOOLEAN DEFAULT false,
            FOREIGN KEY (player1_id) REFERENCES {$wpdb->prefix}clem_player(id),
            FOREIGN KEY (player2_id) REFERENCES {$wpdb->prefix}clem_player(id))");

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

        // on regarde si la table contient des lignes (rows)
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




    //*********COMPETITIONS*********
    // fonction qui va récupérer toutes les compétitions
    public function findAllCompetitions()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}clem_competition");
        return $res;
    }


    // function pour enregistrer une compétition
    public function save_competition()
    {
        global $wpdb;
        // dans une variable, on va récupérer les données du formulaire
        $data = [
            "label" => $_POST["label"]
        ];
        // on vérifie que la competition n'existe pas déjà
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}clem_competition WHERE label = '" . $data["label"] . "'");
        if (is_null($row)) {
            // si le client n'existe pas, on l'insère dans la table
            // méthode insert: 1er paramètre: le nom de la table, 2ème paramètre: les données à insérer(array)
            $wpdb->insert("{$wpdb->prefix}clem_competition", $data);
        } else {
            // Message d'erreur si existe déjà
            wp_die("La compétition avec le nom {$data['label']} existe déjà.");
        }
    }

    // fonction qui supprime un ou plusieurs compet
    public function delete_competition($ids) // $ids est un tableau d'id
    {
        global $wpdb;
        // on check si $ids est dans un tableau, sinon, on le met dans un tableau
        // pour avoir la possibilité de supprimer plusieurs clients
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        // effectuer la requête de suppression
        // implode = transforme un tableau en chaîne de caractères
        $wpdb->query("DELETE FROM {$wpdb->prefix}clem_competition WHERE id IN (" . implode(",", $ids) . ")");
    }





    //*********JOUEURS*********
    // fonction qui va récupérer tous les joueurs
    public function findAll()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT p.id, p.nom, p.prenom, p.surnom, p.email, c.label
        FROM {$wpdb->prefix}clem_player AS p
        INNER JOIN {$wpdb->prefix}clem_competition AS c
        ON p.competition_id = c.id");
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
            "email" => $_POST["email"],
            "competition_id" => $_POST["competition"]
        ];
        // on vérifie que le client n'existe pas déjà
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}clem_player WHERE email = '" . $data["email"] . "'");
        $countByNumberPlayers = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}clem_player WHERE competition_id = " . $data["competition_id"]);

        if ($countByNumberPlayers < 32) {
            // On vérifie si le joueur avec l'adresse e-mail existe déjà
            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}clem_player WHERE email = '" . $data["email"] . "'");
            if (is_null($row)) {
                // Si le joueur n'existe pas, on l'insère dans la table
                $wpdb->insert("{$wpdb->prefix}clem_player", $data);
            } else {
                // Message d'erreur si le joueur existe déjà
                wp_die("Le joueur avec l'adresse e-mail {$data["email"]} existe déjà.");
            }
        } else {
            // Le nombre maximum de joueurs pour cette compétition a été atteint
            wp_die("Le nombre maximum de joueurs pour cette compétition a été atteint, essayez une autre compétition.");
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

    // fonction pour trouver tous les joueurs mais par rapport a une competition
    // TODO: faire marcher cette fonction dans mes joueurs
    public function findAllPlayersByCompetition($competition)
    {
        global $wpdb;

        $res = $wpdb->get_results($wpdb->prepare(
            "SELECT p.id, p.surnom
        FROM {$wpdb->prefix}clem_player AS p
        WHERE p.competition_id = %d",
            $competition
        ));

        return $res;
    }




    //*********GROUPES*********
    // fonction qui va récupérer tous les groupes
    public function findAllGroupes()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}clem_group");
        return $res;
    }


    // function pour enregistrer un groupe
    public function save_groupe()
    {
        global $wpdb;
        // dans une variable, on va récupérer les données du formulaire
        $data = [
            "label" => $_POST["groupe"]
        ];
        // on vérifie que la competition n'existe pas déjà
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}clem_group WHERE label = '" . $data["label"] . "'");
        $limitCount = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}clem_group");

        if ($limitCount < 8) {
            if (is_null($row)) {
                // si le groupe n'existe pas, on l'insère dans la table
                $wpdb->insert("{$wpdb->prefix}clem_group", $data);
            } else {
                // Message d'erreur si existe déjà
                wp_die("Le groupe avec le nom {$data["label"]} existe déjà.");
            }
        } else {
            // Le nombre maximum d'insertions a été atteint
            wp_die("Le nombre maximum de groupes a été atteint (8 groupes au total).");
        }
    }

    // fonction qui supprime un ou plusieurs groupes
    // TODO: faire que cette function marche
    public function delete_groupe($ids) // $ids est un tableau d'id
    {
        global $wpdb;
        // on check si $ids est dans un tableau, sinon, on le met dans un tableau
        // pour avoir la possibilité de supprimer plusieurs clients
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        // effectuer la requête de suppression
        // implode = transforme un tableau en chaîne de caractères
        $wpdb->query("DELETE FROM {$wpdb->prefix}clem_group WHERE id IN (" . implode(",", $ids) . ")");
    }




    //*********POULES*********
    // fonction qui va récupérer toutes les poules
    public function findAllPoules()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT a.id, c.label AS nomcompet, g.label, p.surnom
        FROM {$wpdb->prefix}clem_pool AS a
        INNER JOIN {$wpdb->prefix}clem_competition AS c
        ON a.competition_id = c.id
        INNER JOIN {$wpdb->prefix}clem_group AS g
        ON a.group_id = g.id
        INNER JOIN {$wpdb->prefix}clem_player AS p
        ON a.player_id = p.id
        ");
        return $res;
    }


    // function pour enregistrer une poule
    public function save_poule()
    {
        global $wpdb;
        // dans une variable, on va récupérer les données du formulaire
        $data = [
            "competition_id" => $_POST["nomcompet"],
            "group_id" => $_POST["label"],
            "player_id" => $_POST["surnom"]
        ];

        // on vérifie que le joueur n'est pas déjà répertorié dans une compétition
        // puis on vérifie que la poule dans laquelle il veut s'inscrire n'est pas complète encore
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}clem_pool WHERE player_id = '" . $data["player_id"] . "'");

        if (is_null($row)) {
            // si le joueur n'existe pas, on continue à vérifier la complétude de la poule
            $limitCountGroupeLabel = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->prefix}clem_pool WHERE group_id = %d AND competition_id = %d",
                    $data["group_id"],
                    $data["competition_id"]
                )
            );

            if ($limitCountGroupeLabel < 4) {
                // si la poule n'est pas complète, on insère les données
                $wpdb->insert("{$wpdb->prefix}clem_pool", $data);
            } else {
                // Le nombre maximum d'insertions dans la poule a été atteint
                wp_die("Cette poule est déjà complète pour la compétition.");
            }
        } else {
            // Message d'erreur si le joueur est déjà affilié à une poule
            wp_die("Ce joueur est déjà affilié à une poule.");
        }
    }



    // fonction qui supprime un ou plusieurs poules
    public function delete_poule($ids) // $ids est un tableau d'id
    {
        global $wpdb;
        // on check si $ids est dans un tableau, sinon, on le met dans un tableau
        // pour avoir la possibilité de supprimer plusieurs clients
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        // effectuer la requête de suppression
        // implode = transforme un tableau en chaîne de caractères
        $wpdb->query("DELETE FROM {$wpdb->prefix}clem_pool WHERE id IN (" . implode(",", $ids) . ")");
    }





    //*********MATCHS*********
    // fonction qui va récupérer tous les matchs
    public function findAllMatchs()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT m.id, p.surnom AS joueur1, u.surnom AS joueur2, m.date_match AS date, m.is_pool AS ispool
        FROM {$wpdb->prefix}clem_match AS m
        INNER JOIN {$wpdb->prefix}clem_player AS p
        ON m.player1_id = p.id
        INNER JOIN {$wpdb->prefix}clem_player AS u
        ON m.player2_id = u.id
        ");
        return $res;
    }

    // function pour enregistrer un match
    public function save_match()
    {
        global $wpdb;
        // dans une variable, on va récupérer les données du formulaire
        $data = [
            "player1_id" => intval($_POST["joueur1"]),
            "player2_id" => intval($_POST["joueur2"]),
            "date_match" => $_POST["date"],
            "is_pool" => intval($_POST["ispool"])
        ];
        // Vérifier si un match entre les deux joueurs a déjà eu lieu
        $existingMatch = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}clem_match
                WHERE (player1_id = %d AND player2_id = %d) OR (player1_id = %d AND player2_id = %d)",
                $data["player1_id"], $data["player2_id"],
                $data["player2_id"], $data["player1_id"]
            )
        );

        // Vérifier si le match n'existe pas déjà par sa date
        if (is_null($existingMatch)) {
            $row = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}clem_match WHERE date_match = %s",
                    $data["date_match"]
                )
            );

            if (is_null($row)) {
                // Insérer le nouveau match
                $wpdb->insert("{$wpdb->prefix}clem_match", $data);
            } else {
                // Message d'erreur si le match existe déjà par sa date
                wp_die("Le match en date du {$data["date_match"]} a déjà eu lieu.");
            }
        } else {
            // Message d'erreur si un match entre ces joueurs a déjà eu lieu
            wp_die("Le match entre ces deux joueurs a déjà eu lieu.");
        }
    }

    // fonction qui supprime un ou plusieurs poules
    // TODO: faire que cette function marche
    public function delete_match($ids) // $ids est un tableau d'id
    {
        global $wpdb;
        // on check si $ids est dans un tableau, sinon, on le met dans un tableau
        // pour avoir la possibilité de supprimer plusieurs clients
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        // effectuer la requête de suppression
        // implode = transforme un tableau en chaîne de caractères
        $wpdb->query("DELETE FROM {$wpdb->prefix}clem_match WHERE id IN (" . implode(",", $ids) . ")");
    }




    //*********POINTS*********
    // fonction qui va récupérer tous les points
    public function findAllPoints()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}clem_points");
        return $res;
    }


    // function pour enregistrer des points
    public function save_points()
    {
        global $wpdb;
        // dans une variable, on va récupérer les données du formulaire
        $data = [
            "label" => $_POST["label"],
            "points" => $_POST["points"]
        ];
        // on vérifie que les points n'existe pas déjà
        $wpdb->insert("SELECT * FROM {$wpdb->prefix}clem_points, $data");
    }

    // fonction qui supprime des points
    // TODO: faire que cette function marche
    public function delete_points($ids) // $ids est un tableau d'id
    {
        global $wpdb;
        // on check si $ids est dans un tableau, sinon, on le met dans un tableau
        // pour avoir la possibilité de supprimer plusieurs points
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        // effectuer la requête de suppression
        // implode = transforme un tableau en chaîne de caractères
        $wpdb->query("DELETE FROM {$wpdb->prefix}clem_points WHERE id IN (" . implode(",", $ids) . ")");
    }





    //*********SCORES*********
    // fonction qui va récupérer tous les scores
    public function findAllScores()
    {
        global $wpdb;

        $res = $wpdb->get_results("SELECT 
        s.id, 
        i.label AS nomresultat,
        i.points, 
        p.surnom AS surnom, 
        m.date_match AS date, 
        g.label AS label, 
        c.label AS competition
            FROM {$wpdb->prefix}clem_scores as s
            INNER JOIN {$wpdb->prefix}clem_pool AS po
            ON s.pool_id = po.id
            INNER JOIN {$wpdb->prefix}clem_points AS i
            ON s.points_id = i.id
            INNER JOIN {$wpdb->prefix}clem_player AS p
            ON s.player_id = p.id
            INNER JOIN {$wpdb->prefix}clem_match AS m
            ON s.match_id = m.id
            INNER JOIN {$wpdb->prefix}clem_group AS g
            ON po.group_id = g.id
            INNER JOIN {$wpdb->prefix}clem_competition AS c
            ON po.competition_id = c.id");
        return $res;
    }


    // function pour enregistrer un score
    public function save_scores()
    {
        global $wpdb;

        // dans une variable, on va récupérer les données du formulaire
        $data = [
            "points_id" => $_POST["points"],
            "player_id" => $_POST["player"],
            "match_id" => $_POST["match"],
            "pool_id" => $_POST["pool"]
        ];

        // Vérifiez si l'entrée existe déjà
        $existing_scores = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}clem_scores WHERE match_id = '" . $data["match_id"] . "'");

        if (count($existing_scores) < 2) {
            // si le nombre d'entrées est inférieur à 2, on peut insérer le score
            $wpdb->insert("{$wpdb->prefix}clem_scores", $data);
            } else {
                // Message d'erreur si le score existe déjà
                wp_die("Les scores pour cette rencontre ont déjà été rentrés pour les 2 joueurs en opposition.");
            }
    }
    

    // fonction qui supprime un ou plusieurs compet
    public function delete_scores($ids) // $ids est un tableau d'id
    {
        global $wpdb;
        // on check si $ids est dans un tableau, sinon, on le met dans un tableau
        // pour avoir la possibilité de supprimer plusieurs points
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        // effectuer la requête de suppression
        // implode = transforme un tableau en chaîne de caractères
        $wpdb->query("DELETE FROM {$wpdb->prefix}clem_scores WHERE id IN (" . implode(",", $ids) . ")");
    }

    // TODO: fonction pour rentrer des scores mais seulement dans des matchs qui se sont vraiment passés entre les deux jours choisis.




    // fonction qui supprime la table lors de la désinstallation du plugin
    public static function delete_db()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}clem_player");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}clem_competition");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}clem_group");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}clem_pool");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}clem_match");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}clem_points");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}clem_scores");
    }
}
