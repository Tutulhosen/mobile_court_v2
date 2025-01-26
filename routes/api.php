<?php

use Illuminate\Http\Request;
// use App\Http\Controllers\LandingPageController;
// use App\Http\Controllers\ProsecutionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\MCLawController;
use Illuminate\Contracts\Routing\Registrar;
use App\Http\Controllers\MCSectionController;
use App\Http\Controllers\MagistrateController;
use App\Http\Controllers\MDDashboardController;
use App\Http\Controllers\Api\LoginApiController;
use App\Http\Controllers\RegisterListApiController;
use App\Http\Controllers\MonthlyReportApiController;
use App\Http\Controllers\Api\MobileCourtApiController;
use App\Http\Controllers\Api\ProsecutionApiController;
use App\Http\Controllers\McLawAndSectionApiController;
use App\Http\Controllers\MisnotificationApiController;
use App\Http\Controllers\McCitizenPublicViewApiController;
 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login_mobilecourt', [LoginApiController::class, 'login_mobilecourt'])->name('m.login');

// Route::post('/logined_incc', [LandingPageController::class, 'logined_in'])->name('logined_incc');
// Route::post('/searchProsecutionCriminalBymagistrate', [ProsecutionController::class, 'createProsecutionCriminalBymagistrate'])->name('createProsecution');
// Route::post('/createProsecutionWitness', [ProsecutionController::class, 'createProsecutionWitness'])->name('createProsecutionWitness');
// Route::post('/createProsecution', [ProsecutionController::class, 'createProsecution'])->name('createhhProsecution');
// Route::post('/prosecution/savelist', [ProsecutionController::class, 'savelist'])->name('savelist');
// Route::post('/saveCriminalConfessionSuomotu', [ProsecutionController::class, 'saveCriminalConfessionSuomotu'])->name('saveCriminalConfessionSuomotu');
// Route::post('/getCriminalPreviousCrimeDetails', [ProsecutionController::class, 'getCriminalPreviousCrimeDetails'])->name('getCriminalPreviousCrimeDetails');
// Route::post('/isPunishmentExist', [ProsecutionController::class, 'isPunishmentExist'])->name('isPunishmentExist');
// Route::post('/saveOrderBylaw', [ProsecutionController::class, 'saveOrderBylaw']);
// Route::post('/getOrderListByProsecutionId', [ProsecutionController::class, 'getOrderListByProsecutionId']);
// Route::post('/getCaseInfoByProsecutionId', [ProsecutionController::class, 'getCaseInfoByProsecutionId']);
// Route::post('/deleteOrder', [ProsecutionController::class, 'deleteOrder']);
// Route::post('/saveJimmaderInformation', [ProsecutionController::class, 'saveJimmaderInformation']);
// Route::post('/getOrderSheetInfo', [ProsecutionController::class, 'getOrderSheetInfo']);
// Route::post('/saveOrderSheetdasdfasdf', [ProsecutionController::class, 'saveOrderSheet']);

// Route::get('/division/{id?}', [MobileCourtApiController::class, 'get_division']);
// Route::get('/district', [MobileCourtApiController::class, 'get_district_by_query']);
// Route::get('/upazila', [MobileCourtApiController::class, 'get_upazila_by_query']);
// Route::get('/corporation', [MobileCourtApiController::class, 'get_city_corporation_by_query']);
// Route::get('/metropolition', [MobileCourtApiController::class, 'get_metropolition_by_query']);

//court event list
// Route::get('/court-event', [MobileCourtApiController::class, 'getcourtdataAll']);
// Route::get('/court-event/{id}', [MobileCourtApiController::class, 'getcourtdataById']);
// Route::post('/court-event', [MobileCourtApiController::class, 'court_event_create']);
// Route::put('/court-event/{id}', [MobileCourtApiController::class, 'court_event_update']);
// Route::delete('/court-event/{id}', [MobileCourtApiController::class, 'court_event_delete']);

//mc low list
/* Route::get('/mc-law', [MobileCourtApiController::class, 'getmc_law']);

// mc low type lise
Route::get('/mc-law-type', [MobileCourtApiController::class, 'getmc_law_type']); */

