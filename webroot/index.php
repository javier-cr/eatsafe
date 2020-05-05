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
    <title>Eat Safe: Trust Your Food</title>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-white">
        <a class="navbar-brand mx-auto mt-4 mt-sm-0 mx-sm-0" href="./index.php">
            <img src="./shield.png" width="45" height="45" class="d-inline-block align-top mt-2 mr-1"
                alt="EatSafe logo">
            <h1 class="text-primary navbar-text">EatSafe</h1>
        </a>
    </nav>

    <!--Tagline & Search Box-->
    <div class="container">
        <div class="row justify-content-center pt-5 mt-sm-5">
            <div class="col-10 col-lg-8 col-xl-6 text-center">
                <p class="h2 text-primary">Search for a restaurant or food venue to <span
                        class="font-weight-bolder">find health
                        scores near you</span></p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-12">

                <form autocomplete="off" class="row" method="get" action="./search.php">
                    <div class="col-10 col-lg-8 col-xl-6 mx-auto">

                        <div class="form-group mb-lg-0">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"
                                    placeholder="Enter restaurant name, address, city or ZIP Code"
                                    aria-label="Restuarant search" required="" 
                                    aria-describedby="search_submit" name="query">

                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="search_submit" type="submit">Search</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>

        <div class="row">
            <p class="h6 text-muted mt-4 mx-auto">&#128205; Now available in <span class="font-weight-bold">Los
                    Angeles</span>
            </p>
        </div>

    </div>

</body>

</html>