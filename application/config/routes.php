<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'AuthController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

// ============================================================================
// AUTH ROUTES
// ============================================================================
$route['auth/login'] = 'AuthController/index';
$route['login'] = 'AuthController/login_process';
$route['logout'] = 'AuthController/logout';

// ============================================================================
// ADMIN ROUTES
// ============================================================================
$route['admin'] = 'admin/DashboardController';
$route['admin/dashboard'] = 'admin/DashboardController/index';

// Master Data - Pengguna
$route['admin/pengguna'] = 'admin/PenggunaController/index';
$route['admin/pengguna/create'] = 'admin/PenggunaController/create';
$route['admin/pengguna/store'] = 'admin/PenggunaController/store';
$route['admin/pengguna/edit/(:num)'] = 'admin/PenggunaController/edit/$1';
$route['admin/pengguna/update/(:num)'] = 'admin/PenggunaController/update/$1';
$route['admin/pengguna/delete/(:num)'] = 'admin/PenggunaController/delete/$1';

// Master Data - Produk
$route['admin/produk'] = 'admin/ProdukController/index';
$route['admin/produk/create'] = 'admin/ProdukController/create';
$route['admin/produk/store'] = 'admin/ProdukController/store';
$route['admin/produk/edit/(:num)'] = 'admin/ProdukController/edit/$1';
$route['admin/produk/update/(:num)'] = 'admin/ProdukController/update/$1';
$route['admin/produk/delete/(:num)'] = 'admin/ProdukController/delete/$1';

// Master Data - Kanal
$route['admin/kanal'] = 'admin/KanalController/index';
$route['admin/kanal/create'] = 'admin/KanalController/create';
$route['admin/kanal/store'] = 'admin/KanalController/store';
$route['admin/kanal/edit/(:num)'] = 'admin/KanalController/edit/$1';
$route['admin/kanal/update/(:num)'] = 'admin/KanalController/update/$1';
$route['admin/kanal/delete/(:num)'] = 'admin/KanalController/delete/$1';

// Organisasi
$route['admin/junior-manager'] = 'admin/JuniorManagerController/index';
$route['admin/junior-manager/create'] = 'admin/JuniorManagerController/create';
$route['admin/junior-manager/store'] = 'admin/JuniorManagerController/store';
$route['admin/junior-manager/detail/(:num)'] = 'admin/JuniorManagerController/detail/$1';
$route['admin/junior-manager/edit/(:num)'] = 'admin/JuniorManagerController/edit/$1';
$route['admin/junior-manager/update/(:num)'] = 'admin/JuniorManagerController/update/$1';
$route['admin/junior-manager/delete/(:num)'] = 'admin/JuniorManagerController/delete/$1';

$route['admin/supervisor'] = 'admin/SupervisorController/index';
$route['admin/supervisor/create'] = 'admin/SupervisorController/create';
$route['admin/supervisor/store'] = 'admin/SupervisorController/store';
$route['admin/supervisor/detail/(:num)'] = 'admin/SupervisorController/detail/$1';
$route['admin/supervisor/edit/(:num)'] = 'admin/SupervisorController/edit/$1';
$route['admin/supervisor/update/(:num)'] = 'admin/SupervisorController/update/$1';
$route['admin/supervisor/delete/(:num)'] = 'admin/SupervisorController/delete/$1';

$route['admin/leader'] = 'admin/LeaderController/index';
$route['admin/leader/create'] = 'admin/LeaderController/create';
$route['admin/leader/store'] = 'admin/LeaderController/store';
$route['admin/leader/detail/(:num)'] = 'admin/LeaderController/detail/$1';
$route['admin/leader/edit/(:num)'] = 'admin/LeaderController/edit/$1';
$route['admin/leader/update/(:num)'] = 'admin/LeaderController/update/$1';
$route['admin/leader/delete/(:num)'] = 'admin/LeaderController/delete/$1';

// Tim & CS
$route['admin/tim'] = 'admin/TimController/index';
$route['admin/tim/create'] = 'admin/TimController/create';
$route['admin/tim/store'] = 'admin/TimController/store';
$route['admin/tim/detail/(:num)'] = 'admin/TimController/detail/$1';
$route['admin/tim/edit/(:num)'] = 'admin/TimController/edit/$1';
$route['admin/tim/update/(:num)'] = 'admin/TimController/update/$1';
$route['admin/tim/delete/(:num)'] = 'admin/TimController/delete/$1';

$route['admin/customer-service'] = 'admin/CustomerServiceController/index';
$route['admin/customer-service/create'] = 'admin/CustomerServiceController/create';
$route['admin/customer-service/store'] = 'admin/CustomerServiceController/store';
$route['admin/customer-service/detail/(:num)'] = 'admin/CustomerServiceController/detail/$1';
$route['admin/customer-service/edit/(:num)'] = 'admin/CustomerServiceController/edit/$1';
$route['admin/customer-service/update/(:num)'] = 'admin/CustomerServiceController/update/$1';
$route['admin/customer-service/delete/(:num)'] = 'admin/CustomerServiceController/delete/$1';

// Konfigurasi SPK
$route['admin/kriteria'] = 'admin/KriteriaController/index';
$route['admin/kriteria/create'] = 'admin/KriteriaController/create';
$route['admin/kriteria/store'] = 'admin/KriteriaController/store';
$route['admin/kriteria/edit/(:num)'] = 'admin/KriteriaController/edit/$1';
$route['admin/kriteria/update/(:num)'] = 'admin/KriteriaController/update/$1';
$route['admin/kriteria/delete/(:num)'] = 'admin/KriteriaController/delete/$1';

