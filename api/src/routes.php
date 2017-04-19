<?php
// Routes

//$app->get('/[{name}]', function ($request, $response, $args) {
//    // Sample log message
//    $this->logger->info("Slim-Skeleton '/' route");
//
//    // Render index view
//    return $this->renderer->render($response, 'index.phtml', $args);
//});

$app->get('/hello/{name}', function ($request, $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

// Clients
$app->group('/clients', function () {
    $this->get('[/{page:[0-9]+}[/{search}]]', function ($request, $response, $args) {
        $page = $request->getAttribute('page');
        $page = (empty($page)) ? 0 : (int)$page;
        $search = $request->getAttribute('search');
        $json = \macis\classes\clients::search($page, $search);
        echo json_encode($json, JSON_PRETTY_PRINT);
    });
});

$app->group('/client', function () {
    // Fiche patient
    $this->get('/{id:[0-9]+}', function ($request, $response, $args) {
        $id = $request->getAttribute('id');
        $json = \macis\classes\clients::get($id);
        echo json_encode($json, JSON_PRETTY_PRINT);
    });
});