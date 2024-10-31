<div id="wpbody">
	<div id="wpbody-content" aria-label="Main content" tabindex="0">
		<div class="wrap">
			<div id="icon-users" class="icon32">
				<br />
			</div>
			<h2><?php _e('Request Access - Options', RequestAccess::$text_domain) ?></h2>

			<br clear="all" />

			<?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true'): ?>
				<div id="message" class="updated below-h2" style="">
					<p><?php _e('Options updated.', RequestAccess::$text_domain) ?></p>
				</div>
			<?php endif ?>

			<form method="post" action="options.php"> 
				<?php settings_fields('request_access_settings') ?>
				<?php $options = get_option('request_access_settings') ?>

				<table class="form-table">
		            <tbody>

						<tr>
		                    <th>
		                        <label for="signup_page"><?php echo __('Sign Up Page', RequestAccess::$text_domain) ?></label>
		                    </th>
		                    <td>
		                    	<?php wp_dropdown_pages(array('name' => 'request_access_settings[signup_page]', 'selected' => $options['signup_page'])) ?>
		                    </td>
		                </tr>

		                <tr>
		                    <th>
		                        <label for="login_page"><?php echo __('Log In Page', RequestAccess::$text_domain) ?></label>
		                    </th>
		                    <td>
		                    	<?php wp_dropdown_pages(array('name' => 'request_access_settings[login_page]', 'selected' => $options['login_page'])) ?>
		                    </td>
		                </tr>

		                <tr>
		                    <th>
		                        <label for="thanks_page"><?php echo __('Thanks Page', RequestAccess::$text_domain) ?></label>
		                    </th>
		                    <td>
		                    	<?php wp_dropdown_pages(array('name' => 'request_access_settings[thanks_page]', 'selected' => $options['thanks_page'])) ?>
		                    </td>
		                </tr>

		                <tr>
		                    <th>
		                        <label for="pending_page"><?php echo __('Pending Page', RequestAccess::$text_domain) ?></label>
		                    </th>
		                    <td>
		                    	<?php wp_dropdown_pages(array('name' => 'request_access_settings[pending_page]', 'selected' => $options['pending_page'])) ?>
		                    </td>
		                </tr>

		                <tr>
		                    <th>
		                        <label for="denied_page"><?php echo __('Denied Page', RequestAccess::$text_domain) ?></label>
		                    </th>
		                    <td>
		                    	<?php wp_dropdown_pages(array('name' => 'request_access_settings[denied_page]', 'selected' => $options['denied_page'])) ?>
		                    </td>
		                </tr>

		                <tr>
		                    <th>
		                        <label for="notification_emails"><?php echo __('Notification Emails', RequestAccess::$text_domain) ?></label>
		                    </th>
		                    <td>
								<input type="text" class="large-text" name="request_access_settings[notification_emails]" value="<?php echo $options['notification_emails'] ?>" />
		                    </td>
		                </tr>

		                <tr>
		                    <th>
		                        <label for="approved_email_subject"><?php echo __('Approved Email Subject', RequestAccess::$text_domain) ?></label>
		                    </th>
		                    <td>
		                    	<input type="text" name="request_access_settings[approved_email_subject]" id="approved_email_subject" class="large-text" value="<?php echo $options['approved_email_subject'] ?>" />
		                    </td>
		                </tr>

		                <tr>
		                    <th>
		                        <label for="approved_email"><?php echo __('Approved Email Body', RequestAccess::$text_domain) ?></label>
		                    </th>
		                    <td>
		                    	<textarea rows="10" cols="50" name="request_access_settings[approved_email]" id="approved_email" class="large-text code"><?php echo $options['approved_email'] ?></textarea>
		                    </td>
		                </tr>

		                <tr>
		                    <th>
		                        <label for="denied_email_subject"><?php echo __('Denied Email Subject', RequestAccess::$text_domain) ?></label>
		                    </th>
		                    <td>
		                    	<input type="text" name="request_access_settings[denied_email_subject]" id="denied_email_subject" class="large-text" value="<?php echo $options['denied_email_subject'] ?>" />
		                    </td>
		                </tr>

		                <tr>
		                    <th>
		                        <label for="denied_email"><?php echo __('Denied Email Body', RequestAccess::$text_domain) ?></label>
		                    </th>
		                    <td>
		                    	<textarea rows="10" cols="50" name="request_access_settings[denied_email]" id="denied_email" class="large-text code"><?php echo $options['denied_email'] ?></textarea>
		                    </td>
		                </tr>

		                <tr>
		                	<th><?php echo __('Roles allowed to approve/deny pending requests', RequestAccess::$text_domain) ?></th>
			                <td>
			                	<?php global $wp_roles ?>
			                	<?php foreach (apply_filters('editable_roles', $wp_roles->roles) as $role): ?>
			                		<?php if ($role['name'] == 'Administrator') continue ?>
			                		<label><input type="checkbox" name="request_access_settings[allowed_roles][<?php echo $role['name'] ?>]" value="1"<?php echo (isset($options['allowed_roles'][$role['name']])) ? ' checked' : '' ?>/>&nbsp;&nbsp;<?php echo $role['name'] ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				                <?php endforeach ?>
			                </td>
			            </tr>
		            </tbody>
	            </table>

	            <p class="submit">
		            <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes') ?>">
		        </p>
			</form>
		</div>
	</div>
</div>

