<?php

namespace Config;

class information
{
    public static function reservationRulls(){
      return [
        'days' => [
          'Thu', 'Wed', 'Tue', 'Fri', 'Sat'
        ],
        'dayClose' => [
          'Mon', 'Sun'
        ],
        'min_hours' => 12,
        'max_hours' => 17,
        'reservation_duration' => 1,
        'reservation_limit' => 2,
      ];
    }

    public static function establishmentInfos()
    {
      return [
        'name' => 'V.Hive',
        'description' => "
          Le V.Hive est un bâtiment aux multiples facettes.<br>
          Servant avant tout de base pour l'équipe e-sportive Vitality, il est également ouvert au public et propose de nombreux services.<br>
          Parmi ses nombreux services, on peut citer entre autres : 
          <ul>
            <li>La présence d'un café</li>
            <li>Une boutique où l'on peut retrouver des produits dérivés de la Team Vitality (maillots, casquettes, ...)</li>
            <li>Des salles équipées pour le gaming ainsi que des salles prévues pour des Bootcamp</li>
          </ul>
          Le V.Hive est donc un lieu de rencontre et de rapprochement entre la Team Vitality et sa communauté.<br>
          En plus des services énumérés ci-dessus, divers événements sont organisés au sein du V.Hive (soirée de présentation, événements nationaux, master classes, ...)
          ",
        'adress' => '102 Boulevard de Sébastopol, 75003 Paris',
        'googlemapsLocation' => [
          'lat' => 49.089602278815796,
          'lng' => 2.171276729296133
        ],
        'images' => '',
        'schedule' => [
          'days' => [
            'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'
          ],
          'min-hours' => 12,
          'max-hours' => 17,
        ],
      ];
    }
}