$route['admin/sub-kriteria'] = 'admin/SubKriteriaController/index';
$route['admin/sub-kriteria/create'] = 'admin/SubKriteriaController/create';
$route['admin/sub-kriteria/store'] = 'admin/SubKriteriaController/store';
$route['admin/sub-kriteria/edit/(:num)'] = 'admin/SubKriteriaController/edit/$1';
$route['admin/sub-kriteria/update/(:num)'] = 'admin/SubKriteriaController/update/$1';
$route['admin/sub-kriteria/delete/(:num)'] = 'admin/SubKriteriaController/delete/$1';

$route['admin/range'] = 'admin/RangeController/index';
$route['admin/range/create'] = 'admin/RangeController/create';
$route['admin/range/store'] = 'admin/RangeController/store';
$route['admin/range/edit/(:num)'] = 'admin/RangeController/edit/$1';
$route['admin/range/update/(:num)'] = 'admin/RangeController/update/$1';
$route['admin/range/delete/(:num)'] = 'admin/RangeController/delete/$1';

$route['admin/konversi'] = 'admin/KonversiController/index';
$route['admin/konversi/create'] = 'admin/KonversiController/create';
$route['admin/konversi/store'] = 'admin/KonversiController/store';
$route['admin/konversi/edit/(:num)'] = 'admin/KonversiController/edit/$1';
$route['admin/konversi/update/(:num)'] = 'admin/KonversiController/update/$1';
$route['admin/konversi/delete/(:num)'] = 'admin/KonversiController/delete/$1';

// Penilaian
$route['admin/nilai'] = 'admin/NilaiController/index';
$route['admin/nilai/input'] = 'admin/NilaiController/input';
$route['admin/nilai/store'] = 'admin/NilaiController/store';
$route['admin/nilai/download-template'] = 'admin/NilaiController/download_template';
$route['admin/nilai/delete/(:num)'] = 'admin/NilaiController/delete/$1';

// Ranking & Laporan
$route['admin/ranking'] = 'admin/RankingController/index';
$route['admin/ranking/process'] = 'admin/RankingController/process';
$route['admin/ranking/export'] = 'admin/RankingController/export';
$route['admin/ranking/detail'] = 'admin/RankingController/detail';
$route['admin/ranking/download'] = 'admin/RankingController/download';

$route['admin/laporan'] = 'admin/LaporanController/index';
$route['admin/laporan/export'] = 'admin/LaporanController/export';
$route['admin/laporan/print'] = 'admin/LaporanController/print';

// ============================================================================
// JUNIOR MANAGER ROUTES
// ============================================================================
$route['junior-manager'] = 'manager/DashboardController';
$route['junior-manager/dashboard'] = 'manager/DashboardController/index';

// Supervisor Management
$route['junior-manager/supervisor'] = 'manager/SupervisorController/index';
$route['junior-manager/supervisor/detail/(:num)'] = 'manager/SupervisorController/detail/$1';

// Team Overview
$route['junior-manager/team-overview'] = 'manager/TeamOverviewController/index';
$route['junior-manager/team-overview/detail/(:num)'] = 'manager/TeamOverviewController/detail/$1';

// Penilaian
$route['junior-manager/nilai'] = 'manager/NilaiController/index';
$route['junior-manager/nilai/input'] = 'manager/NilaiController/input';
$route['junior-manager/nilai/history'] = 'manager/NilaiController/history';
$route['junior-manager/nilai/get-sub-kriteria/(:num)'] = 'manager/NilaiController/get_sub_kriteria/$1';
$route['junior-manager/nilai/save'] = 'manager/NilaiController/save';
$route['junior-manager/nilai/delete/(:num)'] = 'manager/NilaiController/delete/$1';

// Ranking
$route['junior-manager/ranking'] = 'manager/RankingController/index';

// ============================================================================
// SUPERVISOR ROUTES
// ============================================================================
$route['supervisor'] = 'supervisor/DashboardController';
$route['supervisor/dashboard'] = 'supervisor/DashboardController/index';

// Leader Management
$route['supervisor/leader'] = 'supervisor/LeaderController/index';
$route['supervisor/leader/detail/(:num)'] = 'supervisor/LeaderController/detail/$1';

// Team Management
$route['supervisor/team'] = 'supervisor/TeamController/index';
$route['supervisor/team/detail/(:num)'] = 'supervisor/TeamController/detail/$1';

// Customer Service Management
$route['supervisor/customer-service'] = 'supervisor/CustomerServiceController/index';
$route['supervisor/customer-service/detail/(:num)'] = 'supervisor/CustomerServiceController/detail/$1';

// Monitoring & Ranking
$route['supervisor/monitor'] = 'supervisor/MonitorController/index';
$route['supervisor/ranking'] = 'supervisor/RankingController/index';

// ============================================================================
// LEADER ROUTES
// ============================================================================
$route['leader'] = 'leader/DashboardController';
$route['leader/dashboard'] = 'leader/DashboardController/index';

$route['leader/anggota'] = 'leader/AnggotaController/index';
$route['leader/customer-service'] = 'leader/CustomerServiceController/index';

$route['leader/ranking'] = 'leader/RankingController/index';
