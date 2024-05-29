<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Survey Results</title>
    <link rel="stylesheet" href="view_results.css">
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

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $result = $conn->query("SELECT * FROM surveys");

            if ($result === false) {
                die("Error executing query: " . $conn->error);
            }

            if ($result->num_rows > 0) {
                $ages = [];
                $favorite_foods = [
                    'Pizza' => 0,
                    'Pasta' => 0,
                    'Pap and Wors' => 0,
                    'Other' => 0
                ];
                $activities = [
                    'watch_movies' => [],
                    'listen_radio' => [],
                    'eat_out' => [],
                    'watch_tv' => []
                ];

                while ($row = $result->fetch_assoc()) {
                    if (!empty($row['date_of_birth'])) {
                        $age = (int)date('Y') - (int)date('Y', strtotime($row['date_of_birth']));
                        $ages[] = $age;
                    }

                    $foods = explode(", ", $row['favorite_food']);
                    foreach ($foods as $food) {
                        if (isset($favorite_foods[$food])) {
                            $favorite_foods[$food]++;
                        }
                    }

                    foreach ($activities as $activity => &$ratings) {
                        if (!empty($row[$activity])) {
                            $ratings[] = (int)$row[$activity];
                        }
                    }
                }

                function calculate_percentage($count, $total) {
                    return ($total > 0) ? round(($count / $total) * 100, 2) : 0;
                }

                function calculate_average($array) {
                    $count = count($array);
                    return ($count > 0) ? round(array_sum($array) / $count, 2) : 0;
                }

                $total_surveys = $result->num_rows;
                echo "<div class='results'>";
                echo "<p>Total number of surveys:</p><p>$total_surveys</p>";
                echo "<p>Average Age:</p><p>" . calculate_average($ages) . "</p>";
                echo "<p>Oldest person who participated in survey:</p><p>" . ($ages ? max($ages) : 'N/A') . "</p>";
                echo "<p>Youngest person who participated in survey:</p><p>" . ($ages ? min($ages) : 'N/A') . "</p>";

                foreach ($favorite_foods as $food => $count) {
                    echo "<p>Percentage of people who like $food:</p><p>" . calculate_percentage($count, $total_surveys) . "%</p>";
                }

                foreach ($activities as $activity => $ratings) {
                    echo "<p>Average rating for " . str_replace("_", " ", $activity) . ":</p><p>" . calculate_average($ratings) . "</p>";
                }
                echo "</div>";
            } else {
                echo "<p>No Surveys Available.</p>";
            }

            $conn->close();
            ?>
        </main>
    </div>
</body>
</html>