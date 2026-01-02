<?php
// src/projects.php

function getProjects() {
    $jsonFile = __DIR__ . '/projects.json';
    
    if (!file_exists($jsonFile)) {
        return [];
    }
    
    $jsonData = file_get_contents($jsonFile);
    
    $projects = json_decode($jsonData, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }
    
    return $projects;
}
?>