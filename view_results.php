<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Survey Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>_Surveys</h1>
            <nav>
                <a href="fill_survey.html">FILL OUT SURVEY</a>
                <a href="view_results.php" class="active">VIEW SURVEY RESULTS</a>
            </nav>
        </header>
        <main>
            <h2>Survey Results</h2>
            
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "survey_db";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $result = $conn->query("SELECT * FROM surveys");

            if ($result === false) {
                die("Error executing query: " . $conn->error);
            }

            // Check if there are any survey results
            if ($result->num_rows > 0) {
                // Initialize arrays for statistics
                $ages = [];
                $favorite_foods = [
                    'Pizza' => 0,
                    'Pasta' => 0,
                    'Pap and Wors' => 0,
                    'Other' => 0
                ];
                $activities = [
                    'watch_movies' => 0,
                    'listen_radio' => 0,
                    'eat_out' => 0,
                    'watch_tv' => 0
                ];

                // Process survey results
                while ($row = $result->fetch_assoc()) {
                    if (!empty($row['date_of_birth'])) {
                        $age = (int)date('Y') - (int)date('Y', strtotime($row['date_of_birth']));
                        $ages[] = $age;
                    }

                    $foods = explode(",", $row['favorite_food']);
                    foreach ($foods as $food) {
                        if (isset($favorite_foods[$food])) {
                            $favorite_foods[$food]++;
                        }
                    }

                    foreach ($activities as $activity => &$count) {
                        if (!empty($row[$activity])) {
                            $count++;
                        }
                    }
                }

                // Function to calculate percentage
                function calculate_percentage($count, $total) {
                    return ($total > 0) ? round(($count / $total) * 100, 2) : 0;
                }

                // Function to calculate average
                function calculate_average($array) {
                    $count = count($array);
                    return ($count > 0) ? array_sum($array) / $count : 0;
                }

                // Display survey statistics
                $total_surveys = $result->num_rows;
                echo "<p>Total number of surveys: $total_surveys</p>";
                echo "<p>Average Age: " . calculate_average($ages) . "</p>";
                echo "<p>Oldest person who participated in survey: " . ($ages ? max($ages) : 'N/A') . "</p>";
                echo "<p>Youngest person who participated in survey: " . ($ages ? min($ages) : 'N/A') . "</p>";

                foreach ($favorite_foods as $food => $count) {
                    echo "<p>Percentage of people who like $food: " . calculate_percentage($count, $total_surveys) . "%</p>";
                }

                foreach ($activities as $activity => $count) {
                    echo "<p>Number of people who like to " . str_replace("_", " ", $activity) . ": $count people</p>";
                }
            } else {
                // If no survey results are available, display a message
                echo "<p>No Surveys Available.</p>";
            }

            $conn->close();
            ?>
        </main>
    </div>
</body>
</html>