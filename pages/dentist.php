<?php
require_once('../classes/database.php');
$con = new database();

$dentists = $con->viewDentists();
$dentists = $con->viewDentists();
$specialties = $con->viewSpecialties();

$updatedentistStatus = '';
$updatedentistMessage = '';

if (isset($_POST['update_dentist'])) {

    $dentist_id  = $_POST['dentist_id'];
    $fullname    = $_POST['fullname'];
    $contact     = $_POST['contact'];
    $specialty_id = $_POST['specialty_id'];

    try {

        $con->updateDentist(
            $dentist_id,
            $fullname,
            $contact,
            $specialty_id
        );


        $updatedentistStatus = 'success';
        $updatedentistMessage = 'Dentist updated successfully.';

    } catch (Exception $e) {

        $updatedentistStatus = 'error';
        $updatedentistMessage = 'Error updating dentist: ' . $e->getMessage();

    }
}

$deletedentistStatus = '';
$deletedentistMessage = '';

if (isset($_POST['delete_dentist'])) {

    $dentist_id = $_POST['dentist_id'];

    try {

        $con->deleteDentist($dentist_id);
        $deletedentistStatus = 'success';
        $deletedentistMessage = 'Dentist deleted successfully.';

    } catch (Exception $e) {

        $deletedentistStatus = 'error';
        $deletedentistMessage =  $e->getMessage();

    }
}


$addDentistStatus = '';
$addDentistMessage = '';

if (isset($_POST['save_dentist'])) {

    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $specialty_id = $_POST['specialty_id'];

    try {

        $con->addDentist(
            $fullname,
            $contact,
            $specialty_id
        );

        $addDentistStatus = 'success';
        $addDentistMessage = 'Dentist added successfully.';

    } catch (Exception $e) {

        $addDentistStatus = 'error';
        $addDentistMessage = $e->getMessage();

    }
}
?>


<!doctype html>
<html>
<head>
  <title>Dentists</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">
</head>

<body style="background:#d1d5db; font-family:Arial;">

<div class="container mt-5">

  <h2>🦷 Dentist List</h2>

  <div class="mb-3 text-end">
    <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addDentistModal">

        + Add Dentist

    </button>
</div>

  <div class="card shadow-sm p-4 mt-4">

    <table class="table table-hover align-middle">

      <thead class="table-success">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Contact</th>
          <th>Specialty</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
      <?php if ($dentists): ?>
        <?php foreach ($dentists as $d): ?>
          <tr>
            <td><?= $d['dentist_id'] ?></td>
            <td><?= $d['fullname'] ?></td>
            <td><?= $d['contact'] ?></td>
            <td>
              <span class="badge bg-primary">
                <?= $d['Specialty'] ?>
              </span>
            </td>
            <td>
              <button class="btn btn-sm btn-outline-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#editModal<?= $d['dentist_id'] ?>">
                Edit
              </button>

              <button class="btn btn-sm btn-outline-danger"
                      data-bs-toggle="modal"
                      data-bs-target="#deleteModal<?= $d['dentist_id'] ?>">
                Delete
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">No dentists found</td>
        </tr>
      <?php endif; ?>
      </tbody>

    </table>

    <div class="mt-3 text-end">
      <a href="patient_dentist.php" class="btn btn-secondary">← Back</a>
    </div>

  </div>
</div>


<!-- ================= MODALS OUTSIDE TABLE ================= -->

<?php if ($dentists): ?>
<?php foreach ($dentists as $d): ?>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal<?= $d['dentist_id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="">

        <div class="modal-header">
          <h5 class="modal-title">Edit Dentist</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="dentist_id" value="<?= $d['dentist_id'] ?>">

          <div class="mb-3">
            <label>Full Name</label>
            <input type="text" class="form-control" name="fullname" value="<?= $d['fullname'] ?>">
          </div>

          <div class="mb-3">
            <label>Contact</label>
            <input type="text" class="form-control" name="contact" value="<?= $d['contact'] ?>">
          </div>

                <div class="mb-3">
            <label>Specialty</label>

            <select name="specialty_id" class="form-control">

                <?php foreach ($specialties as $s): ?>

                    <option value="<?= $s['specialty_id'] ?>"
                        <?= ($s['specialty_name'] == $d['Specialty']) ? 'selected' : '' ?>>

                        <?= $s['specialty_name'] ?>

                    </option>

                <?php endforeach; ?>

            </select>
        </div>

        </div>

        <div class="modal-footer">
          <button type="submit" name="update_dentist" class="btn btn-primary">Save Changes</button>
        </div>

      </form>

    </div>
  </div>
</div>


<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal<?= $d['dentist_id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="">

        <div class="modal-header">
          <h5 class="modal-title">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          Are you sure you want to delete
          <strong><?= $d['fullname'] ?></strong>?

          <input type="hidden" name="dentist_id" value="<?= $d['dentist_id'] ?>">
        </div>

        <div class="modal-footer">
          <button type="submit" name="delete_dentist" class="btn btn-danger">Delete</button>
        </div>

      </form>

    </div>
  </div>
</div>
<!-- ADD DENTIST MODAL -->
<div class="modal fade" id="addDentistModal" tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="">

                <div class="modal-header">
                    <h5 class="modal-title">Add Dentist</h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Full Name</label>

                        <input type="text"
                               name="fullname"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Contact</label>

                        <input type="text"
                               name="contact"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Specialty</label>

                        <select name="specialty_id"
                                class="form-control"
                                required>

                            <option value="">
                                Select Specialty
                            </option>

                            <?php foreach ($specialties as $s): ?>

                                <option value="<?= $s['specialty_id'] ?>">

                                    <?= $s['specialty_name'] ?>

                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit"
                            name="save_dentist"
                            class="btn btn-success">

                        Save Dentist
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<?php endforeach; ?>
<?php endif; ?>


<!-- ================= BOOTSTRAP JS (IMPORTANT FIX) ================= -->
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>
<script>
  const updateDentistStatus = <?php echo json_encode($updatedentistStatus)?>;
  const updateDentistMessage = <?php echo json_encode($updatedentistMessage)?>;
 
  if(updateDentistStatus == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: updateDentistMessage,
      confirmButtonText: 'OK'
    });
  }else if(updateDentistStatus == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: updateDentistMessage,
      confirmButtonText: 'OK'
    });
  }
</script>
<script>
  const deleteDentistStatus = <?php echo json_encode($deletedentistStatus)?>;
  const deleteDentistMessage = <?php echo json_encode($deletedentistMessage)?>;
 
  if(deleteDentistStatus == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: deleteDentistMessage,
      confirmButtonText: 'OK'
    });
  }else if(deleteDentistStatus == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: deleteDentistMessage,
      confirmButtonText: 'OK'
    });
  }
</script>
<script>
  const addDentistStatus = <?php echo json_encode($addDentistStatus)?>;
  const addDentistMessage = <?php echo json_encode($addDentistMessage)?>;

  if(addDentistStatus == 'success'){
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: addDentistMessage,
      confirmButtonText: 'OK'
    });
  }else if(addDentistStatus == 'error'){
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: addDentistMessage,
      confirmButtonText: 'OK'
    });
  }
</script>
</body>
</html>