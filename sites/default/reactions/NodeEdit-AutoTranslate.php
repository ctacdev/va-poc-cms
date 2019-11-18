<?php

hooks_reaction_add('HOOK_node_presave',
    function($node) {

        // We shall only react on new nodes here
        $arrNode = $node->toArray();
        if ( !empty($arrNode['nid']) ) return;

        $fieldMap = array(
            'field_title_eng' => 'field_title_es',
            'field_headline_eng' => 'field_headline_es',
            'field_bluebox_eng' => 'field_bluebox_es',
            'field_body_eng' => 'field_body_es',
            'field_greybox_eng' => 'field_greybox_es',
        );

        $fieldLabelMap = array(
            'field_title_eng' => 'Title',
            'field_headline_eng' => 'Headline',
            'field_bluebox_eng' => 'Blue-Box',
            'field_body_eng' => 'Body',
            'field_greybox_eng' => 'Grey-Box',
        );

        foreach ( $fieldMap as $fieldEng => $fieldEs ) {

            // We only care about empty Spanish fields here
            if ( !empty($node->get($fieldEs)->getString()) ) continue;

            // We only care about populated english fields here
            $fieldValue = $node->get($fieldEng)->getValue();
            if ( empty($fieldValue) ) continue;
            if ( !is_array($fieldValue) ) continue;
            if ( empty($fieldValue[0]['value']) ) continue;

            // Try to translate this field
            $source = $fieldValue[0]['value'];
            $trans = useGoogleToTranslate($source);

            // Complain and skip-on if there was an error
            if ( empty($trans) ) {

                drupal_set_message(
                    t("Failed to translate the {$fieldLabelMap[$fieldEng]} field to Spanish. The Spanish version of this field will be left empty."),
                    "error"
                );
                continue;
            }

            // Set the value for the Spanish field
            $fieldValue[0]['value'] = $trans;
            $node->get($fieldEs)->setValue($fieldValue);

            // Notify the user of this action that was taken
            drupal_set_message(
                t("Automaitcally translate the {$fieldLabelMap[$fieldEng]} field in Spanish using the Google-Translate"),
                "warning"
            );
        }
    }
);

function useGoogleToTranslate($text) {

    $apiKey = 'AIzaSyAfcX3jHY4XGk6r5q6d31GRAoa1_bP4Se4';
    $url =
        'https://www.googleapis.com/language/translate/v2?'
        . 'key=' . $apiKey
        . '&q=' . rawurlencode($text)
        . '&source=en'
        . '&target=es';

    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handle);
    $responseDecoded = json_decode($response, true);
    $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);

    // Bail on failure
    if ( $responseCode != 200 ) return false;
    if ( empty($responseDecoded['data']['translations'][0]['translatedText']) ) return false;
    
    return $responseDecoded['data']['translations'][0]['translatedText'];
}
