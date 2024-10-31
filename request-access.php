<?php
/*
Plugin Name: Request Access
Plugin URI: http://www.knewton.com
Description: Allow users to request access to your website
Version: 1.4
Author: Jon Tascher
Author URI: http://www.knewton.com
*/

if (!class_exists('RequestAccess')) {

	ob_start();

	register_activation_hook(__FILE__, array('RequestAccess', 'activate'));

	class RequestAccess {

		public static $text_domain = 'request-access';
		private static $_options;
		private static $_settings;
		public static $enabled = true;

		public function __construct() {
			add_action('plugins_loaded', array(&$this, 'plugins_loaded'));
			add_action('admin_menu', array(&$this, 'admin_menu'));
			add_action('admin_init', array(&$this, 'admin_init'));
			add_action('template_redirect', array(&$this, 'check_user'), 10, 0);
			add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
			add_action('save_post', array(&$this, 'save_meta_boxes'));
			add_action('update_option_request_access_settings', array(&$this, 'update_option_request_access_settings'), 10, 2);

			$shortcodes = array(
				'signup',
				'login',
				'login-link'
			);

			foreach ($shortcodes as $shortcode) {
				add_shortcode($shortcode, array(&$this, 'handle_shortcode'));
			}
		}

		public function plugins_loaded() {
			 load_plugin_textdomain('request-access', false, basename(self::get_plugin_path()) . '/languages');
		}

		public function admin_menu() {
			 $parent = add_menu_page(__('Request Access', self::$text_domain), __('Request Access', self::$text_domain), 'manage_request_access_users', 'request-access', array(&$this, 'pending'));
			 $log = add_submenu_page('request-access', __('Request Access - Log'), __('Log'), 'manage_options', 'request-access-log', array(&$this, 'log'));
			 $options = add_submenu_page('request-access', __('Request Access - Options'), __('Options'), 'manage_options', 'request-access-options', array(&$this, 'options'));
		}

		public function admin_init() {
			register_setting('request_access', 'request_access');
			register_setting('request_access_settings', 'request_access_settings');
		}

		public function update_option_request_access_settings($old, $new) {

			global $wp_roles;
			
			foreach (apply_filters('editable_roles', $wp_roles->roles) as $role) {
				$role = get_role(strtolower($role['name']));

				if (!is_object($role)) {
					continue;
				}
				
				if ($role->name == 'administrator') {
					$role->add_cap('manage_request_access_users');
				} else {
					if (isset($new['allowed_roles'][ucfirst($role->name)])) {
						$role->add_cap('manage_request_access_users');
					} else {
						$role->remove_cap('manage_request_access_users');
					}
				}
			}
		}

		public function add_meta_boxes() {
			$ignore = array(
				'attachment',
				'revision',
				'nav_menu_item'
			);

			foreach (get_post_types() as $type) {
				if (!in_array($type, $ignore)) {
					add_meta_box('request_access', __('Request Access', self::$text_domain), array(&$this, 'meta_box_inner'), $type, 'side', 'default');
				}
			}
		}

		public function meta_box_inner($post) {
			wp_nonce_field(plugin_basename(__FILE__), 'request_access_nonce');
			$value = get_post_meta($post->ID, '_request_access_public', true);
			echo '<label for="request_access_public">';
       		$checked = $value ? ' checked="checked"' : '';
       		echo '<input style="float:left;" type="checkbox" id="request_access_public" name="request_access_public" value="1"' . $checked . ' />';
       		echo '<span style="width:85%;float:left;padding-left:10px;">' . __("Allow this page to be viewed by logged out users.", self::$text_domain) . '</span><br clear="all" /></label>';
		}

		public function save_meta_boxes($post_id) {
			
			//make sure the user can edit this data
			if ($_POST['post_type'] == 'page' && !current_user_can('edit_page', $post_id)) {
				return;
			} elseif (!current_user_can('edit_post', $post_id)) {
				return;
			}

			//check the nonce
			if (!isset($_POST['request_access_nonce']) || !wp_verify_nonce($_POST['request_access_nonce'], plugin_basename(__FILE__))) {
				return;
			}

			if (isset($_POST['request_access_public']) && $_POST['request_access_public'] == 1) {
				update_post_meta($post_id, '_request_access_public', 1);
			} else {
				update_post_meta($post_id, '_request_access_public', 0);
			}


		}

		public function activate() {
			$pending_users = self::get_option('pending_users');
			if (!is_array($pending_users)) {
				self::update_option('pending_users', array());
			}

			$denied_users = self::get_option('denied_users');
			if (!is_array($denied_users)) {
				self::update_option('denied_users', array());
			}

			$log = self::get_option('log');
			if (!is_array($log)) {
				self::update_option('log', array());
			}

			if (!self::get_setting('approved_email_subject')) {
				self::update_setting('approved_email_subject', 'Welcome to our website.');
			}

			if (!self::get_setting('approved_email')) {
				self::update_setting('approved_email', <<<EOT
Hi there,

You're now ready to rock on our website.

See you there!
EOT
);
			}

			if (!self::get_setting('denied_email_subject')) {
				self::update_setting('denied_email_subject', 'Unfortunately, you\'ve been denied access to our website.');
			}

			if (!self::get_setting('denied_email')) {
				self::update_setting('denied_email', <<<EOT
Hi there,

Your request for an account on our website has been denied.

If you believe this is an error, please ask your point of contact to investigate why your account request was denied.

Best regards
EOT
);
			}
		}

		public static function get_option($option) {
            
            if (!is_array(self::$_options)) {
                self::$_options = get_option('request_access');
            }
            
            return isset(self::$_options[$option]) ? self::$_options[$option] : null;
        }
        
        public static function update_option($option, $value) {
            self::$_options[$option] = $value;
            update_option('request_access', self::$_options);
        }


        public static function get_setting($setting) {
            
            if (!is_array(self::$_settings)) {
                self::$_settings = get_option('request_access_settings');
            }
            
            return isset(self::$_settings[$setting]) ? self::$_settings[$setting] : null;
        }
        
        public static function update_setting($setting, $value) {
            self::$_settings[$setting] = $value;
            update_option('request_access_settings', self::$_settings);
        }

		public static function get_plugin_url() {
			return plugins_url(plugin_basename(dirname(__FILE__)));
		}
		
		public static function get_plugin_path() {
			return preg_replace('|^http://[^/].*?/|i', '/', plugins_url(plugin_basename(dirname(__FILE__))));
		}

		public static function get_user_status($user_id = false) {

			if (!$user_id) {
				$user = wp_get_current_user();
				$user_id = $user->ID;
			}

			return get_user_meta($user_id, 'request-access-status', true);
		}

		public function check_user($do_redirect = true) {

			if (is_admin()) {
				return true;
			}

			$public = get_post_meta(get_the_ID(), '_request_access_public', true);
			if ($public) {
				//this page is explicity set to public by an admin, so do nothing
				return true;
			}

			$post = get_post(get_the_ID());

			//check to see if this is the login or signup page
			$ignore = array(
				self::get_setting('login_page'), 
				self::get_setting('signup_page')
			);

			if (in_array($post->ID, $ignore)) {
				return true;
			}

			if (!is_user_logged_in()) {
				$redirect = get_post(self::get_setting('login_page'));
			} else {
				//the user is logged in, so check their status
				$user = wp_get_current_user();
				$user_status = self::get_user_status($user->ID);

				$redirect = false;

				if ($user_status == 'pending') {

					//if you are pending, and viewing the thanks page, set a flag that this user has already seen the thanks page
					//if this flag is already set, redirect the user to the pending page
					if ($post->ID == self::get_setting('thanks_page')) {

						$seen_thanks = get_user_meta($user->ID, 'request-access-seen-thanks', true);

						if ($seen_thanks) {
							$redirect = get_post(self::get_setting('pending_page'));
						} else {
							update_user_meta($user->ID, 'request-access-seen-thanks', true);
						}
					} elseif ($post->ID != self::get_setting('pending_page')) {
						$redirect = get_post(self::get_setting('pending_page'));
					}

				} elseif ($user_status == 'denied') {
					if ($post->ID != self::get_setting('denied_page')) {
						$redirect = get_post(self::get_setting('denied_page'));
					}
				} else {
					if ($post->ID == self::get_setting('pending_page') || $post->ID == self::get_setting('thanks_page')) {
						$redirect = get_post(get_option('page_on_front'));
					}
				}
			}

			if ($redirect && $do_redirect) {
				wp_redirect(get_permalink($redirect->ID));
				die;
			} else {
				return false;
			}
		}

		public function pending() {

			$pending_users = self::get_option('pending_users');
			$denied_users = self::get_option('denied_users');

			if (isset($_GET['action']) && in_array($_GET['action'], array('approve', 'deny'))) {
				$user_id = (int)$_GET['id'];

				if ($_GET['action'] == 'approve') {
					//if they are approved, just delete their status and remove from the pending array
					$pending_users = array_diff($pending_users, array($user_id));
					delete_user_meta($user_id, 'request-access-status');
					self::update_option('pending_users', $pending_users);
					$subject = self::get_setting('approved_email_subject');
					$body = self::get_setting('approved_email');
				} else {
					//they were denied, so update their status to denied and move them from the pending array to the denied array
					$pending_users = array_diff($pending_users, array($user_id));
					$denied_users[] = $user_id;
					$denied_users = array_unique($denied_users);
					self::update_option('pending_users', $pending_users);
					self::update_option('denied_users', $denied_users);
					update_user_meta($user_id, 'request-access-status', 'denied');
					$subject = self::get_setting('denied_email_subject');
					$body = self::get_setting('denied_email');
				}

				//log what happened
				$log = self::get_option('log');

				global $current_user;
				get_currentuserinfo();

				$log[] = array(
					'user_id' => $user_id,
					'reviewed_datetime' => time(),
					'reviewed_by' => $current_user->ID,
					'new_status' => $_GET['action']
				);

				self::update_option('log', $log);

				//send the email (pending/denied)
				$changed_user = get_userdata($user_id);
				wp_mail($changed_user->user_email, $subject, nl2br($body), array('Content-Type: text/html'));

				//clear the cache
				if (function_exists('wp_cache_flush')) {
					wp_cache_flush();
				}

				//redirect back to pending page with update notice
				wp_redirect(get_admin_url() . '/admin.php?page=request-access&message=1');
				die;
			}

			$filter = 'pending';
			if (isset($_GET['user_filter']) && $_GET['user_filter'] == 'all') {
				$filter = 'all';
			} elseif (isset($_GET['user_filter']) && $_GET['user_filter'] == 'denied') {
				$filter = 'denied';
			}

			if ($filter == 'all') {
				$users = array_merge($pending_users, $denied_users);
			} elseif ($filter == 'pending') {
				$users = $pending_users;
			} elseif ($filter == 'denied') {
				$users = $denied_users;
			}

			include('templates/pending.php');
		}

		public function log() {
			$log = self::get_option('log');
			include('templates/log.php');
		}

		public function options() {
			include('templates/options.php');	
		}

		public function handle_shortcode($args, $content, $shortcode) {
			$shortcode = trim(strtolower($shortcode));
		    ob_start();

		    switch ($shortcode) {
		    	case 'signup':
		    		$errors = self::_signup();
		    		include('templates/signup.php');
		    		break;
		    	case 'login':
			    	$errors = self::_login();
		    		include('templates/login.php');
		    		break;
		    	case 'login-link':
		    		$login_page = self::get_setting('login_page');
		    		$text = $content;
		    		if (!$text) {
		    			$text = __('Click here to login.', self::$text_domain);
		    		}
		    		echo '<a href="' . get_permalink($login_page->ID) . '">' . $text . '</a>';
		    }

		    $html = ob_get_contents();
		    ob_clean();
		    return $html;
		}

		private static function _login() {

			if (is_user_logged_in()) {
				wp_redirect('/');
				die;
			}

			$errors = array();

			if (isset($_POST['request-access-login']) && $_POST['request-access-login']) {
				$result = wp_signon();

				if (is_wp_error($result)) {
					$errors[] = __('Invalid username or password.', self::$text_domain);
				} else {
					$user_status = self::get_user_status();

					$redirect = false;

					if ($user_status == 'pending') {
						$redirect = get_post(self::get_setting('pending_page'));
					} elseif ($user_status == 'denied') {
						$redirect = get_post(self::get_setting('denied_page'));
					}

					if ($redirect) {
						wp_redirect(get_permalink($redirect->ID));
						die;
					} else {
						wp_redirect('/');
						die;
					}
				}
			}

			return $errors;
		}

		private static function _signup() {

			$errors = array();

			if (isset($_POST['request-access-form']) && $_POST['request-access-form']) {

				$required_fields = array(
					'first_name' => __('Please enter your first name.', self::$text_domain),
					'last_name' => __('Please enter your last name.', self::$text_domain),
					'password1' => __('Please enter a password.', self::$text_domain),
					'company' => __('Please enter your company.', self::$text_domain),
					'title' => __('Please enter your title.', self::$text_domain),
					'email' => __('Please enter your email address.', self::$text_domain),
					'poc' => __('Please enter yoru point of contact.', self::$text_domain)
				);

				foreach ($required_fields as $field => $error_message) {
					if (!isset($_POST[$field]) || !$_POST[$field]) {
						$errors[$field] = $error_message;
					}
				}

				if (!isset($errors['email']) && !is_email($_POST['email'])) {
					$errors['email'] = __('Please enter a valid email address.', self::$text_domain);
				}

				if (!isset($errors['password1']) && $_POST['password1'] != $_POST['password2']) {
					$errors['password1'] = __('Your passwords do not match.', self::$text_domain);
				}

				if (!isset($errors['email']) && email_exists($_POST['email'])) {
					$errors['email'] = __('That email address already exists.', self::$text_domain);
				}

				if (!isset($errors['email']) && username_exists($_POST['email'])) {
					$errors['email'] = __('That username already exists.', self::$text_domain);
				}

				if (!$errors) {
					//create the user and mark it as pending
					$userdata = array(
						'user_login' => $_POST['email'],
						'user_email' => $_POST['email'],
						'user_pass' => $_POST['password1'],
						'first_name' => $_POST['first_name'],
						'last_name' => $_POST['last_nanme'],
						'role' => get_option('default_role'),
					);

					$user_id = wp_insert_user($userdata);

					if (is_wp_error($user_id)) {
						$errors['create_user'] =  $user_id->get_error_message();
					} else {
						//insert usermeta for company, title, and poc
						update_user_meta($user_id, 'company', $_POST['company']);
						update_user_meta($user_id, 'title', $_POST['title']);
						update_user_meta($user_id, 'poc', $_POST['poc']);

						//set the user to pending
						update_user_meta($user_id, 'request-access-status', 'pending');

						//add to the list of pending users (for admin display)
						$pending_users = self::get_option('pending_users');

						$pending_users[] = $user_id;

						$new_user = get_userdata($user_id);

						self::update_option('pending_users', $pending_users);

						//log the user in if they aren't already logged in
						if (!is_user_logged_in()) {
							wp_set_current_user($user_id);
							wp_set_auth_cookie($user_id, true);
							do_action('wp_login', $new_user->user_login, $new_user);
						}

						//notify the admin
						wp_mail(self::get_setting('notification_emails'), __('Request Access Plugin: New User Pending', self::$text_domain), '<a href="' . admin_url('admin.php?page=request-access') . '">' . __('There is a new pending user to approve.', self::$text_domain) . '</a><br /><br />' . __('Their email address is: ', self::$text_domain) . $new_user->user_email . __(' and their listed POC is: ', self::$text_domain) . $_POST['poc'], array('Content-Type: text/html'));

						//clear the cache
						if (function_exists('wp_cache_flush')) {
							wp_cache_flush();
						}

						//redirect to thanks page
						$thanks = get_post(self::get_setting('thanks_page'));
						wp_redirect(get_permalink($thanks->ID));
						die;
					}
				}
			}

			return $errors;
		}

	}

	$ra = new RequestAccess;
}