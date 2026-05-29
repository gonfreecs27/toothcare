<?php
    
    session_start();
    require_once('../classes/database.php');
    $con = new database();

// Fetch feedback records
$result = $con->query("SELECT * FROM feedback ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Feedback Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-dark text-white text-center">
      <h3>Feedback Management</h3>
    </div>
    <div class="card-body">
      <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped table-hover">
          <thead class="table-primary">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Message</th>
              <th>Date Submitted</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                <td><?= $row['created_at']; ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="alert alert-info">No feedback has been submitted yet.</div>
      <?php endif; ?>
    </div>
    <div class="card-footer text-center text-muted">
      &copy; <?= date("Y"); ?> Dental Clinic Admin Panel
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
