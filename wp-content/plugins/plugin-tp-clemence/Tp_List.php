<?php
// dans certaines versions de WP il n'arrive pas à étendre la classe WP_List_Table
// pour ce cas, il faut charger manuellement cette classe
if (!class_exists("WP_List_Table")) {
    require_once ABSPATH . "wp-admin/includes/class-wp-list-table.php";
}

// on importe notre classe de Service
require_once plugin_dir_path(__FILE__) . "service/Tp_Wp_Clemence_Database_service.php";

// Liste des compétitions
class Tp_List_Competitions extends WP_List_Table
{
    // on va créer une variable en private qui va contenir l'instance de notre service
    private $dal;
    // 1er: ON DECLARE LE CONSTRUCTEUR
    public function __construct() // on déclare le constructeur
    {
        // on va surcharger le constructeur de la classe parente WP_List_Table
        // pour redéfinir le nom de la table (singulier et au pluriel)
        parent::__construct(
            array(
                "singular" => __("Competition"),
                "plural" => __("Competitions")
            )
        );
        // on instancie notre service
        $this->dal = new Tp_Wp_Clemence_Database_Service();
    }

    // 2ième: ON SURCHARGE LA METHODE prepare_items()
    public function prepare_items() // fonction du parent pour préparer notre liste
    {
        // on va définir toutes nos variables
        $columns = $this->get_columns(); // on va chercher les colonnes
        $hidden = $this->get_hidden_columns(); // on ajoute cette variable si on veut cacher les colonnes 
        $sortable = $this->get_sortable_columns(); // on ajoute cette variable si on veut trier des colonnes
        // PAGINATION
        $perPage = $this->get_items_per_page("competitions_per_page", 10); // on va chercher le nombre d'éléments par page
        $currentPage = $this->get_pagenum(); // on va chercher le numéro de la page courante
        // LES DONNÉES
        $data = $this->dal->findAllCompetitions(); // on va chercher les données dans la base de données
        $totalPage = count($data); // on va compter le nombre de données
        // TRI
        // &$this = pour faire référence à notre classe
        usort($data, array(&$this, "usort_reorder")); // on va trier les données

        $paginationData = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        // on va définir les valeurs de la pagination
        $this->set_pagination_args(
            array(
                "total_items" => $totalPage, // on passe le nombre total d'éléments
                "per_page" => $perPage // on passe le nombre d'éléments par page
            )
        );
        $this->_column_headers = array($columns, $hidden, $sortable); // on construit les entêtes des colonnes
        $this->items = $paginationData; // on alimente les données
    }

    // 3ième : ON SURCHARGE LA MÉTHODE get_columns()
    public function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'id' => 'id',
            'label' => 'Titre compétition'
        ];
        return $columns;
    }

    // 4ième : ON SURCHARGE LA MÉTHODE get_hidden_columns()
    public function get_hidden_columns()
    {
        return [];
        // exemple si on veut cacher la colonne id :
        // return ['id' => 'id',];
    }

    // fonction pour le tri
    public function usort_reorder($a, $b)
    {
        // si je passe un paramètre de tri dans l'url
        // sinon on trie par défaut
        $orderBy = (!empty($_GET["orderby"])) ? $_GET["orderby"] : "id";
        // idem pour l'ordre de tri
        $order = (!empty($_GET['order'])) ? $_GET["order"] : "desc";
        $result  = strcmp($a->orderBy, $b->$orderBy); // on compare les 2  valeurs
        return ($order === "asc") ? $result : -$result; // on retourne le résultat si asc sinon on inverse
    }

    // 5ième : ON SURCHARGE LA MÉTHODE column_default()
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id';
            case 'label':
                return $item->$column_name;
                break;
            default:
                return print_r($item, true);
        }
    }

    // 6ième : ON SURCHARGE LA MÉTHODE get_sortable_columns()
    public function get_sortable_columns()
    {
        $sortable = [
            'id' => ['id', true],
            'label' => ['label', true]
        ];
        return $sortable;
    }

    // 7ième : ON SURCHARGE LA MÉTHODE column_cb()
    public function column_cb($item)
    {
        $item = (array) $item; // on cast l'objet en tableau (pour pouvoir utiliser la méthode sprintf)

        return sprintf(
            '<input type="checkbox" name="delete-competition[]" value="%s" />',
            $item["id"]
        );
    }

    // 8ième : ON SURCHARGE LA MÉTHODE get_bulk_actions()
    public function get_bulk_actions()
    {
        $actions = [
            "delete-competition" => __("Delete")
        ];
        return $actions;
    }
}



