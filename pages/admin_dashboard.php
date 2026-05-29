 <?php
    require_once('../classes/database.php');
    $con = new database();


    session_start();
    if (!isset($_SESSION['admin'])) {
        header("Location: admin_login.php");
        exit();
    }

    $recentAppointments = $con->recentAppointment();
    $totalPatient = $con->totalPatients();
    $pendingAppointments = $con->pendingAppointments();
    $totalAppointment = $con->totalAppointments();
    $totalPayment = $con->totalPayment();
    $todayAppointments = $con->todayAppointments();
    $totalDentists = $con->totalDentists();


    ?>


 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <title>Dental Clinic Dashboard</title>

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
     <link rel="stylesheet" href="../assets/css/style.css">
 </head>

 <body style="background:#d1d5db; font-family:Arial, sans-serif;">

     <!-- MAIN CONTAINER -->
     <div class="container-fluid p-3">

         <div class="main-container d-flex">

             <!-- SIDEBAR -->
             <div class="sidebar shadow">

                 <h2>🦷 Dental Clinic</h2>

                 <ul class="menu">

                     <li><a href="admin_dashboard.php">📊 Dashboard</a></li>

                     <li><a href="appointment.php">📅 Appointment</a></li>

                     <li><a href="patient_dentist.php">👥 Patient/Dentist</a></li>


                     <li><a href="services.php">❤️ Services</a></li>

                     <li><a href="payments.php">💰 Payments</a></li>

                     <li><a href="admin_feedback.php">💬 Feedback</a></li>

                     <li><a href="logout.php">🚪 Logout</a></li>

                 </ul>

             </div>

             <!-- CONTENT -->
             <div class="content shadow">

                 <!-- HEADER -->
                 <div class="d-flex justify-content-between align-items-center mb-4">

                     <div>
                         <h2>Welcome Dra. Rowena R. Cornejo</h2>
                         <p class="text-muted">
                             Dental Clinic Management System
                         </p>
                     </div>

                     <h6 class="text-muted">
                         <?php echo date("F d, Y"); ?>
                     </h6>

                 </div>

                 <!-- CARDS -->
                 <div class="row g-4">

                     <div class="col-md-4">
                         <div class="card-box">
                             <h3><?= $totalPatient ?></h3>
                             <p>Total Patients</p>
                         </div>
                     </div>

                     <div class="col-md-4">
                         <div class="card-box">
                             <h3><?= $totalDentists ?></h3>
                             <p>Total Dentist</p>
                         </div>
                     </div>

                     <div class="col-md-4">
                         <div class="card-box">
                             <h3>₱<?= $totalPayment ?></h3>
                             <p>Total Income</p>
                         </div>
                     </div>

                     <div class="col-md-4">
                         <div class="card-box">
                             <h3><?= $totalAppointment ?></h3>
                             <p>Appointments</p>
                         </div>
                     </div>

                     <div class="col-md-4">
                         <div class="card-box">
                             <h3><?= $pendingAppointments ?></h3>
                             <p>Pending</p>
                         </div>
                     </div>

                     <div class="col-md-4">
                         <div class="card-box">
                             <h3><?= $todayAppointments ?></h3>
                             <p>Today's Appointment</p>
                         </div>
                     </div>

                 </div>

                 <!-- TABLE -->
                 <div class="card shadow-sm mt-5 border-0 rounded-4 p-4">

                     <div class="d-flex justify-content-between align-items-center mb-3">

                         <h4>Recent Appointments</h4>

                         <a href="appointment.php" class="btn btn-primary">
                             View All
                         </a>

                     </div>

                     <div class="table-responsive">

                         <table class="table table-hover align-middle">

                             <thead class="table-primary">

                                 <tr>
                                     <th>Patient ID</th>
                                     <th>Patient Name</th>
                                     <th>Dentist Name</th>
                                     <th>Service</th>
                                     <th>Status</th>
                                 </tr>

                             </thead>

                             <tbody>

                                 <?php foreach ($recentAppointments as $row): ?>

                                     <tr>

                                         <td><?= $row['patient_id']; ?></td>

                                         <td><?= $row['patient']; ?></td>

                                         <td><?= $row['dentist']; ?></td>

                                         <td><?= $row['service_name']; ?></td>

                                         <td>

                                             <?php
                                                if ($row['status'] == 'confirmed') {
                                                    echo "<span class='badge bg-success'>Confirmed</span>";
                                                } elseif ($row['status'] == 'pending') {
                                                    echo "<span class='badge bg-warning text-dark'>Pending</span>";
                                                } elseif ($row['status'] == 'completed') {
                                                    echo "<span class='badge bg-primary'>Completed</span>";
                                                } else {
                                                    echo "<span class='badge bg-danger'>Cancelled</span>";
                                                }
                                                ?>

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

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

 </body>