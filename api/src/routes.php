<?php
// Routes

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

$app->get('/hello/{name}', function (ServerRequestInterface $request, ResponseInterface $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

// Clients
$app->group('/clients', function () {
    $this->get('[/{page:[0-9]+}[/{search}]]', function (ServerRequestInterface $request, ResponseInterface $response, $args) {
        $page = $request->getAttribute('page');
        $page = (empty($page)) ? 0 : (int)$page;
        $search = $request->getAttribute('search');
        $json = \macis\classes\clients::search($page, $search);
        $json['user'] = $_SESSION['user'];
        echo json_encode($json, JSON_PRETTY_PRINT);
    });
});

$app->group('/client', function () {
    // Fiche client
    $this->get('/{id:[0-9]+}', function (ServerRequestInterface $request, ResponseInterface $response, $args) {
        $id = $request->getAttribute('id');
        $json['client'] = \macis\classes\clients::get($id);
        $json['user'] = $_SESSION['user'];
        echo json_encode($json, JSON_PRETTY_PRINT);
    });
    // Update client
    $this->put('/{id:[0-9]+}', function (ServerRequestInterface $request, ResponseInterface $response, $args) {
        $id = $request->getAttribute('id');
        $values = $request->getBody();
        $values = json_decode($values, true);
        $json['client'] = \macis\classes\clients::put($id, $values);
        $json['user'] = $_SESSION['user'];
        echo json_encode($json, JSON_PRETTY_PRINT);
    });
    // Add client
    $this->post('', function (ServerRequestInterface $request, ResponseInterface $response, $args) {
        $values = $request->getBody();
        $values = json_decode($values, true);
        $json['client'] = \macis\classes\clients::post($values);
        $json['user'] = $_SESSION['user'];
        echo json_encode($json, JSON_PRETTY_PRINT);
    });
    // Del client
    $this->delete('/{id:[0-9]+}', function (ServerRequestInterface $request, ResponseInterface $response, $args) {
        $id = $request->getAttribute('id');
        $json['client'] = \macis\classes\clients::delete($id);
        $json['user'] = $_SESSION['user'];
        echo json_encode($json, JSON_PRETTY_PRINT);
    });
});
