<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Roost_bbPress {

    public static function bbPress_active() {
        $roost_settings = Roost::roost_settings();
        $bbPress = array(
            'present' => false,
            'enabled' => $roost_settings['bbPress'],
        );
        if ( class_exists( 'bbPress' ) ) {
            $bbPress['present'] = true;
        }
        return $bbPress;
    }
    
    public function __construct() {
        //blank
    }

    public static function init() {        
        $roost_bbp = null;
        if ( is_null( $roost_bbp ) ) {
			$roost_bbp = new self();
            self::add_actions();
		}
		return $roost_bbp;
    }
    
    public static function add_actions() {
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_roost_bbp_scripts' ), 1 );

        add_action( 'wp_ajax_roost_bbp_subscribe', array( __CLASS__, 'roost_bbp_ajax_subscription' ) );
        add_action( 'wp_ajax_nopriv_roost_bbp_subscribe', array( __CLASS__, 'roost_bbp_ajax_subscription' ) );
        add_action( 'wp_ajax_roost_bbp_unsubscribe', array( __CLASS__, 'roost_bbp_ajax_subscription' ) );
        add_action( 'wp_ajax_nopriv_roost_bbp_unsubscribe', array( __CLASS__, 'roost_bbp_ajax_subscription' ) );

        add_action( 'bbp_theme_before_reply_form_submit_wrapper', array( __CLASS__, 'roost_bbp_reply_subscription' ) );
        add_action( 'bbp_theme_before_topic_form_submit_wrapper', array( __CLASS__, 'roost_bbp_reply_subscription' ) );
        add_action( 'bbp_template_before_single_topic', array( __CLASS__, 'roost_bbp_topic_subscription' ) );
        add_action( 'bbp_template_before_single_forum', array( __CLASS__, 'roost_bbp_forum_subscription' ) );
        add_action( 'bbp_get_request', array( __CLASS__, 'roost_bbp_subscriptions_handler' ), 1 );
        add_action( 'bbp_new_reply', array( __CLASS__, 'roost_bbp_subscriptions_handler' ), 1 );
        add_action( 'bbp_edit_reply', array( __CLASS__, 'roost_bbp_subscriptions_handler' ), 1 );
        add_action( 'bbp_new_topic', array( __CLASS__, 'roost_bbp_subscriptions_handler' ), 1 );
        add_action( 'bbp_edit_topic', array( __CLASS__, 'roost_bbp_subscriptions_handler' ), 1 );
        
        add_action( 'bbp_new_reply', array( __CLASS__, 'roost_bbp_notify_subscribers' ), 11, 5 );
        add_action( 'bbp_new_topic', array( __CLASS__, 'roost_bbp_notify_subscribers' ), 11, 5 );

        add_action( 'bbp_delete_reply', array( __CLASS__, 'roost_bbp_remove_all_subscriptions' ) );
        add_action( 'bbp_delete_topic', array( __CLASS__, 'roost_bbp_remove_all_subscriptions' ) );
        add_action( 'bbp_delete_forum', array( __CLASS__, 'roost_bbp_remove_all_subscriptions' ) );
    }

    public static function load_roost_bbp_scripts() {
        wp_enqueue_script( 'roostbbpjs', ROOST_URL . 'layout/js/roostbbp.js', array('jquery'), Roost::$roost_version, false );
	}
    
    public static function roost_bbp_reply_subscription( $post ) {
        global $post;
        $roost_bbp_subscriptions = get_post_meta( $post->ID, '_roost_bbp_subscription', true );
    ?>
        <div class="roost-bbp-reply-subscription-wrap" style="display:none;">
            <input type="checkbox" value='1' name="roost-bbp-subscription" class="roost-bbp-reply-subscription" />
            <label for="roost-bbp-subscription">Notify me of follow-up replies via <strong>desktop push notifications.</strong></label>
            <input type="hidden" name="roost-bbp-device-token" id="roost-bbp-device-token" value="" />
        </div>
        <script>
            jQuery(document).ready(function($) {
                setTimeout( function() {
                    if ( window.roostEnabled ){
                        jQuery( '.roost-bbp-reply-subscription-wrap' ).show();
                        jQuery( '#roost-bbp-device-token' ).val( window.roostToken );
                        <?php
                            if ( ! empty( $roost_bbp_subscriptions ) ) {
                                $reply_bbp_subscriptions = json_encode( $roost_bbp_subscriptions );
                        ?>
                                var registrations = <?php echo( $reply_bbp_subscriptions ); ?>;
                                if ( 'undefined' !== typeof registrations[window.roostToken] ) {
                                    if ( true === registrations[window.roostToken] ) {
                                        jQuery( '.roost-bbp-reply-subscription' ).prop( 'checked', true );
                                    }
                                }
                        <?php
                            }
                        ?>
                    }
                }, 1500 );
            });
        </script>
    <?php
    }

    public static function roost_bbp_topic_subscription( $post ) {
        global $post;
        $post_id = $post->ID;

        $url = bbp_get_topic_permalink( $post_id );

        $roost_bbp_subscriptions = get_post_meta( $post_id, '_roost_bbp_subscription', true );
        
        echo( sprintf( "<span id='roost-subscribe-%d' style='display:none;'><a href='%s' data-post='%d' class='roost-topic-subscribe-link'></a></span>", $post_id, $url, $post_id ) );
        ?>
        <script>
            jQuery(document).ready(function($) {
                var subscribeLink = $('.roost-topic-subscribe-link');
                var subscribeWrap = $('#roost-subscribe-<?php echo( $post_id ); ?>');
                <?php
                    if ( ! empty( $roost_bbp_subscriptions ) ) {
                        $reply_bbp_subscriptions = json_encode( $roost_bbp_subscriptions );
                ?>
                        var registrations = <?php echo( $reply_bbp_subscriptions ); ?>;
                <?php
                    } else {
                ?>
                        var registrations = [];
                <?php
                    }
                ?>
                
                setTimeout(function(){
                    if ( window.roostEnabled ){
                        if ( 'undefined' !== typeof registrations[window.roostToken] ) {
                            if ( true === registrations[window.roostToken] ) {
                                subscribeLink.text( 'Unsubscribe from Push Notifications' );
                                subscribeLink.data( 'action', 'roost_bbp_unsubscribe' );
                            }
                        } else {
                            subscribeLink.text( 'Subscribe with Push Notifications' );
                            subscribeLink.data( 'action', 'roost_bbp_subscribe' );
                        }
                        <?php
                            if ( true === bbp_is_subscriptions_active() && true === is_user_logged_in() ) {
                        ?>
                            subscribeWrap.detach().appendTo( '#subscription-toggle' ).show();
                            subscribeWrap.prepend( ' | ' );
                        <?php
                            } else if ( true === bbp_is_favorites_active() && true === is_user_logged_in() ) {
                        ?>
                            subscribeWrap.detach();
                            subscribeWrap.appendTo( '.bbp-header .bbp-reply-content' ).append( ' | ' ).wrap( "<div id='subscription-toggle'></div>" ).show();
                        <?php
                            } else {
                        ?>
                            subscribeWrap.detach();
                            subscribeWrap.appendTo( '.bbp-header .bbp-reply-content' ).wrap( "<div id='subscription-toggle'></div>" ).show();
                        <?php
                            }
                        ?>
                    }
                }, 1500);

                subscribeLink.on( 'click', function( e ){
                    e.preventDefault();
                    var data = {
                        link: subscribeLink.attr('href'),
                        action: subscribeLink.data('action'),
                        roostToken: window.roostToken,
                        postID: subscribeLink.data('post'),
                    };
                    if ( 'roost_bbp_subscribe' === subscribeLink.data( 'action' ) ){
                        subscribeLink.text( 'Unsubscribe from Push Notifications' );
                        subscribeLink.data( 'action', 'roost_bbp_unsubscribe' );
                    } else {
                        subscribeLink.text( 'Subscribe with Push Notifications' );
                        subscribeLink.data( 'action', 'roost_bbp_subscribe' );
                    }

                    $.post( ajaxurl, data, function( response ) {

                    });
                });
            });
        </script>
        <?php
    }
    
    public static function roost_bbp_ajax_subscription() {
        $post_id = $_POST['postID'];
        self::roost_bbp_subscriptions_handler( $post_id );
        die();
    }
    
    public static function roost_bbp_forum_subscription( $post ) {
        global $post;
        $post_id = $post->ID;

        $url = bbp_get_forum_permalink( $post_id );

        $roost_bbp_subscriptions = get_post_meta( $post_id, '_roost_bbp_subscription', true );
        
        echo( sprintf( "<span id='roost-subscribe-%d' style='display:none;'><a href='%s' data-post='%d' class='roost-forum-subscribe-link' class='subscription-toggle'></a></span>", $post_id, $url, $post_id ) );
        ?>
        <script>
            jQuery(document).ready(function($) {
                var subscribeLink = $('.roost-forum-subscribe-link');
                var subscribeWrap = $('#roost-subscribe-<?php echo( $post_id ); ?>');
                <?php
                    if ( ! empty( $roost_bbp_subscriptions ) ) {
                        $reply_bbp_subscriptions = json_encode( $roost_bbp_subscriptions );
                ?>
                        var registrations = <?php echo( $reply_bbp_subscriptions ); ?>;
                <?php
                    } else {
                ?>
                        var registrations = [];
                <?php
                    }
                ?>
                
                setTimeout(function(){
                    if ( window.roostEnabled ){
                        if ( 'undefined' !== typeof registrations[window.roostToken] ) {
                            if ( true === registrations[window.roostToken] ) {
                                subscribeLink.text( 'Unsubscribe from Push Notifications' );
                                subscribeLink.data( 'action', 'roost_bbp_unsubscribe' );
                            }
                        } else {
                            subscribeLink.text( 'Subscribe with Push Notifications' );
                            subscribeLink.data( 'action', 'roost_bbp_subscribe' );
                        }
                        <?php
                            if ( true === bbp_is_subscriptions_active() && true === is_user_logged_in() ) {
                        ?>
                            subscribeWrap.detach().appendTo( '#subscription-toggle' ).show();
                            subscribeWrap.prepend( ' | ' );
                        <?php
                            } else {
                        ?>
                            subscribeWrap.wrap( "<div id='subscription-toggle'></div>" ).show();
                        <?php
                            }
                        ?>
                    }
                }, 1500);

                subscribeLink.on( 'click', function( e ){
                    e.preventDefault();
                    var data = {
                        link: subscribeLink.attr( 'href' ),
                        action: subscribeLink.data( 'action' ),
                        roostToken: window.roostToken,
                        postID: subscribeLink.data( 'post' ),
                    };
                    if ( 'roost_bbp_subscribe' === subscribeLink.data( 'action' ) ){
                        subscribeLink.text( 'Unsubscribe from Push Notifications' );
                        subscribeLink.data( 'action', 'roost_bbp_unsubscribe' );
                    } else {
                        subscribeLink.text( 'Subscribe with Push Notifications' );
                        subscribeLink.data( 'action', 'roost_bbp_subscribe' );
                    }

                    $.post( ajaxurl, data, function( response ) {

                    });
                });
            });
        </script>
        <?php
    }
    
    public static function roost_bbp_subscriptions_handler( $post_id ) {
        if ( isset( $_POST['roostToken'] ) ) {
            $action = $_POST['action'];
            $roost_device_token = $_POST['roostToken'];
        } elseif ( isset( $_POST['roost-bbp-device-token'] ) ) {
            $action = false;
            $roost_device_token = $_POST['roost-bbp-device-token'];
        } else {
            return;
        }

        if ( ( ! empty( $roost_device_token ) ) && ( false === $action ) ) {
            if ( isset( $_POST['roost-bbp-subscription'] ) ) {
                $roost_bbp_subscription = true;
            } else {
                $roost_bbp_subscription = false;
            }   

            $subscriptions = get_post_meta( $post_id, '_roost_bbp_subscription', true );
            if ( ! empty( $subscriptions )  ) {
                if ( isset( $subscriptions[$roost_device_token] ) ) {
                    if ( true === $roost_bbp_subscription ) {
                        return;
                    } else {
                        unset( $subscriptions[$roost_device_token] );
                    }
                } else {
                    if ( true === $roost_bbp_subscription ) {
                        $subscriptions[$roost_device_token] = $roost_bbp_subscription;
                    }
                }
            } else {
                $subscriptions = array(
                    $roost_device_token => $roost_bbp_subscription,
                );
            }
            update_post_meta( $post_id, '_roost_bbp_subscription', $subscriptions );
        }

        if ( ( ! empty( $roost_device_token ) ) && ( 'roost_bbp_subscribe' === $action ) ) {
            $subscriptions = get_post_meta( $post_id, '_roost_bbp_subscription', true );
            if ( ! empty( $subscriptions )  ) {
                $subscriptions[$roost_device_token] = true;
            } else {
                $subscriptions = array(
                    $roost_device_token => true,
                );
            }
            update_post_meta( $post_id, '_roost_bbp_subscription', $subscriptions );
        }

        if ( ( ! empty( $roost_device_token ) ) && ( 'roost_bbp_unsubscribe' === $action ) ) {
            $subscriptions = get_post_meta( $post_id, '_roost_bbp_subscription', true );
            unset( $subscriptions[$roost_device_token] );
            update_post_meta( $post_id, '_roost_bbp_subscription', $subscriptions );
        }
    }
    
    public static function roost_bbp_remove_all_subscriptions( $action ) {
        delete_post_meta( $action, '_roost_bbp_subscription' );
    }
    
    public static function roost_bbp_notify_subscribers( $reply_id, $topic_id, $forum_id, $anonymous_data, $reply_author = 0 ) {
        $topic_id = bbp_get_topic_id( $topic_id );
        $forum_id = bbp_get_forum_id( $forum_id );

        if ( ! bbp_is_topic_published( $topic_id ) ) {
            return false;
        }
        
        $topic_title = bbp_get_topic_title( $topic_id );
        $topic_url = get_permalink( $topic_id );
        if ( isset( $_POST['bbp_reply_to'] ) ) {
            $reply_to_id = $_POST['bbp_reply_to'];
        }
        
        $roost_settings = ROOST::roost_settings();
        $app_key = $roost_settings['appKey'];
        $app_secret = $roost_settings['appSecret'];
        
        $message = 'New Post in ' . $topic_title;
        
        if ( ! empty( $reply_to_id ) ) {
            $reply_subscriptions = get_post_meta( $reply_to_id, '_roost_bbp_subscription', true );
        }
        
        $topic_subscriptions = get_post_meta( $topic_id, '_roost_bbp_subscription', true );
        $forum_subscriptions = get_post_meta( $forum_id, '_roost_bbp_subscription', true );

        if ( ! empty( $reply_subscriptions ) ) {
            $device_tokens = array();
            foreach( $reply_subscriptions as $token => $active ) {
                $device_tokens[] = $token;
            }
        }
        
        if ( ! empty( $topic_subscriptions ) ) {
            if ( empty( $device_tokens ) ) {
                $device_tokens = array();
            }
            foreach( $topic_subscriptions as $token => $active ) {
                $device_tokens[] = $token;
            }
        }

        if ( ! empty( $forum_subscriptions ) ) {
            if ( empty( $device_tokens ) ) {
                $device_tokens = array();
            }
            foreach( $forum_subscriptions as $token => $active ) {
                $device_tokens[] = $token;
            }
        }
        
        if ( empty( $device_tokens) ) {
            return;
        }
        Roost_API::send_notification( $message, $topic_url, null, $app_key, $app_secret, $device_tokens );
    }
}
