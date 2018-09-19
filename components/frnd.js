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
		
		var url = ajaxurl + '?action=add_new_task'

        $.post(url, $('#task-form').serialize(), function(data)
		{
			$('#save-task').removeClass('disabled');
			alert("Success!")
        }, 'json');
    e.preventDefault();
    });
});