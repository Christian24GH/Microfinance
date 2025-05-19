<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM vacancy where id=".$_GET['id'])->fetch_array();
	foreach($qry as $k =>$v){
		$$k = $v;
	}
}

?>

<?php if (isset($_GET['view'])): ?>
  <h5><strong>Position :</strong></h5>
  <p><?php echo isset($job_name) ? $job_name : 'N/A'; ?></p>

  <h5><strong>Availability :</strong></h5>
  <p><?php echo isset($availability) ? $availability : 'N/A'; ?></p>

  <h5><strong>Status :</strong></h5>
  <p><?php echo isset($status) ? ($status == 1 ? 'Active' : 'Inactive') : 'N/A'; ?></p>

  <hr>

  <div>
	<h5><strong>Description :</strong></h5>
    <?php echo isset($description) ? $description : '<em>No description provided.</em>'; ?>
  </div>
<?php else: ?>
  <!-- This is the normal editable form -->
  <form method="POST" action="save_vacancy.php" class="p-4 border rounded shadow-sm bg-light">
    <?php if (isset($id)): ?>
      <input type="hidden" name="id" value="<?= $id ?>">
    <?php endif; ?>

    <h4 class="mb-4"><?= isset($id) ? 'Edit Vacancy' : 'Add New Vacancy' ?></h4>

    <!-- Position Name -->
    <div class="mb-3">
      <label for="job_name" class="form-label">Position Name</label>
      <input type="text" id="job_name" name="job_name" value="<?= isset($job_name) ? $job_name : '' ?>" class="form-control" placeholder="Enter position title" required>
    </div>

    <!-- Availability -->
    <div class="mb-3">
      <label for="availability" class="form-label">Availability</label>
      <input type="number" id="availability" name="availability" value="<?= isset($availability) ? $availability : '' ?>" class="form-control" placeholder="Number of openings" required min="0">
    </div>

    <!-- Status -->
    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <select id="status" name="status" class="form-select" required>
      <option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
      <option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
      </select>
    </div>

    <!-- Description -->
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea id="description" name="description" class="text-jqte form-control" rows="5" placeholder="Write a brief job description"><?= isset($description) ? $description : '' ?></textarea>
    </div>

    <!-- Submit Button -->
    <div class="text-end">
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
	</form>


<?php endif; ?>

<script>
	$('.text-jqte').jqte();
	$('#manage-vacancy').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_vacancy',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					alert_toast("Data successfully saved.",'success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})
	})
</script>