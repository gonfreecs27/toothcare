<?php
require_once('../classes/database.php');
$con = new database();

$alertType = '';
$alertMessage = '';

/* ================= SEARCH ================= */
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search != "") {
  $patients = $con->searchPatients($search);
} else {
  $patients = $con->viewPatients();
}

/* ================= ALERT VARIABLES ================= */
$addPatientStatus = null;
$addPatientMessage = '';

/* ================= ADD PATIENT ================= */
if (isset($_POST['save_patient'])) {

  $firstname = $_POST['firstname'];
  $lastname  = $_POST['lastname'];
  $birthdate = $_POST['birthdate'];
  $gender    = $_POST['gender'];
  $contact   = $_POST['contact'];
  $address   = $_POST['address'];

  try {

    $con->addPatient(
      $firstname,
      $lastname,
      $birthdate,
      $gender,
      $contact,
      $address
    );

    $addPatientStatus = 'success';
    $addPatientMessage = 'Patient added successfully.';

  } catch (Exception $e) {

    $addPatientStatus = 'error';
    $addPatientMessage = $e->getMessage();

  }
}

$alertType = '';
$alertMessage = '';

if (isset($_POST['update_patient'])) {

  $patient_id = $_POST['patient_id'];
  $firstname  = $_POST['firstname'];
  $lastname   = $_POST['lastname'];
  $birthdate  = $_POST['birthdate'];
  $contact    = $_POST['contact'];
  $address    = $_POST['address'];

  try {

    $con->updatePatient(
      $patient_id,
      $firstname,
      $lastname,
      $birthdate,
      $contact,
      $address
    );

    $alertType = 'success';
  $alertMessage = 'Patient updated successfully.';

  } catch (Exception $e) {

    $alertType = 'error';
    $alertMessage = $e->getMessage();

  }
}

$deleteType = '';
$deleteMessage = '';

