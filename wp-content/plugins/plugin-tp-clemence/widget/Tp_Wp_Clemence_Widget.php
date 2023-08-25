<?php
class Tp_Wp_Clemence_Widget extends WP_Widget // on étend la classe WP_Widget
{
    // on surcharge son constructor
    public function __construct()
    {
        // on déclare une variable avec les options du widget
        $widget_ops = array( // on ajoute des options au widget
            // ajout d'une classe css
            "className" => "ern_random_photo",
            // ajout d'une description
            "description" => __("Show the big rando of pictures !"), // on traduit le texte
            // pour éviter de rafraîchir la fenêtre du navigateur
            'customize_selective_refresh' => true
        );
        // on surcharge le constructeur de la classe parent
        parent::__construct(
            // on donne un nom au widget
            'photos',
            // on donne un titre au widget
            __("Random photo"),
            // on lui donne des options
            $widget_ops
        );
    }

    // création du formulaire pour le back office
    public function form($instance)
    {
        // création d'un tableau de valeurs par défaut
        // wp_parse_args permet de fusionner les valeurs dans un tableau
        $instance = wp_parse_args((array) $instance, [
            "query" => "",
            "nbr" => "",
            "cle" => ""
        ]);
?>
        <!-- Création de nos inputs -->
        <div>
            <label for="<?= $this->get_field_id('query') ?>">Mot de recherche</label>
            <input type="text" name="<?= $this->get_field_name('query') ?>" id="<?= $this->get_field_id('query') ?>" value="<?= esc_attr($instance['query']) ?>">
        </div>
        <div>
            <label for="<?= $this->get_field_id('nbr') ?>">Nombre de photos</label>
            <input type="text" name="<?= $this->get_field_name('nbr') ?>" id="<?= $this->get_field_id('nbr') ?>" value="<?= esc_attr($instance['nbr']) ?>">
        </div>
        <div>
            <label for="<?= $this->get_field_id('cle') ?>">Clé Unsplash</label>
            <input type="text" name="<?= $this->get_field_name('cle') ?>" id="<?= $this->get_field_id('cle') ?>" value="<?= esc_attr($instance['cle']) ?>">
        </div>
<?php
    }

    // création du formulaire de la fonction update pour modifier les valeurs du formulaire et générer d'autres images
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        // sanitize_text_field permet de nettoyer les données
        $instance['query'] = sanitize_text_field($new_instance['query']);
        $instance['nbr'] = sanitize_text_field($new_instance['nbr']);
        $instance['cle'] = sanitize_text_field($new_instance['cle']);

        return $instance;
    }

    // création de la fonction widget pour afficher les images
    public function widget($args, $instance)
    {
        // on definit le titre
        $title = "Photos";
        // nombre de photos minimum
        ($instance['nbr'] != 0) ? $nbr = $instance['nbr'] : $nbr = 1;
        // construction de l'url de l'API unsplash
        $url = "https://api.unsplash.com/search/photos?query=" . $instance['query'] . "&per_page=" . $nbr;

        // configuration des headers pour autoriser la consommation de l'API
        $argCle = [
            'headers' => [
                'Authorization' => 'Client-ID ' . $instance['cle']
            ]
        ];

        // on fait l'appel à l'API grace à wp_remote_get
        $request = wp_remote_get($url, $argCle);
        // gestion d'erreur de retour
        if (is_wp_error($request)) {
            return false;
        }
        // si ok, on récupère le body de la réponse sous forme de json
        $body = wp_remote_retrieve_body($request);
        // on décode le json
        $data = json_decode($body, true);

        // construction du rendu HTML pour afficher les images

        echo $args['before_widget'];
        // on affiche le titre
        echo $args['before_title'] . $title . $args['after_title'];
        echo "<div class='photo'>";
        if (!empty($data)) {
            for ($i = 0; $i < $nbr; $i++) {
                echo "<p>" . $data['results'][$i]['id'] . "</p>";
                echo "<img src='" . $data['results'][$i]['urls']['thumb'] . "' alt'" . $data['results'][$i]['description'] . "'/>";
            }
        }
        echo "</div>";

        echo $args['after_widget'];
        return '';
    }
}
