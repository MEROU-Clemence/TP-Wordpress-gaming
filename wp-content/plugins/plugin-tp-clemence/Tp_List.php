<?php
// dans certaines versions de WP il n'arrive pas à étendre la classe WP_List_Table
// pour ce cas, il faut charger manuellement cette classe
if (!class_exists("WP_List_Table")) {
    require_once ABSPATH . "wp-admin/includes/class-wp-list-table.php";
}

// on importe notre classe de Service
require_once plugin_dir_path(__FILE__) . "service/Tp_Wp_Clemence_Database_service.php";

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
            'competition' => 'Compétition numéro'
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
    public function usort_recorder($a, $b)
    {
        // si je passe un paramètre de tri dans l'url
        // sinon on trie par défaut
        $orderBy = (!empty($_GET["orderby"])) ? $_GET["orderby"] : "id";
        // idem pour l'ordre de tri
        $order = (!empty($_GET['order'])) ? $_GET["order"] : "desc";
        $result  = strcmp($a->orderBy, $b - $orderBy); // on compare les 2  valeurs
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
            'id' => ['id', true],
            'nom' => ['nom', true],
            'prenom' => ['prenom', true],
            'surnom' => ['surnom', true],
            'email' => ['email', true],
            'competition' => ['competition', true]
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
