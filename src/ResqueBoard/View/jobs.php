<?php

	$jobStatus = array(
				ResqueBoard\Lib\ResqueStat::JOB_STATUS_WAITING => 'waiting',
				ResqueBoard\Lib\ResqueStat::JOB_STATUS_RUNNING => 'running',
				ResqueBoard\Lib\ResqueStat::JOB_STATUS_FAILED => 'failed',
				ResqueBoard\Lib\ResqueStat::JOB_STATUS_COMPLETE => 'complete'
			);


?><div class="container" id="main">
	<div class="page-header">
		<h2>Jobs <small class="subtitle">View jobs details</small></h2>
	</div>
	<div class="row">
		<div class="span8">
				
		<?php
		
		if ($searchToken !== null)
		{
			echo '<h2>Results for <mark>' . $searchToken . '</mark></h2>';
			echo 'Page ' . $pagination->current .' of ' . $pagination->totalPage . ', found ' . $pagination->totalResult . ' jobs';
		}
		else
		{
			echo '<h2>'.$pageTitle. '</h2>';
		}
		
		if (!empty($jobs))
		{
			echo '<ul class="unstyled" id="job-details">';

			foreach($jobs as $job)
			{
				?>
				<li class="accordion-group<?php if ($job['status'] == ResqueBoard\Lib\ResqueStat::JOB_STATUS_FAILED) echo ' error' ?>">
					<div class="accordion-heading" data-toggle="collapse" data-target="#<?php echo $job['job_id']?>">
						<div class="accordion-toggle">
                            <span title="Job <?php echo $jobStatus[$job['status']] ?>" class="job-status-icon" rel="tooltip"><img src="/img/job_<?php echo $jobStatus[$job['status']] ?>.png" /></span>
							<span class="label label-info pull-right"><?php echo $job['worker']?></span>
							<h4>#<?php echo $job['job_id']?></h4>
							<small>Waiting for <code><?php echo $job['class']?></code> in
							<span class="label label-success"><?php echo $job['queue']?></span></small>
						</div>
					</div>
					<div class="collapse<?php if (count($jobs) == 1) echo ' in'; ?> accordion-body" id="<?php echo $job['job_id']?>">
						<div class="accordion-inner">
							<p><i class="icon-time<?php if ($job['status'] == ResqueBoard\Lib\ResqueStat::JOB_STATUS_FAILED) echo ' icon-white' ?>"></i> <b>Added on </b><?php echo $job['time']; ?></p>
							
							<?php if (isset($job['log']))
							{
								echo '<div class="alert alert-error">' . $job['log'] . '</div>';
							}
							
							if (isset($job['trace']))
							{
								echo '<pre rel="job-trace">'. $job['trace'] . '</pre>';
							}
							?>
							
							<pre rel="job-args"><?php echo $job['args'] ?></pre>
						</div>
					</div>
				</li>
				<?php
			}
			echo '</ul>';
			
			if (isset($pagination))
			{
				?>
				        <ul class="pager">
					    <li class="previous<?php if ($pagination->current == 1) echo ' disabled'?>">
					    	<a href="<?php
					    		if ($pagination->current > 1)
					    		{
					    			echo $pagination->baseUrl . $pagination->limit . '/' . ($pagination->current - 1);
					    		}
					    		else
					    		{
					    			echo '#';
					    		}
					    	?>">&larr; Older</a>
					    </li>
					    <li>
					    	Page <?php echo $pagination->current?> of <?php echo $pagination->totalPage ?>, found <?php echo $pagination->totalResult?> jobs
					    </li>
					    <li class="next<?php if ($pagination->current == $pagination->totalPage) echo ' disabled'?>">
					    	<a href="<?php
					    		if ($pagination->current < $pagination->totalPage)
					    		{
					    			echo $pagination->baseUrl . $pagination->limit . '/' . ($pagination->current + 1);
					    		}
					    		else
					    		{
					    			echo '#';
					    		}
					    	?>">Newer &rarr;</a>
					    </li>
					    </ul>
					
				<?php
			}
			
			
		}
		elseif ($searchToken !== null)
		{
			?>
			    <div class="alert alert-info">
			    	No jobs found matching <mark><?php echo $searchToken?></mark>
			    </div>
			<?php
		}
		else
		{
			?>
			    <div class="alert alert-info">
			    	Nothing to display
			    </div>
			<?php
		}
		?>
		</div>
		
		<div class="span4">

		
		
		<div class="well" style="padding: 8px 0;">
		<ul class="nav nav-list">
			<li class="nav-header">
				Search Job
			</li>
			<li>
			<form class="form-search" action="/jobs" method="POST">
				<input type="text" name="job_id" class="input-medium search-query" placeholder="Job #Id"/>
				<button type="submit" class="btn">Search Job</button>
			</form>
			</li>
			<li class="nav-header">
				Active workers <span class="label"><?php echo count($workers); ?></span>
			</li>
			<?php
				foreach ($workers as $worker) {
					echo '<li><a href="/jobs/'.$worker['host'] . '/' . $worker['process'].'"><i class="icon-cog"></i>';
					echo $worker['host'] . ':' . $worker['process'];
					echo '</a></li>';
				}
			?>
		</ul>
		</div>
		</div>
	</div>
	
</div>