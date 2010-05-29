<?php if( !defined('SUSPECTPATH') ) exit('No direct script access allowed.'); ?>

	<div id="suspect">
		<ul id="suspect_nav">
			<li id="close"><a href="#close">X</a></li>
			<li class="page_time">Page: <?php timer_stop(1); ?> seconds</li>
			<li><a href="#suspect_actions">Actions (<?php echo count($wp_actions); ?>)</a></li>
			<li><a href="#suspect_queries">Queries (<?php echo get_num_queries(); ?>)</a></li>
			<li><a href="#suspect_post">POST (<?php echo count($_POST); ?>)</a></li>
		</ul>

		<div id="details">
			<table id="suspect_actions">
				<tbody>
				<?php foreach($suspect_actions as $k => $v) : ?>
					<tr>
						<td class="first number"><?php echo $k + 1; ?></td>
						<td><?php echo $v; ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<table id="suspect_queries">
				<thead>
					<tr>
						<th>No.</th>
						<th>Query</th>
						<th>Time (ms)</th>
					</tr>
				</thead>
				
				<tbody>
					<?php $this->wpdb_data(); ?>
				</tbody>

				<tfoot>
					<tr>
						<th colspan="3">Total time: <?php echo number_format($this->total_query_time, 4) * 1000; ?> ms</th>
					</tr>
				</tfoot>
			</table>

			<table id="suspect_post">
				<tbody>
					<?php $this->post_data(); ?>
				</tbody>
			</table>
		</div>
	</div>
