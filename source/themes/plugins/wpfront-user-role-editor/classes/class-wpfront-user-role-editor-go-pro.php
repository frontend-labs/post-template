<?php

/*
  WPFront User Role Editor Plugin
  Copyright (C) 2014, WPFront.com
  Website: wpfront.com
  Contact: syam@wpfront.com

  WPFront User Role Editor Plugin is distributed under the GNU General Public License, Version 3,
  June 2007. Copyright (C) 2007 Free Software Foundation, Inc., 51 Franklin
  St, Fifth Floor, Boston, MA 02110, USA

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
  ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
  ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

if (!class_exists('WPFront_User_Role_Editor_Go_Pro')) {

    /**
     * Go Pro
     *
     * @author Syam Mohan <syam@wpfront.com>
     * @copyright 2014 WPFront.com
     */
    class WPFront_User_Role_Editor_Go_Pro extends WPFront_User_Role_Editor_Controller_Base {

        const MENU_SLUG = 'wpfront-user-role-editor-go-pro';

        private static $go_pro_html_url = 'https://wpfront.com/syam/wordpress-plugins/wpfront-user-role-editor/pro/comparison/';
        private static $store_url = 'https://wpfront.com/';
        private $pro_html = '';
        private $has_license = FALSE;
        private $need_license = FALSE;
        private $license_key = NULL;
        private $license_key_k = NULL;
        private $license_expires = NULL;
        private $license_expired = FALSE;
        private $product = NULL;
        private $error = NULL;

        public function __construct($main) {
            parent::__construct($main);

            $this->ajax_register('wp_ajax_wpfront_user_role_editor_license_functions', array($this, 'license_functions'));
        }

        public function go_pro() {
            $this->main->verify_nonce();

            if (!current_user_can('manage_options')) {
                $this->main->permission_denied();
                return;
            }

            if (!empty($_POST['license_key']) && !empty($_POST['activate'])) {
                $this->activate_license($_POST['license_key']);
            }

            if (!empty($_POST['deactivate'])) {
                $this->deactivate_license();
            }

            $options = new WPFront_User_Role_Editor_Entity_Options();

            $time_key = self::MENU_SLUG . '-html-last-update';
            $html_key = self::MENU_SLUG . '-html';

            $time = $options->get_option($time_key);

            if ($time === NULL || $time < time() - 24 * 3600) {
                $options->update_option($time_key, time());
                $result = wp_remote_get(self::$go_pro_html_url, array('timeout' => 15, 'sslverify' => false));
                if (!is_wp_error($result) && wp_remote_retrieve_response_code($result) == 200) {
                    $this->pro_html = wp_remote_retrieve_body($result);
                    $options->update_option($html_key, $this->pro_html);
                }
            }

            if ($this->pro_html === '') {
                $key = self::MENU_SLUG . '-html';
                $this->pro_html = $options->get_option($key);
                if ($this->pro_html === NULL)
                    $this->pro_html = '';
            }
            
            if($this->pro_html === '') {
                $this->pro_html = file_get_contents($this->main->pluginDIR() . 'templates/go-pro-table');
            }

            include($this->main->pluginDIR() . 'templates/go-pro.php');
        }

        public function set_license($key = NULL, $product = NULL) {
            if ($key === NULL && $this->license_key_k === NULL)
                return;

            if ($key !== NULL) {
                $this->need_license = TRUE;
                $this->license_key_k = $key . '-license-key';
                $this->product = $product;
            }

            if (is_multisite()) {
                $options = new WPFront_User_Role_Editor_Options($this->main);
                switch_to_blog($options->ms_options_blog_id());
            }

            $entity = new WPFront_User_Role_Editor_Entity_Options();
            $this->license_key = $entity->get_option($this->license_key_k);
            if ($this->license_key !== NULL) {
                $last_checked = $entity->get_option($this->license_key_k . '-last-checked');
                if ($last_checked < time() - 24 * 3600) {
                    $entity->update_option($this->license_key_k . '-last-checked', time());
                    $result = $this->remote_get('check_license', $this->license_key);
                    if (($result->activations_left === 'unlimited' || $result->activations_left >= 0) && ($result->license === 'valid' || $result->license === 'expired')) {
                        $entity->update_option($this->license_key_k . '-status', $result->license);
                        $entity->update_option($this->license_key_k . '-expires', $result->expires);
                    } else {
                        $this->deactivate_license(TRUE);
                        return;
                    }
                }
                $this->has_license = TRUE;
                $this->license_expired = $entity->get_option($this->license_key_k . '-status') === 'expired';
                $this->license_expires = date('F d, Y', strtotime($entity->get_option($this->license_key_k . '-expires')));

                for ($i = 0; $i < strlen($this->license_key) - 4; $i++) {
                    $this->license_key = substr_replace($this->license_key, 'X', $i, 1);
                }

                //Software licensing change
                $this->edd_plugin_update();
                //add_action('admin_init', array($this, 'edd_plugin_update'));
            } else {
                $this->license_key = '';
                $this->has_license = FALSE;
                $this->license_expires = NULL;
            }

            if (is_multisite()) {
                restore_current_blog();
            }
        }

        private function activate_license($license) {
            if ($this->license_key_k === NULL)
                return;

            $this->license_key = $license;

            $result = $this->remote_get('activate_license', $license);
            if ($result === NULL)
                return;

            $entity = new WPFront_User_Role_Editor_Entity_Options();
            $entity->delete_option($this->license_key_k);
            $entity->delete_option($this->license_key_k . '-expires');
            $entity->delete_option($this->license_key_k . '-last-checked');

            if ($result->license === 'valid' || $result->error === 'expired') {
                $entity->update_option($this->license_key_k, $license);
                $entity->update_option($this->license_key_k . '-status', $result->license === 'valid' ? 'valid' : 'expired');
                $entity->update_option($this->license_key_k . '-expires', $result->expires);
                $entity->update_option($this->license_key_k . '-last-checked', time());

                $this->set_license();
            } elseif ($result->error === 'no_activations_left') {
                $this->error = $this->__('ERROR') . ': ' . $this->__('License key activation limit reached');
            } else {
                $this->error = $this->__('ERROR') . ': ' . $this->__('Invalid license key');
            }
        }

        private function deactivate_license($forced = TRUE) {
            if ($this->license_key_k === NULL)
                return;

            $entity = new WPFront_User_Role_Editor_Entity_Options();
            $this->license_key = $entity->get_option($this->license_key_k);

            $result = $this->remote_get('deactivate_license', $this->license_key);
            if ($result === NULL)
                return;

            if ($result->license === 'deactivated' || $forced) {
                $entity->delete_option($this->license_key_k);
                $entity->delete_option($this->license_key_k . '-expires');
                $entity->delete_option($this->license_key_k . '-last-checked');
            } else {
                $this->error = $this->__('ERROR') . ': ' . $this->__('Unable to deactivate, expired license?');
            }

            $this->set_license();
        }

        private function remote_get($action, $license) {
            if ($this->product === NULL)
                return NULL;

            $api_params = array(
                'edd_action' => $action,
                'license' => urlencode($license),
                'item_name' => urlencode($this->product),
                'url' => urlencode(home_url())
            );

            $response = wp_remote_get(add_query_arg($api_params, self::$store_url), array('timeout' => 15, 'sslverify' => false));
            if (is_wp_error($response)) {
                $this->error = $this->__('ERROR') . ': ' . $this->__('Unable to contact wpfront.com')
                        . '<br />'
                        . $this->__('Details') . ': ' . $response->get_error_message();
                return NULL;
            }

            $result = json_decode(wp_remote_retrieve_body($response));

            if (!is_object($result)) {
                $this->error = $this->__('ERROR') . ': ' . $this->__('Unable to parse response');
                return NULL;
            }

            return $result;
        }

        public function edd_plugin_update() {
            $entity = new WPFront_User_Role_Editor_Entity_Options();

            new EDD_SL_Plugin_Updater(self::$store_url, WPFRONT_USER_ROLE_EDITOR_PLUGIN_FILE, array(
                'version' => WPFront_User_Role_Editor::VERSION,
                'license' => $entity->get_option($this->license_key_k),
                'item_name' => $this->product,
                'author' => 'Syam Mohan'
            ));
        }

        public function license_functions() {
            if (!wp_verify_nonce($_POST['_wpnonce'], $_POST['_wp_http_referer'])) {
                echo 'true';
                die();
            }

            if (!current_user_can('manage_options')) {
                echo 'true';
                die();
            }

            if (!empty($_POST['license_key']) && !empty($_POST['activate'])) {
                $this->activate_license($_POST['license_key']);
            }

            if (!empty($_POST['deactivate'])) {
                $this->deactivate_license();
            }

            if ($this->error === NULL)
                echo 'true';
            else
                echo 'false';
            die();
        }

        public function has_license() {
            if ($this->need_license)
                return $this->has_license;

            return TRUE;
        }

    }

}