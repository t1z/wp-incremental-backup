<h2>Incremental Backup</h2>

<h3>Setup parameters</h3>
<table>
	<tr>
		<th>Param. name</th>
		<th>Param. value</th>
	</tr>
	<tr>
		<td>Server software</td>
		<td><?php echo $this->server_soft; ?></td>
	</tr>
	<tr>
		<td>System info</td>
		<td><?php echo $uname; ?></td>
	</tr>
	<tr>
		<td>System path</td>
		<td><?php echo $sys_path; ?></td>
	</tr>
	<tr>
		<td>PHP version</td>
		<td><?php echo $php_version; ?></td>
	</tr>
	<tr>
		<td>PHP5 path</td>
		<td><?php echo $php5_path; ?></td>
	</tr>
	<tr>
		<td>zip binary</td>
		<td><?php echo $zip_bin; ?></td>
	</tr>
	<tr>
		<td>bzip2 binary</td>
		<td><?php echo $bzip2_bin; ?></td>
	</tr>
	<tr>
		<td>mysqldump binary</td>
		<td><?php echo $mysqldump_bin; ?></td>
	</tr>
	<tr>
		<td>WP size (MB)</td>
		<td>
			<?php echo $wp_size; ?>
			<ul>
			<?php foreach($du_out as $line): ?>
				<?php $line_size = $this->du_line_size($line); ?>
				<?php if($line_size > 1000): ?>
				<li><?php echo $line; ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
		
		</td>
	</tr>
	<tr>
		<td>Database size (MB)</td>
		<td><?php echo $db_size; ?></td>
	</tr>
	<tr>
		<td>Activation id</td>
		<td><?php echo $this->activation_id; ?></td>
	</tr>
	<?php foreach ($params as $key => $value): ?>
		<tr>
			<td><?php echo $key; ?></td>
			<td><?php echo $value; ?></td>
		</tr>
	<?php endforeach; ?>
</table>

<h3>Large files</h3>
<ul>
<?php foreach($large_files as $file): ?>
	<li><?php
		echo $file . ' (' . $this->format_filesize_as_mb($file) . ') => ';
		// echo fnmatch($delete_filter, $file) ? '<b>delete</b>' : 'pass';
		// if (fnmatch($delete_filter, $file)) unlink($file);
	?></li>
<?php endforeach; ?>
</ul>

<h3>Output dir content</h3>
<ul>
<?php foreach($files as $file): ?>
	<?php $filepath = $this->inc_bak->get_output_dir() . '/' . $file; ?>
	<li><a href="admin-ajax.php?action=wpib_download&amp;filename=<?php echo urlencode($file); ?>"><?php echo sprintf('%s (%d)', $file, filesize($filepath)); ?></a>
	<?php
	if (fnmatch('*.pid', $filepath)) {
		$pid = (int)trim(file_get_contents($filepath));
		echo ' is pid: ' . $this->is_running($pid) ? '<b>running</b>' : 'down';
	}
	?>
	</li>
<?php endforeach; ?>
</ul>

<?php if(false): ?>
<h3>Process results</h3>
	<h4>New files</h4>
	<ul>
	
	<?php foreach($result['new'] as $file): ?>
		<li></li>
	<?php endforeach; ?>
	</ul>

	<h4>Modified files</h4>
	<ul>
	<?php foreach($result['modified'] as $file => $md5s): ?>
		<li><?php echo "$file => old md5: $md5s[0], new md5: $md5s[0]"; ?></li>
	<?php endforeach; ?>
	</ul>

	<h4>Deleted files</h4>
	<ul>
	<?php foreach($result['deleted'] as $file): ?>
		<li><?php $file; ?></li>
	<?php endforeach; ?>
	</ul>
<?php else: ?>
<!-- <h3>Run process</h3>
<form action="admin-ajax.php?action=wpib_generate" method="POST">
	<input type="submit" class="button" value="Run" />
</form> -->
<form action="" method="POST">
	<input type="hidden" name="reset_activation_id" />
	<input type="submit" class="button" value="Reset Activation ID" />
</form>

<?php endif; ?>