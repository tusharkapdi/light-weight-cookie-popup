<?php
/*
Plugin Name: Light Weight Cookie Popup
Plugin URI: http://amplebrain.com/light-weight-cookie-popup/
Description: Light Weight Cookie Popup allows you to inform to users that your site uses cookies and to comply with the EU cookie law regulations.
Version: 1.0.0
Author: Tushar Kapdi
Author URI: http://amplebrain.com/
Text Domain: lwcp
Domain Path: /languages/
Copyright: 2018 Tushar Kapdi
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Light Weight Cookie Popup is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Light Weight Cookie Popup is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Light Weight Cookie Popup. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * LWCP_Congiguration class.
 */
class LWCP_Configuration
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        //hooks
        register_deactivation_hook( __FILE__, array( $this, 'LWCP_deactivate_hook' ) );

        //variables
        $this->options = get_option( 'lwcp' );

        //actions
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'LWCP_admin_enqueue_scripts' ) );
        
        add_action( 'wp_enqueue_scripts', array( $this, 'LWCP_enqueue_scripts_and_styles' ) );
        add_action( 'wp_footer', array( $this, 'LWCP_script' ), 100 );

        //filters
        add_filter( 'plugin_row_meta', array( $this, 'LWCP_plugin_row_meta' ), 10, 2 );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            __('Settings Admin', 'lwcp'), 
            __('Light Weight Cookie Popup', 'lwcp'), 
            'manage_options', 
            'lwcp', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        //$this->options = get_option( 'lwcp' );
        ?>
        <div class="wrap">
            <h1><?php echo __('Light Weight Cookie Popup', 'lwcp');?></h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'lwcp_group' );
                do_settings_sections( 'lwcp' );
                submit_button();
            ?>
            </form><script type="text/javascript">jQuery(document).ready(function($){$('.color-picker').wpColorPicker();});</script>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'lwcp_group', // Option group
            'lwcp', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            __('Settings', 'lwcp'), // Title
            array( $this, 'print_section_info' ), // Callback
            'lwcp' // Page
        );  

        add_settings_field(
            'popup_content', 
            __('Message', 'lwcp'), 
            array( $this, 'content_callback' ), 
            'lwcp', 
            'setting_section_id'
        );

        add_settings_field(
            'expires', // ID
            __('Cookie Expiry', 'lwcp'),
            array( $this, 'expires_callback' ),
            'lwcp', 
            'setting_section_id' 
        );

        add_settings_field(
            'country',
            __('Country', 'lwcp'),
            array( $this, 'country_callback' ),
            'lwcp', 
            'setting_section_id' 
        );

        add_settings_field(
            'position', 
            __('Popup Position', 'lwcp'), 
            array( $this, 'position_callback' ), 
            'lwcp', 
            'setting_section_id' 
        );

        add_settings_field(
            'padding', 
            __('Popup Padding', 'lwcp'), 
            array( $this, 'padding_callback' ), 
            'lwcp', 
            'setting_section_id' 
        );

        add_settings_field(
            'bg', 
            __('Popup Background Color', 'lwcp'), 
            array( $this, 'bg_callback' ), 
            'lwcp', 
            'setting_section_id'
        );

        add_settings_field(
            'color', 
            __('Popup Text Color', 'lwcp'), 
            array( $this, 'color_callback' ), 
            'lwcp', 
            'setting_section_id'
        );

        add_settings_field(
            'font', 
            __('Popup Font Size', 'lwcp'), 
            array( $this, 'font_callback' ), 
            'lwcp', 
            'setting_section_id'
        );  

        add_settings_field(
            'is_close', 
            __('Hide Close Button', 'lwcp'), 
            array( $this, 'is_close_callback' ),
            'lwcp', 
            'setting_section_id'       
        );

        add_settings_field(
            'close', 
            __('Close Button Text', 'lwcp'), 
            array( $this, 'close_callback' ), 
            'lwcp', 
            'setting_section_id'         
        );

        add_settings_field(
            'is_button', 
            __('Hide Accept Button', 'lwcp'), 
            array( $this, 'is_button_callback' ), 
            'lwcp', 
            'setting_section_id' 
        );

        add_settings_field(
            'button', 
            __('Accept Button Text', 'lwcp'),
            array( $this, 'button_callback' ), 
            'lwcp', 
            'setting_section_id' 
        );   

        add_settings_field(
            'button-bg', 
            __('Accept Button Background Color', 'lwcp'), 
            array( $this, 'button_bg_callback' ), 
            'lwcp', 
            'setting_section_id'
        );

        add_settings_field(
            'button-color', 
            __('Accept Button Text Color', 'lwcp'), 
            array( $this, 'button_color_callback' ), 
            'lwcp', 
            'setting_section_id'
        );

        add_settings_field(
            'button-class', 
            __('Accept Button Class', 'lwcp'), 
            array( $this, 'button_class_callback' ), 
            'lwcp', 
            'setting_section_id'
        );

        add_settings_field(
            'is_readmore', 
            __('Hide Read More Link', 'lwcp'), 
            array( $this, 'is_readmore_callback' ),
            'lwcp', 
            'setting_section_id' 
        );

        add_settings_field(
            'readmore-color', 
            __('Read More Color', 'lwcp'), 
            array( $this, 'readmore_color_callback' ), 
            'lwcp', 
            'setting_section_id'
        );

        add_settings_field(
            'readmore', 
            __('Read More Text', 'lwcp'), 
            array( $this, 'readmore_callback' ), 
            'lwcp', 
            'setting_section_id' 
        ); 

        add_settings_field(
            'readmore-link', 
            __('Read More Link', 'lwcp'), 
            array( $this, 'readmore_link_callback' ), 
            'lwcp', 
            'setting_section_id' 
        );

        add_settings_field(
            'readmore-target', 
            __('Reamore Link Target', 'lwcp'),
            array( $this, 'readmore_target_callback' ), 
            'lwcp', 
            'setting_section_id' 
        ); 

        add_settings_field(
            'readmore-class', 
            __('Reamore Link class', 'lwcp'), 
            array( $this, 'readmore_class_callback' ), 
            'lwcp', 
            'setting_section_id' 
        );  
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        
        if( isset( $input['position'] ) )
            $new_input['position'] = sanitize_text_field( $input['position'] );

        if( isset( $input['padding'] ) )
            $new_input['padding'] = sanitize_text_field( $input['padding'] );

        if( isset( $input['expires'] ) )
            $new_input['expires'] = sanitize_text_field( $input['expires'] );

        if( isset( $input['popup_content'] ) )
            $new_input['popup_content'] = addslashes( $input['popup_content'] );

        if( isset( $input['country'] ) )
            $new_input['country'] = ( $input['country'] );

        if( isset( $input['bg'] ) )
            $new_input['bg'] = sanitize_text_field( $input['bg'] );

        if( isset( $input['color'] ) )
            $new_input['color'] = sanitize_text_field( $input['color'] );

        if( isset( $input['font'] ) )
            $new_input['font'] = sanitize_text_field( $input['font'] );

        if( isset( $input['is_close'] ) )
            $new_input['is_close'] = sanitize_text_field( $input['is_close'] );

        if( isset( $input['close'] ) )
            $new_input['close'] = sanitize_text_field( $input['close'] );

        if( isset( $input['is_button'] ) )
            $new_input['is_button'] = sanitize_text_field( $input['is_button'] );

        if( isset( $input['button'] ) )
            $new_input['button'] = sanitize_text_field( $input['button'] );

        if( isset( $input['button-bg'] ) )
            $new_input['button-bg'] = sanitize_text_field( $input['button-bg'] );

        if( isset( $input['button-color'] ) )
            $new_input['button-color'] = sanitize_text_field( $input['button-color'] );

        if( isset( $input['button-class'] ) )
            $new_input['button-class'] = sanitize_text_field( $input['button-class'] );

        if( isset( $input['is_readmore'] ) )
            $new_input['is_readmore'] = sanitize_text_field( $input['is_readmore'] );

        if( isset( $input['readmore'] ) )
            $new_input['readmore'] = sanitize_text_field( $input['readmore'] );

        if( isset( $input['readmore-color'] ) )
            $new_input['readmore-color'] = sanitize_text_field( $input['readmore-color'] );

        if( isset( $input['readmore-link'] ) )
            $new_input['readmore-link'] = sanitize_text_field( $input['readmore-link'] );

        if( isset( $input['readmore-target'] ) )
            $new_input['readmore-target'] = sanitize_text_field( $input['readmore-target'] );

        if( isset( $input['readmore-class'] ) )
            $new_input['readmore-class'] = sanitize_text_field( $input['readmore-class'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function position_callback()
    {
        echo '<select id="position" name="lwcp[position]">';
            echo '<option value="bottom" '.( ( $this->options['position'] == 'bottom' ) ? 'selected="selected"' : '' ).'>Bottom</option>';
            echo '<option value="top" '.( ( $this->options['position'] == 'top' ) ? 'selected="selected"' : '' ).'>Top</option>';
        echo '</select>';  
        echo '<div><i>'.__('Select position of popup','lwcp').'</i></div>';
    }

    public function padding_callback()
    {
        printf(
            '<input type="text" id="padding" name="lwcp[padding]" value="%s" placeholder="15px" />',
            isset( $this->options['padding'] ) ? esc_attr( $this->options['padding']) : ''
        );
        echo '<div><i>'.__('Enter padding of popup box. i.e. `15px`, `15px 20px`, `15px 20px 15px 20px`','lwcp').'</i></div>';
    }

    public function expires_callback()
    {
        printf(
            '<input type="text" id="expires" name="lwcp[expires]" value="%s" placeholder="1" />',
            isset( $this->options['expires'] ) ? esc_attr( $this->options['expires']) : ''
        );
        echo '<div><i>'.__('Enter day(s) of cookie expiration. Set 0(zero) to show popup always. Default is 1 day','lwcp').'</i></div>';
    }

    public function content_callback()
    {
        printf(
            '<textarea id="popup_content" name="lwcp[popup_content]" row="20" cols="90">%s</textarea>',
            isset( $this->options['popup_content'] ) ? ( stripslashes( $this->options['popup_content'] ) ) : ''
        );
        echo '<div><i>'.__('Enter the message of popup','lwcp').'</i></div>';
    }

    public function country_callback()
    {
    $json = '{"BD": "Bangladesh", "BE": "Belgium", "BF": "Burkina Faso", "BG": "Bulgaria", "BA": "Bosnia and Herzegovina", "BB": "Barbados", "WF": "Wallis and Futuna", "BL": "Saint Barthelemy", "BM": "Bermuda", "BN": "Brunei", "BO": "Bolivia", "BH": "Bahrain", "BI": "Burundi", "BJ": "Benin", "BT": "Bhutan", "JM": "Jamaica", "BV": "Bouvet Island", "BW": "Botswana", "WS": "Samoa", "BQ": "Bonaire, Saint Eustatius and Saba ", "BR": "Brazil", "BS": "Bahamas", "JE": "Jersey", "BY": "Belarus", "BZ": "Belize", "RU": "Russia", "RW": "Rwanda", "RS": "Serbia", "TL": "East Timor", "RE": "Reunion", "TM": "Turkmenistan", "TJ": "Tajikistan", "RO": "Romania", "TK": "Tokelau", "GW": "Guinea-Bissau", "GU": "Guam", "GT": "Guatemala", "GS": "South Georgia and the South Sandwich Islands", "GR": "Greece", "GQ": "Equatorial Guinea", "GP": "Guadeloupe", "JP": "Japan", "GY": "Guyana", "GG": "Guernsey", "GF": "French Guiana", "GE": "Georgia", "GD": "Grenada", "GB": "United Kingdom", "GA": "Gabon", "SV": "El Salvador", "GN": "Guinea", "GM": "Gambia", "GL": "Greenland", "GI": "Gibraltar", "GH": "Ghana", "OM": "Oman", "TN": "Tunisia", "JO": "Jordan", "HR": "Croatia", "HT": "Haiti", "HU": "Hungary", "HK": "Hong Kong", "HN": "Honduras", "HM": "Heard Island and McDonald Islands", "VE": "Venezuela", "PR": "Puerto Rico", "PS": "Palestinian Territory", "PW": "Palau", "PT": "Portugal", "SJ": "Svalbard and Jan Mayen", "PY": "Paraguay", "IQ": "Iraq", "PA": "Panama", "PF": "French Polynesia", "PG": "Papua New Guinea", "PE": "Peru", "PK": "Pakistan", "PH": "Philippines", "PN": "Pitcairn", "PL": "Poland", "PM": "Saint Pierre and Miquelon", "ZM": "Zambia", "EH": "Western Sahara", "EE": "Estonia", "EG": "Egypt", "ZA": "South Africa", "EC": "Ecuador", "IT": "Italy", "VN": "Vietnam", "SB": "Solomon Islands", "ET": "Ethiopia", "SO": "Somalia", "ZW": "Zimbabwe", "SA": "Saudi Arabia", "ES": "Spain", "ER": "Eritrea", "ME": "Montenegro", "MD": "Moldova", "MG": "Madagascar", "MF": "Saint Martin", "MA": "Morocco", "MC": "Monaco", "UZ": "Uzbekistan", "MM": "Myanmar", "ML": "Mali", "MO": "Macao", "MN": "Mongolia", "MH": "Marshall Islands", "MK": "Macedonia", "MU": "Mauritius", "MT": "Malta", "MW": "Malawi", "MV": "Maldives", "MQ": "Martinique", "MP": "Northern Mariana Islands", "MS": "Montserrat", "MR": "Mauritania", "IM": "Isle of Man", "UG": "Uganda", "TZ": "Tanzania", "MY": "Malaysia", "MX": "Mexico", "IL": "Israel", "FR": "France", "IO": "British Indian Ocean Territory", "SH": "Saint Helena", "FI": "Finland", "FJ": "Fiji", "FK": "Falkland Islands", "FM": "Micronesia", "FO": "Faroe Islands", "NI": "Nicaragua", "NL": "Netherlands", "NO": "Norway", "NA": "Namibia", "VU": "Vanuatu", "NC": "New Caledonia", "NE": "Niger", "NF": "Norfolk Island", "NG": "Nigeria", "NZ": "New Zealand", "NP": "Nepal", "NR": "Nauru", "NU": "Niue", "CK": "Cook Islands", "XK": "Kosovo", "CI": "Ivory Coast", "CH": "Switzerland", "CO": "Colombia", "CN": "China", "CM": "Cameroon", "CL": "Chile", "CC": "Cocos Islands", "CA": "Canada", "CG": "Republic of the Congo", "CF": "Central African Republic", "CD": "Democratic Republic of the Congo", "CZ": "Czech Republic", "CY": "Cyprus", "CX": "Christmas Island", "CR": "Costa Rica", "CW": "Curacao", "CV": "Cape Verde", "CU": "Cuba", "SZ": "Swaziland", "SY": "Syria", "SX": "Sint Maarten", "KG": "Kyrgyzstan", "KE": "Kenya", "SS": "South Sudan", "SR": "Suriname", "KI": "Kiribati", "KH": "Cambodia", "KN": "Saint Kitts and Nevis", "KM": "Comoros", "ST": "Sao Tome and Principe", "SK": "Slovakia", "KR": "South Korea", "SI": "Slovenia", "KP": "North Korea", "KW": "Kuwait", "SN": "Senegal", "SM": "San Marino", "SL": "Sierra Leone", "SC": "Seychelles", "KZ": "Kazakhstan", "KY": "Cayman Islands", "SG": "Singapore", "SE": "Sweden", "SD": "Sudan", "DO": "Dominican Republic", "DM": "Dominica", "DJ": "Djibouti", "DK": "Denmark", "VG": "British Virgin Islands", "DE": "Germany", "YE": "Yemen", "DZ": "Algeria", "US": "United States", "UY": "Uruguay", "YT": "Mayotte", "UM": "United States Minor Outlying Islands", "LB": "Lebanon", "LC": "Saint Lucia", "LA": "Laos", "TV": "Tuvalu", "TW": "Taiwan", "TT": "Trinidad and Tobago", "TR": "Turkey", "LK": "Sri Lanka", "LI": "Liechtenstein", "LV": "Latvia", "TO": "Tonga", "LT": "Lithuania", "LU": "Luxembourg", "LR": "Liberia", "LS": "Lesotho", "TH": "Thailand", "TF": "French Southern Territories", "TG": "Togo", "TD": "Chad", "TC": "Turks and Caicos Islands", "LY": "Libya", "VA": "Vatican", "VC": "Saint Vincent and the Grenadines", "AE": "United Arab Emirates", "AD": "Andorra", "AG": "Antigua and Barbuda", "AF": "Afghanistan", "AI": "Anguilla", "VI": "U.S. Virgin Islands", "IS": "Iceland", "IR": "Iran", "AM": "Armenia", "AL": "Albania", "AO": "Angola", "AQ": "Antarctica", "AS": "American Samoa", "AR": "Argentina", "AU": "Australia", "AT": "Austria", "AW": "Aruba", "IN": "India", "AX": "Aland Islands", "AZ": "Azerbaijan", "IE": "Ireland", "ID": "Indonesia", "UA": "Ukraine", "QA": "Qatar", "MZ": "Mozambique"}';

        $countries = json_decode($json, true);

        echo '<select id="country" name="lwcp[country][]" multiple="multiple" style="height:200px;">';
            echo '<option value="" '.( ( $this->options['country'][0] == '' ) ? 'selected="selected"' : '' ).'>'.__('All Countries', 'lwcp').'</option>';
            foreach ($countries as $key => $value) {
                echo '<option value="'.$key.'" '.( ( in_array($key, $this->options['country']) ) ? 'selected="selected"' : '' ).'>'.$key." - ".$value.'</option>';
            }
        echo '</select>';
        echo '<div><i>'.__('Cookie Popup display in selected country. Hold down the control (ctrl) button to select multiple countries','lwcp')."<br />".__('Note: Free usage of API is limited to 1,000 API requests per day','lwcp')."<br />".__('Please find more inforamation about API here - https://ipinfo.io/developers#rate-limits','lwcp').'</i></div>';
    }

    public function bg_callback()
    {
        printf(
            '<input type="text" name="lwcp[bg]" id="bg" class="lwcp_colorfield color-picker" data-rgba="true" value="%s" />',
            isset( $this->options['bg'] ) ? ( stripslashes( $this->options['bg'] ) ) : ''
        );
        echo '<div><i>'.__('You can use rgba for transparency. i.e. rgba(60,190,150,0.9)','lwcp').'</i></div>';
    }

    public function color_callback()
    {
        printf(
            '<input type="text" name="lwcp[color]" id="color" class="lwcp_colorfield color-picker" data-rgba="true" value="%s" />',
            isset( $this->options['color'] ) ? ( stripslashes( $this->options['color'] ) ) : ''
        );
        echo '<div><i>'.__('Choose text color','lwcp').'</i></div>';
    }

    public function font_callback()
    {
        printf(
            '<input type="text" id="font" name="lwcp[font]" value="%s" placeholder="13px" />',
            isset( $this->options['font'] ) ? esc_attr( $this->options['font']) : ''
        );
        echo '<div><i>'.__('Enter font size of message text. i.e. `13px`','lwcp').'</i></div>';
    }

    public function is_close_callback()
    {

        printf(
            '<input type="checkbox" id="is_close" name="lwcp[is_close]" value="1" %s />',
            isset( $this->options['is_close'] ) ? 'checked="checked"' : ''
        );
        echo '<div><i>'.__('Checked to hide close button. Close button will also accept the cookie.','lwcp').'</i></div>';
    }

    public function close_callback()
    {
        printf(
            '<input type="text" id="close" name="lwcp[close]" value="%s" placeholder="x" />',
            isset( $this->options['close'] ) ? esc_attr( $this->options['close']) : ''
        );
        echo '<div><i>'.__('Enter close button text. Default is `x`','lwcp').'</i></div>';
    }

    public function is_button_callback()
    {

        printf(
            '<input type="checkbox" id="is_button" name="lwcp[is_button]" value="1" %s />',
            isset( $this->options['is_button'] ) ? 'checked="checked"' : ''
        );
        echo '<div><i>'.__('Checked to hide accept button.','lwcp').'</i></div>';
    }

    public function button_callback()
    {
        printf(
            '<input type="text" id="button" name="lwcp[button]" value="%s" placeholder="Ok" />',
            isset( $this->options['button'] ) ? esc_attr( $this->options['button']) : ''
        );
        echo '<div><i>'.__('Enter accept button text. Default is `Ok`','lwcp').'</i></div>';

    }

    public function button_bg_callback()
    {
        printf(
            '<input type="text" name="lwcp[button-bg]" id="button-bg" class="lwcp_colorfield color-picker" data-rgba="true" value="%s" />',
            isset( $this->options['button-bg'] ) ? ( stripslashes( $this->options['button-bg'] ) ) : ''
        );
        echo '<div><i>'.__('Choose accept button background color','lwcp').'</i></div>';
    }

    public function button_color_callback()
    {
        printf(
            '<input type="text" name="lwcp[button-color]" id="button-color" class="lwcp_colorfield color-picker" data-rgba="true" value="%s" />',
            isset( $this->options['button-color'] ) ? ( stripslashes( $this->options['button-color'] ) ) : ''
        );
        echo '<div><i>'.__('Choose accept button font color','lwcp').'</i></div>';
    }

    public function button_class_callback()
    {
        printf(
            '<input type="text" id="button-class" name="lwcp[button-class]" value="%s" placeholder="btn-default" />',
            isset( $this->options['button-class'] ) ? esc_attr( $this->options['button-class']) : ''
        );
        echo '<div><i>'.__('Enter accept button class name','lwcp').'</i></div>';
    }

    public function is_readmore_callback()
    {

        printf(
            '<input type="checkbox" id="is_readmore" name="lwcp[is_readmore]" value="1" %s />',
            isset( $this->options['is_readmore'] ) ? 'checked="checked"' : ''
        );
        echo '<div><i>'.__('Checked to hide readmore link.','lwcp').'</i></div>';
    }

    public function readmore_color_callback()
    {
        printf(
            '<input type="text" name="lwcp[readmore-color]" id="readmore-color" class="lwcp_colorfield color-picker" data-rgba="true" value="%s" />',
            isset( $this->options['readmore-color'] ) ? ( stripslashes( $this->options['readmore-color'] ) ) : ''
        );
        echo '<div><i>'.__('Choose read more font color','lwcp').'</i></div>';
    }

    public function readmore_callback()
    {
        printf(
            '<input type="text" id="readmore" name="lwcp[readmore]" value="%s" placeholder="Read more..." />',
            isset( $this->options['readmore'] ) ? esc_attr( $this->options['readmore']) : ''
        );
        echo '<div><i>'.__('Enter readmore link text. Default is `Read more...`','lwcp').'</i></div>';
    }

    public function readmore_link_callback()
    {
        printf(
            '<input type="text" id="readmore-link" name="lwcp[readmore-link]" value="%s" placeholder="http://www.domain.com/" />',
            isset( $this->options['readmore-link'] ) ? esc_attr( $this->options['readmore-link']) : ''
        );
        echo '<div><i>'.__('Enter readmore link URL. Start with `http(s)://`','lwcp').'</i></div>';
    }

    public function readmore_target_callback()
    {
        echo '<select id="readmore-target" name="lwcp[readmore-target]">';
            echo '<option value="_blank" '.( ( $this->options['readmore-target'] == '_blank' ) ? 'selected="selected"' : '' ).'>_blank</option>';
            echo '<option value="_self" '.( ( $this->options['readmore-target'] == '_self' ) ? 'selected="selected"' : '' ).'>_self</option>';
        echo '</select>';
        echo '<div><i>'.__('Select readmore link target','lwcp').'</i></div>';
    }

    public function readmore_class_callback()
    {
        printf(
            '<input type="text" id="readmore-class" name="lwcp[readmore-class]" value="%s" placeholder="btn-default" />',
            isset( $this->options['readmore-class'] ) ? esc_attr( $this->options['readmore-class']) : ''
        );
        echo '<div><i>'.__('Enter readmore link class name','lwcp').'</i></div>';
    }
    
    /**
     * Load scripts and styles - wp-admin.
     */
    public function LWCP_admin_enqueue_scripts( $hook ) {
    
        if (isset( $_GET['page'] ) && $_GET['page'] == 'lwcp' ) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker');
        }  
    }

    /**
     * Add custom donate link on plugin list page
     *
     * @since 1.0
     */
    public function LWCP_plugin_row_meta( $links, $file ) {

        if ( strpos( $file, 'ab-cookie-popup.php' ) !== false ) {
            $new_links = array(
                'donate' => '<a href="http://amplebrain.com/donate/" target="_blank">Donate</a>'
            );
            
            $links = array_merge( $links, $new_links );
        }
        
        return $links;
    }

    /**
     * Plugin deactivate hook
     *
     * @since 1.0
     */
    public function LWCP_deactivate_hook() {
        
        delete_option( 'lwcp' );
    }

    /**
    * Light Weight Cookie Popup output.
    *
    * @return mixed
    */
    public function LWCP_script()
    {
        if( $_COOKIE['lwcp'] != 'set' ){

            $display = true;
            $lwcp = $this->options;//get_option( 'lwcp' );
            
            if ( isset($lwcp["country"]) && is_array($lwcp["country"]) ) {
                $countries = array_filter( $lwcp["country"], 'strlen' );
                if( count($countries) ){
                    $IPaddress  =  $_SERVER['REMOTE_ADDR'];
                    $details = $this->LWCP_ip_details("$IPaddress");
                    $country = $details->country;

                    if( !in_array($country, $countries) )
                        $display = false;

                    if( empty($country) || $country != '' )
                        $display = true;
                }
            }

            if($display){
            
                $popup_content = stripslashes( trim( $lwcp["popup_content"] ) );
                $popup_content = str_replace(array("\n", "\r"), array('<br />', ''), $popup_content);
                $popup_content = ($popup_content != '') ? $popup_content : __('We use cookies to ensure that we give you the best experience on our website. If you continue to use this site we will assume that you are happy with it.', 'lwcp');

                $fields = apply_filters( 'lwcp_options_args', array(
                    'message'           => $popup_content,
                    'background'        => $lwcp['bg'],
                    'text-color'        => $lwcp['color'],
                    'font-size'         => $lwcp['font'],
                    'position'          => $lwcp['position'],
                    'padding'           => $lwcp['padding'],
                    'hide-close-button' => isset($lwcp['is_close']) ? $lwcp['is_close'] : 0,
                    'close-text'        => $lwcp['close'],
                    'hide-button'       => isset($lwcp['is_button']) ? $lwcp['is_button'] : 0,
                    'button-text'       => $lwcp['button'],
                    'button-bg'         => $lwcp['button-bg'],
                    'button-color'      => $lwcp['button-color'],
                    'button-class'      => $lwcp['button-class'],
                    'hide-readmore'     => isset($lwcp['is_readmore']) ? $lwcp['is_readmore'] : 0,
                    'readmore-text'     => $lwcp['readmore'],
                    'readmore-color'    => $lwcp['readmore-color'],
                    'readmore-link'     => $lwcp['readmore-link'],
                    'readmore-target'   => $lwcp['readmore-target'],
                    'readmore-class'    => $lwcp['readmore-class'],
                ) );
                
                $style = '';
                if( isset($fields['background']) && $fields['background'] != '' )
                    $style .= 'background-color:'.$fields['background'].";";
                if( isset($fields['text-color']) && $fields['text-color'] != '' )
                    $style .= 'color:'.$fields['text-color'].";";
                if( isset($fields['font-size']) && $fields['font-size'] != '' )
                    $style .= 'font-size:'.$fields['font-size'].";";
                if( isset($fields['position']) && $fields['position'] == 'top' )
                    $style .= 'top:0;bottom:inherit;';
                if( isset($fields['padding']) && $fields['padding'] != '' )
                    $style .= 'padding:'.$fields['padding'].";";

                if( isset($fields['button-text']) && $fields['button-text'] != '' )
                    $button = $fields['button-text'];
                else
                    $button = __('Ok', 'lwcp');
                $button_style = '';
                if( isset($fields['button-bg']) && $fields['button-bg'] != '' )
                    $button_style .= 'background-color:'.$fields['button-bg'].";";
                if( isset($fields['button-color']) && $fields['button-color'] != '' )
                    $button_style .= 'color:'.$fields['button-color'].";";

                if( isset($fields['readmore-text']) && $fields['readmore-text'] != '' )
                    $readmore = $fields['readmore-text'];
                else
                    $readmore = __('Read more...', 'lwcp');

                if( isset($fields['readmore-link']) && $fields['readmore-link'] != '' )
                    $readmore_link = $fields['readmore-link'];
                else
                    $readmore_link = '#';

                $readmore_style = '';
                if( isset($fields['readmore-color']) && $fields['readmore-color'] != '' )
                    $readmore_style .= 'color:'.$fields['readmore-color'].";";

                $readmore_target = '_self';
                if( isset($fields['readmore-target']) && $fields['readmore-target'] != '' )
                    $readmore_target = $fields['readmore-target'];

                if( isset($fields['close-text']) && $fields['close-text'] != '' )
                    $close = $fields['close-text'];
                else
                    $close = __('x', 'lwcp');


                $output = '<div class="cookie-pop" style="'.$style.'">';
                    if( $fields['hide-close-button'] != 1 ){
                        $output .= '<a href="javascript:void(0)" class="close_button lwcpcb">'.$close.'</a>';
                    }
                    $output .= $popup_content;
                    if( $fields['hide-readmore'] != 1 ){
                        $output .= ' <a href="'.$readmore_link.'" class="readmore_link '.$fields['readmore-class'].'" target="'.$readmore_target.'" style="'.$readmore_style.'">'.$readmore.'</a>';
                    }
                    if( $fields['hide-button'] != 1 ){
                        $output .= ' <button class="accept_button lwcpcb '.$fields['button-class'].'" style="'.$button_style.'">'.$button.'</button>';
                    }
                $output .= '</div>';

                echo apply_filters( 'lwcp_cookie_output', $output, $fields );
            }//display
        }//set
    }

    /**
    * get ip details
    *
    * @return OBJECT
    */
    public function LWCP_ip_details($IPaddress) 
    {
        $json       = file_get_contents("http://ipinfo.io/{$IPaddress}");
        $details    = json_decode($json);
        return $details;
    }

    /**
     * Load scripts and styles - front
     */
    public function LWCP_enqueue_scripts_and_styles() {
 
        $lwcp = $this->options;//get_option( 'lwcp' );
        
        if( !isset($_COOKIE['lwcp']) || $_COOKIE['lwcp'] != 'set' ){
            
            wp_enqueue_script(
                'jquery-cookie',
                plugins_url( '/js/jquery.cookie.min.js', __FILE__ ),
                array( 'jquery' ),
                '1.4.1',
                true
            );
         
            wp_register_script(
                'cookie-pop-script',
                plugins_url( '/js/ab-cookie-popup.min.js', __FILE__ ),
                array( 'jquery', 'jquery-cookie' ),
                '1.0.0',
                true
            );
         
            wp_localize_script(
                'cookie-pop-script',
                'lwcp_options', array(
                    'days' => ( isset($lwcp['expires']) && $lwcp['expires'] != '' ) ? $lwcp['expires'] : 1,
                    'pos'  => ( isset($lwcp['position']) && $lwcp['position'] == 'top' ) ? 'top' : 'bottom'
                )
            );
         
            wp_enqueue_script( 'cookie-pop-script' );
         
            wp_enqueue_style(
                'cookie-pop-style',
                plugins_url( '/css/ab-cookie-popup.css', __FILE__ ),
                array(),
                '1.0.0'
            );
        }
    }

}
$lwcp_class_instance = new LWCP_Configuration();


if( !function_exists('LWCP_cookie_accepted') ){
    
    /**
    * Check if cookie is accepted
    *
    * @return bool
    */
    function LWCP_cookie_accepted() {
        return apply_filters( 'lwcp_is_cookie_accepted', isset( $_COOKIE['lwcp'] ) && $_COOKIE['lwcp'] === 'set' );
    }
}

if( !function_exists('LWCP_cookies_set') ){
    /**
    * Check if cookie is set.
    *
    * @return boolean whether cookie is set
    */
    function LWCP_cookies_set() {
        return apply_filters( 'lwcp_is_cookie_set', isset( $_COOKIE['lwcp'] ) );
    }
}