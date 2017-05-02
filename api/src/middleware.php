<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
use \Slim\Middleware\HttpBasicAuthentication\PdoAuthenticator;

$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path" => "/",
    "realm" => "Protected",

    // perhaps the following need to be rewrited
    "authenticator" => new PdoAuthenticator([
        "pdo" => \DB\connectDB::getPDO(),
        "table" => "users",
        "user" => "username",
        "hash" => "password"
    ]),
    "error" => function ($request, $response, $arguments) {
        $data = [];
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response->write(json_encode($data, JSON_UNESCAPED_SLASHES));
    },
    "callback" => function ($request, $response, $arguments) {
        \macis\classes\users::login($arguments['user'], $arguments['password']);
    }
]));
