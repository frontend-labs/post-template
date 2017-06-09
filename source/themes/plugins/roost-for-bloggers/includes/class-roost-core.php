<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Roost {

    public static $roost_version = '2.1.8';

    protected static $database_version = 20140819;
    
    public static function site_url() {
        return get_option( 'siteurl' );
    }

    public static function registration_url() {
        $tld = 'https://go.goroost.com/signup?returnURL=';
        $admin_path = admin_url( 'admin.php?page=roost-web-push' );
        $url = $tld . urlencode( $admin_path . '&source=wpplugin' );
        return $url;
    }

    public static function login_url( $sso ) {
        $tld = 'https://go.goroost.com/login?returnURL=';
        $admin_path = admin_url( 'admin.php?page=roost-web-push' );
        $url = $tld . urlencode( $admin_path );
        $url = $url . '&oauth=' . $sso;
        return $url;
    }

    public static function roost_settings() {
        return get_option( 'roost_settings' );
    }

    public static function roost_active() {
        $roost_settings = self::roost_settings();        
        $app_key = $roost_settings['appKey'];
        if ( ! empty( $app_key ) ) {
            return true;
        } else {
            return false;
        }
    }
    
    public function __construct() {
        //blank
    }

    public static function init() {
        $roost = null;
        
        if ( is_null( $roost ) ) {
			$roost = new self();
            self::add_actions();
            $roost_settings = self::roost_settings();
            if ( empty( $roost_settings ) || ( self::$roost_version !== $roost_settings['version'] ) ) {
                self::install( $roost_settings );
            }
		}
		return $roost;
	}

	public static function install( $roost_settings ) {
		if ( empty( $roost_settings ) ) {
			$roost_settings = array(
				'appKey' => '',
				'appSecret' => '',
				'version' => self::$roost_version,
				'autoPush' => 0,
                'bbPress' => 1,
                'database_version' => self::$database_version,
                'prompt_min' => 0,
                'prompt_visits' => 2,
                'prompt_event' => 0,
            );
			add_option( 'roost_settings', $roost_settings );
		}
        if ( self::$roost_version !== $roost_settings['version'] ) {
            self::update( $roost_settings );
        }
	}

    public static function update( $roost_settings ) {
        $roost_settings['version'] = self::$roost_version;
        update_option( 'roost_settings', $roost_settings );
        if ( empty( $roost_settings['database_version'] ) || $roost_settings['database_version'] < self::$database_version ) {
            self::update_database( $roost_settings );
        }
    }
    
    protected static function update_database( $roost_settings ) {
        if ( empty( $roost_settings['database_version'] ) || ( 1407 >= $roost_settings['database_version'] ) ) {
            if ( empty( $roost_settings['bbPress'] ) ) {
                $roost_settings['bbPress'] = 1;
            }
            $roost_settings['prompt_min'] = 0;
            $roost_settings['prompt_visits'] = 2;
            $roost_settings['prompt_event'] = 0;
        }
        if ( 1408 >= $roost_settings['database_version'] ) {
            if ( $roost_settings['prompt_visits'] === 1 ) {
                $roost_settings['prompt_visits'] = 2;
            }
            global $wpdb;
            $wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = '_roost_override' WHERE meta_key = 'roostOverride'" );
            $wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = '_roost_custom_note_text' WHERE meta_key = 'roost_custom_note_text'" );
            $wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = '_roost_force' WHERE meta_key = 'roostForce'" );
            $wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = '_roost_bbp_subscription' WHERE meta_key = 'roost_bbp_subscription'" );
        }
        $roost_settings['database_version'] = self::$database_version;
        update_option('roost_settings', $roost_settings);
    }

    public static function activate_redirect() {
        $redirect_state = get_option( 'roost_redirected' );
        if ( empty( $redirect_state ) ) {
            update_option( 'roost_redirected', true );
            if ( ! isset( $_GET['activate-multi'] ) ){
                wp_redirect( admin_url( 'admin.php?page=roost-web-push' ) );
                exit;
            }
        }
    }

    public static function add_actions() {
        add_action( 'wp_head', array( __CLASS__, 'byline' ), 1 );
        add_action( 'wp_footer', array( __CLASS__, 'roostJS' ) );
        add_filter( 'clean_url', array( __CLASS__, 'add_async' ), 2, 1 );
        add_action( 'transition_post_status', array( __CLASS__, 'build_note' ), 10, 3 );

        if ( is_admin() ) {
            add_filter( 'plugin_action_links_roost-for-bloggers/roost.php', array( __CLASS__, 'add_action_links' ) );
            add_action( 'admin_init', array( __CLASS__, 'activate_redirect' ) );
            add_action( 'admin_init', array( __CLASS__, 'roost_logout' ) );
            add_action( 'admin_init', array( __CLASS__, 'roost_save_settings' ) );
            add_action( 'admin_init', array( __CLASS__, 'manual_send' ) );
            add_action( 'admin_notices', array( __CLASS__, 'setup_notice' ) );
            add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );
            add_action( 'admin_menu', array( __CLASS__, 'admin_menu_add' ) );
            add_action( 'wp_ajax_graph_reload', array( __CLASS__, 'graph_reload' ) );
            add_action( 'wp_ajax_subs_check', array( __CLASS__, 'subs_check' ) );
            add_action( 'post_submitbox_misc_actions', array( __CLASS__, 'note_override' ) );
            add_action( 'add_meta_boxes_post', array( __CLASS__, 'custom_note_text' ) );
            add_action( 'save_post', array( __CLASS__, 'save_post_meta_roost' ) );
        }
    }

    public static function add_action_links ( $links ) {
        $rlink = array(
            '<a href="' . admin_url( 'admin.php?page=roost-web-push' ) . '">Go to Plugin</a>',
        );
        return array_merge( $rlink, $links );
    }

    public static function add_async( $url ) {
        if ( false === strpos( $url, '#async' ) ) {
            return $url;
        } else if ( is_admin() ) {
            return str_replace( '#async', '', $url );
        } else {
            return str_replace( '#async', '', $url )."' async data-roost='true";
        }
    }

    public static function byline() {
        $byline = "<!-- Push notifications for this website enabled by Roost. Support for Safari, Firefox, and Chrome Browser Push. (v ". self::$roost_version .") - http://goroost.com/ -->";
        echo "\n${byline}\n";
    }

    public static function roostJS() {
        if ( false === self::roost_active() ) {
            return;
        }
        $roost_settings = self::roost_settings();
        $app_key = $roost_settings['appKey'];
    ?>
        <script>var _roost = _roost || [];</script><noscript>Please enable JavaScript to use web push. Still not working? <a href="https://goroost.com/site-contact?noscript&amp;appkey=<?php echo( $app_key ); ?>" target="_blank">Troubleshoot web push notification registration with Roost.</a></noscript><script src="//cdn.goroost.com/roostjs/<?php echo( $app_key ); ?>" async></script>
    <?php
        if ( ( true === $roost_settings['prompt_min'] ) || ( true === $roost_settings['prompt_event'] ) ) {
    ?>
            <script>
                _roost.push( [ 'autoprompt', false ] );
                <?php
                    if ( true == $roost_settings['prompt_min'] ) {
                ?>
                    _roost.push( [ 'minvisits', <?php echo( $roost_settings['prompt_visits'] ); ?> ] );
                <?php
                    }
                    if ( true === $roost_settings['prompt_event'] ) {
                ?>
                        ( function( $ ) {
                            $( '.roost-prompt-wp' ).on( 'click', function( e ) {
                                e.preventDefault();
                                _roost.prompt();
                            });
                            _roost.push(['onload', function(data){
                                if ( false === data.promptable ) {
                                    $( '.roost-prompt-wp' ).hide();
                                }
                            }]);
                            _roost.push(['onresult', function(data){
                                console.log(data);
                                if ( true === data.registered || false === data.registered ) {
                                    $( '.roost-prompt-wp' ).hide();
                                }
                            }]);
                        })( jQuery );
                <?php
                    }
                ?>
            </script>
    <?php
        }
    }

    public static function setup_notice() {
        global $hook_suffix;
        $roost_page = 'toplevel_page_roost-web-push';
        
        $roost_settings = self::roost_settings();
        $app_key = $roost_settings['appKey'];

        if ( false === self::roost_active() && $hook_suffix !== $roost_page ) {
    ?>
		<div class="updated" id="roost-setup-notice">
            <div id="roost-notice-logo">
                <img src="<?php echo( ROOST_URL . 'layout/images/roost_logo.png' ) ?>" />
            </div>
            <div id="roost-notice-text">
                <p>
                    Thanks for installing the Roost plugin! You’re almost finished with<br />setup, all you need to do is create an account and login.
                </p>
            </div>
            <div id="roost-notice-target">
                <a href="<?php echo( admin_url( 'admin.php?page=roost-web-push' ) ); ?>" id="roost-notice-CTA" >
                    <span id="roost-notice-CTA-highlight"></span>
                    Finish Setup
                </a>
            </div>
		</div>
    <?php
        } else if ( ! $app_key && ( $hook_suffix === $roost_page ) ) {
            $api_check = Roost_API::api_check();
            if ( is_wp_error( $api_check ) ) {
    ?>
        <div class="error" id="roost-api-error">There was a problem accessing the <strong>Roost API</strong>. You may not be able to log in. Contact Roost support at <a href="mailto:support@goroost.com" target="_blank">support@goroost.com</a> for more information.</div>
    <?php
            }
        }
    }

	public static function admin_menu_add(){
	    add_menu_page(
        	'Roost Web Push',
	        'Roost Web Push',
	        'manage_options',
            'roost-web-push',
        	array( __CLASS__, 'admin_menu_page' ),
	        ROOST_URL . 'layout/images/roost_thumb.png'
	    );
	}

	public static function admin_scripts() {
        wp_enqueue_style( 'rooststyle', ROOST_URL . 'layout/css/rooststyle.css', '', self::$roost_version );
        wp_enqueue_script( 'roostGoogleFont', ROOST_URL . 'layout/js/roostGoogleFont.js', '', self::$roost_version, false );
        if ( true === self::roost_active() ) {
            wp_enqueue_style( 'morrisstyle', '//s3.amazonaws.com/roost/plugins/morris-0.4.3.min.css', '', self::$roost_version );
            wp_enqueue_script( 'morrisscript', '//s3.amazonaws.com/roost/plugins/morris-0.4.3.min.js', array( 'jquery', 'raphael' ), self::$roost_version );
            wp_enqueue_script( 'raphael', '//s3.amazonaws.com/roost/plugins/raphael-min-2.1.0.js', array( 'jquery' ), self::$roost_version );
            wp_enqueue_script( 'roostscript', ROOST_URL . 'layout/js/roostscript.js', array( 'jquery' ), self::$roost_version, true );
        }
    }
	
	public static function update_keys( $form_keys ){
		$roost_settings = self::roost_settings();
		$roost_settings['appKey'] = $form_keys['appKey'];
		$roost_settings['appSecret'] = $form_keys['appSecret'];
		update_option('roost_settings', $roost_settings);
	}
    
	public static function update_settings($form_data){	
		$roost_settings = self::roost_settings();
		$roost_settings['autoPush'] = $form_data['autoPush'];
		$roost_settings['bbPress'] = $form_data['bbPress'];
        $roost_settings['prompt_min'] = $form_data['prompt_min'];
        $roost_settings['prompt_visits'] = $form_data['prompt_visits'];
        $roost_settings['prompt_event'] = $form_data['prompt_event'];
		update_option('roost_settings', $roost_settings);
	}

    public static function save_post_meta_roost( $post_id ) {
        if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || empty( $_POST['hiddenRooster'] ) ) {
            return false;
        } else {
            $no_note = get_post_meta( $post_id, '_roost_override', true );
            $send_note = get_post_meta( $post_id, '_roost_force', true );
            if ( isset( $_POST['roost-override'] ) && ! $no_note ) {
                $override_setting = $_POST['roost-override'];
                add_post_meta( $post_id, '_roost_override', $override_setting, true );
            } elseif ( ! isset( $_POST['roost-override'] ) && $no_note ) {
                delete_post_meta( $post_id, '_roost_override' );
            }
            if ( isset( $_POST['roost-force'] ) && ! $send_note ) {
                $override_setting = $_POST['roost-force'];
                add_post_meta( $post_id, '_roost_force', $override_setting, true );
            } elseif ( ! isset( $_POST['roost-force'] ) && $send_note ) {
                delete_post_meta( $post_id, '_roost_force' );
            }
            if ( isset( $_POST['roost-custom-note-text'] ) ) {
                update_post_meta( $post_id, '_roost_custom_note_text', $_POST['roost-custom-note-text'] );
            }
        }
    }

    public static function filter_string( $string ) {
        $string = str_replace( '&#8220;', '&quot;', $string );
        $string = str_replace( '&#8221;', '&quot;', $string );
        $string = str_replace( '&#8216;', '&#39;', $string );
        $string = str_replace( '&#8217;', '&#39;', $string );
        $string = str_replace( '&#8211;', '-', $string );
        $string = str_replace( '&#8212;', '-', $string );
        $string = str_replace( '&#8242;', '&#39;', $string );
        $string = str_replace( '&#8230;', '...', $string );
        $string = str_replace( '&prime;', '&#39;', $string );
        return html_entity_decode( $string, ENT_QUOTES );
    }

    public static function build_note( $new_status, $old_status, $post ) {
        if ( false === self::roost_active() ) {
            return;
        }
        if ( $new_status != $old_status && ! empty( $post ) ) {
            $post_type = get_post_type( $post );
		    if ( 'post' === $post_type && 'publish' === $new_status ) {
                $post_id = $post->ID;
				$roost_settings = self::roost_settings();
				$app_key = $roost_settings['appKey'];
				$app_secret = $roost_settings['appSecret'];
				$auto_push = $roost_settings['autoPush'];
                
                if ( ( 'publish' === $new_status && 'future' === $old_status ) || empty( $_POST['hiddenRooster'] ) ) {
                    $override = get_post_meta( $post_id, '_roost_override', true );
                    $send_note = get_post_meta( $post_id, '_roost_force', true );
                    $custom_headline = get_post_meta( $post_id, '_roost_custom_note_text', true );
                } else {
                    if ( isset( $_POST['roost-override'] ) ) {
                        $override = $_POST['roost-override'];
                    }
                    if ( isset( $_POST['roost-force'] ) ) {
                        $send_note = $_POST['roost-force'];
                    }
                    if ( isset( $_POST['roost-custom-note-text'] ) && ! empty( $_POST['roost-custom-note-text'] ) ) {
                        $custom_headline = $_POST['roost-custom-note-text'];
                    }
                }
                if ( ( 1 == $auto_push || ! empty( $send_note ) ) ) {
					if ( empty( $override ) ) {
                        if ( ! empty( $custom_headline ) ) {
                            $alert = $custom_headline;
                        } else {
                            $alert = get_the_title( $post_id );
                        }
						$url = wp_get_shortlink( $post_id );
						if ( has_post_thumbnail( $post_id ) ) {
						    $raw_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ) );
						    $image_url = $raw_image[0];
						} else {
						    $image_url = null;
						}
						Roost_API::send_notification( $alert, $url, $image_url, $app_key, $app_secret, null );
					}
				}
			}
        }
    }
    
	public static function note_override() {
        if ( false === self::roost_active() ) {
            return;
        }
        $roost_settings = self::roost_settings();
        global $post;
        if ( 'post' === $post->post_type ) {
            if ( 'publish' === $post->post_status ) {
                $check_hidden = true;
            }
            $auto_push = $roost_settings['autoPush'];
            printf('<div class="misc-pub-section misc-pub-section-last" id="roost-post-checkboxes" %s >', ( isset( $check_hidden ) ) ? 'style="display:none;"' : '' );
            $pid = get_the_ID();
            if ( 1 == $auto_push ) {
                $checked = get_post_meta($pid, '_roost_override', true);
                printf('<label><input type="checkbox" value="1" id="roost-override-checkbox" name="roost-override" %s />', ( ! empty( $checked ) ) ? 'checked="checked"' : '' );
                echo '<strong>Do NOT</strong> send notification with <strong>Roost</strong></label>';
            } else {
                $checked = get_post_meta($pid, '_roost_force', true);
                printf('<label><input type="checkbox" value="1" id="roost-forced-checkbox" name="roost-force" %s />', ( ! empty( $checked ) ) ? 'checked="checked"' : '' );
                echo '<strong>Send</strong> notification with <strong>Roost</strong></label>';
            }
            echo '<input type="hidden" name="hiddenRooster" value="true" />';
            echo '</div>';
        }
	}
    
    public static function custom_note_text( $post ) {
        if ( false === self::roost_active() ) {
            return;
        }
        add_meta_box(
            'roost_meta',
            'Roost Web Push - Custom Notification Headline',
            array( __CLASS__, 'roost_custom_headline_content' ),
            'post',
            'normal',
            'high'
        );
    }

    public static function roost_custom_headline_content( $post ) {
        $custom_note_text = get_post_meta( $post->ID, '_roost_custom_note_text', true );
        ?>
        <div id="roost-custom-note">
            <input type="text" id="roost-custom-note-text" placeholder="Enter Custom Headline for your Notification" name="roost-custom-note-text" value="<?php echo( ! empty( $custom_note_text ) ? $custom_note_text : '' ); ?>" />
            <span id="roost-custom-note-text-description" >When using a custom headline, this text will be used in place of the default blog post title for your push notification. ( Leave this blank to default to post title. )</span>
        </div>
    <?php
    }
    
    public static function complete_login( $logged_in, $site ) {
        if ( ! empty( $logged_in ) ) {
            if ( true === $logged_in['success'] ) {
                if ( count( $logged_in['apps'] ) > 1 ){
                    $roost_sites = $logged_in['apps'];
                    return $roost_sites;
                } else {
                    $form_keys = array(
                        'appKey' => $logged_in['apps'][0]['key'],
                        'appSecret' => $logged_in['apps'][0]['secret'],
                    );
                }
            }
        } elseif ( ! empty( $site ) ) {
            $site_key = $site[0];
            $site_secret = $site[1];
            $form_keys = array(
                'appKey' => $site_key,
                'appSecret' => $site_secret,
            );
        }
    
        $response = array();

        if ( ! empty( $form_keys ) ) {
            self::update_keys( $form_keys );
            $response['status'] = true;
            $response['firstTime'] = true;
            $response['server_settings'] = Roost_API::get_server_settings( $form_keys['appKey'], $form_keys['appSecret'] );	
            $response['stats'] = Roost_API::get_stats( $form_keys['appKey'], $form_keys['appSecret'] );
            self::admin_scripts();
        } else {
            $response['status'] = 'Please check your Email or Username and Password.';
            $response['stats'] = null;
            $response['server_settings'] = null;
        }
        return $response;
    }

    public static function graph_reload() {
        $roost_settings = self::roost_settings();
        $app_key = $roost_settings['appKey'];
        $app_secret = $roost_settings['appSecret'];
        $type = $_POST['type'];
        $range = $_POST['range'];
        $value = $_POST['value'];
        $time_offset = $_POST['offset'];
        $roost_graph_data = Roost_API::get_graph_data( $app_key, $app_secret, $type, $range, $value, $time_offset );
        $roost_graph_data = json_encode( $roost_graph_data );
        echo $roost_graph_data;
        die();
    }

    public static function subs_check() {
        $roost_settings = self::roost_settings();
        $app_key = $roost_settings['appKey'];
        $app_secret = $roost_settings['appSecret'];
        $roost_stats = Roost_API::get_stats( $app_key, $app_secret );
        $roost_subs = json_encode( $roost_stats['registrations'] );
        echo $roost_subs;
        die();
    }

    public static function roost_logout() {
        if ( isset( $_POST['clearkey'] ) ) {
            $form_keys = array(
                'appKey' => '',
                'appSecret' => '',
            );
            self::update_keys( $form_keys );
            wp_dequeue_script( 'roostscript' );
            $status = 'Roost has been disconnected.';
            $status = urlencode( $status );
            wp_redirect( admin_url( 'admin.php?page=roost-web-push' ) . '&status=' . $status );
            exit;
        }
    }

    public static function roost_save_settings() {
        if ( isset( $_POST['savesettings'] ) ) {
            $roost_settings = self::roost_settings();

            $app_key = $roost_settings['appKey'];
            $app_secret = $roost_settings['appSecret'];

            $roost_server_settings = Roost_API::get_server_settings( $app_key, $app_secret );

            $autoPush = false;
            $bbPress = false;
            $prompt_min = false;
            $prompt_visits = 2;
            $prompt_event = false;

            if ( isset( $_POST['autoPush'] ) ) {
                $autoPush = true;
            }
            if ( isset( $_POST['bbPress'] ) ) {
                $bbPress = true;
            }
            if ( isset( $_POST['roost-prompt-min'] ) ) {
                $prompt_min = true;
            }
            if ( isset( $_POST['roost-prompt-visits'] ) ) {
                if ( '0' === $_POST['roost-prompt-visits'] || '1' === $_POST['roost-prompt-visits'] ) {
                    $prompt_visits = 2;
                } else {
                    $prompt_visits = $_POST['roost-prompt-visits'];
                }
            }
            if ( isset( $_POST['roost-prompt-event'] ) ) {
                $prompt_event = true;
            }

            $form_data = array(
                'autoPush' => $autoPush,
                'bbPress' => $bbPress,
                'prompt_min' => $prompt_min,
                'prompt_visits' => $prompt_visits,
                'prompt_event' => $prompt_event,
            );
            self::update_settings( $form_data );
            Roost_API::save_remote_settings( $app_key, $app_secret, $roost_server_settings, $_POST );
            $status = 'Settings Saved.';
            $status = urlencode( $status );
            wp_redirect( admin_url( 'admin.php?page=roost-web-push' ) . '&status=' . $status );
            exit;
        }
    }
    
    public static function manual_send() {
        if ( isset( $_POST['manualtext'] ) ) {
            $manual_text = $_POST['manualtext'];
	        $manual_link = $_POST['manuallink'];
            $manual_text = stripslashes( $manual_text );
            if ( '' == $manual_text || '' == $manual_link ) {
                $status = 'Your message or link can not be blank.';
            } else {
                $roost_settings = self::roost_settings();
                $app_key = $roost_settings['appKey'];
                $app_secret = $roost_settings['appSecret'];
                if ( false === strpos( $manual_link, 'http' ) ) {
                    $manual_link = 'http://' . $manual_link;
                }
                $msg_status = Roost_API::send_notification( $manual_text, $manual_link, null, $app_key, $app_secret, null );
                if ( true === $msg_status['success'] ) {
                    $status = 'Message Sent.';
                } else {
                    $status = 'Message failed. Please make sure you have a valid URL.';
                }
			}
            $status = urlencode( $status );
            wp_redirect( admin_url( 'admin.php?page=roost-web-push' ) . '&status=' . $status );
            exit;
        }
    }

    public static function admin_menu_page() {
        $roost_settings = self::roost_settings();      
        $app_key = $roost_settings['appKey'];
        $app_secret = $roost_settings['appSecret'];

        if ( true === self::roost_active() ) {
            $bbPress_active = Roost_bbPress::bbPress_active();
            $roost_active_key = true;
        } else {
            $roost_active_key = false;
        }
        
        if ( true === self::roost_active() && empty( $roost_server_settings ) ) {
            $roost_server_settings = Roost_API::get_server_settings( $app_key, $app_secret );
            $roost_stats = Roost_API::get_stats( $app_key, $app_secret );
        }        
        
        if ( false === self::roost_active() && isset( $_GET['roost_token'] ) ) {
            $roost_token = $_GET['roost_token'];
            $roost_token = urldecode($roost_token);
            $logged_in = Roost_API::login( null, null, $roost_token );
            $response = self::complete_login( $logged_in, null );
            $first_time = $response['firstTime'];
            $roost_server_settings = $response['server_settings'];
            $roost_stats = $response['stats'];
            $roost_active_key = true;
        }
        
	    if ( isset( $_POST['roostlogin'] ) ) {
            $roost_user = $_POST['roostuserlogin'];
            $roost_pass = $_POST['roostpasslogin'];
            $logged_in = Roost_API::login( $roost_user, $roost_pass, null );
            $response = self::complete_login( $logged_in, null );
            if ( empty( $response['status'] ) ) {
                $roost_sites = $response;
            } else {
                if ( ! empty( $response['firstTime'] ) ) {
                    $first_time = $response['firstTime'];
                    $roost_server_settings = $response['server_settings'];
                    $roost_stats = $response['stats'];
                    $roost_active_key = true;
                } else {
                    $status = $response['status'];
                }
            }
		}

	    if ( isset( $_POST['roostconfigselect'] ) ) {
            $selected_site = $_POST['roostsites'];
            $site = explode( '|', $selected_site );
            $response = self::complete_login( null, $site );
            $first_time = $response['firstTime'];
            $roost_server_settings = $response['server_settings'];
            $roost_stats = $response['stats'];
            $roost_active_key = true;
		}
        if ( isset( $_GET['status'] ) ) {
            $status = urldecode( $_GET['status'] );
        }
        
        require_once( dirname( plugin_dir_path( __FILE__ ) ) . '/layout/admin.php' );
	}
}
