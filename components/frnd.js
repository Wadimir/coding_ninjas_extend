jQuery(document).ready(function($)
{	

	$('table.table').DataTable( {
         order: [[ 3, 'desc' ], [ 0, 'asc' ]]
    } );
	
    $('a[href="#add-new-task"]').click(function(e)
    {
        $('#taskModal').modal('show');
        e.preventDefault();
    });
    $('#taskModal').on('hidden.bs.modal', function ()
    {
        window.location.reload();
    });
    $('#save-task').click(function(e)
    {   
		$('#save-task').addClass('disabled');
        $.post('/wp-admin/admin-ajax.php?action=add_new_task', $('#task-form').serialize(), function(data)
		{
			$('#save-task').removeClass('disabled');
			alert("Success!")
        }, 'json');
    e.preventDefault();
    });
});