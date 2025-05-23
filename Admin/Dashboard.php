<?php
session_start();

require_once 'includes/dbc.inc.php';

$search = '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];

    
    $sql = "SELECT * FROM users WHERE username LIKE :search OR email LIKE :search";
    $stmt = $conn->prepare($sql);
    $searchParam = '%' . $search . '%'; 
    $stmt->bindParam(':search', $searchParam);
    $stmt->execute();

    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
   
    $sql = "SELECT * FROM users";
    $stmt = $conn->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004a99;
        }
    </style>
</head>
<body> <div class="bg-white">
  <header class="fixed-top bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light bg-white container-fluid py-3">
     
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Logo" width="30" height="30">
        <span class="nav-item">JEEPS</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" href="Dashboard.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Service</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="includes/logout.inc.php">Log Out</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="dashboard-container">
    <div class="container mt-5">
        <h2 class="text-center">Admin Dashboard</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
       
        <form method="GET" action="Dashboard.php" class="mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search by username or email" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

       
        

        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Account Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                require_once 'includes/dbc.inc.php';

                $search = isset($_GET['search']) ? $_GET['search'] : '';

              
                $sql = "SELECT * FROM users WHERE username LIKE :search OR email LIKE :search";
                $stmt = $conn->prepare($sql);
                $searchParam = '%' . $search . '%';
                $stmt->bindParam(':search', $searchParam);
                $stmt->execute();

                while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($user['account_type'] != 'admin') 
                    {
                        echo "<tr>";
                        echo "<td>{$user['id']}</td>";
                        echo "<td>{$user['username']}</td>";
                        echo "<td>{$user['email']}</td>";
                        echo "<td>{$user['account_type']}</td>";
                        echo "<td>
                                <a href='edit_user.php?id={$user['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_user.php?id={$user['id']}' class='btn btn-danger btn-sm'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>
   </body>
</html>
