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

    $app->post("/homies", function() use ($app) {
        $homie = new Homie($name = $_POST['name'],$phone = $_POST['phone'],$address = $_POST['address']);
        $homie->save();
        return $app['twig']->render('create_homie.html.twig', array('newhomie' => $homie));
    });

    $app->post("/delete_homies", function() use ($app){
        Homie::deleteAll();
        return $app['twig']->render('delete_homies.html.twig');
    });

    return $app;
?>
