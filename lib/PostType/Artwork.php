<?php

namespace TMS\Theme\Taidemuseo\PostType;

use Closure;
use Geniem\RediPress\Entity\TextField;
use TMS\Theme\Base\Interfaces\PostType;
use TMS\Theme\Base\Settings;
use WP_Post;

/**
 * Artwork CPT
 *
 * @package TMS\Theme\Taidemuseo\PostType
 */
class Artwork implements PostType {

    /**
     * This defines the slug of this post type.
     */
    public const SLUG = 'artwork';

    /**
     * This defines what is shown in the url. This can
     * be different than the slug which is used to register the post type.
     *
     * @var string
     */
    private $url_slug = 'artwork';

    /**
     * Define the CPT description
     *
     * @var string
     */
    private $description = '';

    /**
     * This is used to position the post type menu in admin.
     *
     * @var int
     */
    private $menu_order = 5;

    /**
     * This defines the CPT icon.
     *
     * @var string
     */
    private $icon = 'dashicons-art';

    /**
     * Constructor
     */
    public function __construct() {
        $this->description = _x( 'Artwork', 'theme CPT', 'tms-theme-taidemuseo' );
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', Closure::fromCallable( [ $this, 'register' ] ), 15 );
        add_filter( 'tms/gutenberg/blocks', Closure::fromCallable( [ $this, 'allowed_blocks' ] ), 10, 1 );

        add_filter(
            'tms/base/breadcrumbs/before_prepare',
            Closure::fromCallable( [ $this, 'format_single_breadcrumbs' ] ),
            10,
            3
        );

        add_filter( 'redipress/schema_fields', function ( $fields ) {
            $fields[] = new TextField( [
                'name'     => 'artists',
                'sortable' => true,
            ] );

            return $fields;
        }, PHP_INT_MAX, 1 );

        add_filter( 'redipress/additional_field/artists', function ( $value, $post_id, $post ) {
            if ( $post->post_type === Artwork::SLUG ) {
                $value = get_post_meta( $post_id, 'artists', true );
            }

            return $value;
        }, 10, 3 );

        add_filter( 'redipress/search_fields', function ( $fields ) {
            $fields[] = 'artists';

            return $fields;
        } );
    }

    /**
     * Get post type slug
     *
     * @return string
     */
    public function get_post_type() : string {
        return static::SLUG;
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                  => _x( 'Artwork', 'theme CPT', 'tms-theme-taidemuseo' ),
            'singular_name'         => 'Taideteos',
            'menu_name'             => 'Teokset',
            'name_admin_bar'        => 'Teokset',
            'archives'              => 'Arkistot',
            'attributes'            => 'Ominaisuudet',
            'parent_item_colon'     => 'Vanhempi:',
            'all_items'             => 'Kaikki',
            'add_new_item'          => 'Lisää uusi',
            'add_new'               => 'Lisää uusi',
            'new_item'              => 'Uusi',
            'edit_item'             => 'Muokkaa',
            'update_item'           => 'Päivitä',
            'view_item'             => 'Näytä',
            'view_items'            => 'Näytä kaikki',
            'search_items'          => 'Etsi',
            'not_found'             => 'Ei löytynyt',
            'not_found_in_trash'    => 'Ei löytynyt roskakorista',
            'featured_image'        => 'Kuva',
            'set_featured_image'    => 'Aseta kuva',
            'remove_featured_image' => 'Poista kuva',
            'use_featured_image'    => 'Käytä kuvana',
            'insert_into_item'      => 'Aseta julkaisuun',
            'uploaded_to_this_item' => 'Lisätty tähän julkaisuun',
            'items_list'            => 'Listaus',
            'items_list_navigation' => 'Listauksen navigaatio',
            'filter_items_list'     => 'Suodata listaa',
        ];

        $rewrite = [
            'slug'       => $this->url_slug,
            'with_front' => false,
            'pages'      => true,
            'feeds'      => true,
        ];

        $args = [
            'label'           => $labels['name'],
            'description'     => '',
            'labels'          => $labels,
            'supports'        => [
                'title',
                'thumbnail',
                'excerpt',
                'editor',
            ],
            'hierarchical'    => false,
            'public'          => true,
            'menu_position'   => $this->menu_order,
            'menu_icon'       => $this->icon,
            'show_in_menu'    => true,
            'show_ui'         => true,
            'can_export'      => true,
            'has_archive'     => false,
            'rewrite'         => $rewrite,
            'show_in_rest'    => true,
            'capability_type' => 'artwork',
            'map_meta_cap'    => true,
        ];

        register_post_type( static::SLUG, $args );
    }

    /**
     * Set allowed blocks.
     *
     * @param array $blocks Block list.
     */
    public function allowed_blocks( $blocks ) {
        $allowed_blocks = [
            'acf/image',
            'acf/video',
            'acf/material',
            'acf/quote',
            'acf/map',
        ];

        foreach ( $allowed_blocks as $block ) {
            $blocks[ $block ]['post_types'][] = self::SLUG;
        }

        return $blocks;
    }

    /**
     * Get archive breadcrumbs base.
     *
     * @param false $is_cpt_archive Defines if cpt archive link is active.
     *
     * @return array[]
     */
    private function get_breadcrumbs_base( $is_cpt_archive = false ) : array {
        $breadcrumbs = [
            'home' => [
                'title'     => _x( 'Home', 'Breadcrumbs', 'tms-theme-taidemuseo' ),
                'permalink' => trailingslashit( get_home_url() ),
                'icon'      => '',
            ],
        ];

        $archive_page_id = Settings::get_setting( 'artwork_archive_page' );

        if ( ! empty( $archive_page_id ) ) {
            $breadcrumbs[] = [
                'title'     => get_the_title( $archive_page_id ),
                'permalink' => get_permalink( $archive_page_id ),
                'icon'      => false,
                'is_active' => $is_cpt_archive,
            ];
        }

        return $breadcrumbs;
    }

    /**
     * Format single view breadcrumbs.
     *
     * @param array  $breadcrumbs  Default breadcrumbs.
     * @param string $current_type Post type.
     * @param string $current_id   Current post ID.
     *
     * @return array[]
     */
    public function format_single_breadcrumbs( $breadcrumbs, $current_type, $current_id ) {
        if ( $current_type !== self::SLUG ) {
            return $breadcrumbs;
        }

        $breadcrumbs   = $this->get_breadcrumbs_base();
        $breadcrumbs[] = [
            'title'     => get_the_title( $current_id ),
            'permalink' => false,
            'icon'      => false,
            'is_active' => true,
        ];

        return $breadcrumbs;
    }
}
