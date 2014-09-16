<?php
/**
 * To use include cuztom.php and your metabox file in functions.php of your theme
 * For example:
 *
 * include( TEMPLATEPATH . '/includes/cuztom/cuztom.php' );
 *
 * //Add edition specific metaboxes
 * require_once( TEMPLATEPATH . '/includes/metaboxes/example.php' );
 *
 *
 * metaboxes-example.php
 * Provides custom metabox support for edition toggle displayed page data entry
 * Only shows on page post_type
 *
 **/

$heading = 'Example metaboxes';
$this_id = 'page_data';
$metabox_required_templates = array(
    'custom-post-template.php' => array(
        'page_data', 
        'another_block_id'
    ), 
    'another-custom-post-template.php' => array(
        'page_data_2', 
        'a_second_block_id'
    )
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
 * Hide / show the metabox when needed
 */
function check_admin_metabox_display()
{
    global $post, $metabox_required_templates;

    if( $post && !empty( $metabox_required_templates ) )
    {
        foreach( $metabox_required_templates as $template => $template_ids )
        {
            if( is_array( $template_ids ) )
            {
                $ids = array();
                foreach( $template_ids as $i )
                {
                    $ids[] = '#'. $i;
                }
                $this_id = implode( ', ', $ids );
            }
            else
            {
                $this_id = '#'. $template_ids;
            }
            echo '<script>
            (function( $ ) {
            "use strict";

            $(function() {
                var metabox_templates = ["'.$template.'"];
                $(\'#page_template\').change(function() {
                    $(\''.$this_id.'\' ).toggle((metabox_templates.indexOf($(this).val()) > -1));
                }).change();
            });

            }(jQuery));</script>';

            if( get_post_meta( $post->ID, '_wp_page_template', true ) != $template )
            {
                echo '<style>'.$this_id.'{ display:none; }</style>';
            }
            
        } //end foreach
        
    } //end if $post and $metabox_required_templates
}
add_action( 'admin_notices', 'check_admin_metabox_display' );