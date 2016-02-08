<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>

<div id="rooster">
    <div id="roost-header">
        <?php if ( $roost_active_key ) { ?>
            <div class="roost-wrapper">
                <div id="roost-header-right">
                    <form action="" method="post">
                        <input type="Submit" id="roost-log-out" name="clearkey" value="Log Out" />
                    </form>
                    <span id="roost-username">
                        <span id="roost-user-logo">
                            <?php echo get_avatar($roost_server_settings['ownerEmail'], 25 ); ?>
                        </span>
                        <?php
                            echo $roost_server_settings['ownerEmail'];
                        ?>
                    </span>
                </div>
                <img src="<?php echo ROOST_URL; ?>layout/images/roost-red-logo.png" />
                <?php if ( $roost_active_key ) { ?>
                    <div id="roost-site-name"><?php echo( $roost_server_settings['name'] ); ?></div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <?php if ( ! empty( $first_time ) ) { ?>
        <div class="updated roost-wrapper" id="roost-first-time-setup">
            <div id="roost-notice-text">
                <h3>Welcome to Roost, the plugin is up and running!</h3>
                <h4>Your site visitors can now opt-in to receive notifications from you. ( Safari / Mavericks only right now )</h4>
            </div>
            <div id="roost-notice-target">
                <a href="#" id="roost-notice-CTA" ><span id="roost-notice-CTA-highlight"></span>Dismiss</a>
            </div>
		</div>
    <?php } ?>
    <?php if ( isset( $status ) && empty( $first_time ) ){ ?>
        <div id="rooster-status"><span id="rooster-status-text"><?php echo($status); ?></span><span id="rooster-status-close">Dismiss</span></div>
    <?php } ?>
        <!--BEGIN ADMIN TABS-->
        <?php if ( $roost_active_key ) { ?>

            <div id="roost-tabs" class="roost-wrapper">
                <ul>
                    <li class="active">Dashboard</li>
                    <li>Send a notification</li>
                    <li>Settings</li>
                </ul>
            </div>
        <?php } ?>
        <!--END ADMIN TABS-->
        <div id="roost-pre-wrap" class="<?php echo( ! empty( $roost_active_key ) ? 'roost-white':''); ?>">
            <div id="roost-main-wrapper">
                    <!--BEGIN USER LOGIN SECTION-->
                    <?php if ( ! $roost_active_key ) { ?>
                        <form action="" method="post">
                            <div id="roost-login-wrapper">
                                <?php if ( empty( $roost_sites ) ){ ?>
                                    <div id="roost-signup-wrapper">
                                        <div id="roost-signup-inner">
                                            <img src="<?php echo ROOST_URL; ?>layout/images/roost_logo.png" alt="Roost Logo" />
                                            <h2>Create a free account</h2>
                                            <p>
                                                Welcome! Creating an account only takes a few seconds and will give you access 
                                                to additional features like our analytics dashboard at goroost.com
                                            </p>
                                            <a href="<?php echo( Roost::registration_url() ); ?>" id="roost-create-account" class="roost-signin-link"><img src="<?php echo ROOST_URL; ?>layout/images/roost-arrow-white.png" />Create an account</a>
                                            <div id="roost-bottom-right">Already have an account? <span class="roost-signup">Sign in</span></div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div id="roost-signin-wrapper" class="roost-login-account">
                                    <div id="roost-primary-logo">
                                        <img src="<?php echo ROOST_URL; ?>layout/images/roost_logo.png" alt="" />
                                    </div>
                                    <div class="roost-primary-heading">
                                        <span class="roost-primary-cta">Welcome! Log in to your Roost account below.</span>
                                        <span class="roost-secondary-cta">If you don’t have a Roost account <a href="<?php echo( Roost::registration_url() ); ?>" class="roost-signin-link">sign up for free!</a></span>
                                    </div>
                                    <div class="roost-section-content">
                                        <!--USER NAME-->
                                        <div class="roost-login-input">
                                            <span class="roost-label">Email:</span>
                                            <input name="roostuserlogin" type="text" class="roost-control-login" value="<?php echo isset($_POST['roostuserlogin']) ? $_POST['roostuserlogin'] : '' ?>" size="50" tabindex="1" />
                                        </div>
                                        <div class="roost-login-input">
                                            <!--PASSWORD-->
                                            <span class="roost-label">Password:</span>
                                            <input name="roostpasslogin" type="password" class="roost-control-login" value="<?php echo isset($_POST['roostpasslogin']) ? $_POST['roostpasslogin'] : '' ?>" size="50" tabindex="2" />
                                        </div>
                                        <?php if ( isset( $roost_sites ) ) { ?>
                                            <!--CONFIGS-->
                                            <div class="roost-login-input">

                                                <span class="roost-label">Choose a configurations to use:</span>

                                                <select id="roostsites" name="roostsites" class="roost-site-select">
                                                    <option value="none" selected="selected">-- Choose Site --</option>
                                                    <?php  
                                                        for($i = 0; $i < count( $roost_sites ); $i++ ) {
                                                    ?>
                                                        <option value="<?php echo $roost_sites[$i]['key'] . '|' . $roost_sites[$i]['secret']; ?>"><?php echo $roost_sites[$i]['name']; ?></option>
                                                    <?php 
                                                        }
                                                    ?>
                                                </select>
                                                <span class="roost-disclaimer">
                                                    To switch configurations after you log in, you will need to log out and choose a different configuration.
                                                </span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="roost-primary-footer">
                                        <input type="hidden" id="roost-timezone-offset" name="roost-timezone-offset" value="" />
                                        <input type="submit" id="roost-middle-save" class="roost-login" name="<?php echo isset($roost_sites) ? 'roostconfigselect' : 'roostlogin' ?>" value="<?php echo isset( $roost_sites ) ? 'Choose Site' : 'Login' ?>" tabindex="3" />
                                        <?php submit_button( 'Cancel', 'delete', 'cancel', false, array( 'tabindex' => '4' ) ); ?>
                                        <span class="roost-left-link"><a href="https://go.goroost.com/login?forgot=true" target="_blank">forget password?</a></span>
                                    </div>
                                    <div id="roost-sso">
                                        <div id="roost-sso-text">
                                            Or sign in with
                                        </div>
                                        <div class="roost-sso-option">
                                            <a href="<?php echo( Roost::login_url( 'FACEBOOK' ) ); ?>" class="roost-sso-link">
                                                <span id="roost-sso-facebook" class="roost-plugin-image">Facebook</span>
                                            </a>
                                        </div>
                                        <div class="roost-sso-option">  
                                            <a href="<?php echo( Roost::login_url( 'TWITTER' ) ); ?>" class="roost-sso-link"><span id="roost-sso-twitter" class="roost-plugin-image">Twitter</span></a>
                                        </div>
                                        <div class="roost-sso-option">
                                            <a href="<?php echo( Roost::login_url( 'GOOGLE' ) ); ?>" class="roost-sso-link"><span id="roost-sso-google" class="roost-plugin-image">Google</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                    <!--END USER LOGIN SECTION-->

                    <!--BEGIN ALL TIME STATS SECTION-->
                    <?php if ( $roost_active_key ) { ?>
                        <div id="roost-activity" class="roost-admin-section">
                            <div id="roost-all-stats">
                                <div class="roost-no-collapse">
                                    <div class="roostStats">
                                        <div class="roost-stats-metric">
                                            <span class="roost-stat"><?php echo(number_format($roost_stats['registrations'])); ?></span>
                                            <hr />
                                            <span class="roost-stat-label">Total subscribers on <?php echo( $roost_server_settings['name'] ); ?></span>
                                        </div>
                                        <div class="roost-stats-metric">
                                            <span class="roost-stat"><?php echo(number_format($roost_stats['notifications'])); ?></span>
                                            <hr />
                                            <span class="roost-stat-label">Total notifications sent to your subscribers</span>
                                        </div>
                                        <div class="roost-stats-metric">
                                            <span class="roost-stat"><?php echo(number_format($roost_stats['read'])); ?></span>
                                            <hr />
                                            <span class="roost-stat-label">Total notifications read by your subscribers</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php } ?>
                    <!--END ALL TIME STATS SECTION-->

                    <!--BEGIN RECENT ACTIVITY SECTION-->
                    <?php if ( $roost_active_key ) { ?>
                            <div class="roost-section-wrapper">
                                <div class="roost-section-heading" id="roost-chart-heading">
                                    Recent Activity
                                    <div id="roost-time-period">
                                        <span class="roost-chart-range-toggle roost-chart-reload"><span class="load-chart" data-type="APP" data-range="DAY">Day</span></span>
                                        <span class="roost-chart-range-toggle roost-chart-reload active"><span class="load-chart" data-type="APP" data-range="WEEK">Week</span></span>
                                        <span class="roost-chart-range-toggle roost-chart-reload"><span class="load-chart" data-type="APP" data-range="MONTH">Month</span></span>
                                    </div>
                                    <div id="roost-metric-options">
                                        <ul>
                                            <li class="roost-chart-metric-toggle roost-chart-reload active"><span class="chart-value" data-value="s">Subscribes</span></li>
                                            <li class="roost-chart-metric-toggle roost-chart-reload"><span class="chart-value" data-value="n">Notifications</span></li>
                                            <li class="roost-chart-metric-toggle roost-chart-reload"><span class="chart-value" data-value="r">Reads</span></li>
                                            <li class="roost-chart-metric-toggle roost-chart-reload"><span class="chart-value" data-value="u">Unsubscribes</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="roost-section-content roost-section-secondary" id="roost-recent-activity">
                                    <div class="roost-no-collapse">
                                        <div id="roost-curtain">
                                            <div id="roost-curtain-notice">Graphs will appear once you have some subscribers.</div>
                                        </div>
                                        <div id="roostchart-dynamic" class="roostStats">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!--END RECENT ACTIVITY SECTION-->

                    <!--BEGIN MANUAL PUSH SECTION-->
                    <?php if ( $roost_active_key ) { ?>
                        <form action="" method="post" id="manual-push-form">
                            <div id="roost-manual-push" class="roost-admin-section">
                                <div class="roost-section-wrapper">
                                    <span class="roost-section-heading">Send a manual push notification</span>
                                    <div class="roost-section-content roost-section-secondary" id="roost-manual-send-section">
                                        <div class="roost-no-collapse">
                                            <div id="roost-manual-send-wrapper">
                                                <div class="roost-send-type roost-send-active" id="roost-send-with-link" data-related="1">	
                                                    <div class="roost-input-text">
                                                        <div class="roost-label">Notification text:</div>
                                                        <div class="roost-input-wrapper">
                                                            <span id="roost-manual-note-count"><span id="roost-manual-note-count-int">0</span> / 70 (reccommended)</span>
                                                            <input name="manualtext" type="text" class="roost-control-secondary" id="roost-manual-note" value="" size="50" />
                                                            <span class="roost-input-caption">Enter the text for the notification you would like to send your subscribers.</span>
                                                        </div>
                                                    </div>
                                                    <div class="roost-input-text">
                                                        <div class="roost-label">Notification link:</div>
                                                        <div class="roost-input-wrapper">
                                                            <input name="manuallink" type="text" class="roost-control-secondary" value="" size="50" />
                                                            <span class="roost-input-caption">Enter a website link (URL) that your subscribers will be sent to upon clicking the notification.</span>
                                                        </div>
                                                    </div>
                                                    <input type="Submit" class="roost-control-secondary" name="manualpush" id="manualpush" value="Send notification" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                    <!--END MANUAL PUSH SECTION-->

                    <!--BEGIN SETTINGS SECTION-->
                    <?php if ( $roost_active_key ) { ?>
                        <form action="" method="post">
                            <div id="roost-settings" class="roost-admin-section">
                                <div class="roost-section-wrapper">
                                    <span class="roost-section-heading">Settings</span>
                                    <div class="roost-section-content roost-section-secondary">
                                        <div class="roost-no-collapse">
                                            <div class="roost-block <?php if ( ! empty( $roost_settings['prompt_event'] ) ) { echo( 'roost-settings-top-floor' ); } ?>">
                                                <div class="roost-setting-wrapper">
                                                    <span class="roost-label">Auto Push:</span>
                                                    <input type="checkbox" name="autoPush" class="roost-control-secondary" value="1" <?php if ( ! empty( $roost_settings['autoPush'] ) ) { echo( "checked='checked'" ); } ?> />
                                                    <span class="roost-setting-caption">Automatically send a push notification to your subscribers every time you publish a new post.</span>
                                                </div>
                                                <div class="roost-setting-wrapper">
                                                    <span class="roost-label">Activate all Roost features:</span>
                                                    <input type="checkbox" name="autoUpdate" class="roost-control-secondary" value="1" <?php if ( true == $roost_server_settings['autoUpdate'] ){ echo( "checked='checked'" ); } ?> />
                                                    <span class="roost-setting-caption">This will automatically activate current and future features as they are added to the plugin.</span>

                                                </div>
                                                <div class="roost-setting-wrapper">
                                                    <span class="roost-label">bbPress Push Notifications:</span>
                                                    <input type="checkbox" name="bbPress" class="roost-control-secondary" value="1" <?php if ( true == $roost_settings['bbPress'] ){ echo( 'checked="checked"' ); } ?> <?php echo( ! empty( $bbPress_active['present'] ) ? '' : 'disabled="disabled"' ); ?> />
                                                    <span class="roost-setting-caption">Extends subscriptions for bbPress forums, topics, and replies to allow subscribing via push notifications.</span>
                                                </div>
                                                <div class="roost-setting-wrapper">
                                                    <span class="roost-label long-label">Notification prompt options:</span>
                                                    <input type="checkbox" name="roost-prompt-min" id="roost-prompt-min" value="1" <?php if ( ! empty( $roost_settings['prompt_min'] ) ) { echo( 'checked="checked"' ); } ?> />
                                                    <span class="roost-setting-caption" id="roost-settings-lift">Prompt visitors for notifications when they visit the site <input name="roost-prompt-visits" type="text" id="roost-min-visits" value="<?php echo( $roost_settings['prompt_visits'] ); ?>" <?php echo( ! empty( $roost_settings['prompt_min'] ) ? '' : 'disabled="disabled"' ); ?>/>times.</span>
                                                </div>
                                                <div class="roost-setting-wrapper">
                                                    <span class="roost-label"></span>
                                                    <input type="checkbox" name="roost-prompt-event" id="roost-prompt-event" value="1" <?php if ( ! empty( $roost_settings['prompt_event'] ) ) { echo( 'checked="checked"' ); } ?> />
                                                    <span class="roost-setting-caption">Prompt visitors for notifications once they complete an action (clicking a button or link).</span>
                                                    <div id="roost-event-hints">
                                                        <div>
                                                            &bull; Assign the class <span class="roost-code">"roost-prompt-wp"</span> to any element to prompt the visitor on click.
                                                            <span id="roost-hint-code-line">Example: <span class="roost-code">&lt;a href="#" class="roost-prompt-wp"&gt;Receive Desktop Notifications&lt;/a&gt;</span></span>
                                                        </div>

                                                        &bull; You could also create a <a href="<?php echo( admin_url( 'nav-menus.php' ) ); ?>">menu item</a> and add the same class, to trigger the prompt.
                                                    </div>
                                                    <span id="roost-event-hints-disclaimer">*Links or buttons with this class will be hidden to visitors already subscribed, or using a browser that does not support push notifications.</span>
                                                </div>

                                                <input type="Submit" class="roost-control-secondary" id="roost-settings-save" name="savesettings" value="Save Settings" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                    <!--END SETTINGS SECTION-->
                <div id="roost-support-tag">Have Questions, Comments, or Need a Hand? Hit us up at <a href="mailto:support@goroost.com" target="_blank">support@goroost.com</a> We're Here to Help.</div>
            </div>
        </div>
	<script>
        (function( $ ){
            $( '#rooster-status-close' ).click( function() {
                $( '#rooster-status' ).css( 'display', 'none' );
            });
            $( '#roost-notice-CTA' ).click(function( e ) {
                e.preventDefault();
                $( '#roost-first-time-setup' ).css( 'display', 'none' );
            });
            var timeZoneOffset = new Date().getTimezoneOffset();
            $( '#roost-timezone-offset' ).val( timeZoneOffset );
            <?php if ( $roost_active_key ) { ?>
                $( '.roost-chart-range-toggle, .roost-chart-metric-toggle' ).on( 'click', function() {
                    $( this ).parent().find( '.active' ).removeClass( 'active' );
                    $( this ).addClass( 'active' );
                });
            <?php if ( 0 !== $roost_stats['registrations'] ) { ?>
                    $( '#roost-curtain' ).hide();
                    var chart;
                    var data = {
                        type: $( '.roost-chart-range-toggle.active span' ).data( 'type' ),
                        range: $( '.roost-chart-range-toggle.active span' ).data( 'range' ),
                        value: $( '.roost-chart-metric-toggle.active span' ).data( 'value' ),
                        offset: new Date().getTimezoneOffset(),
                        action: 'graph_reload',
                    };
                    $( '.roost-chart-reload' ).on( 'click', function( e ) {
                        e.preventDefault();
                        $( '#roostchart-dynamic' ).html( "" );
                        data = {
                            type: $( '.roost-chart-range-toggle.active span' ).data( 'type' ),
                            range: $( '.roost-chart-range-toggle.active span' ).data( 'range' ),
                            value: $( '.roost-chart-metric-toggle.active span' ).data( 'value' ),
                            offset: new Date().getTimezoneOffset(),
                            action: 'graph_reload',
                        };

                        graphDataRequest( data );
                    });
                    function graphDataRequest( data ) {
                        $.post( ajaxurl, data, function( response ) {
                            var data = $.parseJSON( response );
                            loadGraph( data );
                        });
                    }
                    function loadGraph( data ) {
                        $( '#roostchart-dynamic' ).html( '' );
                        chart = new Morris.Bar({
                            element: $( '#roostchart-dynamic' ),
                            data: data,
                            barColors: ['#e25351'],
                            xkey: 'label',
                            ykeys: ['value'],
                            labels: ['Value'],
                            hideHover: 'auto',
                            barRatio: 0.4,
                            xLabelAngle: 20
                        });
                    }
                    $( window ).resize( function() {
                        chart.redraw();
                    });
                    graphDataRequest( data );
                <?php } ?>
            <?php } ?>
        })( jQuery );
        <?php if ( isset( $roost_sites ) ){ ?>
			jQuery( '.roost-control-login' ).attr( 'disabled', 'disabled' );
		<?php } ?>
		<?php if ( $roost_active_key ) { ?>
            (function( $ ){
                function confirmMessage() {
                    if ( ! confirm( 'Are you sure you would like to send a notification?' ) ) {
                        return false;
                    } else {
                        return true;
                    }
                }
                $( '#manualpush' ).on( 'click', function( e ) {
                    e.preventDefault();
                    var subscribers = <?php echo $roost_stats['registrations']; ?>;
                    if ( 0 === subscribers ) {
                        var resub;
                        $.post( ajaxurl, { action: 'subs_check' }, function( response ) {
                            var response = $.parseJSON( response );
                            resub = response;
                            if ( 0 === resub ) {
                                alert('You must have one visitor subscribed to your site to send notifications');
                                return;
                            } else {
                                if ( true === confirmMessage() ) {
                                     $( '#manualpush' ).unbind( 'click' ).trigger( 'click' );
                                }
                            }
                        });
                    } else {
                        if ( true === confirmMessage() ) {
                             $( '#manualpush' ).unbind( 'click' ).trigger( 'click' );
                        }
                    }
                });
            })( jQuery );
		<?php } ?>
        <?php if ( empty( $roost_sites ) ){ ?>
            (function( $ ){
                if ( $( '#roost-login-wrapper' ).length ) {
                    var signup = $( '#roost-signup-wrapper' );
                    var signin = $( '#roost-signin-wrapper' );
                    signin.hide();
                    $( '.roost-signup' ).on( 'click', function() {
                        signup.toggle();
                        signin.toggle();
                    });
                }
            })( jQuery );
        <?php } ?>
	</script>
</div>
