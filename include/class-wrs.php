<?php

/**
 * @project wp-remote-sync
 * @author  nguyenvanduocit
 * @date    01/26/2015
 */
require_once WRS_PLUGIN_DIR . "/include/WordpressClient.php";

class WRS
{
    private $options;
    private $client;
    static $_instance;

    public static function instance ()
    {
        if ( !isset ( self::$_instance ) ) {
            self::$_instance = new static();
        }

        return self::$_instance;
    }

    function __construct ( $wrs_options = NULL )
    {
        if ( $wrs_options == NULL ) {
            global $wrs_options;
        }
        $this->options = $wrs_options;
        add_action ( 'plugins_loaded', array ( $this, "init_hook" ) );

    }

    public function get_client ()
    {
        if($this->client == null)
        {
            $this->client = new WordpressClient( $this->options->endpoint, $this->options->username, $this->options->password );
        }

        return $this->client;
    }

    public function init_hook ()
    {
        add_action ( 'wp_insert_post', array ( static::instance (), "save_post_sync" ), 10, 3 );
    }

    public function save_post_sync ( $post_id, $post, $update )
    {
        if ( ($post->post_status == 'publish') && ($post->post_type == 'post') ) {
            $client = $this->get_client ();

            $post_array = array (
                'post_excerpt' => $post->post_excerpt,
                'custom_fields' => array ( array ( 'key' => WRS_REMOTE_METAKEY, 'value' => $post_id ) ),
                'terms' => array('category' => array($this->options->category_id))
            );

            $thumbnail_id = $this->upload_media ( $post_id );
            if(FALSE != $thumbnail_id){
                $post_array['post_thumbnail'] = $thumbnail_id;
            }
            $remote_id = $client->newPost ( $post->post_title, $post->post_content, $post_array );
            if ( is_numeric ( $remote_id ) && ( $remote_id > 0 ) ) {
                update_post_meta ( $post_id, WRS_REMOTE_METAKEY, $remote_id );
            }
        }
    }

    public function upload_media ( $post_id )
    {
        $post_thumbnail_id = get_post_thumbnail_id( $post_id );
        $file_upload_path = get_attached_file ( $post_thumbnail_id );
        if ( FALSE == $file_upload_path ) {
            return FALSE;
        }
        $client = $this->get_client ();
        $content = file_get_contents ( $file_upload_path );
        $mime = mime_content_type ( $file_upload_path );
        $filename = basename ( $file_upload_path );
        $file = $client->uploadFile ( $filename, $mime, $content );
        return $file[ 'id' ];
    }
}