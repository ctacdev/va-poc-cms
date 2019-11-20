<?php

hooks_reaction_add(
    array(
        'HOOK_form_node_site_page_edit_form_alter',
        'HOOK_form_node_site_page_form_alter'
    ),
    function(&$form, $form_state, $form_id) {

        // Kill the vertical-tab area on the right side
        $form['advanced']['#attributes']['style'] = 'display: none;';

        // Visually the publishing fields
        $form['field_pub_eng']['#attributes']['style'] = 'display: none;';
        $form['field_pub_es']['#attributes']['style'] = 'display: none;';

        // Logic for the Edit vs Workflow tabs
        if ( empty($_GET['workflow']) ) {

            // Force this value to false
            $form['field_pub_eng']['widget']['value']['#value'] = false;
            $form['field_pub_eng']['widget']['value']['#default_value'] = false;

            // Force this value to false
            $form['field_pub_es']['widget']['value']['#value'] = false;
            $form['field_pub_es']['widget']['value']['#default_value'] = false;

        } else {

            /** LOGC FORTHE WORKFLOW TAB HERE **/

            // Hide the English drop-down
            $form['field_bluebox_eng']['#access'] = false;
            $form['field_body_eng']['#access'] = false;
            $form['field_greybox_eng']['#access'] = false;
            $form['field_headline_eng']['#access'] = false;
            $form['field_title_eng']['#access'] = false;

            // Hide the Spanish drop-down
            $form['field_bluebox_es']['#access'] = false;
            $form['field_body_es']['#access'] = false;
            $form['field_greybox_es']['#access'] = false;
            $form['field_headline_es']['#access'] = false;
            $form['field_title_es']['#access'] = false;

            $form['field_children']['#access'] = false;

            // Hide the actions and the internal-title
            $form['title']['#access'] = false;
            $form['actions']['#attributes']['style'] = 'display: none;';

            $form['break'] = array(
                '#markup' => '<br/>'
            );

            $form['wf_pub_eng'] = array(
                '#type' => 'button',
                '#value' => 'Publish English Content',
                '#attributes' => array(
                    'onclick' => "jQuery('#edit-field-pub-eng-value').prop('checked', true); jQuery('#edit-revision-log-wrapper textarea').val('English content published'); jQuery('#edit-submit').click(); return false;"
                )
            );

            $form['break2'] = array(
                '#markup' => '<br/><br/>'
            );

            $form['wf_pub_es'] = array(
                '#type' => 'button',
                '#value' => 'Publish Spanish Content',
                '#attributes' => array(
                    'onclick' => "jQuery('#edit-field-pub-es-value').prop('checked', true); jQuery('#edit-revision-log-wrapper textarea').val('Spanish content published'); jQuery('#edit-submit').click(); return false;"
                )
            );

        }

    }
);
