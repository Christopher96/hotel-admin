<?php
// Created by: Christopher Gauffin
// Description: Sign out script which kills the session and redirects to sign in page

session_destroy();
header('Location: ?signin');
