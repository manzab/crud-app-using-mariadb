<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "sprint2";
$path = "?path";
$num = 12;

$conn = mysqli_connect($servername, $username, $password, $dbname); // Create connection

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";
if(isset($_GET['action']) and $_GET['action'] == 'delete'){
    $sql = 'DELETE FROM projects WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['id']);
    $res = $stmt->execute();
    $stmt->close();
    mysqli_close($conn);
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    die();
}
print("<head><link rel='stylesheet' href='styles.css'></head>");
print("<body>");
print("<br>");
print("<br>");
print("<header><div class='links'><a href='$path=projects'>PROJECTS</a><a href='$path=employees'>EMPLOYEES</a></div><div class='logo'><img src='https://cdn1.iconfinder.com/data/icons/youtuber/256/thumbs-up-like-gesture-512.png'></div></header>");
print("</header");
print("<br>");
if (isset($_GET['path']) and $_GET['path'] == 'projects') {
    $sql = "SELECT projects.id, projects.name as Projects, GROUP_CONCAT(employees.name SEPARATOR ', ') as Employees FROM projects LEFT JOIN employees ON projects.employees_id = employees.id GROUP BY projects.id";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        print("<table>");
        print("<thead><tr><th>#</th><th>Projects</th><th>Employees</th><th>Actions</th></tr></thead><tbody>");
        while ($row = mysqli_fetch_assoc($result)) {
            print('<tr>' . '<td>' . $num++ . '</td>' . '<td>' . $row['Projects'] . '</td>' . '<td>' . $row['Employees'] . '</td>' . '<td>' . '<a href="?action=delete&id=' . $row['id'] . '"><button>DELETE</button></a>'. '</td>'. '</tr>');
        }
        print('</tbody></table>');
    } else {
        echo "0 results";
    }
};
if (isset($_GET['path']) and $_GET['path'] == 'employees') {
    $sql = "SELECT employees.name as Employees, projects.name as Projects FROM employees LEFT JOIN projects ON employees.project_id = projects.id";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        print("<table>");
        print("<thead><tr><th>#</th><th>Name</th><th>Projects</th><th>Actions</th></tr></thead><tbody>");
        while ($row = mysqli_fetch_assoc($result)) {
            print("<tr>" . "<td>" . $num++ . "</td>" . "<td>" . $row["Employees"] . "</td>" . "<td>" . $row["Projects"] . "</td>" . "</tr><br>");
        }
        print("</tbody></table>");
    } else {
        echo "0 results";
    }
}
mysqli_close($conn);
print("</body>");