// mc section list
// Route::get('/mc-section', [MobileCourtApiController::class, 'getmc_section']);
//! Mobile court api
// Route::post('/mc/law/section', [McLawAndSectionApiController::class, 'mc_law_section']);
// Route::post('/mc/law/section/store', [McLawAndSectionApiController::class, 'mc_law_section_store']);
//for section
Route::get('/mc/section/get', [MCSectionController::class, 'index']);
Route::post('/mc/section/store', [MCSectionController::class, 'store']);
Route::get('/mc/section/edit/{id}', [MCSectionController::class, 'edit']);
Route::post('/mc/section/update/{id}', [MCSectionController::class, 'update']);



//for law 
Route::post('/mc/law/store', [MCLawController::class, 'store']);
Route::get('/mc/law/get', [MCLawController::class, 'index']);
Route::get('/mc/law/edit/{id}', [MCLawController::class, 'edit']);
Route::post('/mc/law/update/{id}', [MCLawController::class, 'update']);

Route::middleware('auth:api')->group(function () {

    Route::get('/division/{id?}', [MobileCourtApiController::class, 'get_division']);
    //get district list
    Route::get('/district', [MobileCourtApiController::class, 'get_district_by_query']);
    // get upazila list
    Route::get('/upazila', [MobileCourtApiController::class, 'get_upazila_by_query']);

    // get corprations list
    Route::get('/corporation', [MobileCourtApiController::class, 'get_city_corporation_by_query']);

    // get metropolition list
    Route::get('/metropolition', [MobileCourtApiController::class, 'get_metropolition_by_query']);


    // get thana by metropoliton
    Route::get('/thana-by-metropoliton', [MobileCourtApiController::class, 'getThanaByMetropoliton']);





    //court event list
    Route::get('/court-event', [MobileCourtApiController::class, 'getcourtdataAll']);
    Route::get('/court-event/{id}', [MobileCourtApiController::class, 'getcourtdataById']);
    Route::post('/court-event', [MobileCourtApiController::class, 'court_event_create']);
    Route::post('/court-event/{id}', [MobileCourtApiController::class, 'court_event_update']);
    Route::delete('/court-event-delete/{id}', [MobileCourtApiController::class, 'court_event_delete']);

    // profile api
    Route::get('/profile', [ProsecutionApiController::class, 'myProfile']);


    // //mc low list
    Route::get('/mc-law', [MobileCourtApiController::class, 'getmc_law']);

    // // mc low type lise
    Route::get('/mc-law-type', [MobileCourtApiController::class, 'getmc_law_type']);

    // // mc section list
    Route::get('/mc-section', [MobileCourtApiController::class, 'getmc_section']);

    //prosecution list
    Route::post('/prosecution-create', [ProsecutionApiController::class, 'create_prosecution']);
    Route::post('/prosecution-create-by-prosecutor', [ProsecutionApiController::class, 'create_prosecution_by_prosecutor']);


   //laws broken list
   Route::get('/laws-broken/ordersheet-details/{prosecutionID}', [ProsecutionApiController::class, 'orderFormLawsBroken']);


   Route::get('/get-previous/criminal-details', [ProsecutionApiController::class, 'getPreviousCrimeDetails']);
    //witness
    Route::post('/witness-create', [ProsecutionApiController::class, 'create_witness']);
    Route::post('/witness-create-for-prosecutor', [ProsecutionApiController::class, 'create_witness_for_prosecutor']);

    // ovijug list
    Route::post('/ovijug-create', [ProsecutionApiController::class, 'create_ovijug']);
    Route::post('/ovijug-create-for-prosecutor', [ProsecutionApiController::class, 'create_ovijug_for_prosecutor']);

    // seizureitem_types list
    Route::get('/seizureitem-type/{id?}', [ProsecutionApiController::class, 'get_seizure_item_type']);

    // create seizuritemlist
    Route::post('/seizureitem-create', [ProsecutionApiController::class, 'create_seizure_item_list']);

    // save order release criminal
    Route::post('/save-order/release-by-law', [ProsecutionApiController::class, 'saveOrderBylawRelease']);

    // save order regular case
    Route::post('/save-order/regular-case', [ProsecutionApiController::class, 'saveOrderByRegularCase']);

    // save order punishments
    Route::post('/save-order/punishments-case', [ProsecutionApiController::class, 'saveOrderByPunishments']);

   // Jail Api
   Route::get('/jail-list', [ProsecutionApiController::class, 'jailList']);


   // delete order
    Route::post('/delete-order', [ProsecutionApiController::class, 'deleteOrder']);

    // get order list by procecution id
    Route::get('/get-order-list-by-procecution', [ProsecutionApiController::class, 'getOrderListByProcecutionId']);

   // punishments jimmadar information
   Route::post('/punishment/save/jimmader-information-preview', [ProsecutionApiController::class, 'saveJimmaderInformation']);

   // save order sheet info
   Route::post('/save-order-sheet-info', [ProsecutionApiController::class, 'saveOrderSheetInfo']);


    // get thana by User zilla
    Route::get('/get-thana-by-user-zilla', [MobileCourtApiController::class, 'getThanaByUsersZillaId']);

    // create statement list

    Route::post('/statement-create', [ProsecutionApiController::class, 'saveCriminalConfessionSuomotu']);
    Route::get('/prosecution/incompletecase', [ProsecutionApiController::class, 'incompletecase']);
    Route::get('/prosecution/incompletecase/details', [ProsecutionApiController::class, 'getIncompleteCaseDetails']);
    Route::get('/prosecution/withoutCriminal/incompletecase/details', [ProsecutionApiController::class, 'getIncompleteCaseDetailsWithoutCriminal']);
    Route::get('/prosecution/prosecutorListwithCriminal', [ProsecutionApiController::class, 'prosecutorListwithCriminal']);

    // procecutor
    Route::get('/division-wise/zilla', [ProsecutionApiController::class, 'getOwnDivisionWiseZilla']);
    Route::get('/zilla-wise/magistrate', [ProsecutionApiController::class, 'getOwnZillaWiseMagistrate']);

    // Api made by Aoyon - 15-09-2024
    Route::get('/prosecution/searchComplainList', [ProsecutionApiController::class, 'searchComplain']);
    Route::get('/prosecution/showFormsList', [ProsecutionApiController::class, 'showForms']);
    Route::get('/prosecution/incompletecaseWithoutCriminalList', [ProsecutionApiController::class, 'incompletecaseWithoutCriminal']);
    Route::get('/prosecution/searchProsecutionWithoutCriminalList', [ProsecutionApiController::class, 'searchProsecutionWithoutCriminal']);


   // Dashboard information
   Route::get('/dashboard-information', [ProsecutionApiController::class, 'dashboardInformation']);

   // Without Criminal
   // jimmader Preview
    Route::post('/without-criminal/jimmader-preview', [ProsecutionApiController::class, 'saveJimmaderInformationWithOutCriminal']);
    
    /// seizedList
    Route::get('/prosecution/seizedList/', [ProsecutionApiController::class, 'seizedList']);
    Route::get('/prosecution/show/list/', [ProsecutionApiController::class, 'showProsecutionList']);
    
});

