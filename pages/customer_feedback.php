?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Feedback Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-primary text-white text-center">
      <h3>Feedback Form</h3>
    </div>
    <div class="card-body">
      <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success; ?></div>
      <?php endif; ?>
      <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
          <label for="message" class="form-label">Your Feedback</label>
          <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Submit Feedback</button>
      </form>
    </div>
    <div class="card-footer text-center text-muted">
      &copy; <?= date("Y"); ?> Dental Clinic
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>