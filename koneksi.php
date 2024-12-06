<?php
$con = mysqli_connect("localhost", "root", "", "pbfhmi");
$conakuntansi = mysqli_connect("localhost", "root", "", "pbfhmi_akuntansi");
$appname = 'PBF Husada Mulia Indonesia';

// function makePagination($con, $query)
// {
//     $limit = 50;
//     $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
//     $start = ($page - 1) * $limit;
//     $tgl_mulaii = date('Y-m-d', strtotime('2024-03-28'));
//     $result = $con->query($query);
//     $total_records = $result->num_rows;
//     $total_pages = ceil($total_records / $limit);
//     $cekPage = '';
//     if (isset($_GET['page'])) {
//         $cekPage = $_GET['page'];
//     } else {
//         $cekPage = '1';
//     }
//     return [
//         'limit' => $limit,
//         'page' => $page,
//         'start' => $start,
//         'tgl_mulaii' => $tgl_mulaii,
//         'total_records' => $total_records,
//         'total_pages' => $total_pages,
//         'cekPage' => $cekPage,
//     ];
//     // End Pagination
// }

// function displayPagination($page, $total_pages, $urlPage)
// {
//     // Display pagination
//     echo '<nav>';
//     echo '<ul class="pagination justify-content-center">';

//     // Back button
//     if ($page > 1) {
//         echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page - 1) . '">Back</a></li>';
//     }

//     // Determine the start and end page
//     $start_page = max(1, $page - 2);
//     $end_page = min($total_pages, $page + 2);

//     if ($start_page > 1) {
//         echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=1">1</a></li>';
//         if ($start_page > 2) {
//             echo '<li class="page-item"><span class="page-link">...</span></li>';
//         }
//     }

//     for ($i = $start_page; $i <= $end_page; $i++) {
//         if ($i == $page) {
//             echo '<li class="page-item"><span class="page-link active text-light" style="color: white;">' . $i . '</span></li>';
//         } else {
//             echo '<li class="page-item" style="color: white;"><a class="page-link" href="' . $urlPage . '&page=' . $i . '">' . $i . '</a></li>';
//         }
//     }

//     if ($end_page < $total_pages) {
//         if ($end_page < $total_pages - 1) {
//             echo '<li class="page-item"><span class="page-link">...</span></li>';
//         }
//         echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
//     }

//     // Next button
//     if ($page < $total_pages) {
//         echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page + 1) . '">Next</a></li>';
//     }

//     echo '</ul>';
//     echo '</nav>';
// }
