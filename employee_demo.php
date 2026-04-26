<?php
// employee_demo.php
// CW06 MySQL + PHP demo page.
// This page lets the user add an employee record into the MySQL database.

$message = "";
$messageClass = "";

// Only connect to the database after the form is submitted.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  require_once "db.php";

  // Grab the form data and trim extra spaces.
  $empName = trim($_POST["emp_name"]);
  $jobName = trim($_POST["job_name"]);
  $salary = trim($_POST["salary"]);
  $hireDate = trim($_POST["hire_date"]);
  $departmentId = trim($_POST["department_id"]);
  $departmentName = trim($_POST["department_name"]);

  // Basic validation so empty data does not get saved.
  if (
    $empName === "" ||
    $jobName === "" ||
    $salary === "" ||
    $hireDate === "" ||
    $departmentId === "" ||
    $departmentName === ""
  ) {
    $message = "Please fill out every field before submitting.";
    $messageClass = "error";
  } else {
    // Prepared statements help keep the database safer from SQL injection.
    $sql = "INSERT INTO employees
            (emp_name, job_name, salary, hire_date, department_id, department_name)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param(
        "ssdsis",
        $empName,
        $jobName,
        $salary,
        $hireDate,
        $departmentId,
        $departmentName
      );

      if ($stmt->execute()) {
        $message = "Employee record was added successfully.";
        $messageClass = "success";
      } else {
        $message = "Something went wrong while saving the employee.";
        $messageClass = "error";
      }

      $stmt->close();
    } else {
      $message = "The insert statement could not be prepared.";
      $messageClass = "error";
    }

    $conn->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CW06 MySQL + PHP Employee Demo</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <main class="page">
    <section class="card">
      <div class="header-block">
        <p class="eyebrow">CW06 MySQL + PHP</p>
        <h1>Employee Database Demo</h1>
        <p class="intro">
          This page uses PHP, MySQL, and a prepared statement to insert employee
          information into a database table.
        </p>
      </div>

      <?php if ($message !== ""): ?>
        <div class="message <?php echo $messageClass; ?>">
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="employee_demo.php" class="employee-form">
        <div class="form-row">
          <label for="emp_name">Employee Name</label>
          <input type="text" id="emp_name" name="emp_name" placeholder="Example: Kenny Vo">
        </div>

        <div class="form-row">
          <label for="job_name">Job Name</label>
          <input type="text" id="job_name" name="job_name" placeholder="Example: Web Developer">
        </div>

        <div class="form-row">
          <label for="salary">Salary</label>
          <input type="number" step="0.01" id="salary" name="salary" placeholder="Example: 65000.00">
        </div>

        <div class="form-row">
          <label for="hire_date">Hire Date</label>
          <input type="date" id="hire_date" name="hire_date">
        </div>

        <div class="form-row">
          <label for="department_id">Department ID</label>
          <input type="number" id="department_id" name="department_id" placeholder="Example: 10">
        </div>

        <div class="form-row">
          <label for="department_name">Department Name</label>
          <input type="text" id="department_name" name="department_name" placeholder="Example: Technology">
        </div>

        <button type="submit">Add Employee</button>
      </form>
    </section>
  </main>
</body>
</html>
