<?php

hooks_reaction_add('HOOK_node_presave',
    function($node) {

        $log = '';

        // We shall only react on NON basic pages here
        $arrNode = $node->toArray();
        if ( $arrNode['type'][0]['target_id'] == 'basic_page' ) return;

        // If this node already has a revision-log message, bail
        if ( !empty($node->revision_log->getString()) ) return;

        // Load up the original node (before save)
        $nid = $node->nid->getString();
        if ( empty($nid) ) {
            $node->revision_log->setValue('Initial content creation');
            return;
        }
        $orig = \Drupal\node\Entity\Node::load($nid);

        // Check for changes to English fields
        if ( $orig->field_title_eng->getString() != $node->field_title_eng->getString() ) $log .= "English Title changed.\n";
        if ( $orig->field_headline_eng->getString() != $node->field_headline_eng->getString() ) $log .= "English Headline changed.\n";
        if ( $orig->field_bluebox_eng->getString() != $node->field_bluebox_eng->getString() ) $log .= "English BlueBox changed.\n";
        if ( $orig->field_body_eng->getString() != $node->field_body_eng->getString() ) $log .= "English Body changed.\n";
        if ( $orig->field_greybox_eng->getString() != $node->field_greybox_eng->getString() ) $log .= "English GreyBox changed.\n";

        // Check for changes to Spanish fields
        if ( $orig->field_title_es->getString() != $node->field_title_es->getString() ) $log .= "Spanish Title changed.\n";
        if ( $orig->field_headline_es->getString() != $node->field_headline_es->getString() ) $log .= "Spanish Headline changed.\n";
        if ( $orig->field_bluebox_es->getString() != $node->field_bluebox_es->getString() ) $log .= "Spanish BlueBox changed.\n";
        if ( $orig->field_body_es->getString() != $node->field_body_es->getString() ) $log .= "Spanish Body changed.\n";
        if ( $orig->field_greybox_es->getString() != $node->field_greybox_es->getString() ) $log .= "Spanish GreyBox changed.\n";

        // Set the revision log message
        $node->revision_log->setValue($log);
    }
);
