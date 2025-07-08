<?php

function getDureeTrajet($data)
{
    $url = "https://router.project-osrm.org/route/v1/driving/{$data['latitudeDepart']},{$data['longitudeDepart']};{$data['latitudeArrivee']},{$data['longitudeArrivee']}?overview=false";

    $response = file_get_contents($url);
    if (!$response) {
        return null;
    }
    $info = json_decode($response);

    $duree = $info->routes[0]->duration ?? null;

    if ($duree === null) {
        $data['duree'] = null;
    } else {
        $data['duree'] = floor($duree / 60);
    }
    return $data;
};
