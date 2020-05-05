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
    <link rel="stylesheet" href="./bootstrap.min.css">
    <link rel="stylesheet" href="./customes.css">
    <title>Eat Safe Search</title>

    <?php
        //Validate that we're being passed a value before connecting to db
        if (isset($_GET['query'])) {
            $query = $_GET['query'];

            $searchbox_value = $query; //pull out value so we can show it in search box

            $query = "%$query%"; //reformat so we can find text before and after keyword
            
            try {

                // Bring in env vars & set charset
                $dbhost = $_SERVER['RDS_HOSTNAME'];
                $dbport = $_SERVER['RDS_PORT'];
                $dbname = $_SERVER['RDS_DB_NAME'];

                $username = $_SERVER['RDS_USERNAME'];
                $password = $_SERVER['RDS_PASSWORD'];

                $charset = 'utf8';

                // Create 1st part of connection string
                $dsn = "mysql:host={$dbhost};port={$dbport};dbname={$dbname};charset={$charset}";
                
                // Assemble complete connection string
                $dbh = new PDO($dsn, $username, $password);

                // Send errors to php log, not on screen
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $q = 'SELECT `program_name`, `facility_address`, `facility_city`, `facility_state`, 
                `facility_zip` FROM restaurants WHERE 
                `program_name` LIKE :query OR
                `facility_address` LIKE :query OR
                `facility_city` LIKE :query OR
                `facility_state` LIKE :query OR
                `facility_zip` LIKE :query';

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
                establishing connection with database</small></h3></head></html>');
            }
        }

        else{
            /**
             * If the value of the 'id' GET parameter is not set, stop executing, return
             * a 'Bad request' HTTP status code (400), and display an error
             */
            http_response_code(400);
            die('<h3>Whoops! Something went wrong. <small class="text-muted">Error processing bad 
            or malformed request</small></h3></head></html>');
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

    <div class="container">
        <!--Filters-->
        <div class="row">
            <div class="col">
                <!--revisit-->
            </div>
        </div>
        <!--Results-->
        <div class="row">

            <?php
    foreach ($result as $item){ 

        // Create vars to reference later
        $program_name = ucwords( strtolower($item["program_name"] ));
        $address = ucwords( strtolower($item["facility_address"] ));
        $city = ucwords( strtolower($item["facility_city"] ));
        $state = ($item["facility_state"]);
        $zip = ($item["facility_zip"]);
        $score = "score_placeholder"; //($item["score"]);
        $grade = "grade_placeholder"; //($item["grade"]);

        // Clickable Google Maps directions link for each item
        $gmaps_url = "https://www.google.com/maps?saddr=My+Location&daddr=" . $item["program_name"] . "+" . $item["address"] . "+" . $item["city"] . "+" . $item["state"] . "+" . $item["zip"];

        // Table entry for each item
        echo '
            <div class="col">
                <div class="card mx-auto" style="width: 44rem;">
                    <div class="card-body">
                        <h5 class="card-title">'.$program_name.'</h5>
                        <h6 class="card-subtitle mb-2 text-muted">'.$address.', '.$city.', '.$state.' '.$zip.'</h6>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the cards content.</p>
                        <a href="'.$gmaps_url.'" target="self" class="card-link">Directions</a>
                        <a href="#" class="card-link">Another link</a>
                    </div>
                </div>
            </div>
            ';
    }
    ?>

        </div>
    </div>

</body>

</html>