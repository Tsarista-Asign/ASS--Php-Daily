<?php
// Hàm hiển thị mảng
function hienMang($arr) {
    return implode(", ", $arr);
}

// Hàm tính tổng
function tinhTong($arr) {
    return array_sum($arr);
}
function timMax($mang) {
            return max($mang);
}

function timMin($mang) {
    return min($mang);
}

// Hàm sắp xếp tăng dần
function sapXepTang($arr) {
    sort($arr);
    return $arr;
}

// Hàm đếm phần tử
function demPhanTu($arr) {
    return count($arr);
}
?>