// Liste des joueurs
class Tp_List extends WP_List_Table
{
    // on va créer une variable en private qui va contenir l'instance de notre service
    private $dal;
    // 1er: ON DECLARE LE CONSTRUCTEUR
    public function __construct() // on déclare le constructeur
    {
        // on va surcharger le constructeur de la classe parente WP_List_Table
        // pour redéfinir le nom de la table (singulier et au pluriel)
        parent::__construct(
            array(
                "singular" => __("Player"),
                "plural" => __("Players")
            )
        );
        // on instancie notre service
        $this->dal = new Tp_Wp_Clemence_Database_Service();
    }

    // 2ième: ON SURCHARGE LA METHODE prepare_items()
    public function prepare_items() // fonction du parent pour préparer notre liste
    {
        // on va définir toutes nos variables
        $columns = $this->get_columns(); // on va chercher les colonnes
        $hidden = $this->get_hidden_columns(); // on ajoute cette variable si on veut cacher les colonnes 
        $sortable = $this->get_sortable_columns(); // on ajoute cette variable si on veut trier des colonnes
        // PAGINATION
        $perPage = $this->get_items_per_page("players_per_page", 10); // on va chercher le nombre d'éléments par page
        $currentPage = $this->get_pagenum(); // on va chercher le numéro de la page courante
        // LES DONNÉES
        $data = $this->dal->findAll(); // on va chercher les données dans la base de données
        $totalPage = count($data); // on va compter le nombre de données
        // TRI
        // &$this = pour faire référence à notre classe
        usort($data, array(&$this, "usort_reorder")); // on va trier les données

        $paginationData = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        // on va définir les valeurs de la pagination
        $this->set_pagination_args(
            array(
                "total_items" => $totalPage, // on passe le nombre total d'éléments
                "per_page" => $perPage // on passe le nombre d'éléments par page
            )
        );
        $this->_column_headers = array($columns, $hidden, $sortable); // on construit les entêtes des colonnes
        $this->items = $paginationData; // on alimente les données
    }

    // 3ième : ON SURCHARGE LA MÉTHODE get_columns()
    public function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'id' => 'id',
            'nom' => 'Nom',
            'prenom' => 'Prénom',
            'surnom' => 'Surnom',
            'email' => 'Email',
            'label' => 'Compétition'
        ];
        return $columns;
    }

    // 4ième : ON SURCHARGE LA MÉTHODE get_hidden_columns()
    public function get_hidden_columns()
    {
        return [];
        // exemple si on veut cacher la colonne id :
        // return ['id' => 'id',];
    }

    // fonction pour le tri
    public function usort_reorder($a, $b)
    {
        // si je passe un paramètre de tri dans l'url
        // sinon on trie par défaut
        $orderBy = (!empty($_GET["orderby"])) ? $_GET["orderby"] : "id";
        // idem pour l'ordre de tri
        $order = (!empty($_GET['order'])) ? $_GET["order"] : "desc";
        $result  = strcmp($a->orderBy, $b->$orderBy); // on compare les 2  valeurs
        return ($order === "asc") ? $result : -$result; // on retourne le résultat si asc sinon on inverse
    }

    // 5ième : ON SURCHARGE LA MÉTHODE column_default()
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id';
            case 'nom';
            case 'prenom';
            case 'surnom';
            case 'email';
            case 'label':
                return $item->$column_name;
                break;
            default:
                return print_r($item, true);
        }
    }

    // 6ième : ON SURCHARGE LA MÉTHODE get_sortable_columns()
    public function get_sortable_columns()
    {
        $sortable = [
            'id' => ['id', true],
            'nom' => ['nom', true],
            'prenom' => ['prenom', true],
            'surnom' => ['surnom', true],
            'email' => ['email', true],
            'label' => ['label', true]
        ];
        return $sortable;
    }

    // 7ième : ON SURCHARGE LA MÉTHODE column_cb()
    public function column_cb($item)
    {
        $item = (array) $item; // on cast l'objet en tableau (pour pouvoir utiliser la méthode sprintf)

        return sprintf(
            '<input type="checkbox" name="delete-player[]" value="%s" />',
            $item["id"]
        );
    }

    // 8ième : ON SURCHARGE LA MÉTHODE get_bulk_actions()
    public function get_bulk_actions()
    {
        $actions = [
            "delete-player" => __("Delete")
        ];
        return $actions;
    }
}



