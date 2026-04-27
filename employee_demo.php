<?php
// employee_demo.php
// CW06 MySQL + PHP employee demo.
// This page inserts employee data and also displays current records.

$message = "";
$messageClass = "";

// Keep form values if validation fails.
$empName = "";
$jobName = "";
$salary = "";
$hireDate = "";
$departmentId = "";
$departmentName = "";

// Run insert logic if the form was submitted.
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
    $message = "Please enter a valid salary.";
    $messageClass = "error";
  } elseif (!is_numeric($departmentId) || $departmentId <= 0) {
    $message = "Please enter a valid department ID.";
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

        // Clear the form after success.
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
  }
}

// Always connect so we can display the employee table.
if (!isset($conn)) {
  require_once "db.php";
}

// Pull current employees.
$employees = [];
$result = $conn->query("SELECT * FROM employees ORDER BY id DESC");

if ($result) {
  while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
  }
}

$conn->close();
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
          This page inserts employee data into MySQL and displays all saved records.
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
          <input type="text" id="emp_name" name="emp_name"
            value="<?php echo htmlspecialchars($empName); ?>">
        </div>

        <div class="form-row">
          <label for="job_name">Job Name</label>
          <input type="text" id="job_name" name="job_name"
            value="<?php echo htmlspecialchars($jobName); ?>">
        </div>

        <div class="form-row">
          <label for="salary">Salary</label>
          <input type="number" step="0.01" id="salary" name="salary"
            value="<?php echo htmlspecialchars($salary); ?>">
        </div>

        <div class="form-row">
          <label for="hire_date">Hire Date</label>
          <input type="date" id="hire_date" name="hire_date"
            value="<?php echo htmlspecialchars($hireDate); ?>">
        </div>

        <div class="form-row">
          <label for="department_id">Department ID</label>
          <input type="number" id="department_id" name="department_id"
            value="<?php echo htmlspecialchars($departmentId); ?>">
        </div>

        <div class="form-row">
          <label for="department_name">Department Name</label>
          <input type="text" id="department_name" name="department_name"
            value="<?php echo htmlspecialchars($departmentName); ?>">
        </div>

        <button type="submit">Add Employee</button>
      </form>

      <div class="table-wrap">
        <h2>Current Employees</h2>

        <?php if (count($employees) > 0): ?>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Job</th>
                <th>Salary</th>
                <th>Hire Date</th>
                <th>Dept ID</th>
                <th>Department</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($employees as $employee): ?>
                <tr>
                  <td><?php echo $employee["id"]; ?></td>
                  <td><?php echo htmlspecialchars($employee["emp_name"]); ?></td>
                  <td><?php echo htmlspecialchars($employee["job_name"]); ?></td>
                  <td>$<?php echo number_format($employee["salary"], 2); ?></td>
                  <td><?php echo $employee["hire_date"]; ?></td>
                  <td><?php echo $employee["department_id"]; ?></td>
                  <td><?php echo htmlspecialchars($employee["department_name"]); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p class="empty-text">No employees saved yet.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>
</body>
</html>
