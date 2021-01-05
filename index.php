<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "sprint2";
$path = "?path";

$conn = mysqli_connect($servername, $username, $password, $dbname); // Create connection

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
print("<head><link rel='stylesheet' href='styles.css'></head>");
print("<body>");
print("<br>");
print("<br>");
print("<header><div class='links'><a href='$path=projects'>PROJECTS</a><a href='$path=employees'>EMPLOYEES</a></div><div class='logo'>EMPLOYEES</div></header>");
print("</header");
print("<br>");
print("<table>");
print("<thead><tr><th>Id</th><th>Name</th><th>Projects</th></tr></thead>");

if (isset($_GET['path']) and $_GET['path'] = 'projects' and  $_GET['path'] != 'employees') {
    $sql = "SELECT id, name, employees FROM projects";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            print("<tr>" . "<td>" . $row["id"] . "</td>" . "<td>" . $row["name"] . "</td>" . "<td>" . $row["employees"] . "</td>" . "</tr><br>");
        }
    } else {
        echo "0 results";
    }
    print_r($_GET);
};
if (isset($_GET['path']) and $_GET['path'] = 'employees') {
    $sql = "SELECT id, name, projects FROM employees";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            print("<tr>" . "<td>" . $row["id"] . "</td>" . "<td>" . $row["name"] . "</td>" . "<td>" . $row["projects"] . "</td>" . "</tr><br>");
        }
    } else {
        echo "0 results";
    }
    
}

print("</table>");
print("</body>");

mysqli_close($conn);