// Liste des compétitions
class Tp_List_Groupes extends WP_List_Table
{
    // on va créer une variable en private qui va contenir l'instance de notre service
    private $dal;
    // 1er: ON DECLARE LE CONSTRUCTEUR
    public function __construct() // on déclare le constructeur
    {
        // on va surcharger le constructeur de la classe parente WP_List_Table
        // pour redéfinir le nom de la table (singulier et au pluriel)
        parent::__construct(
            array(
                "singular" => __("Groupe"),
                "plural" => __("Groupes")
            )
        );
        // on instancie notre service
        $this->dal = new Tp_Wp_Clemence_Database_Service();
    }

    // 2ième: ON SURCHARGE LA METHODE prepare_items()
    public function prepare_items() // fonction du parent pour préparer notre liste
    {
        // on va définir toutes nos variables
        $columns = $this->get_columns(); // on va chercher les colonnes
        $hidden = $this->get_hidden_columns(); // on ajoute cette variable si on veut cacher les colonnes 
        $sortable = $this->get_sortable_columns(); // on ajoute cette variable si on veut trier des colonnes
        // PAGINATION
        $perPage = $this->get_items_per_page("groupes_per_page", 10); // on va chercher le nombre d'éléments par page
        $currentPage = $this->get_pagenum(); // on va chercher le numéro de la page courante
        // LES DONNÉES
        $data = $this->dal->findAllGroupes(); // on va chercher les données dans la base de données
        $totalPage = count($data); // on va compter le nombre de données
        // TRI
        // &$this = pour faire référence à notre classe
        usort($data, array(&$this, "usort_reorder")); // on va trier les données

        $paginationData = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        // on va définir les valeurs de la pagination
        $this->set_pagination_args(
            array(
                "total_items" => $totalPage, // on passe le nombre total d'éléments
                "per_page" => $perPage // on passe le nombre d'éléments par page
            )
        );
        $this->_column_headers = array($columns, $hidden, $sortable); // on construit les entêtes des colonnes
        $this->items = $paginationData; // on alimente les données
    }

    // 3ième : ON SURCHARGE LA MÉTHODE get_columns()
    public function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'id' => 'id',
            'label' => 'Groupes'
        ];
        return $columns;
    }

    // 4ième : ON SURCHARGE LA MÉTHODE get_hidden_columns()
    public function get_hidden_columns()
    {
        return [];
        // exemple si on veut cacher la colonne id :
        // return ['id' => 'id',];
    }

    // fonction pour le tri
    public function usort_reorder($a, $b)
    {
        // si je passe un paramètre de tri dans l'url
        // sinon on trie par défaut
        $orderBy = (!empty($_GET["orderby"])) ? $_GET["orderby"] : "id";
        // idem pour l'ordre de tri
        $order = (!empty($_GET['order'])) ? $_GET["order"] : "desc";
        $result  = strcmp($a->orderBy, $b->$orderBy); // on compare les 2  valeurs
        return ($order === "asc") ? $result : -$result; // on retourne le résultat si asc sinon on inverse
    }

    // 5ième : ON SURCHARGE LA MÉTHODE column_default()
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id';
            case 'label':
                return $item->$column_name;
                break;
            default:
                return print_r($item, true);
        }
    }

    // 6ième : ON SURCHARGE LA MÉTHODE get_sortable_columns()
    public function get_sortable_columns()
    {
        $sortable = [
            'id' => ['id', true],
            'label' => ['label', true]
        ];
        return $sortable;
    }

    // 7ième : ON SURCHARGE LA MÉTHODE column_cb()
    public function column_cb($item)
    {
        $item = (array) $item; // on cast l'objet en tableau (pour pouvoir utiliser la méthode sprintf)

        return sprintf(
            '<input type="checkbox" name="delete-groupe[]" value="%s" />',
            $item["id"]
        );
    }

    // 8ième : ON SURCHARGE LA MÉTHODE get_bulk_actions()
    public function get_bulk_actions()
    {
        $actions = [
            "delete-groupe" => __("Delete")
        ];
        return $actions;
    }
}



