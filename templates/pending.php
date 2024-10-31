<div id="wpbody">
	<div id="wpbody-content" aria-label="Main content" tabindex="0">
		<div class="wrap">
			<div id="icon-users" class="icon32">
				<br>
			</div>
			<h2><?php _e('Request Access', RequestAccess::$text_domain) ?></h2>

			<?php if (isset($_GET['message']) && $_GET['message'] == 1): ?>
				<div id="message" class="updated below-h2" style="">
					<p><?php _e('User updated.', RequestAccess::$text_domain) ?></p>
				</div>
			<?php endif ?>

			<?php $filter = 'pending' ?>
			<?php if (isset($_GET['user_filter']) && $_GET['user_filter'] == 'all'): ?>
				<?php $filter = 'all' ?>
			<?php elseif (isset($_GET['user_filter']) && $_GET['user_filter'] == 'denied'): ?>
				<?php $filter = 'denied' ?>
			<?php endif ?>

			<ul class="subsubsub">
				<li class="all"><a href="<?php echo get_admin_url() ?>admin.php?page=request-access&user_filter=all"<?php echo $filter == 'all' ? ' class="current"' : '' ?>><?php _e('All', RequestAccess::$text_domain) ?> <span class="count">(<?php echo count($pending_users) + count($denied_users) ?>)</span></a> |</li>
				<li class="pending"><a href="<?php echo get_admin_url() ?>admin.php?page=request-access&user_filter=pending"<?php echo $filter == 'pending' ? ' class="current"' : '' ?>><?php _e('Pending', RequestAccess::$text_domain) ?> <span class="count">(<?php echo count($pending_users) ?>)</span></a> |</li>
				<li class="denied"><a href="<?php echo get_admin_url() ?>admin.php?page=request-access&user_filter=denied"<?php echo $filter == 'denied' ? ' class="current"' : '' ?>><?php _e('Denied', RequestAccess::$text_domain) ?> <span class="count">(<?php echo count($denied_users) ?>)</span></a></li>
			</ul>

			<form action="" method="get">
				<table class="wp-list-table widefat fixed users" cellspacing="0">
					<thead>
						<tr>
							<?php /*
							<th scope="col" id="cb" class="manage-column column-cb check-column">
								<label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select All') ?></label>
								<input id="cb-select-all-1" type="checkbox" />
							</th>
							*/ ?>
							<th scope="col" id="name" class="manage-column column-name">
								<a href="#"><span><?php _e('Name', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="email" class="manage-column column-email">
								<a href="#"><span><?php _e('Email', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="company" class="manage-column column-company">
								<a href="#"><span><?php _e('Company', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="poc" class="manage-column column-poc">
								<a href="#"><span><?php _e('POC', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="request-date" class="manage-column column-request-date">
								<a href="#"><span><?php _e('Request Date', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="status" class="manage-column column-status">
								<a href="#"><span><?php _e('Request Status', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<?php /*
							<th scope="col" id="cb" class="manage-column column-cb check-column">
								<label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select All') ?></label>
								<input id="cb-select-all-1" type="checkbox" />
							</th>
							*/ ?>
							<th scope="col" id="name" class="manage-column column-name">
								<a href="#"><span><?php _e('Name', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="email" class="manage-column column-email">
								<a href="#"><span><?php _e('Email', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="company" class="manage-column column-company">
								<a href="#"><span><?php _e('Company', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="poc" class="manage-column column-poc">
								<a href="#"><span><?php _e('POC', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="request-date" class="manage-column column-request-date">
								<a href="#"><span><?php _e('Request Date', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="status" class="manage-column column-status">
								<a href="#"><span><?php _e('Request Status', RequestAccess::$text_domain) ?></span><span class="sorting-indicator"></span></a>
							</th>
						</tr>
					</tfoot>
					<tbody>
						<?php $i = 0 ?>
						<?php foreach ($users as $user): ?>
							<?php $user = get_userdata($user) ?>
							<?php $user_status = get_user_meta($user->ID, 'request-access-status', true) ?>
							<tr class="<?php echo $i++ % 2 != 0 ? 'alternate' : '' ?>">
								<?php /*
								<th scope="row" class="check-column">
									<label class="screen-reader-text" for="cb-select-131"><?php _e('Select row') ?></label>
									<input type="checkbox" />
								</th>
								*/ ?>
								<td class="name column-name">
									<strong><a href="<?php echo get_admin_url() ?>/user-edit.php?user_id=<?php echo $user->ID ?>"><?php echo $user->first_name ?> <?php echo $user->last_name ?></a></strong>
									<?php if ($user_status != 'denied'): ?>
										<br />
										<div class="row-actions">
											<span><a onclick="return confirm('<?php _e('Are you sure you want to APPROVE this user?') ?>')" href="<?php echo get_admin_url() ?>admin.php?page=request-access&action=approve&id=<?php echo $user->ID ?>"><?php _e('Approve') ?></a> | </span>
											<span><a onclick="return confirm('<?php _e('Are you sure you want to DENY this user?') ?>')" class="submitdelete" href="<?php echo get_admin_url() ?>admin.php?page=request-access&action=deny&id=<?php echo $user->ID ?>"><?php _e('Deny') ?></a></span>
										</div>
									<?php endif ?>
								</td>
								<td class="email column-email">
									<a href="mailto:<?php echo $user->user_email ?>" title="<?php _e('E-mail') ?>: <?php echo $user->user_email ?>"><?php echo $user->user_email ?></a>
								</td>
								<td class="company column-company"><?php echo get_user_meta($user->ID, 'company', true) ?></td>
								<td class="poc column-poc"><?php echo get_user_meta($user->ID, 'poc', true) ?></td>
								<td class="request-date column-request-date"><?php echo date('n/j/Y', strtotime($user->user_registered)) ?></td>
								<td class="status column-status"><?php echo ucwords($user_status) ?></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</form>

			<br class="clear">
		</div>
		<div class="clear"></div>
	</div><!-- wpbody-content -->
	<div class="clear"></div>
</div>