//jurisdiction route
Route::post('/mc/check/user/permission', [MagistrateController::class, 'check_user_per']);
Route::post('/mc/jurisdiction/store', [MagistrateController::class, 'jurisdiction_store_for_admin']);

//cancel mamla from admin panel
Route::post('/mc/cancel/mamla/from/admin', [MagistrateController::class, 'mamla_cancel_from_admin']);

Route::post('/mc/dashboard/ajaxDataFineVSCase', [MDDashboardController::class, 'ajaxDataFineVSCase']);
Route::post('/mc/dashboard/ajaxdashboardCaseStatistics', [MDDashboardController::class, 'ajaxdashboardCaseStatistics']);
Route::post('/mc/dashboard/ajaxDashboardCriminalInformation', [MDDashboardController::class, 'ajaxDashboardCriminalInformation']);
Route::post('/mc/dashboard/ajaxCitizen', [MDDashboardController::class, 'ajaxCitizen']);
Route::post('/mc/dashboard/ajaxDataLocationVSCase', [MDDashboardController::class, 'ajaxDataLocationVSCase']);
Route::post('/mc/dashboard/ajaxDataLawVSCase', [MDDashboardController::class, 'ajaxDataLawVSCase']);
Route::post('/mc/dashboard/dashboard_monthly_report', [MDDashboardController::class, 'dashboard_monthly_report']);

Route::post('/mc/citizen_public_view/create', [McCitizenPublicViewApiController::class, 'store']);
Route::post('/mc/citizen_public_view/search', [McCitizenPublicViewApiController::class, 'search']);

