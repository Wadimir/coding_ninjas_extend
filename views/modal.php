<?php

function update_modal($freelancers) {
	?>
	<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="taskModalLabel"><?php _e('Add new task'); ?></h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" id="task-form" method="post">
						<div class="form-group" id="title-group">
							<label for="task-title" class="col-sm-3 control-label">Task Title</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="task_title" id="task-title" placeholder="Title" />
							</div>
						</div>
						<div class="form-group" id="freelancerDone">
							<label for="task-freelancer" class="col-sm-3 control-label">Freelancer</label>
							<div class="col-sm-6">
								<select class="form-control" name="task_freelancer" id="task-freelancer">
									<option value="0">Select Freelancer</option>
									<?php
										foreach ($freelancers as $freelancer):
									?>
									<option value="<?php echo $freelancer->ID; ?>"><?php echo esc_html($freelancer->post_title); ?></option>
									<?php
										endforeach;
									?>
								</select>
							</div>
						</div>
						<div class="form-group" id="title-group">
						<label for="task-freelancer" class="col-sm-3 control-label"></label>
							<div class="col-sm-6" >
								<button type="button" id="save-task" class="btn btn-primary">Add</button>
							</div>
						</div>
					</form>
				</div>
				
				<div class="modal-footer" id="footer-default">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<?php
}

?>