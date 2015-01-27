<?php
/**
 * @project wp-frontend-analytics
 * @author thuytien
 * @date 01/23/2015
 */
if(!class_exists('WRS_Admin')) {
    class WRS_Admin extends scbBoxesPage
    {
        private $profile_fields;
        function setup() {
            $this->args = array(
                'page_title' => 'Remote Syns',
                'capability' => 'manage_options',
                'action_link' => "Settings"
            );

            $this->boxes = array(
                array( 'settins', 'Profile Setting', 'normal' ),
                array( 'info', 'API settings', 'side' ),
            );
            $this->profile_fields = array(
                array(
                    'title' => 'Endpoint',
                    'type' => 'text',
                    'name' => 'endpoint',
                    'value' =>''
                ),
                array(
                    'title' => 'Username',
                    'type' => 'text',
                    'name' => 'username',
                    'value'=>''
                ),
                array(
                    'title' => 'Password',
                    'type' => 'text',
                    'name' => 'password',
                    'value' => '',
                ),
                array(
                    'title' => 'Category',
                    'type' => 'text',
                    'name' => 'category_id',
                ),
                );
        }

        function settins_box() {
            echo $this->form_table($this->profile_fields);
        }
        function settins_handler()
        {
            $to_update = scbForms::validate_post_data($this->profile_fields);
            $this->options->update($to_update);
            add_action( 'admin_notices', array( $this, 'admin_msg' ) );
            return true;
        }
        function info_box() {
            echo "pass";

        }


    }
}