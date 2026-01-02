<?php
// utils.php

/**
 * Format the time ago string for a given date.
 *
 * @param string $dateStr The date string to format.
 * @return string The formatted time ago string.
 */
function timeAgo($dateStr) {
    $d = new DateTime($dateStr);
    $now = new DateTime();
    $diff = $now->getTimestamp() - $d->getTimestamp(); // seconds
    $units = [
        [31536000, "năm"], 
        [2592000, "tháng"], 
        [604800, "tuần"], 
        [86400, "ngày"], 
        [3600, "giờ"], 
        [60, "phút"]
    ];
    foreach ($units as [$sec, $label]) {
        $v = floor($diff / $sec);
        if ($v >= 1) return "$v $label trước";
    }
    return "vừa xong";
}

/**
 * Filter projects based on search query and tag.
 *
 * @param array $projects The array of projects to filter.
 * @param string $query The search query.
 * @param string $tag The tag to filter by.
 * @return array The filtered array of projects.
 */
function filterProjects($projects, $query, $tag) {
    return array_filter($projects, function($project) use ($query, $tag) {
        $matchQ = empty($query) || (stripos($project['name'], $query) !== false || stripos($project['desc'], $query) !== false || in_array($query, $project['tags']));
        $matchTag = $tag === "all" || in_array($tag, $project['tags']);
        return $matchQ && $matchTag;
    });
}
?>