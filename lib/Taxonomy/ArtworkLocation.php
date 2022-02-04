<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Taidemuseo\Taxonomy;

use \TMS\Theme\Base\Interfaces\Taxonomy;
use TMS\Theme\Taidemuseo\PostType\Artwork;
use TMS\Theme\Base\Traits\Categories;

/**
 * This class defines the taxonomy.
 *
 * @package TMS\Theme\Base\Taxonomy
 */
class ArtworkLocation implements Taxonomy {

    use Categories;

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'artwork-location';

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                       => 'Sijainnit',
            'singular_name'              => 'Sijainti',
            'menu_name'                  => 'Sijainnit',
            'all_items'                  => 'Kaikki Sijainnit',
            'new_item_name'              => 'Lisää uusi sijainti',
            'add_new_item'               => 'Lisää uusi sijainti',
            'edit_item'                  => 'Muokkaa sijaintia',
            'update_item'                => 'Päivitä sijainti',
            'view_item'                  => 'Näytä sijainti',
            'separate_items_with_commas' => 'Erottele sijainnit pilkulla',
            'add_or_remove_items'        => 'Lisää tai poista sijainti',
            'choose_from_most_used'      => 'Suositut sijainnit',
            'popular_items'              => 'Suositut sijainnit',
            'search_items'               => 'Etsi sijainti',
            'not_found'                  => 'Ei tuloksia',
            'no_terms'                   => 'Ei tuloksia',
            'items_list'                 => 'Sijainnit',
            'items_list_navigation'      => 'Sijainnit',
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
            'capabilities'      => [
                'manage_terms' => 'manage_artwork_locations',
                'edit_terms'   => 'edit_artwork_locations',
                'delete_terms' => 'delete_artwork_locations',
                'assign_terms' => 'assign_artwork_locations',
            ],
        ];

        register_taxonomy( self::SLUG, [ Artwork::SLUG ], $args );
    }
}
