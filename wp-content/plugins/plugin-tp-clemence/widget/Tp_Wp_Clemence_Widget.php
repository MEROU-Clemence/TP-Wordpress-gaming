<?php
class Tp_Wp_Clemence_Widget extends WP_Widget // on étend la classe WP_Widget
{
    // on surcharge son constructor
    public function __construct()
    {
        // on déclare une variable avec les options du widget
        $widget_ops = array( // on ajoute des options au widget
            // ajout d'une classe css
            "className" => "Tp_widget_clemence",
            // ajout d'une description
            "description" => __("Widget pour compétition !"), // on traduit le texte
            // pour éviter de rafraîchir la fenêtre du navigateur
            'customize_selective_refresh' => true
        );
        // on surcharge le constructeur de la classe parent
        parent::__construct(
            // on donne un nom au widget
            'widget compet',
            // on donne un titre au widget
            __("Widget compétition"),
            // on lui donne des options
            $widget_ops
        );
    }
}