// Liste des poules
class Tp_List_Poules extends WP_List_Table
{
    // on va créer une variable en private qui va contenir l'instance de notre service
    private $dal;
    // 1er: ON DECLARE LE CONSTRUCTEUR
    public function __construct() // on déclare le constructeur
    {
        // on va surcharger le constructeur de la classe parente WP_List_Table
        // pour redéfinir le nom de la table (singulier et au pluriel)
        parent::__construct(
            array(
                "singular" => __("Poule"),
                "plural" => __("Poules")
            )
        );
        // on instancie notre service
        $this->dal = new Tp_Wp_Clemence_Database_Service();
    }

    // 2ième: ON SURCHARGE LA METHODE prepare_items()
    public function prepare_items() // fonction du parent pour préparer notre liste
    {
        // on va définir toutes nos variables
        $columns = $this->get_columns(); // on va chercher les colonnes
        $hidden = $this->get_hidden_columns(); // on ajoute cette variable si on veut cacher les colonnes 
        $sortable = $this->get_sortable_columns(); // on ajoute cette variable si on veut trier des colonnes
        // PAGINATION
        $perPage = $this->get_items_per_page("poules_per_page", 10); // on va chercher le nombre d'éléments par page
        $currentPage = $this->get_pagenum(); // on va chercher le numéro de la page courante
        // LES DONNÉES
        $data = $this->dal->findAllPoules(); // on va chercher les données dans la base de données
        $totalPage = count($data); // on va compter le nombre de données
        // TRI
        // &$this = pour faire référence à notre classe
        usort($data, array(&$this, "usort_reorder")); // on va trier les données

        $paginationData = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        // on va définir les valeurs de la pagination
        $this->set_pagination_args(
            array(
                "total_items" => $totalPage, // on passe le nombre total d'éléments
                "per_page" => $perPage // on passe le nombre d'éléments par page
            )
        );
        $this->_column_headers = array($columns, $hidden, $sortable); // on construit les entêtes des colonnes
        $this->items = $paginationData; // on alimente les données
    }

    // 3ième : ON SURCHARGE LA MÉTHODE get_columns()
    public function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'id' => 'id',
            'nomcompet' => 'Nom de la Compétition',
            'label' => 'Groupe appartenance',
            'surnom' => 'Pseudo du joueur'
        ];
        return $columns;
    }

    // 4ième : ON SURCHARGE LA MÉTHODE get_hidden_columns()
    public function get_hidden_columns()
    {
        return [];
        // exemple si on veut cacher la colonne id :
        // return ['id' => 'id',];
    }

    // fonction pour le tri
    public function usort_reorder($a, $b)
    {
        // si je passe un paramètre de tri dans l'url
        // sinon on trie par défaut
        $orderBy = (!empty($_GET["orderby"])) ? $_GET["orderby"] : "id";
        // idem pour l'ordre de tri
        $order = (!empty($_GET['order'])) ? $_GET["order"] : "desc";
        $result  = strcmp($a->orderBy, $b->$orderBy); // on compare les 2  valeurs
        return ($order === "asc") ? $result : -$result; // on retourne le résultat si asc sinon on inverse
    }

    // 5ième : ON SURCHARGE LA MÉTHODE column_default()
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id';
            case 'nomcompet';
            case 'label';
            case 'surnom':
                return $item->$column_name;
                break;
            default:
                return print_r($item, true);
        }
    }

    // 6ième : ON SURCHARGE LA MÉTHODE get_sortable_columns()
    public function get_sortable_columns()
    {
        $sortable = [
            'id' => ['id', true],
            'nomcompet' => ['nomcompet', true],
            'label' => ['label', true],
            'surnom' => ['surnom', true]
        ];
        return $sortable;
    }

    // 7ième : ON SURCHARGE LA MÉTHODE column_cb()
    public function column_cb($item)
    {
        $item = (array) $item; // on cast l'objet en tableau (pour pouvoir utiliser la méthode sprintf)

        return sprintf(
            '<input type="checkbox" name="delete-poule[]" value="%s" />',
            $item["id"]
        );
    }

    // 8ième : ON SURCHARGE LA MÉTHODE get_bulk_actions()
    public function get_bulk_actions()
    {
        $actions = [
            "delete-poule" => __("Delete")
        ];
        return $actions;
    }
}




