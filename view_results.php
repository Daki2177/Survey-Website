<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Survey Results</title>
    <link rel="stylesheet" href="styles.css">
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
            $total_surveys = $result->num_rows;

            $ages = [];
            $favorite_foods = [
                'Pizza' => 0,
                'Pasta' => 0,
                'Pap and Wors' => 0,
                'Other' => 0
            ];
            $ratings = [
                'watch_movies' => [],
                'listen_radio' => [],
                'eat_out' => [],
                'watch_tv' => []
            ];

            while($row = $result->fetch_assoc()) {
                if (isset($row['date_of_birth'])) {
                    $age = (int)date('Y') - (int)date('Y', strtotime($row['date_of_birth']));
                    $ages[] = $age;
                }

                if (isset($row['favorite_food']) && isset($favorite_foods[$row['favorite_food']])) {
                    $favorite_foods[$row['favorite_food']]++;
                }

                if (isset($row['watch_movies'])) {
                    $ratings['watch_movies'][] = $row['watch_movies'];
                }

                if (isset($row['listen_radio'])) {
                    $ratings['listen_radio'][] = $row['listen_radio'];
                }

                if (isset($row['eat_out'])) {
                    $ratings['eat_out'][] = $row['eat_out'];
                }

                if (isset($row['watch_tv'])) {
                    $ratings['watch_tv'][] = $row['watch_tv'];
                }
            }

            function calculate_percentage($count, $total) {
                return ($total > 0) ? round(($count / $total) * 100, 2) : 0;
            }

            function calculate_average($array) {
                $count = count($array);
                return ($count > 0) ? array_sum($array) / $count : 0;
            }

            echo "<p>Total number of surveys: $total_surveys</p>";
            echo "<p>Average Age: " . calculate_average($ages) . "</p>";
            echo "<p>Oldest person who participated in survey: " . ($ages ? max($ages) : 'N/A') . "</p>";
            echo "<p>Youngest person who participated in survey: " . ($ages ? min($ages) : 'N/A') . "</p>";

            foreach($favorite_foods as $food => $count) {
                echo "<p>Percentage of people who like $food: " . calculate_percentage($count, $total_surveys) . "%</p>";
            }

            foreach($ratings as $activity => $rating_array) {
                echo "<p>People who like to " . str_replace("_", " ", $activity) . ": " . calculate_average($rating_array) . "</p>";
            }

            $conn->close();
            ?>
        </main>
    </div>
</body>
</html>