<?php
function getCoordinatesFromAddress($rue, $codePostal, $ville)
{
    $query = urlencode("$rue, $codePostal $ville");
    $url = "https://api-adresse.data.gouv.fr/search/?q=$query&limit=1";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        return null;
    }

    $data = json_decode($response, true);

    if (!empty($data['features'][0]['geometry']['coordinates'])) {
        $coordinates = $data['features'][0]['geometry']['coordinates'];
        return [
            'longitude' => $coordinates[0],
            'latitude' => $coordinates[1]
        ];
    }

    return null;
}

function getAdresseDetailsFromAPI($adresse)
{
    $query = urlencode($adresse);
    $url = "https://api-adresse.data.gouv.fr/search/?q=$query&limit=1";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        return null;
    }

    $data = json_decode($response, true);

    if (!empty($data['features'][0]['properties']) && !empty($data['features'][0]['geometry']['coordinates'])) {
        $props = $data['features'][0]['properties'];
        $coords = $data['features'][0]['geometry']['coordinates'];
        return [
            'rue' => $props['name'] ?? '',
            'codePostal' => $props['postcode'] ?? '',
            'ville' => $props['city'] ?? '',
            'longitude' => $coords[0],
            'latitude' => $coords[1]
        ];
    }

    return null;
}
