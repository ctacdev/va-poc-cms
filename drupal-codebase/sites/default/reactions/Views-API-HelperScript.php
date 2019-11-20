<?php


function hooks_reaction_views_pre_view($view, $display_id, &$args) {

    //
    if ( empty($display_id) || !is_string($display_id) ) return;
    if ( $display_id !== 'rest_export_1' ) return;

    // Get the target URL
    $ruri = @$_SERVER['REQUEST_URI'];
    if ( strpos($ruri, '/api-eng') === false && strpos($ruri, '/api-es') === false ) return;
    if ( empty($ruri) ) return;
    $ruri = str_replace('/api-eng', '', $ruri);
    $ruri = str_replace('/api-es', '', $ruri);

    // Lookup the node
    $query = \Drupal::database()->select('url_alias', 'u');
    $query->addField('u', 'source');
    $query->condition('u.alias', $ruri);
    $result = $query->execute()->fetchColumn();
    if ( empty($result) ) return;
    $result = str_replace('/node/', '', $result);

    // $view->setArguments( [18] );
    $view->setArguments( [ $result ] );
}