Route::post('/mc/misnotification/printmobilecourtreport', [MisnotificationApiController::class, 'printmobilecourtreport']);
Route::post('/mc/misnotification/printappealcasereport', [MisnotificationApiController::class, 'printappealcasereport']);
Route::post('/mc/misnotification/printadmcasereport', [MisnotificationApiController::class, 'printadmcasereport']);
Route::post('/mc/misnotification/printemcasereport', [MisnotificationApiController::class, 'printemcasereport']);
Route::post('/mc/misnotification/printcourtvisitreport', [MisnotificationApiController::class, 'printcourtvisitreport']);
Route::post('/mc/misnotification/printcaserecordreport', [MisnotificationApiController::class, 'printcaserecordreport']);
Route::post('/mc/misnotification/getReportsData', [MisnotificationApiController::class, 'getReportsData']);
Route::post('/mc/misnotification/setReportDataUnapproved', [MisnotificationApiController::class, 'setReportDataUnapproved']);


//Monthly report api
Route::post('/mc/monthly_report/printcountrymobilecourtreport', [MonthlyReportApiController::class, 'printcountrymobilecourtreport']);
Route::post('/mc/monthly_report/printdivmobilecourtreport', [MonthlyReportApiController::class, 'printdivmobilecourtreport']);
Route::post('/mc/monthly_report/printdivappealcasereport', [MonthlyReportApiController::class, 'printdivappealcasereport']);
Route::post('/mc/monthly_report/printdivadmcasereport', [MonthlyReportApiController::class, 'printdivadmcasereport']);
Route::post('/mc/monthly_report/printdivemcasereport', [MonthlyReportApiController::class, 'printdivemcasereport']);
Route::post('/mc/monthly_report/printdivapprovedreport', [MonthlyReportApiController::class, 'printdivapprovedreport']);

Route::post('/mc/monthly_report/printmobilecourtreport', [MonthlyReportApiController::class, 'printmobilecourtreport']);
Route::post('/mc/monthly_report/printappealcasereport', [MonthlyReportApiController::class, 'printappealcasereport']);
Route::post('/mc/monthly_report/printadmcasereport', [MonthlyReportApiController::class, 'printadmcasereport']);
Route::post('/mc/monthly_report/printemcasereport', [MonthlyReportApiController::class, 'printemcasereport']);
Route::post('/mc/monthly_report/printcourtvisitreport', [MonthlyReportApiController::class, 'printcourtvisitreport']);
Route::post('/mc/monthly_report/printcaserecordreport', [MonthlyReportApiController::class, 'printcaserecordreport']);
Route::post('/mc/monthly_report/printmobilecourtstatisticreport', [MonthlyReportApiController::class, 'printmobilecourtstatisticreport']);
// graph 
Route::get('/mc/monthly_report/ajaxDataCourt', [MonthlyReportApiController::class, 'ajaxDataCourt']);
Route::get('/mc/monthly_report/ajaxDataCase', [MonthlyReportApiController::class, 'ajaxDataCase']);
Route::get('/mc/monthly_report/ajaxDataFine', [MonthlyReportApiController::class, 'ajaxDataFine']);
Route::get('/mc/monthly_report/ajaxDataAppeal', [MonthlyReportApiController::class, 'ajaxDataAppeal']);
Route::get('/mc/monthly_report/ajaxDataEm', [MonthlyReportApiController::class, 'ajaxDataEm']);
Route::get('/mc/monthly_report/ajaxDataAdm', [MonthlyReportApiController::class, 'ajaxDataAdm']);
// Registrar
Route::post('/mc/register_list/printcitizenregister', [RegisterListApiController::class, 'printcitizenregister']);
Route::post('/mc/register_list/printPunishmentJailRegister', [RegisterListApiController::class, 'printPunishmentJailRegister']);
Route::post('/mc/register_list/printmonthlystatisticsregister', [RegisterListApiController::class, 'printmonthlystatisticsregister']);
Route::post('/mc/register_list/printlawbasedReport', [RegisterListApiController::class, 'printlawbasedReport']);
Route::post('/mc/register_list/printPunishmentFineRegister', [RegisterListApiController::class, 'printPunishmentFineRegister']);

//count all case 
Route::post('/case/count/for/mc', [MDDashboardController::class, 'case_count_for_mc']);