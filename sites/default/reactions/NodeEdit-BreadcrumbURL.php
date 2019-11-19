<?php

hooks_reaction_add(['HOOK_node_update', 'HOOK_node_insert'],
    function($node) {

        $aNode = $node->toArray();

        // We shall only react on NON basic pages here
        if ( $aNode['type'][0]['target_id'] == 'basic_page' ) return;

        // Bail if there is no node-ID
        $nid = @$aNode['nid'][0]['value'];
        $nid = intval($nid);
        if ( empty($nid) ) return;

        // Get the current path alias
        $curPath = @$aNode['path'][0]['alias'];

        // Determine new path alias
        $setPath = _traceBreadcrumbUrl($nid);

        // Bail if the path is not changing
        if ( $curPath == $setPath ) return;

        // Delete any path that currently in the way
        \Drupal::service('path.alias_storage')->delete(['source' => '/node/'.$nid]);

        // Save new path alias
        $path = \Drupal::service('path.alias_storage')->save(
            '/node/' . $node->id(),
            $setPath,
            'en'
        );

        // Tell the user about this action
        drupal_set_message("This content-item now has the URL path of; {$setPath}", "status");
    }
);

function _traceBreadcrumbUrl($nid) {

    $path = '';

    for ( $limit = 0 ; $limit < 20 ; $limit++ ) {

        // Lookup the title for this node
        $query = \Drupal::database()->select('node_field_revision', 'r');
        $query->addField('r', 'title');
        $query->condition('r.nid', $nid);
        $query->orderBy('r.vid', 'DESC');
        $title = $query->execute()->fetchColumn();
        if ( empty($title) ) break;

        // Build the new URL-alias
        $path = "/{$title}{$path}";

        // Lookup the parent for this node
        $query = \Drupal::database()->select('node__field_children', 'c');
        $query->addField('c', 'entity_id');
        $query->condition('c.field_children_target_id', $nid);
        $parentNid = $query->execute()->fetchColumn();
        $parentNid = intval($parentNid);
        if ( empty($parentNid) ) break;

        $nid = $parentNid;
    }

    return $path;
}