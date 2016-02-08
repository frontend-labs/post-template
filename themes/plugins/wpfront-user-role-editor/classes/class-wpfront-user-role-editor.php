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

require_once(plugin_dir_path(__FILE__) . "base/class-wpfront-base.php");

if (!class_exists('WPFront_User_Role_Editor')) {

    /**
     * Main class of WPFront User Role Editor Plugin
     *
     * @author Syam Mohan <syam@wpfront.com>
     * @copyright 2014 WPFront.com
     */
    class WPFront_User_Role_Editor extends WPFront_Base_URE {

        //Constants
        const VERSION = '2.1';
        const OPTIONS_GROUP_NAME = 'wpfront-user-role-editor-options-group';
        const OPTION_NAME = 'wpfront-user-role-editor-options';
        const PLUGIN_SLUG = 'wpfront-user-role-editor';

        public static $DYNAMIC_CAPS = array();
        public static $ROLE_CAPS = array('list_roles', 'create_roles', 'edit_roles', 'delete_roles', 'edit_role_menus', 'edit_posts_role_permissions', 'edit_pages_role_permissions');
        public static $DEFAULT_ROLES = array('administrator', 'editor', 'author', 'contributor', 'subscriber');
        public static $STANDARD_CAPABILITIES = array(
            'Dashboard' => array(
                'read' => array('administrator', 'editor', 'author', 'contributor', 'subscriber'),
                'edit_dashboard' => array('administrator')
            ),
            'Posts' => array(
                'publish_posts' => array('administrator', 'editor', 'author'),
                'edit_posts' => array('administrator', 'editor', 'author', 'contributor'),
                'delete_posts' => array('administrator', 'editor', 'author', 'contributor'),
                'edit_published_posts' => array('administrator', 'editor', 'author'),
                'delete_published_posts' => array('administrator', 'editor', 'author'),
                'edit_others_posts' => array('administrator', 'editor'),
                'delete_others_posts' => array('administrator', 'editor'),
                'read_private_posts' => array('administrator', 'editor'),
                'edit_private_posts' => array('administrator', 'editor'),
                'delete_private_posts' => array('administrator', 'editor'),
                'manage_categories' => array('administrator', 'editor')
            ),
            'Media' => array(
                'upload_files' => array('administrator', 'editor', 'author'),
                'unfiltered_upload' => array('administrator')
            ),
            'Pages' => array(
                'publish_pages' => array('administrator', 'editor'),
                'edit_pages' => array('administrator', 'editor'),
                'delete_pages' => array('administrator', 'editor'),
                'edit_published_pages' => array('administrator', 'editor'),
                'delete_published_pages' => array('administrator', 'editor'),
                'edit_others_pages' => array('administrator', 'editor'),
                'delete_others_pages' => array('administrator', 'editor'),
                'read_private_pages' => array('administrator', 'editor'),
                'edit_private_pages' => array('administrator', 'editor'),
                'delete_private_pages' => array('administrator', 'editor')
            ),
            'Comments' => array(
                'edit_comment' => array(),
                'moderate_comments' => array('administrator', 'editor')
            ),
            'Themes' => array(
                'switch_themes' => array('administrator'),
                'edit_theme_options' => array('administrator'),
                'edit_themes' => array('administrator'),
                'delete_themes' => array('administrator'),
                'install_themes' => array('administrator'),
                'update_themes' => array('administrator')
            ),
            'Plugins' => array(
                'activate_plugins' => array('administrator'),
                'edit_plugins' => array('administrator'),
                'install_plugins' => array('administrator'),
                'update_plugins' => array('administrator'),
                'delete_plugins' => array('administrator')
            ),
            'Users' => array(
                'list_users' => array('administrator'),
                'create_users' => array('administrator'),
                'edit_users' => array('administrator'),
                'delete_users' => array('administrator'),
                'promote_users' => array('administrator'),
                'add_users' => array('administrator'),
                'remove_users' => array('administrator')
            ),
            'Tools' => array(
                'import' => array('administrator'),
                'export' => array('administrator')
            ),
            'Admin' => array(
                'manage_options' => array('administrator'),
                'update_core' => array('administrator'),
                'unfiltered_html' => array('administrator', 'editor')
            ),
            'Links' => array(
                'manage_links' => array('administrator', 'editor')
            )
        );
        public static $DEPRECATED_CAPABILITIES = array(
            'Deprecated' => array(
                'edit_files' => array('administrator'),
                'level_0' => array('administrator', 'editor', 'author', 'contributor', 'subscriber'),
                'level_1' => array('administrator', 'editor', 'author', 'contributor'),
                'level_2' => array('administrator', 'editor', 'author'),
                'level_3' => array('administrator', 'editor'),
                'level_4' => array('administrator', 'editor'),
                'level_5' => array('administrator', 'editor'),
                'level_6' => array('administrator', 'editor'),
                'level_7' => array('administrator', 'editor'),
                'level_8' => array('administrator'),
                'level_9' => array('administrator'),
                'level_10' => array('administrator')
            )
        );
        public static $OTHER_CAPABILITIES = array(
            'Other Capabilities' => array(
            )
        );
        public static $CUSTOM_POST_TYPES_DEFAULTED = array();
        private static $CAPABILITIES = NULL;
        //Variables
        protected $admin_menu = array();
        protected $options;
        protected $objList;
        protected $objAddEdit;
        protected $objRestore;
        protected $objAssignUsers;
        protected $objGoPro;

        function __construct() {
            parent::__construct(__FILE__, self::PLUGIN_SLUG);

            //$this->add_menu($this->__('WPFront User Role Editor'), $this->__('User Role Editor'));

            $this->options = new WPFront_User_Role_Editor_Options($this);
            $this->objGoPro = new WPFront_User_Role_Editor_Go_Pro($this);

            if ($this->objGoPro->has_license()) {
                $this->objList = new WPFront_User_Role_Editor_List($this);
                $this->objAddEdit = new WPFront_User_Role_Editor_Add_Edit($this);
                $this->objRestore = new WPFront_User_Role_Editor_Restore($this);
                $this->objAssignUsers = new WPFront_User_Role_Editor_Assign_Roles($this);
            }
        }

        public function plugins_loaded() {
            
        }

        public function admin_init() {
            register_setting(self::OPTIONS_GROUP_NAME, self::OPTION_NAME);

            $this->rename_role_capabilities();
        }

        protected function add_submenu_page($position, $title, $name, $capability, $slug, $func, $scripts = NULL, $styles = NULL, $controller = NULL) {
            if ($scripts === NULL)
                $scripts = 'enqueue_role_scripts';
            if ($styles === NULL)
                $styles = 'enqueue_role_styles';

            $this->admin_menu[$position] = array($title, $name, $capability, $slug, $func, $scripts, $styles, $controller);
        }

        protected function add_pro_page() {
            if (isset($this->admin_menu[1000]))
                return;

            $this->add_submenu_page(1000, $this->__('Go Pro'), '<span class="wpfront-go-pro">' . $this->__('Go Pro') . '</span>', 'manage_options', WPFront_User_Role_Editor_Go_Pro::MENU_SLUG, array($this->objGoPro, 'go_pro'));
        }

        public function admin_menu() {
            //parent::admin_menu();

            $this->add_pro_page();

            if ($this->objGoPro->has_license())
                $menu_slug = WPFront_User_Role_Editor_List::MENU_SLUG;
            else
                $menu_slug = WPFront_User_Role_Editor_Go_Pro::MENU_SLUG;

            if ($this->objGoPro->has_license()) {
                $this->add_submenu_page(10, $this->__('Roles'), $this->__('All Roles'), $this->get_capability_string('list'), WPFront_User_Role_Editor_List::MENU_SLUG, array($this->objList, 'list_roles'), NULL, NULL, $this->objList);
                $this->add_submenu_page(20, $this->__('Add New Role'), $this->__('Add New'), $this->get_capability_string('create'), WPFront_User_Role_Editor_Add_Edit::MENU_SLUG, array($this->objAddEdit, 'add_edit_role'), NULL, NULL, $this->objAddEdit);
                $this->add_submenu_page(30, $this->__('Restore Role'), $this->__('Restore'), $this->get_capability_string('edit'), WPFront_User_Role_Editor_Restore::MENU_SLUG, array($this->objRestore, 'restore_role'), NULL, NULL, $this->objRestore);
                $this->add_submenu_page(100, $this->__('Settings'), $this->__('Settings'), 'manage_options', WPFront_User_Role_Editor_Options::MENU_SLUG, array($this->options, 'settings'), NULL, NULL, $this->options);
            }

            if (!empty($this->admin_menu))
                add_menu_page($this->__('Roles'), $this->__('Roles'), $this->get_capability_string('list'), $menu_slug, null, $this->pluginURL() . 'images/roles_menu.png', '69.999999');

            ksort($this->admin_menu);
            foreach ($this->admin_menu as $key => $value) {
                $page_hook_suffix = add_submenu_page($menu_slug, $value[0], $value[1], $value[2], $value[3], $value[4]);
                add_action('admin_print_scripts-' . $page_hook_suffix, array($this, $value[5]));
                add_action('admin_print_styles-' . $page_hook_suffix, array($this, $value[6]));
                if ($value[7] !== NULL)
                    $value[7]->set_page_hook($page_hook_suffix);
            }

            if ($this->objGoPro->has_license()) {
                $page_hook_suffix = add_users_page($this->__('Assign Roles | Migrate Users'), $this->__('Assign / Migrate'), 'promote_users', WPFront_User_Role_Editor_Assign_Roles::MENU_SLUG, array($this->objAssignUsers, 'assign_roles'));
                $this->objAssignUsers->set_page_hook($page_hook_suffix);
                add_action('admin_print_scripts-' . $page_hook_suffix, array($this, 'enqueue_role_scripts'));
                add_action('admin_print_styles-' . $page_hook_suffix, array($this, 'enqueue_role_styles'));
            }
        }

        //add scripts
        public function enqueue_role_scripts() {
//            $jsRoot = $this->pluginURLRoot . 'js/';

            wp_enqueue_script('jquery');
        }

        //add styles
        public function enqueue_role_styles() {
            wp_enqueue_style('font-awesome-410', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css', array(), '4.1.0');
            $styleRoot = $this->pluginURLRoot . 'css/';
            wp_enqueue_style('wpfront-user-role-editor-styles', $styleRoot . 'style.css', array(), self::VERSION);
        }

        //options page scripts
        public function enqueue_options_scripts() {
            $this->enqueue_role_scripts();
        }

        //options page styles
        public function enqueue_options_styles() {
            $this->enqueue_role_styles();

            $styleRoot = $this->pluginURLRoot . 'css/';
            wp_enqueue_style('wpfront-user-role-editor-options', $styleRoot . 'options.css', array(), self::VERSION);
        }

        public function get_capability_string($capability) {
            if ($this->enable_role_capabilities())
                return $capability . '_roles';

            return $capability . '_users';
        }

        public function permission_denied() {
            wp_die($this->__('You do not have sufficient permissions to access this page.'));
        }

        public function current_user_can($capability) {
            switch ($capability) {
                case 'list_roles':
                    return current_user_can($this->get_capability_string('list'));
                case 'edit_roles':
                    return current_user_can($this->get_capability_string('edit'));
                case 'delete_roles':
                    return current_user_can($this->get_capability_string('delete'));
                case 'create_roles':
                    return current_user_can($this->get_capability_string('create'));
                default :
                    return current_user_can($capability);
            }
        }

        public function create_nonce() {
            if (empty($_SERVER['REQUEST_URI'])) {
                $this->permission_denied();
                exit;
                return;
            }
            $referer = $_SERVER['REQUEST_URI'];
            echo '<input type = "hidden" name = "_wpnonce" value = "' . wp_create_nonce($referer) . '" />';
            echo '<input type = "hidden" name = "_wp_http_referer" value = "' . $referer . '" />';
        }

        public function verify_nonce() {
            if (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
                $flag = TRUE;
                if (empty($_POST['_wpnonce'])) {
                    $flag = FALSE;
                } else if (empty($_POST['_wp_http_referer'])) {
                    $flag = FALSE;
                } else if (!wp_verify_nonce($_POST['_wpnonce'], $_POST['_wp_http_referer'])) {
                    $flag = FALSE;
                }

                if (!$flag) {
                    $this->permission_denied();
                    exit;
                }
            }
        }

        public function footer() {
            return;
            ?>
            <div class="footer">
                <a target="_blank" href="http://wpfront.com/contact/"><?php echo $this->__('Feedback'); ?></a> 
                |
                <a target="_blank" href="http://wpfront.com/donate/"><?php echo $this->__('Buy me a Beer'); ?></a> 
            </div>
            <?php
        }

        public function options_page_header($title, $optionsGroupName = self::OPTIONS_GROUP_NAME) {
            parent::options_page_header($title, $optionsGroupName);
        }

        public function options_page_footer($settingsLink, $FAQLink, $extraLinks = NULL) {
            parent::options_page_footer($settingsLink, $FAQLink, $extraLinks);
        }

        public function get_capabilities($exclude_custom_post_types = FALSE) {
            if (self::$CAPABILITIES != NULL)
                return self::$CAPABILITIES;

            self::$CAPABILITIES = array();

            foreach (self::$STANDARD_CAPABILITIES as $key => $value) {
                self::$CAPABILITIES[$key] = array();
                foreach ($value as $cap => $roles) {
                    self::$CAPABILITIES[$key][] = $cap;
                }
            }

            foreach (self::$DEPRECATED_CAPABILITIES as $key => $value) {
                self::$CAPABILITIES[$key] = array();
                foreach ($value as $cap => $roles) {
                    self::$CAPABILITIES[$key][] = $cap;
                }
            }

            reset(self::$OTHER_CAPABILITIES);
            $other_key = key(self::$OTHER_CAPABILITIES);

            if($exclude_custom_post_types) {
                foreach (self::$DYNAMIC_CAPS as $cap) {
                    self::$ROLE_CAPS = array_diff(self::$ROLE_CAPS, array($cap));
                    self::$OTHER_CAPABILITIES[$other_key][] = $cap;
                }
            }
            
            if ($this->enable_role_capabilities())
                self::$CAPABILITIES['Roles (WPFront)'] = self::$ROLE_CAPS;

            global $wp_roles;
            if (isset($wp_roles->roles) && is_array($wp_roles->roles)) {
                foreach ($wp_roles->roles as $key => $role) {
                    foreach ($role['capabilities'] as $cap => $value) {
                        $found = FALSE;
                        foreach (self::$CAPABILITIES as $g => $wcaps) {
                            if (in_array($cap, $wcaps)) {
                                $found = TRUE;
                                break;
                            }
                        }
                        if (!$found && !in_array($cap, self::$OTHER_CAPABILITIES[$other_key])) {
                            self::$OTHER_CAPABILITIES[$other_key][] = $cap;
                        }
                    }
                }
            }

            if (!$exclude_custom_post_types) {
                $post_types = get_post_types(array(
                    '_builtin' => FALSE
                ));

                $other_caps = self::$OTHER_CAPABILITIES[$other_key];
                unset(self::$OTHER_CAPABILITIES[$other_key]);

                foreach ($post_types as $key => $value) {
                    $post_type_object = get_post_type_object($key);
                    $caps = $post_type_object->cap;

                    if ($post_type_object->capability_type === 'post') {
                        if ($post_type_object->show_ui)
                            self::$CUSTOM_POST_TYPES_DEFAULTED[$this->get_custom_post_type_label($post_type_object)] = array();
                    } else {
                        $caps = (OBJECT) $this->remove_meta_capabilities((ARRAY) $caps, $other_caps);
                        self::$OTHER_CAPABILITIES[$this->get_custom_post_type_label($post_type_object)] = array_values((array) $caps);
                    }
                }

                self::$OTHER_CAPABILITIES[$other_key] = $other_caps;
            }

            foreach (self::$OTHER_CAPABILITIES as $key => $value) {
                if (count($value) === 0)
                    continue;

                self::$CAPABILITIES[$key] = $value;

                if ($key != $other_key) {
                    foreach ($value as $cap) {
                        self::$OTHER_CAPABILITIES[$other_key] = array_values(array_diff(self::$OTHER_CAPABILITIES[$other_key], array($cap)));
                    }
                }
            }

            return self::$CAPABILITIES;
        }

        public function add_role_capability($cap) {
            self::$ROLE_CAPS[] = $cap;
            $this->add_dynamic_capability($cap);
        }

        public function add_dynamic_capability($cap) {
            self::$DYNAMIC_CAPS[$cap] = $cap;
        }

        private function get_custom_post_type_label($post_type_object) {
            return $post_type_object->labels->name . ' (' . $post_type_object->name . ')';
        }

        private function remove_meta_capabilities($caps, $other_caps) {
            foreach ($caps as $key => $value) {
                if ($key === 'read') {
                    unset($caps[$key]);
                    continue;
                }

                if (!in_array($value, $other_caps))
                    unset($caps[$key]);
            }

            if (array_key_exists('create_posts', $caps) && array_key_exists('edit_posts', $caps)) {
                if ($caps['create_posts'] === $caps['edit_posts'])
                    unset($caps['create_posts']);
            }

            return $caps;
        }

        public function reset_capabilities() {
            self::$CAPABILITIES = NULL;

            foreach (self::$OTHER_CAPABILITIES as $key => $value) {
                self::$OTHER_CAPABILITIES[$key] = array();
            }
        }

        public function display_deprecated() {
            return $this->options->display_deprecated();
        }

        public function enable_role_capabilities() {
            return TRUE;
        }

        public function remove_nonstandard_capabilities_restore() {
            return $this->options->remove_nonstandard_capabilities_restore();
        }

        public function override_edit_permissions() {
            return $this->options->override_edit_permissions();
        }

        public function customize_permission_custom_post_types() {
            return $this->options->customize_permission_custom_post_types();
        }

        public function enable_multisite_only_options($multisite) {
            return TRUE;
        }

        public function enable_pro_only_options() {
            return FALSE;
        }

        private function rename_role_capabilities() {
            global $wp_roles;
            foreach ($wp_roles->role_objects as $key => $role) {
                foreach (self::$ROLE_CAPS as $value) {
                    if ($role->has_cap('wpfront_' . $value)) {
                        $role->add_cap($value);
                        $role->remove_cap('wpfront_' . $value);
                    }
                }
            }

            $role_admin = $wp_roles->role_objects['administrator'];
            foreach (self::$ROLE_CAPS as $value) {
                $role_admin->add_cap($value);
            }
        }

        public static function Instanciate($file) {
            if (defined('WPFRONT_USER_ROLE_EDITOR_PLUGIN_FILE'))
                return;

            $f = 'wpfront-user-role-editor.php';
            $current_folder = strtolower(basename(dirname($file)));

            $folder = 'wpfront-user-role-editor-business-pro';
            if ($current_folder === $folder) {
                define('WPFRONT_USER_ROLE_EDITOR_PLUGIN_FILE', $file);
                new WPFront_User_Role_Editor_Business_Pro_Base();
                return;
            }

            if (!function_exists('is_plugin_active'))
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

            if (is_plugin_active($folder . '/' . $f))
                return;

            $folder = 'wpfront-user-role-editor-personal-pro';
            if ($current_folder === $folder) {
                define('WPFRONT_USER_ROLE_EDITOR_PLUGIN_FILE', $file);
                new WPFront_User_Role_Editor_Personal_Pro_Base();
                return;
            }

            if (is_plugin_active($folder . '/' . $f))
                return;

            define('WPFRONT_USER_ROLE_EDITOR_PLUGIN_FILE', $file);
            new WPFront_User_Role_Editor();
        }

    }

}

require_once(plugin_dir_path(__FILE__) . "class-wpfront-user-role-editor-controller-base.php");
require_once(plugin_dir_path(__FILE__) . "class-wpfront-user-role-editor-options.php");
require_once(plugin_dir_path(__FILE__) . "class-wpfront-user-role-editor-list.php");
require_once(plugin_dir_path(__FILE__) . "class-wpfront-user-role-editor-add-edit.php");
require_once(plugin_dir_path(__FILE__) . "class-wpfront-user-role-editor-delete.php");
require_once(plugin_dir_path(__FILE__) . "class-wpfront-user-role-editor-restore.php");
require_once(plugin_dir_path(__FILE__) . "class-wpfront-user-role-editor-assign-roles.php");
require_once(plugin_dir_path(__FILE__) . "class-wpfront-user-role-editor-go-pro.php");



if (file_exists(plugin_dir_path(__FILE__) . "personal-pro/class-wpfront-user-role-editor-personal-pro.php"))
    require_once(plugin_dir_path(__FILE__) . "personal-pro/class-wpfront-user-role-editor-personal-pro.php");

if (file_exists(plugin_dir_path(__FILE__) . "business-pro/class-wpfront-user-role-editor-business-pro.php"))
    require_once(plugin_dir_path(__FILE__) . "business-pro/class-wpfront-user-role-editor-business-pro.php");