// Liste des matchs
class Tp_List_Matchs extends WP_List_Table
{
    // on va créer une variable en private qui va contenir l'instance de notre service
    private $dal;
    // 1er: ON DECLARE LE CONSTRUCTEUR
    public function __construct() // on déclare le constructeur
    {
        // on va surcharger le constructeur de la classe parente WP_List_Table
        // pour redéfinir le nom de la table (singulier et au pluriel)
        parent::__construct(
            array(
                "singular" => __("Match"),
                "plural" => __("Matchs")
            )
        );
        // on instancie notre service
        $this->dal = new Tp_Wp_Clemence_Database_Service();
    }

    // 2ième: ON SURCHARGE LA METHODE prepare_items()
    public function prepare_items() // fonction du parent pour préparer notre liste
    {
        // on va définir toutes nos variables
        $columns = $this->get_columns(); // on va chercher les colonnes
        $hidden = $this->get_hidden_columns(); // on ajoute cette variable si on veut cacher les colonnes 
        $sortable = $this->get_sortable_columns(); // on ajoute cette variable si on veut trier des colonnes
        // PAGINATION
        $perPage = $this->get_items_per_page("matchs_per_page", 10); // on va chercher le nombre d'éléments par page
        $currentPage = $this->get_pagenum(); // on va chercher le numéro de la page courante
        // LES DONNÉES
        $data = $this->dal->findAllMatchs(); // on va chercher les données dans la base de données
        $totalPage = count($data); // on va compter le nombre de données
        // TRI
        // &$this = pour faire référence à notre classe
        usort($data, array(&$this, "usort_reorder")); // on va trier les données

        $paginationData = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        // on va définir les valeurs de la pagination
        $this->set_pagination_args(
            array(
                "total_items" => $totalPage, // on passe le nombre total d'éléments
                "per_page" => $perPage // on passe le nombre d'éléments par page
            )
        );
        $this->_column_headers = array($columns, $hidden, $sortable); // on construit les entêtes des colonnes
        $this->items = $paginationData; // on alimente les données
    }

    // 3ième : ON SURCHARGE LA MÉTHODE get_columns()
    public function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'joueur1' => 'Joueur 1',
            'joueur2' => 'Joueur 2',
            'date' => 'Date du match',
            'ispool' => 'Fait partie d\'une poule (1=oui 0=non)'
        ];
        return $columns;
    }

    // 4ième : ON SURCHARGE LA MÉTHODE get_hidden_columns()
    public function get_hidden_columns()
    {
        return [];
        // exemple si on veut cacher la colonne id :
        // return ['id' => 'id',];
    }

    // fonction pour le tri
    public function usort_reorder($a, $b)
    {
        // si je passe un paramètre de tri dans l'url
        // sinon on trie par défaut
        $orderBy = (!empty($_GET["orderby"])) ? $_GET["orderby"] : "id";
        // idem pour l'ordre de tri
        $order = (!empty($_GET['order'])) ? $_GET["order"] : "desc";
        $result  = strcmp($a->orderBy, $b->$orderBy); // on compare les 2  valeurs
        return ($order === "asc") ? $result : -$result; // on retourne le résultat si asc sinon on inverse
    }

    // 5ième : ON SURCHARGE LA MÉTHODE column_default()
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'joueur1';
            case 'joueur2';
            case 'date';
            case 'ispool':
                return $item->$column_name;
                break;
            default:
                return print_r($item, true);
        }
    }

    // 6ième : ON SURCHARGE LA MÉTHODE get_sortable_columns()
    public function get_sortable_columns()
    {
        $sortable = [
            'joueur1' => ['joueur1', true],
            'joueur2' => ['joueur2', true],
            'date' => ['date', true],
            'ispool' => ['ispool', true]
        ];
        return $sortable;
    }

    // 7ième : ON SURCHARGE LA MÉTHODE column_cb()
    public function column_cb($item)
    {
        $item = (array) $item; // on cast l'objet en tableau (pour pouvoir utiliser la méthode sprintf)

        return sprintf(
            '<input type="checkbox" name="delete-match[]" value="%s" />',
            $item["id"]
        );
    }

    // 8ième : ON SURCHARGE LA MÉTHODE get_bulk_actions()
    public function get_bulk_actions()
    {
        $actions = [
            "delete-match" => __("Delete")
        ];
        return $actions;
    }
}




