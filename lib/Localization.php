<?php

namespace TMS\Theme\Taidemuseo;

use TMS\Theme\Taidemuseo\Taxonomy\ArtistCategory;
use TMS\Theme\Taidemuseo\Taxonomy\ArtworkLocation;
use TMS\Theme\Taidemuseo\Taxonomy\ArtworkType;

/**
 * Class Localization
 *
 * @package TMS\Theme\Sara_Hilden
 */
class Localization extends \TMS\Theme\Base\Localization implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Load theme translations.
     */
    public function load_theme_textdomains() {
        \load_theme_textdomain(
            'tms-theme-base',
            get_template_directory() . '/lang'
        );

        \load_child_theme_textdomain(
            'tms-theme-taidemuseo',
            get_stylesheet_directory() . '/lang'
        );
    }

    /**
     * This adds the CPTs that are not public to Polylang translation.
     *
     * @param array   $post_types  The post type array.
     * @param boolean $is_settings A not used boolean flag to see if we're in settings.
     *
     * @return array The modified post_types -array.
     */
    protected function add_cpts_to_polylang( $post_types, $is_settings ) { // phpcs:ignore
        if ( ! DPT_PLL_ACTIVE ) {
            return $post_types;
        }

        $post_types[ PostType\Artist::SLUG ] = PostType\Artist::SLUG;

        // Coming soon:
        // $post_types[ PostType\Exhibition::SLUG ] = PostType\Exhibition::SLUG;
        $post_types[ PostType\Artwork::SLUG ] = PostType\Artwork::SLUG;

        return $post_types;
    }

    /**
     * This adds the taxonomies that are not public to Polylang translation.
     *
     * @param array   $tax_types   The taxonomy type array.
     * @param boolean $is_settings A not used boolean flag to see if we're in settings.
     *
     * @return array The modified tax_types -array.
     */
    protected function add_tax_to_polylang( $tax_types, $is_settings ) : array { // phpcs:ignore
        $tax_types[ ArtistCategory::SLUG ]  = ArtistCategory::SLUG;
        $tax_types[ ArtworkLocation::SLUG ] = ArtworkLocation::SLUG;
        $tax_types[ ArtworkType::SLUG ]     = ArtworkType::SLUG;

        return $tax_types;
    }
}
