<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2>PayPal Donations</h2>

    <h2 class="nav-tab-wrapper">
        <ul id="paypal-donations-tabs">
            <li id="paypal-donations-tab_1" class="nav-tab nav-tab-active"><?php _e('General', 'paypal-donations'); ?></li>
            <li id="paypal-donations-tab_2" class="nav-tab"><?php _e('Advanced', 'paypal-donations'); ?></li>
        </ul>
    </h2>

    <form method="post" action="options.php">
        <?php settings_fields($optionDBKey); ?>
        <div id="paypal-donations-tabs-content">
            <div id="paypal-donations-tab-content-1">
                <?php do_settings_sections($pageSlug); ?>
            </div>
        </div>
        <?php submit_button(); ?>
    </form>
