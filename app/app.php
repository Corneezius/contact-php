<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/homie.php";

    session_start();
    if (empty($_SESSION['list_of_homies'])) {
    $_SESSION['list_of_homies'] = array();
    }

    $app = new Silex\Application();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
));

    $app->get("/", function() use ($app) {

      return $app['twig']->render('homies.html.twig', array('homies' => Homie::getAll()));
    });

    $app->post("/homies", function() {
        $homie = new Homie($name = $_POST['name'],$phone = $_POST['phone'],$address = $_POST['address']);
        $homie->save();
        return "
            <h1>All my homies!</h1>
            <ol>
              <li><h1>" . $homie->getName() . "</h1>
                  <p>" . $homie->getPhone() . "</p>
                  <p>" . $homie->getAddress() . "</p></li>
            </ol>
            <p><a href='/'>View my homies.</a></p>
        ";
    });

    $app->post("/delete_homies", function() {

        Homie::deleteAll();

        return "
            <h1>List cleared!</h1>
            <p><a href='/'>Home</a></p>
        ";
    });

    return $app;
?>
