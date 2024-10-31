<div id="wpbody">
	<div id="wpbody-content" aria-label="Main content" tabindex="0">
		<div class="wrap">
			<div id="icon-users" class="icon32">
				<br>
			</div>
			<h2><?php _e('Request Access - Log', RequestAccess::$text_domain) ?></h2>

			<br clear="all" />


			<table class="wp-list-table widefat fixed users" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" id="name" class="manage-column column-name">
							<a href="#"><span><?php _e('Name', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-email">
							<a href="#"><span><?php _e('Email', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="action" class="manage-column column-action">
							<a href="#"><span><?php _e('Action', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="date" class="manage-column column-date">
							<a href="#"><span><?php _e('Date', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="admin" class="manage-column column-admin">
							<a href="#"><span><?php _e('Admin User', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col" id="name" class="manage-column column-name">
							<a href="#"><span><?php _e('Name', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-email">
							<a href="#"><span><?php _e('Email', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="action" class="manage-column column-action">
							<a href="#"><span><?php _e('Action', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="date" class="manage-column column-date">
							<a href="#"><span><?php _e('Date', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="admin" class="manage-column column-admin">
							<a href="#"><span><?php _e('Admin User', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
						</th>
					</tr>
				</tfoot>
				<tbody>
					<?php $i = 0 ?>
					<?php foreach ($log as $item): ?>
						<?php $user = get_userdata($item['user_id']) ?>
						<tr class="<?php echo $i++ % 2 != 0 ? 'alternate' : '' ?>">
							<td class="name column-name">
								<strong><?php echo $user->first_name ?> <?php echo $user->last_name ?></strong>
							</td>
							<td class="email column-email">
								<a href="mailto:<?php echo $user->user_email ?>" title="<?php _e('E-mail') ?>: <?php echo $user->user_email ?>"><?php echo $user->user_email ?></a>
							</td>
							<td class="action column-action"><?php echo $item['new_status'] == 'approve' ? _e('Approved', RequestAccess::$text_domain) : _e('Denied', RequestAccess::$text_domain) ?></td>
							<td class="date column-date"><?php echo date('n/j/Y', $item['reviewed_datetime']) ?></td>
							<?php $admin = get_userdata($item['reviewed_by']) ?>
							<td class="admin-user column-admin-user"><?php echo $admin->display_name ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

			<br class="clear">
		</div>
		<div class="clear"></div>
	</div><!-- wpbody-content -->
	<div class="clear"></div>
</div>