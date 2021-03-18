$(document).ready(function() {
	$('#add_button').click(function() {
		$('.modal-title').text("Add Product Here");
		$('#user_form')[0].reset();
		$('#action').val("Add");
		$('#operation').val("Add");
		$('#user_uploaded_image').html('');
	});
	var dataTable = $('#product-table').DataTable({
		"processing": true,
		"serverSide": true,
		"order": [],
		"ajax": {
			url: "datatable.php",
			type: "POST"
		},
		"columnDefs": [{
			"targets": [0, 3, 4],
			"orderable": false,
		}, ],
	});
	$(document).on('submit', '#user_form', function(event) {
		event.preventDefault();
		var extension = $('#product_image').val().split('.').pop().toLowerCase();
		if(extension != '') {
			if(jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
				alert("Invalid Image File");
				$('#product_image').val('');
				return false;
			}
		}
		
			$.ajax({
				url: "query.php",
				method: 'POST',
				data: new FormData(this),
				contentType: false,
				processData: false,
				success: function(data) {
					
					$('#user_form')[0].reset();
					$('#userModal').modal('hide');
                    dataTable.ajax.reload();
                    setTimeout(function(){  
                        $('#alert_action').fadeOut("Slow");  
                   }, 2000);
				}
			});
		 
	});
	$(document).on('click', '.update', function() {
		var user_id = $(this).attr("id");
		var operation = ("fetch_single");
		$.ajax({
			url: "query.php",
			method: "POST",
			data: {
				user_id: user_id,
				operation: operation
			},
			dataType: "json",
			success: function(data) {
				$('#userModal').modal('show');
				$('#product_name').val(data.product_name);
				$('#hs_code').val(data.hs_code);
				$('#price').val(data.price);
				$('#unit').val(data.unit);
				$('#igst').val(data.igst);
				$('#description').val(data.description);
				$('.modal-title').text("Edit Product");
				$('#user_id').val(user_id);
				$('#user_uploaded_image').html(data.product_image);
				$('#action').val("Edit");
				$('#operation').val("Edit");
			}
		})
	});
	$(document).on('click', '.delete', function() {
		var user_id = $(this).attr("id");
		var operation = ('Delete');
		if(confirm("Are you sure you want to delete this?")) {
			$.ajax({
				url: "query.php",
				method: "POST",
				data: {
					user_id: user_id,
					operation: operation
				},
				success: function(data) {
                    setTimeout(function(){  
                        $('#alert_action').fadeOut("Slow");  
                   }, 2000);
					dataTable.ajax.reload();
				}
			});
		} else {
			return false;
		}
	});
});