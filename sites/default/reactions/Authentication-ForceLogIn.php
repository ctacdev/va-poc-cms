<?php

hooks_reaction_add('HOOK_preprocess_page',
    function(&$variables) {

        // If this user is signed in, then bail from this function
        if ( Drupal::currentUser()->isAuthenticated() !== false ) return;

        // If this is the sign-in page, bail from this function
        if ( strpos($_SERVER['REQUEST_URI'], 'user/login') !== false ) return;

        // If we have no bailed yet, then redirect the user to the login-page
        header('Location: /user/login', true, 303);
        exit();
    }
);
