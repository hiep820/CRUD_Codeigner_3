<html>

<head>
	<title>DataTables Example</title>


	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<!-- css -->
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
	<!-- toastr -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

</head>

<body>
	<table id="myTable">
		<thead>
			<tr>
				<th>id</th>
				<th>Name</th>
				<th>price</th>
				<th>description</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
	</table>
	<div class="container">
		<button type="button" class="btn btn-primary showModal" style="margin-bottom:15px">
			Create Product
		</button>
		<div class="modal fade" id="modalProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Create Product</h5>
					</div>
					<div class="modal-body">
						<form action="" method="POST" id="formProject">
							<input type="hidden" class="form-control " id="id" name="id" value="">
							<div class=" form-group">
								<label for="formGroupExampleInput">Name Product</label>
								<input type="text" class="form-control" id="name" name="name" >
								<span style="color:red" id="name_error" class="error"></span>
							</div>
							<div class="form-group">
								<label for="formGroupExampleInput2">price</label>
								<input type="text" class="form-control" id="price" name="price" >
								<!-- <span style="color:red" id="description_error" class="error"></span> -->
							</div>
							<div class="form-group">
								<label for="formGroupExampleInput2">Description</label>
								<textarea class="form-control" id="description" rows="3" name="description" value=""></textarea>
								<!-- <span style="color:red" id="description_error" class="error"></span> -->
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="button" id="btn" class="btn btn-primary saveItem">Save product</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#myTable').DataTable({
				ajax: {
					url: '<?php echo base_url("product/getProduct"); ?>',
					type: 'POST',
				},
			});

			$("body").on("click", ".showModal", function() {
					$("#modalProduct").modal("show");
					let id = $(this).attr("data-id");
					if (!id) {
						$(".error").remove();
						$("#formProject")[0].reset();
						$(".modal-title").text("Create Product");
					} else {
						$(".modal-title").text("Edit Product");
						$.ajax({
							url: "<?php echo base_url("product/edit_product/"); ?>" + id,
							type: "POST",
							dataType: "JSON",
						}).done(function(response) {
							$(".error").remove();
							console.log(response);
							if (response) {
								$.each(response, function(i, item) {
									$('[name="' + i + '"]').val(item);
								});
							}
						});
					}
				}),

				$("body").on("click", ".saveItem", function() {
					let id = $('input[name="id"]').val();
					let url;
					if (!id) {
						url = "<?php echo base_url("product/addProduct/"); ?>";
						console.log(url);
					} else {
						url = "<?php echo base_url("product/updateProduct/"); ?>" + id;
					}
					$.ajax({
						url: url,
						type: "POST",
						dataType: "JSON",
						data: $("#formProject").serialize(),

					}).done(function(response) {
						$(".error").remove();
						console.log(response);
						if (response.status === "success") {
							$("#modalProduct").modal("hide");
							toastr.success(response.massage);
							$("#myTable").DataTable().ajax.reload();
							$("#formProject")[0].reset();
						} else {
							$.each(response.errors, function(i, item) {
								$('[name="' + i + '"]').after(
									'<span  style="color:red"  class="error">' + item + "</span>"
								);
							});
						}
					});

				}),

				$("body").on("click", ".DeleteProduct", function() {
					var id = $(this).data("id");
					$.ajax({
						url: "<?php echo base_url("product/DeleteProduct/"); ?>" + id,
						type: "POST",
						dataType: "JSON",
						success: function(data) {
							toastr.success(data.massage);
							$("#myTable").DataTable().ajax.reload();
						},
						fail: function(jqXHR, textStatus, errorThrown) {
							alert("Xóa sản phẩm thất bại!");
						},
					});
				});

		});
	</script>
</body>

</html>