// Liste des points
class Tp_List_Points extends WP_List_Table
{
    // on va créer une variable en private qui va contenir l'instance de notre service
    private $dal;
    // 1er: ON DECLARE LE CONSTRUCTEUR
    public function __construct() // on déclare le constructeur
    {
        // on va surcharger le constructeur de la classe parente WP_List_Table
        // pour redéfinir le nom de la table (singulier et au pluriel)
        parent::__construct(
            array(
                "singular" => __("Point"),
                "plural" => __("Points")
            )
        );
        // on instancie notre service
        $this->dal = new Tp_Wp_Clemence_Database_Service();
    }

    // 2ième: ON SURCHARGE LA METHODE prepare_items()
    public function prepare_items() // fonction du parent pour préparer notre liste
    {
        // on va définir toutes nos variables
        $columns = $this->get_columns(); // on va chercher les colonnes
        $hidden = $this->get_hidden_columns(); // on ajoute cette variable si on veut cacher les colonnes 
        $sortable = $this->get_sortable_columns(); // on ajoute cette variable si on veut trier des colonnes
        // PAGINATION
        $perPage = $this->get_items_per_page("points_per_page", 10); // on va chercher le nombre d'éléments par page
        $currentPage = $this->get_pagenum(); // on va chercher le numéro de la page courante
        // LES DONNÉES
        $data = $this->dal->findAllPoints(); // on va chercher les données dans la base de données
        $totalPage = count($data); // on va compter le nombre de données
        // TRI
        // &$this = pour faire référence à notre classe
        usort($data, array(&$this, "usort_reorder")); // on va trier les données

        $paginationData = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        // on va définir les valeurs de la pagination
        $this->set_pagination_args(
            array(
                "total_items" => $totalPage, // on passe le nombre total d'éléments
                "per_page" => $perPage // on passe le nombre d'éléments par page
            )
        );
        $this->_column_headers = array($columns, $hidden, $sortable); // on construit les entêtes des colonnes
        $this->items = $paginationData; // on alimente les données
    }

    // 3ième : ON SURCHARGE LA MÉTHODE get_columns()
    public function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'label' => 'Résultat',
            'points' => 'Points'
        ];
        return $columns;
    }

    // 4ième : ON SURCHARGE LA MÉTHODE get_hidden_columns()
    public function get_hidden_columns()
    {
        return [];
        // exemple si on veut cacher la colonne id :
        // return ['id' => 'id',];
    }

    // fonction pour le tri
    public function usort_reorder($a, $b)
    {
        // si je passe un paramètre de tri dans l'url
        // sinon on trie par défaut
        $orderBy = (!empty($_GET["orderby"])) ? $_GET["orderby"] : "id";
        // idem pour l'ordre de tri
        $order = (!empty($_GET['order'])) ? $_GET["order"] : "desc";
        $result  = strcmp($a->orderBy, $b->$orderBy); // on compare les 2  valeurs
        return ($order === "asc") ? $result : -$result; // on retourne le résultat si asc sinon on inverse
    }

    // 5ième : ON SURCHARGE LA MÉTHODE column_default()
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'label';
            case 'points':
                return $item->$column_name;
                break;
            default:
                return print_r($item, true);
        }
    }

    // 6ième : ON SURCHARGE LA MÉTHODE get_sortable_columns()
    public function get_sortable_columns()
    {
        $sortable = [
            'label' => ['label', true],
            'points' => ['points', true]
        ];
        return $sortable;
    }

    // 7ième : ON SURCHARGE LA MÉTHODE column_cb()
    public function column_cb($item)
    {
        $item = (array) $item; // on cast l'objet en tableau (pour pouvoir utiliser la méthode sprintf)

        return sprintf(
            '<input type="checkbox" name="delete-points[]" value="%s" />',
            $item["id"]
        );
    }

    // 8ième : ON SURCHARGE LA MÉTHODE get_bulk_actions()
    public function get_bulk_actions()
    {
        $actions = [
            "delete-points" => __("Delete")
        ];
        return $actions;
    }
}




