<?php
// employee_demo.php
// CW06 MySQL + PHP CRUD-style insert demo.
// This page collects employee information and inserts it into MySQL.

$message = "";
$messageClass = "";

// These variables help keep the form filled in if validation fails.
$empName = "";
$jobName = "";
$salary = "";
$hireDate = "";
$departmentId = "";
$departmentName = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $empName = trim($_POST["emp_name"] ?? "");
  $jobName = trim($_POST["job_name"] ?? "");
  $salary = trim($_POST["salary"] ?? "");
  $hireDate = trim($_POST["hire_date"] ?? "");
  $departmentId = trim($_POST["department_id"] ?? "");
  $departmentName = trim($_POST["department_name"] ?? "");

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
  } elseif (!is_numeric($salary) || $salary <= 0) {
    $message = "Please enter a valid salary greater than 0.";
    $messageClass = "error";
  } elseif (!is_numeric($departmentId) || $departmentId <= 0) {
    $message = "Please enter a valid department ID greater than 0.";
    $messageClass = "error";
  } else {
    require_once "db.php";

    $sql = "INSERT INTO employees
            (emp_name, job_name, salary, hire_date, department_id, department_name)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $salaryValue = (float) $salary;
      $departmentIdValue = (int) $departmentId;

      // This prepared statement keeps the insert safer than putting values directly into SQL.
      $stmt->bind_param(
        "ssdsis",
        $empName,
        $jobName,
        $salaryValue,
        $hireDate,
        $departmentIdValue,
        $departmentName
      );

      if ($stmt->execute()) {
        $message = "Employee record was added successfully.";
        $messageClass = "success";

        $empName = "";
        $jobName = "";
        $salary = "";
        $hireDate = "";
        $departmentId = "";
        $departmentName = "";
      } else {
        $message = "The employee record could not be saved.";
        $messageClass = "error";
      }

      $stmt->close();
    } else {
      $message = "The SQL statement could not be prepared.";
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
          This page uses PHP, MySQL, form validation, and a prepared statement to
          save employee records into a database table.
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
          <input
            type="text"
            id="emp_name"
            name="emp_name"
            placeholder="Example: Kenny Vo"
            value="<?php echo htmlspecialchars($empName); ?>"
          >
        </div>

        <div class="form-row">
          <label for="job_name">Job Name</label>
          <input
            type="text"
            id="job_name"
            name="job_name"
            placeholder="Example: Web Developer"
            value="<?php echo htmlspecialchars($jobName); ?>"
          >
        </div>

        <div class="form-row">
          <label for="salary">Salary</label>
          <input
            type="number"
            step="0.01"
            id="salary"
            name="salary"
            placeholder="Example: 65000.00"
            value="<?php echo htmlspecialchars($salary); ?>"
          >
        </div>

        <div class="form-row">
          <label for="hire_date">Hire Date</label>
          <input
            type="date"
            id="hire_date"
            name="hire_date"
            value="<?php echo htmlspecialchars($hireDate); ?>"
          >
        </div>

        <div class="form-row">
          <label for="department_id">Department ID</label>
          <input
            type="number"
            id="department_id"
            name="department_id"
            placeholder="Example: 10"
            value="<?php echo htmlspecialchars($departmentId); ?>"
          >
        </div>

        <div class="form-row">
          <label for="department_name">Department Name</label>
          <input
            type="text"
            id="department_name"
            name="department_name"
            placeholder="Example: Technology"
            value="<?php echo htmlspecialchars($departmentName); ?>"
          >
        </div>

        <button type="submit">Add Employee</button>
      </form>

      <div class="note-box">
        <h2>What this demo shows</h2>
        <p>
          The form sends data to PHP, PHP validates the input, and then a prepared
          statement inserts the data into the employees table.
        </p>
      </div>
    </section>
  </main>
</body>
</html>
