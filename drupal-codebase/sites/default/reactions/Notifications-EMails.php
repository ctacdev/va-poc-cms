<?php

hooks_reaction_add('HOOK_node_presave',
    function($node) {

        $log = '';

        // We shall only react on NON basic pages here
        $arrNode = $node->toArray();
        if ( $arrNode['type'][0]['target_id'] == 'basic_page' ) return;

        // Load up the original node (before save)
        $nid = $node->nid->getString();
        if ( empty($nid) ) {
            $node->revision_log->setValue('Initial content creation');
            return;
        }
        $orig = \Drupal\node\Entity\Node::load($nid);

        // Check for changes to English fields
        $doNotify = false;
        if ( $orig->field_title_eng->getString() != $node->field_title_eng->getString() ) $doNotify = true;
        if ( $orig->field_headline_eng->getString() != $node->field_headline_eng->getString() ) $doNotify = true;
        if ( $orig->field_bluebox_eng->getString() != $node->field_bluebox_eng->getString() ) $doNotify = true;
        if ( $orig->field_body_eng->getString() != $node->field_body_eng->getString() ) $doNotify = true;
        if ( $orig->field_greybox_eng->getString() != $node->field_greybox_eng->getString() ) $doNotify = true;

        // If there is no EMail to send, then bail
        if ( $doNotify === false ) return;

        // Show EMail notification
        drupal_set_message(
            "English field-values have been changed on this content-item, and an EMail shall be "
                ."dispatched to users with the Spanish-Editor role informing them of this change.",
            'status'
        );
    }
);
