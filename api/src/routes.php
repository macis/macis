<?php
// Routes

//$app->get('/[{name}]', function ($request, $response, $args) {
//    // Sample log message
//    $this->logger->info("Slim-Skeleton '/' route");
//
//    // Render index view
//    return $this->renderer->render($response, 'index.phtml', $args);
//});


// Clients
$app->group('/clients', function () {

    $this->any('', function ($request, $response, $args) {
        return $response->withStatus(302)->withHeader('Location', '/patients/recherche');
    });

    $this->group('/recherche', function () {
        $this->post('', function ($request, $response, $args) {
            $search = $request->getParsedBody()['search'];
            if (!empty($search)) {
                return $response->withStatus(302)->withHeader('Location', '/patients/recherche/' . $search);
            } else {
                return $response->withStatus(302)->withHeader('Location', '/patients/recherche');
            }
        });
        $this->get('[/{search}[/{page}]]', function ($request, $response, $args) {
            $json = \macis\classes\contact::search($this, $request, $args);
            echo json_encode($json, JSON_PRETTY_PRINT);
        });

    });
});

$app->group('/client', function () {
    // Fiche patient
    $this->get('/{id}', function ($request, $response, $args) {
        $result = \macis\classes\contact::get($this, $request, $args);
        return json_encode($result);
    });
});