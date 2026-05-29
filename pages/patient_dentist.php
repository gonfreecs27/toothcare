<?php
require_once('../classes/database.php');
$con = new database();

$patients = $con->viewPatients();

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search != "") {
  $patients = $con->searchPatients($search);
}

$addPatientStatus = null;
$addPatientMessage = '';

if (isset($_POST['save_patient'])) {

  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $birthdate = $_POST['birthdate'];
  $gender = $_POST['gender'];
  $contact = $_POST['contact'];
  $address = $_POST['address'];

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
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Patients</title>

  <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body style="background:#d1d5db; font-family:Arial, sans-serif;">

<div class="container-fluid p-3">

  <div class="main-container d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar shadow">

      <h2>🦷Dental Clinic</h2>

      <ul class="menu">
        <li><a href="admin_dashboard.php">📊 Dashboard</a></li>
        <li><a href="appointment.php">📅 Appointment</a></li>
        <li><a href="patient_dentist.php">👥 Patient/Dentist</a></li>
        <li><a href="services.php">❤️ Services</a></li>
        <li><a href="payments.php">💰 Payments</a></li>
        <li><a href="feedback.php">💬 Feedback</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
      </ul>

    </div>

    <!-- CONTENT -->
    <div class="content shadow">

      <!-- 🔥 ADDED: PATIENT + DENTIST BOX -->
      <div class="row mb-4">

        <div class="col-md-6">
          <div class="card shadow-sm border-0 rounded-4 p-4">
            <h4>👥 Patients</h4>
            <p class="text-muted mb-2">Manage patient records</p>
            <a href="patient.php" class="btn btn-primary w-100">View Patients</a>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card shadow-sm border-0 rounded-4 p-4">
            <h4>🦷 Dentists</h4>
            <p class="text-muted mb-2">Manage dentist records</p>
            <a href="dentist.php" class="btn btn-success w-100">View Dentists</a>
          </div>
        </div>

      </div>


      

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>


<script>
const addPatientStatus = <?php echo json_encode($addPatientStatus) ?>;
const addPatientMessage = <?php echo json_encode($addPatientMessage) ?>;

if (addPatientStatus == 'success') {
  Swal.fire({
    icon: 'success',
    title: 'Success',
    text: addPatientMessage
  });
} else if (addPatientStatus == 'error') {
  Swal.fire({
    icon: 'error',
    title: 'Error',
    text: addPatientMessage
  });
}
</script>

</body>
</html>