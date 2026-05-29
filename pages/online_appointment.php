<?php
require_once('../classes/database.php');

$con = new Database();

$dentists = $con->viewDentists();
$services = $con->viewServices();

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {

        $data = [

            'firstname' => trim($_POST['firstname']),
            'lastname' => trim($_POST['lastname']),
            'birthdate' => trim($_POST['birthdate']),
            'gender' => trim($_POST['gender']),
            'contact' => trim($_POST['contact']),
            'address' => trim($_POST['address']),
            'dentist_id' => trim($_POST['dentist_id']),
            'service_id' => trim($_POST['service_id']),
            'appointment_date' => trim($_POST['appointment_date']),
            'appointment_time' => trim($_POST['appointment_time'])

        ];

        // GET SERVICE INFO
        $service = $con->getServiceCost($data['service_id']);

        if (!$service) {
            throw new Exception("Service not found.");
        }

        $duration = $service['duration'];

        // CHECK DENTIST AVAILABILITY
        $available = $con->isDentistAvailable(
            $data['dentist_id'],
            $data['appointment_date'],
            $data['appointment_time'],
            $duration
        );

        if (!$available) {
            throw new Exception("Dentist not available on this schedule.");
        }

        // SAVE APPOINTMENT (same function mo)
        $con->addAppointment($data);

        $success = "Appointment booked successfully!";

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Online Appointment</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet"
          href="../assets/css/style.css">

</head>

<body>

<div class="container">

<div class="card-box">

<h3 class="text-center mb-3">🦷 Online Appointment</h3>

<!-- ALERT -->
<?php if ($error): ?>
<div class="alert alert-danger"><?= $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success"><?= $success; ?></div>
<?php endif; ?>

<form method="POST">

<input type="text" name="firstname" class="form-control mb-2" placeholder="First Name" required>

<input type="text" name="lastname" class="form-control mb-2" placeholder="Last Name" required>

<input type="date" name="birthdate" class="form-control mb-2" required>

<select name="gender" class="form-control mb-2" required>
    <option value="">Select Gender</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
</select>

<input type="text" name="contact" class="form-control mb-2" placeholder="Contact" required>

<textarea name="address" class="form-control mb-2" placeholder="Address" required></textarea>

<!-- DENTIST -->
<select name="dentist_id" class="form-control mb-2" required>
    <option value="">Select Dentist</option>

    <?php foreach ($dentists as $d): ?>
        <option value="<?= $d['dentist_id']; ?>">
            <?= $d['fullname']; ?>
        </option>
    <?php endforeach; ?>

</select>

<!-- SERVICE -->
<select name="service_id" class="form-control mb-2" required>
    <option value="">Select Service</option>

    <?php foreach ($services as $s): ?>
        <option value="<?= $s['service_id']; ?>">
            <?= $s['service_name']; ?>
        </option>
    <?php endforeach; ?>

</select>

<input type="date" name="appointment_date" class="form-control mb-2" required>

<input type="time" name="appointment_time" class="form-control mb-3" required>

<button type="submit" class="btn btn-primary w-100">
    Book Appointment
</button>

</form>

</div>

</div>

</body>
</html>