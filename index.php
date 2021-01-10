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
mysqli_query($conn, "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

if(isset($_GET['action']) and $_GET['action'] == 'deleteProject'){
    $sql = 'DELETE FROM projects WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['id']);
    $res = $stmt->execute();
    $stmt->close();
    mysqli_close($conn);
    header("Location: ?path=projects");
    die();
};
if(isset($_GET['action']) and $_GET['action'] == 'deleteEmployee'){
    $sql = 'DELETE FROM employees WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['id']);
    $res = $stmt->execute();
    $stmt->close();
    mysqli_close($conn);
    header("Location: ?path=employees");
    die();
};
// if(isset($_GET['action']) and $_GET['action'] == 'updateProject') {

// }

print("<head><link rel='stylesheet' href='styles.css'></head>");
print("<body>");
print("<br>");
print("<br>");
print("<header><div class='links'><a href='$path=projects'>PROJECTS</a><a href='$path=employees'>EMPLOYEES</a></div><div class='logo'><img src='https://cdn1.iconfinder.com/data/icons/youtuber/256/thumbs-up-like-gesture-512.png'></div></header>");
print("</header");
print("<br>");
if (isset($_GET['path']) and $_GET['path'] == 'projects') {

    $sql = "SELECT projects.id, GROUP_CONCAT(employees.name SEPARATOR ', ') as Employees, projects.name as Projects FROM projects LEFT JOIN employees ON projects.employees_id = employees.id GROUP BY projects.name";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        print("<table>");
        print("<thead><tr><th>#</th><th>Projects</th><th>Employees</th><th>Actions</th></tr></thead><tbody>");
        while ($row = mysqli_fetch_assoc($result)) {
            print('<tr>' . '<td>' . $num++ . '</td>' . '<td>' . $row['Projects'] . '</td>' . '<td>' . $row['Employees'] . '</td>' . '<td>' . '<a href="?action=deleteProject&id=' . $row['id'] . '"><button>DELETE</button></a>'.'<a href="?action=updateProject&id=' . $row['id'] .'"><button>UPDATE</button></a>'. '</td>'. '</tr>');
        }
        print('</tbody></table>');
    } else {
        echo "0 results";
    }
};
if (isset($_GET['path']) and $_GET['path'] == 'employees') {
    $sql = "SELECT employees.id , employees.name as Employees, projects.name as Projects FROM employees LEFT JOIN projects ON employees.project_id = projects.id";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        print("<table>");
        print("<thead><tr><th>#</th><th>Name</th><th>Projects</th><th>Actions</th></tr></thead><tbody>");
        while ($row = mysqli_fetch_assoc($result)) {
            print("<tr>" . "<td>" . $num++ . "</td>" . "<td>" . $row["Employees"] . "</td>" . "<td>" . $row["Projects"] . "</td>" . '<td>' . '<a href="?action=deleteEmployee&id=' . $row['id'] . '"><button>DELETE</button></a>'. '<a href="?action=updateProject&id=' . $row['id'] .'"><button>UPDATE</button></a>'. '</td>'. '</tr>');
        }
        print("</tbody></table>");
    } else {
        echo "0 results";
    }
}
mysqli_close($conn);
print("</body>");