if (isset($_POST['delete_patient'])) {

  $patient_id = $_POST['patient_id'];

  try {

    $con->deletePatient($patient_id);

    $deleteType = 'success';
    $deleteMessage = 'Patient deleted successfully.';

  } catch (Exception $e) {

   $deleteType = 'error';
  $deleteMessage = $e->getMessage();   

  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Patients</title>

  <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">
</head>

<body style="background:#f1f5f9; font-family:Arial;">

<div class="container-fluid p-4">

  <!-- HEADER -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>🦷 Patients</h2>
      <small class="text-muted">Manage patient records</small>
    </div>

    <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addPatientModal">
      + Add Patient
    </button>
  </div>

  <!-- SEARCH -->
  <div class="card p-3 mb-3">
    <form method="GET" class="d-flex gap-2">
      <input type="text"
             name="search"
             class="form-control"
             placeholder="Search patient..."
             value="<?= htmlspecialchars($search) ?>">

      <button class="btn btn-outline-primary">Search</button>
    </form>
  </div>

  <!-- TABLE -->
  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-hover table-striped align-middle">

        <thead class="table-success">
          <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Birthdate</th>
            <th>Gender</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Created At</th>
            <th class="text-center" style="width:150px;">Actions</th>
          </tr>
        </thead>

        <tbody>
          <?php if ($patients): ?>
            <?php foreach ($patients as $p): ?>
              <tr>
                <td><?= $p['patient_id'] ?></td>
                <td><?= $p['firstname'] ?></td>
                <td><?= $p['lastname'] ?></td>
                <td><?= $p['birthdate'] ?></td>
                <td>
                  <span class="badge bg-primary"><?= $p['gender'] ?></span>
                </td>
                <td><?= $p['contact'] ?></td>
                <td><?= $p['address'] ?></td>
                <td><?= $p['created_at'] ?></td>

                <td class="text-center text-nowrap">

                  <button class="btn btn-sm btn-outline-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#editModal<?= $p['patient_id'] ?>">
                    Edit
                  </button>

                  <button class="btn btn-sm btn-outline-danger"
                          data-bs-toggle="modal"
                          data-bs-target="#deleteModal<?= $p['patient_id'] ?>"
                          ">
                    Delete
                  </button>
 
                </td>
              </tr>

              <!-- EDIT MODAL -->
              <div class="modal fade" id="editModal<?= $p['patient_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">

                    <form method="POST" action="">

                      <div class="modal-header">
                        <h5 class="modal-title">Edit Patient</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>

                      <div class="modal-body">
                        <input type="hidden" name="patient_id" value="<?= $p['patient_id'] ?>">

                        <input type="text" name="firstname" class="form-control mb-2" value="<?= $p['firstname'] ?>">
                        <input type="text" name="lastname" class="form-control mb-2" value="<?= $p['lastname'] ?>">
                        <input type="date" name="birthdate" class="form-control mb-2" value="<?= $p['birthdate'] ?>">
                        <input type="text" name="contact" class="form-control mb-2" value="<?= $p['contact'] ?>">
                        <input type="text" name="address" class="form-control mb-2" value="<?= $p['address'] ?>">
                      </div>

                      <div class="modal-footer">
                        <button name="update_patient" type="submit" class="btn btn-primary w-100">Save Changes</button>
                      </div>

                    </form>

                  </div>
                </div>
              </div>

              <!-- DELETE MODAL -->
              <div class="modal fade" id="deleteModal<?= $p['patient_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">

                    <form method="POST" action="">

                      <div class="modal-header">
                        <h5 class="modal-title">Delete Patient</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>

                      <div class="modal-body">
                        Are you sure you want to delete
                        <b><?= $p['firstname'] . ' ' . $p['lastname'] ?></b>?

                        <input type="hidden" name="patient_id" value="<?= $p['patient_id'] ?>">
                      </div>

                      <div class="modal-footer">
                        <button type="submit" name="delete_patient" class="btn btn-danger w-100">Delete</button>
                      </div>

                    </form>

                  </div>
                </div>
              </div>

            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" class="text-center">No patients found</td>
            </tr>
          <?php endif; ?>
        </tbody>

      </table>
    </div>

    <!-- 🔥 BACK BUTTON (FIXED - ALWAYS VISIBLE) -->
    <div class="mt-3 text-end">
      <a href="patient_dentist.php" class="btn btn-secondary">
        ← Back
      </a>
    </div>

  </div>

</div>

<!-- ADD PATIENT MODAL -->
<div class="modal fade" id="addPatientModal" tabindex="-1">
  <div class="modal-dialog">

    <form method="POST" class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Add Patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <input type="text" name="firstname" class="form-control mb-2" placeholder="First Name" required>
        <input type="text" name="lastname" class="form-control mb-2" placeholder="Last Name" required>
        <input type="date" name="birthdate" class="form-control mb-2">

        <select name="gender" class="form-control mb-2">
          <option value="">Select Gender</option>
          <option>Male</option>
          <option>Female</option>
        </select>

        <input type="text" name="contact" class="form-control mb-2" placeholder="Contact">
        <input type="text" name="address" class="form-control mb-2" placeholder="Address">

      </div>

      <div class="modal-footer">
        <button type="submit" name="save_patient" class="btn btn-success w-100">
          Save Patient
        </button>
      </div>

    </form>

  </div>
</div>

<!-- SCRIPTS -->
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>
 
<script>
  const addPatientStatus = <?php echo json_encode($addPatientStatus)?>;
  const addPatientMessage = <?php echo json_encode($addPatientMessage)?>;
 
  if(addPatientStatus == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: addPatientMessage,
      confirmButtonText: 'OK'
    });
  }else if(addPatientStatus == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: addPatientMessage,
      confirmButtonText: 'OK'
    });
  }
</script>
<script>
  const alertType = <?php echo json_encode($deleteType)?>;
  const alertMessage = <?php echo json_encode($alertMessage)?>;
 
  if(alertType == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: alertMessage,
      confirmButtonText: 'OK'
    });
  }else if(alertType == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: alertMessage,
      confirmButtonText: 'OK'
    });
  }
</script>
<script>
  const deleteType = <?php echo json_encode($deleteType)?>;
  const deleteMessage = <?php echo json_encode($deleteMessage)?>;
 
  if(deleteType == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: deleteMessage,
      confirmButtonText: 'OK'
    });
  }else if(deleteType == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: deleteMessage,
      confirmButtonText: 'OK'
    });
  }
</script>

</body>
</html>