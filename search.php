<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="follow">
    <meta name="description" content="Restaurant health scores for your favorite spots." />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#ffffff">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./customes.css">
    <title>Eat Safe Search</title>

    <?php
        //Validate that we're being passed a value before connecting to db
        if (isset($_GET['query'])) {
            $query = $_GET['query'];

            $searchbox_value = $query; //pull out value so we can show it in search box

            $query = "%$query%"; //reformat so we can find text before and after keyword
            
            try {

                $config = parse_ini_file('../db.ini');

                // Set up db connection
                $dbh = new PDO('mysql:dbname=publichealth;host=localhost', $config['user'], $config['pass']);
                // Send errors to php log, not on screen
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $q = 'SELECT * FROM inspections WHERE 
                `owner_name` LIKE :query OR 
                `biz_name` LIKE :query OR
                `address` LIKE :query OR
                `city` LIKE :query OR
                `state` LIKE :query OR
                `zip` LIKE :query OR
                `score` LIKE :query';

                // Prepare the SQL query string
                $sth = $dbh->prepare($q);

                // Bind parametres to statement variables
                $sth->bindParam(':query', $query);

                // Execute statement
                $sth->execute();

                // Set fetch mode to FETCH_ASSOC to return an array indexed by column name.
                $sth->setFetchMode(PDO::FETCH_ASSOC);
        
                // Fetch result.
                $result = $sth->fetchAll();

                // HTML encode our result using htmlentities() to prevent stored XSS
                //$result = htmlentities($result);
        
                //Close the connection to the database.
                $dbh = null;

            } catch (PDOException $e) {
                // Log PDO errors to php log, not on screen
                error_log('PDOException - ' . $e->getMessage(), 0);
                /**
                 * Stop executing, return an Internal Server Error HTTP status code (500),
                 * and display an error
                 */
                http_response_code(500);
                die('<h3>Whoops! Something went wrong. <small class="text-muted">Error 
                establishing connection with database</small></h3>');
            }
        }

        else{
            /**
             * If the value of the 'id' GET parameter is not set, stop executing, return
             * a 'Bad request' HTTP status code (400), and display an error
             */
            http_response_code(400);
            die('<h3>Whoops! Something went wrong. <small class="text-muted">Error processing bad 
            or malformed request</small></h3>');
        }
    ?>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-white">
        <a class="navbar-brand" href="./index.php">
            <img src="./shield.png" width="45" height="45" class="d-inline-block align-top mt-2 mr-1"
                alt="EatSafe logo">
            <h1 class="text-primary navbar-text">EatSafe</h1>
        </a>
    </nav>

    <!--Search box-->
    <div class="container">
        <div class="row mt-4">
            <div class="col">

                <form autocomplete="off" class="row" method="get" action="./search.php">
                    <div class="col-10 col-lg-8 col-xl-6 mx-auto">

                        <div class="form-group mb-lg-0">
                            <div class="input-group mb-3">
                                <!--Textbox placeholder is query-->
                                <input type="text" class="form-control" input value="<?php echo $searchbox_value ?>"
                                    aria-label="Restuarant search" required="" aria-describedby="search_submit"
                                    name="query">

                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="search_submit" type="submit">Search</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <!--Results table setup-->
    <table class="table table-hover mx-auto col-10 col-md-8">
        <div class="table responsive">
            <!--Heading-->
            <thead>
                <tr>
                    <th scope="col">Business Name</th>
                    <th scope="col">Latest Score</th>
                    <th scope="col">Latest Grade</th>
                </tr>
            </thead>

            <!--Rows-->
            <tbody>

                <?php
            foreach ($result as $item){ 

                // Create vars to reference later
                $biz_name = ucwords( strtolower($item["biz_name"] ));
                $address = ucwords( strtolower($item["address"] ));
                $city = ucwords( strtolower($item["city"] ));
                $state = ($item["state"]);
                $zip = ($item["zip"]);
                $score = ($item["score"]);
                $grade = ($item["grade"]);

                // Clickable Google Maps link for each item
                $gmaps_url = "https://www.google.com/maps?saddr=My+Location&daddr=" . $item["biz_name"] . "+" . $item["address"] . "+" . $item["city"] . "+" . $item["state"] . "+" . $item["zip"];

                // Table entry for each item
                echo '
                    <!--Start Row-->
                    <tr>
                        <td scope="row">
                            <strong>
                                '.$biz_name.'
                            </strong>
                            <br>
                            <a href="'.$gmaps_url.'" target="self">

                            '.$address.'
                            <br>
                            '.$city.', '.$state.' '.$zip.'
                            </a>
                        </td>

                        <td>
                            '.$item["score"].'
                        </td>

                        <td>
                            '.$item["grade"].'
                        </td>
                    </tr>';
            }
        ?>
            </tbody>
    </table>
</body>

</html>