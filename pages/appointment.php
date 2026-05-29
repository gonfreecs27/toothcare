<?php
require_once('../classes/database.php');

$con = new Database();

$appointments = $con->getAppointmentReport();
$dentists = $con->viewDentists();
$services = $con->viewServices();

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {

        // SANITIZE INPUTS
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

        // GET SERVICE COST + DURATION
        $service = $con->getServiceCost(
            $data['service_id']
        );

        if (!$service) {

            throw new Exception(
                "Service information not found."
            );
        }

        $duration = $service['duration'];

        // CHECK AVAILABILITY
        $available = $con->isDentistAvailable(

            $data['dentist_id'],
            $data['appointment_date'],
            $data['appointment_time'],
            $duration

        );

        if (!$available) {

            throw new Exception(
                "Dentist is not available at this schedule."
            );
        }

        // SAVE APPOINTMENT
        $con->addAppointmentWithPayment($data);

        $success = "Appointment booked successfully.";

        // REFRESH DATA
        $appointments = $con->getAppointmentReport();

    } catch (Exception $e) {

        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dental Clinic Appointments</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="../assets/css/style.css">

</head>

<body style="background:#d1d5db; font-family:Arial, sans-serif;">

<div class="container-fluid p-3">

    <div class="main-container d-flex">

        <!-- SIDEBAR -->
        <div class="sidebar shadow">

            <h2>🦷 Dental Clinic</h2>

            <ul class="menu">

                <li>
                    <a href="admin_dashboard.php">
                        📊 Dashboard
                    </a>
                </li>

                <li>
                    <a href="appointment.php">
                        📅 Appointment
                    </a>
                </li>

                <li>
                    <a href="patient_dentist.php">
                        👥 Patient/Dentist
                    </a>
                </li>

                <li>
                    <a href="services.php">
                        ❤️ Services
                    </a>
                </li>

                <li>
                    <a href="payments.php">
                        💰 Payments
                    </a>
                </li>

                <li>
                    <a href="feedback.php">
                        💬 Feedback
                    </a>
                </li>

                <li>
                    <a href="logout.php">
                        🚪 Logout
                    </a>
                </li>

            </ul>

        </div>

        <!-- CONTENT -->
        <div class="content shadow">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2>Appointments</h2>

                    <p class="text-muted">
                        Manage Dental Clinic Appointments
                    </p>

                </div>

                <button class="btn btn-primary rounded-pill px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#addAppointmentModal">

                    + Book Appointment

                </button>

            </div>

            <!-- ALERTS -->
            <?php if (!empty($error)): ?>

                <div class="alert alert-danger">
                    <?= htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>

            <?php if (!empty($success)): ?>

                <div class="alert alert-success">
                    <?= htmlspecialchars($success); ?>
                </div>

            <?php endif; ?>

            <!-- SEARCH -->
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">

                <form method="GET" class="d-flex gap-2">

                    <input type="text"
                           name="search"
                           class="form-control rounded-pill"
                           placeholder="Search appointment...">

                    <button class="btn btn-outline-primary rounded-pill px-4">

                        Search

                    </button>

                </form>

            </div>

            <!-- TABLE -->
            <div class="card border-0 shadow-sm rounded-4 p-4">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-primary">

                            <tr>

                                <th>Patient</th>
                                <th>Date & Time</th>
                                <th>Dentist</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Actions</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($appointments as $row): ?>

                            <tr>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($row['Patient']); ?>
                                    </strong>
                                </td>

                                <td>

                                    <?= htmlspecialchars($row['appointment_date']); ?>

                                    <br>

                                    <small class="text-muted">

                                        <?= date(
                                            "h:i A",
                                            strtotime($row['appointment_time'])
                                        ); ?>

                                    </small>

                                </td>

                                <td>
                                    <?= htmlspecialchars($row['Dentist']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['service_name']); ?>
                                </td>

                                <td>

                                    <?php if ($row['status'] == 'confirmed'): ?>

                                        <span class="badge bg-success px-3 py-2">
                                            Confirmed
                                        </span>

                                    <?php elseif ($row['status'] == 'pending'): ?>

                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            Pending
                                        </span>

                                    <?php elseif ($row['status'] == 'completed'): ?>

                                        <span class="badge bg-primary px-3 py-2">
                                            Completed
                                        </span>

                                    <?php elseif ($row['status'] == 'ongoing'): ?>

                                        <span class="badge bg-info px-3 py-2">
                                            Ongoing
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger px-3 py-2">
                                            Cancelled
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if ($row['status'] == 'pending'): ?>

                                      <button class="btn btn-sm btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmModal"
                                        data-id="<?= $row['appointment_id']; ?>"
                                        data-status="confirmed">
                                        Accept
                                    </button>

                                    <button class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmModal"
                                        data-id="<?= $row['appointment_id']; ?>"
                                        data-status="cancelled">
                                        Cancel
                                    </button>

                                    <?php elseif ($row['status'] == 'confirmed'): ?>

                                        <button class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmModal"
                                            data-id="<?= $row['appointment_id']; ?>"
                                            data-status="ongoing">
                                            Ongoing
                                        </button>

                                    <?php elseif ($row['status'] == 'ongoing'): ?>

                                     <button class="btn btn-sm btn-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmModal"
                                            data-id="<?= $row['appointment_id']; ?>"
                                            data-status="completed">
                                            Finish
                                        </button>

                                    <?php else: ?>

                                        <span class="text-muted">

                                            No Actions

                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- CONFIRM MODAL -->
<div class="modal fade"
     id="confirmModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST"
                  action="update_status.php">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Confirm Action

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">

                    </button>

                </div>

                <div class="modal-body">

                    <p id="modalMessage">

                        Are you sure?

                    </p>

                   <input type="hidden" name="appointment_id" id="appointment_id">
                    <input type="hidden" name="status" id="status">

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-primary">

                        Yes, Proceed

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- ADD APPOINTMENT MODAL -->
<div class="modal fade"
     id="addAppointmentModal"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content rounded-4 border-0">

            <form action=""
                  method="POST">

                <div class="modal-header border-0">

                    <h5 class="modal-title">

                        Book Appointment

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">

                    </button>

                </div>

                <div class="modal-body">

                    <input type="text"
                           name="firstname"
                           class="form-control mb-2"
                           placeholder="First Name"
                           required>

                    <input type="text"
                           name="lastname"
                           class="form-control mb-2"
                           placeholder="Last Name"
                           required>

                    <input type="date"
                           name="birthdate"
                           class="form-control mb-2"
                           required>

                    <select name="gender"
                            class="form-control mb-2"
                            required>

                        <option value="">
                            Select Gender
                        </option>

                        <option value="Male">
                            Male
                        </option>

                        <option value="Female">
                            Female
                        </option>

                    </select>

                    <input type="text"
                           name="contact"
                           class="form-control mb-2"
                           placeholder="Contact"
                           required>

                    <textarea name="address"
                              class="form-control mb-2"
                              placeholder="Address"
                              required></textarea>

                    <select name="dentist_id"
                            class="form-control mb-2"
                            required>

                        <option value="">
                            Select Dentist
                        </option>

                        <?php foreach ($dentists as $d): ?>

                            <option value="<?= $d['dentist_id']; ?>">

                                <?= htmlspecialchars($d['fullname']); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <select name="service_id"
                            class="form-control mb-2"
                            required>

                        <option value="">
                            Select Services
                        </option>

                        <?php foreach ($services as $ser): ?>

                            <option value="<?= $ser['service_id']; ?>">

                                <?= htmlspecialchars($ser['service_name']); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <input type="date"
                           name="appointment_date"
                           class="form-control mb-2"
                           required>

                    <input type="time"
                           name="appointment_time"
                           class="form-control mb-2"
                           required>

                </div>

                <div class="modal-footer border-0">

                    <button type="submit"
                            class="btn btn-primary w-100 rounded-pill">

                        Book Now

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

var confirmModal = document.getElementById('confirmModal');

confirmModal.addEventListener('show.bs.modal', function (event) {

    var button = event.relatedTarget;

    var appointmentId = button.getAttribute('data-id');
    var status = button.getAttribute('data-status');

    document.getElementById('appointment_id').value = appointmentId;
    document.getElementById('status').value = status;

});

</script>

</body>
</html>