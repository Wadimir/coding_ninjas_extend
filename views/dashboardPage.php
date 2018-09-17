<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3"> <i class="fa fa-users fa-5x"></i></div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                            <?php echo $this->get_total('freelancer'); ?>
                        </div>
                        <div>Freelancers</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3"> <i class="fa fa-tasks fa-5x"></i></div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                            <?php echo $this->get_total('task'); ?>
                        </div>
                        <div>Tasks</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>