<?php
/*
Plugin Name: LeadElephant for Wordpress
Plugin URI: http://www.leadelephant.com
Description: Plugin for integrating LeadElephant into your website.
Author: LeadElephant
Version: 1.0.0
Author URI: http://www.leadelephant.com
*/

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}

if (!class_exists( 'leTrackingCode' )){

    class leTrackingCode {

        function __construct(){ 
            add_action( 'admin_menu', array( $this, 'menuItem' ) );
            add_action( 'wp_footer',  array( $this, 'leFooter' ) );
        }

        function menuItem(){
            $page = add_submenu_page( 'options-general.php', 'LeadElephant Tracking Code', 'LeadElephant', 'manage_options', 'leadelephant-tracking-code', array( $this, 'init' ));
        }

        function init(){
            if( isset($_REQUEST['save']) ){
                update_option( 'leadelephant_tracking_code', $_REQUEST );
                $message = "Tracking code succesvol opgeslagen.";
            }

            $data = get_option('leadelephant_tracking_code');
            $this->form( $data, @$message );
        }

        function leFooter(){
            $data = get_option('leadelephant_tracking_code');
            if( isset( $data['tracking_footer']['enable'] ) )
                echo stripcslashes($data['tracking_footer']['code']) . "\n\n";
        }

        function form( $data, $message = null ){ ?>

            <div class="wrap">

                <form method="post" action="">

                    <h2>LeadElephant Tracking Code</h2>
                    <?php if( $message ) : ?>
                        <br /><div class="updated"><p><strong><?php echo $message; ?></strong></p></div>
                    <?php endif; ?>

                    <?php if (!@$data['tracking_footer']['code']){ ?>
                    <br /><div class="updated" style="border-color: #2ea2cc;"><p><strong>Nog geen tracking code? Ga naar <a target="_blank" href="http://www.leadelephant.com/?utm_source=wordpress&utm_medium=link&utm_campaign=leadelephant-plugin">LeadElephant.com</a> en meld je aan.</strong></p></div>
                    <?php } ?>

                    <table class="form-table">
                      <tbody>
                        <tr>
                        	<th><label for="tracking_code">Tracking Code:</label></th>
                        	<td><textarea class="large-text code" style="padding: 10px; height: 320px;" id="tracking_code" name="tracking_footer[code]"><?php echo stripcslashes( @$data['tracking_footer']['code'] ); ?></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="tracking_code_active">Activeren:</label></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text"><span>Activeren</span></legend>
                                    <label for="tracking_code_active">
                                    <input id="tracking_code_active" type="checkbox" name="tracking_footer[enable]" <?php checked( @$data['tracking_footer']['enable'], 'on' ); ?> /> Voeg tracking code toe aan elke pagina.
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                      </tbody>
                    </table>

                    <p><input class="button-primary" type="submit" name="save" value="Wijzigingen opslaan"/></p>

                </form>

            </div>

        <?php

        }

    }
}

global $leTrackingCode;

$leTrackingCode = new leTrackingCode;

?>