// Liste des points
class Tp_List_Scores extends WP_List_Table
{
    // on va créer une variable en private qui va contenir l'instance de notre service
    private $dal;
    // 1er: ON DECLARE LE CONSTRUCTEUR
    public function __construct() // on déclare le constructeur
    {
        // on va surcharger le constructeur de la classe parente WP_List_Table
        // pour redéfinir le nom de la table (singulier et au pluriel)
        parent::__construct(
            array(
                "singular" => __("Score"),
                "plural" => __("Scores")
            )
        );
        // on instancie notre service
        $this->dal = new Tp_Wp_Clemence_Database_Service();
    }

    // 2ième: ON SURCHARGE LA METHODE prepare_items()
    public function prepare_items() // fonction du parent pour préparer notre liste
    {
        // on va définir toutes nos variables
        $columns = $this->get_columns(); // on va chercher les colonnes
        $hidden = $this->get_hidden_columns(); // on ajoute cette variable si on veut cacher les colonnes 
        $sortable = $this->get_sortable_columns(); // on ajoute cette variable si on veut trier des colonnes
        // PAGINATION
        $perPage = $this->get_items_per_page("scores_per_page", 10); // on va chercher le nombre d'éléments par page
        $currentPage = $this->get_pagenum(); // on va chercher le numéro de la page courante
        // LES DONNÉES
        $data = $this->dal->findAllScores(); // on va chercher les données dans la base de données
        $totalPage = count($data); // on va compter le nombre de données
        // TRI
        // &$this = pour faire référence à notre classe
        usort($data, array(&$this, "usort_reorder")); // on va trier les données

        $paginationData = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        // on va définir les valeurs de la pagination
        $this->set_pagination_args(
            array(
                "total_items" => $totalPage, // on passe le nombre total d'éléments
                "per_page" => $perPage // on passe le nombre d'éléments par page
            )
        );
        $this->_column_headers = array($columns, $hidden, $sortable); // on construit les entêtes des colonnes
        $this->items = $paginationData; // on alimente les données
    }

    // 3ième : ON SURCHARGE LA MÉTHODE get_columns()
    public function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'nomresultat' => 'Résultat',
            'points' => 'Points',
            'surnom' => 'Joueur',
            'date' => 'Date du Match',
            'group' => 'Poule/Groupe',
            'competition' => 'Compétition'
        ];
        return $columns;
    }

    // 4ième : ON SURCHARGE LA MÉTHODE get_hidden_columns()
    public function get_hidden_columns()
    {
        return [];
        // exemple si on veut cacher la colonne id :
        // return ['id' => 'id',];
    }

    // fonction pour le tri
    public function usort_reorder($a, $b)
    {
        // si je passe un paramètre de tri dans l'url
        // sinon on trie par défaut
        $orderBy = (!empty($_GET["orderby"])) ? $_GET["orderby"] : "id";
        // idem pour l'ordre de tri
        $order = (!empty($_GET['order'])) ? $_GET["order"] : "desc";
        $result  = strcmp($a->orderBy, $b->$orderBy); // on compare les 2  valeurs
        return ($order === "asc") ? $result : -$result; // on retourne le résultat si asc sinon on inverse
    }

    // 5ième : ON SURCHARGE LA MÉTHODE column_default()
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'nomresultat';
            case 'points';
            case 'surnom';
            case 'date';
            case 'group';
            case 'competition':
                return $item->$column_name;
                break;
            default:
                return print_r($item, true);
        }
    }

    // 6ième : ON SURCHARGE LA MÉTHODE get_sortable_columns()
    public function get_sortable_columns()
    {
        $sortable = [
            'nomresultat' => ['nomresultat', true],
            'points' => ['points', true],
            'surnom' => ['surnom', true],
            'date' => ['date', true],
            'group' => ['group', true],
            'competition' => ['competition', true]
        ];
        return $sortable;
    }

    // 7ième : ON SURCHARGE LA MÉTHODE column_cb()
    public function column_cb($item)
    {
        $item = (array) $item; // on cast l'objet en tableau (pour pouvoir utiliser la méthode sprintf)

        return sprintf(
            '<input type="checkbox" name="delete-scores[]" value="%s" />',
            $item["id"]
        );
    }

    // 8ième : ON SURCHARGE LA MÉTHODE get_bulk_actions()
    public function get_bulk_actions()
    {
        $actions = [
            "delete-scores" => __("Delete")
        ];
        return $actions;
    }
}
