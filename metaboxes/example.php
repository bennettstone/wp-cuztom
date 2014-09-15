<?php
/**
 * To use include cuztom.php and your metabox file in functions.php of your theme
 * For example:
 *
 * include( TEMPLATEPATH . '/includes/cuztom/cuztom.php' );
 *
 * //Add edition specific metaboxes
 * require_once( TEMPLATEPATH . '/includes/metaboxes/edition-metaboxes-2.php' );
 *
 *
 * metaboxes-example.php
 * Provides custom metabox support for edition toggle displayed page data entry
 * Only shows on page post_type
 *
 **/

$heading = 'Example metaboxes';
$this_id = 'page_data';
$required_templates = array(
    'custom-post-template.php', 
    'another-custom-post-template.php'
);

$custom_metaboxes = new Cuztom_Post_Type( 'page' );
    
$custom_metabox_fields = array(
    array(
        'label'    => 'Metabox 1', // <label>
        'name'    => 'metabox_one_id', // field id and name
        'type'    => 'text' // type of field
    ),
    array(
        'label'    => 'Metabox 2', // <label>
        'name'    => 'metabox_two_id', // field id and name
        'type'    => 'textarea' // type of field
    )
);

$custom_metaboxes->add_meta_box( 
    $this_id,
    $heading, 
    $custom_metabox_fields
);

/*
 * Hide / show the Price table metabox when needed
 */
function check_admin_metabox_display()
{
    global $post, $required_templates, $this_id;

    if( $post && !empty( $required_templates ) )
    {
        echo '<script>
        (function( $ ) {
        "use strict";

        $(function() {
            var metabox_templates = ["'.implode( '", "', $required_templates ).'"];
            $(\'#page_template\').change(function() {
                $(\'#'.$this_id.'\' ).toggle((metabox_templates.indexOf($(this).val()) > -1));
            }).change();
        });

        }(jQuery));</script>';
        
        if( !in_array( get_post_meta( $post->ID, '_wp_page_template', true ), $required_templates ) )
        {
            echo '<style>#'.$this_id.'{ display:none; }</style>';
        }
    }
}
add_action( 'admin_notices', 'check_admin_metabox_display' );