<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SSOController;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\NewsController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeoThanasController;
use App\Http\Controllers\MyprofileController;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Controllers\DashboardController2;
use App\Http\Controllers\MagistrateController;
use App\Http\Controllers\SottogolpoController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProsecutionController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\RegisterListController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\CitizenComplainController;
use App\Http\Controllers\GeoMetropolitanController;
use App\Http\Controllers\CitizenPublicViewController;
use App\Http\Controllers\GeoCityCorporationsController;
use App\Http\Controllers\PunishmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});
Route::get('/en2bn', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'notify' => en2bn($request->notify),
    ]);
})->name('en2bn');
Route::get('login', [LandingPageController::class, 'show_log_in_page']);
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/login/page', [LandingPageController::class, 'show_log_in_page'])->name('show_log_in_page');
Route::post('/logined_in', [LandingPageController::class, 'logined_in'])->name('logined_in');
Route::post('/cslogout', [LandingPageController::class, 'cslogout'])->name('cslogout');
Route::post('/audio_file_get', [LandingPageController::class, 'audio_file_get'])->name('audio_file_get');
Route::get('/citizen_public_view/new', [CitizenPublicViewController::class, 'new'])->name('citizen_public_view.new');
Route::post('/citizen_public_view/create', [CitizenPublicViewController::class, 'create'])->name('citizen_public_view.create');
Route::post('/job_description/getzilla/{ld?}', [RegisterListController::class, 'getzilla'])->name('getzilla');
Route::post('/job_description/getUpazila/{ld?}', [RegisterListController::class, 'getUpazila'])->name('getUpazila');
// citicorporation 
Route::post('/geo_city_corporations/getCityCorporation', [GeoCityCorporationsController::class, 'getCityCorporation'])->name('geo.getCityCorporation');
Route::post('/geo_metropolitan/getmetropolitan', [GeoMetropolitanController::class, 'getmetropolitan'])->name('geo.getmetropolitan');
Route::post('/geo_thanas/getthanas', [GeoThanasController::class, 'getthanas'])->name('geo.getthanas');

Route::middleware('auth')->group(function () {

    Route::get('/my-profile', [MyprofileController::class, 'index'])->name('my-profile.index');
    Route::get('/my-profile/basic', [MyprofileController::class, 'basic_edit'])->name('my-profile.basic_edit');
    Route::post('/my-profile/basic/update', [MyprofileController::class, 'basic_update'])->name('my-profile.basic_update');
    Route::get('/my-profile/image', [MyprofileController::class, 'imageUpload'])->name('my-profile.imageUpload');
    Route::post('/my-profile/image/update', [MyprofileController::class, 'image_update'])->name('my-profile.image_update');
    Route::get('/my-profile/change-password', [MyprofileController::class, 'change_password'])->name('change.password');
    Route::post('/my-profile/update-password', [MyprofileController::class, 'update_password'])->name('update.password');




    Route::get('/home_redirct', [LandingPageController::class, 'home_redirct'])->name('home_redirct');
    Route::get('/dashboard', [DashboardController2::class, 'index'])->name('dashboard.index');
    // Route::post('/dashboard/ajaxDashboardCaseStatistics', [DashboardController2::class, 'ajaxDashboardCaseStatistics'])->name('dashboard.caseStatistics');



    Route::get('/court/openclose', [CourtController::class, 'index'])->name('court.openclose');
    Route::post('/court/create_events', [CourtController::class, 'create_events'])->name('court.create_events');
    Route::post('/court/create_events/{id}', [CourtController::class, 'update_events'])->name('court.update_events');
    Route::post('/court/update_events', [CourtController::class, 'update_events'])->name('court.update_eventsss');
    Route::post('/court/delete_events', [CourtController::class, 'delete_events'])->name('court.delete_events');

    Route::get('/court/getcourtdataAll', [CourtController::class, 'getcourtdataAll'])->name('court.getcourtdataAll');
    Route::get('/prosecution/suomotucourt/{id?}', [ProsecutionController::class, 'suomotucourt'])->name('prosecution.suomotucourt');
    Route::get('/prosecution/prosecution_form', [ProsecutionController::class, 'prosecution_form'])->name('prosecution.prosecution_form');

    Route::get('/prosecution/incompletecase', [ProsecutionController::class, 'incompletecase'])->name('prosecution.incompletecase');
    Route::get('/prosecution/searchProsecution', [ProsecutionController::class, 'searchProsecution'])->name('prosecution.searchProsecution');
    Route::get('/prosecution/newComplain/{id}', [ProsecutionController::class, 'newComplain'])->name('prosecution.newComplain');
    Route::get('/prosecution/searchComplain', [ProsecutionController::class, 'searchComplain'])->name('prosecution.searchComplain');
    Route::get('/prosecution/searchCase', [ProsecutionController::class, 'searchCase'])->name('searchCase');
    Route::get('/prosecution/extendOrderSheet/{id}', [ProsecutionController::class, 'extendOrderSheet'])->name('extendOrderSheet');
    Route::post('/prosecution/saveExtendOrderSheet', [ProsecutionController::class, 'saveExtendOrderSheet'])->name('saveExtendOrderSheet');

    Route::get('/magistrate/newattachmentrequisition', [MagistrateController::class, 'newattachmentrequisition'])->name('magistrate.newattachmentrequisition');
    Route::post('/magistrate/attachmentrequisitionlist', [MagistrateController::class, 'attachmentrequisitionlist'])->name('magistrate.attachmentrequisitionlist');
    Route::get('/magistrate/complainVarification', [MagistrateController::class, 'complainVarification'])->name('magistrate.complainVarification');
    Route::get('/magistrate/attachmentcaselist', [MagistrateController::class, 'attachmentcaselist'])->name('magistrate.attachmentcaselist');
    Route::post('/magistrate/saverequisitionattachment', [MagistrateController::class, 'saverequisitionattachment'])->name('magistrate.saverequisitionattachment');
    Route::post('/magistrate/saveFeedback', [MagistrateController::class, 'saveFeedback'])->name('magistrate.saveFeedback');
    Route::get('news/createnews', [NewsController::class, 'createNews'])->name('news.createnews');
    Route::get('news/newslist', [NewsController::class, 'index'])->name('news.newslist');
    Route::post('news/newSave', [NewsController::class, 'newsSave'])->name('news.newSave');
    Route::post('news/upload', [NewsController::class, 'upload'])->name('news.upload');


    Route::get('sottogolpo/create', [SottogolpoController::class, 'create'])->name('sottogolpo.create');
    Route::get('sottogolpo/list', [SottogolpoController::class, 'index'])->name('sottogolpo.list');
    
    //Determination of Jurisdiction
    Route::get('/jurisdiction/determination',[MagistrateController::class, 'jurisdiction'])->name('jurisdiction.determination');
    Route::post('/jurisdiction/store', [MagistrateController::class, 'jurisdiction_store'])->name('jurisdiction.store');
    Route::get('/check/user/permission', [MagistrateController::class, 'check_user_permission'])->name('check.user.permission');




    Route::post('/prosecution/checkCaseNumberDuplicacy', [ProsecutionController::class, 'checkCaseNumberDuplicacy'])->name('checkCaseNumberDuplicacy');
    Route::post('/prosecution/createProsecutionCriminalBymagistrate', [ProsecutionController::class, 'createProsecutionCriminalBymagistrate'])->name('createProsecutions');
    Route::post('/prosecution/createProsecutionWitness', [ProsecutionController::class, 'createProsecutionWitness'])->name('createProsecutionWitness.mc');
    Route::post('/prosecution/createProsecution', [ProsecutionController::class, 'createProsecution'])->name('createhhProsecution.mc');
    Route::post('/prosecution/savelist', [ProsecutionController::class, 'savelist'])->name('savelist.mc');
    Route::post('/prosecution/saveCriminalConfessionSuomotu', [ProsecutionController::class, 'saveCriminalConfessionSuomotu'])->name('saveCriminalConfe.mc');
    Route::post('/prosecution/getCaseInfoByProsecutionId', [ProsecutionController::class, 'getCaseInfoByProsecutionId'])->name('getCaseInfoByProsecutionId.mc');
    Route::post('/prosecution/saveOrderBylaw', [ProsecutionController::class, 'saveOrderBylaw'])->name('saveOrderBylaw.mc');
    Route::post('/criminal/getCriminalPreviousCrimeDetails', [ProsecutionController::class, 'getCriminalPreviousCrimeDetails'])->name('getCriminalLPrevDe.mc');
    Route::post('/punishment/isPunishmentExist', [ProsecutionController::class, 'isPunishmentExist'])->name('isPunishmentExist.mc');
    Route::post('/geo_thanas/getThanaByUsersZillaId', [ProsecutionController::class, 'getThanaByUsersZillaId'])->name('getThanaByUsersZillaId.mc');
    Route::post('/punishment/saveJimmaderInformation', [ProsecutionController::class, 'saveJimmaderInformation'])->name('saveJimmaderInformation.mc');
    Route::get('/law/getLaw/', [ProsecutionController::class, 'getLaw']);
    Route::get('/section/getSectionByLawId', [ProsecutionController::class, 'getSectionByLawId']);
    Route::get('/section/getPunishmentBySectionId', [ProsecutionController::class, 'getPunishmentBySectionId']);
    Route::any('/prosecution/showFormByProsecution', [ProsecutionController::class, 'showFormByProsecution']);
    Route::post('/prosecution/createComplain', [ProsecutionController::class, 'createComplain']);
    Route::get('/prosecution/editCriminalConfession/{id}', [ProsecutionController::class, 'editCriminalConfession']);
    Route::get('/prosecution/editOrderSheet/{id}', [ProsecutionController::class, 'editOrderSheet']);
    Route::get('/prosecution/suomotucourtWithoutCriminal/{id?}', [ProsecutionController::class, 'suomotucourtWithoutCriminal'])->name('suomotucourtWithoutCriminal');
    Route::get('/prosecution/incompletecaseWithoutCriminal', [ProsecutionController::class, 'incompletecaseWithoutCriminal'])->name('incompletecaseWithoutCriminal');
    Route::get('/prosecution/searchProsecutionWithoutCriminal', [ProsecutionController::class, 'searchProsecutionWithoutCriminal'])->name('searchProsecutionWithoutCriminal');


    Route::get('/location/division/', [LocationController::class, 'division']);
    Route::get('/location/zilla/{id}', [LocationController::class, 'zilla']);
    Route::get('/location/upazilla/{id}', [LocationController::class, 'upazilla']);
    Route::get('/location/citycorporation/{id}', [LocationController::class, 'citycorporation']);
    Route::get('/location/metropolitan/{id}', [LocationController::class, 'metropolitan']);
    Route::get('/location/thana/{id}', [LocationController::class, 'thana']);
    Route::post('/punishment/getOrderListByProsecutionId', [ProsecutionController::class, 'getOrderListByProsecutionId']);

    Route::post('/punishment/deleteOrder', [ProsecutionController::class, 'deleteOrder']);
    Route::get('/punishment/previewOrderSheet', [ProsecutionController::class, 'previewOrderSheet'])->name('previewOrderSheet.mc');
    Route::post('/punishment/getOrderSheetInfo', [ProsecutionController::class, 'getOrderSheetInfo'])->name('getOrderSheetInfo.mc');
    Route::post('/punishment/saveOrderSheet', [ProsecutionController::class, 'saveOrderSheet'])->name('saveOrderSheet.mc');

    Route::get('/prosecution/showForms', [ProsecutionController::class, 'showForms'])->name('showForms.mc');
    Route::get('/proceutions/details/{id}', [ProsecutionController::class, 'details'])->name('proceutions.details');
    Route::get('/prosecution/printForms/{id}', [ProsecutionController::class, 'printForms'])->name('proceutions.printForms');
    Route::post('/prosecution/showTableByProsecution', [ProsecutionController::class, 'showTableByProsecution'])->name('proceutions.showTableByProsecution');

    // newProsecution


    Route::get('/prosecution/newProsecution/{id?}', [ProsecutionController::class, 'newProsecution'])->name('newProsecution');
    Route::post('/court/getScheduleByMagistrateId/{id}', [CourtController::class, 'getScheduleByMagistrateId'])->name('getScheduleByMagistrateId');
    Route::get('/law/getLawListByProsecutorId/{prosecutorId?}', [ProsecutionController::class, 'getLawListByProsecutorId'])->name('getLawListByProsecutorId');
    Route::get('/prosecution/showProsecutionList', [ProsecutionController::class, 'showProsecutionList'])->name('showProsecutionList');
    Route::get('/prosecution/incompleteProsecution', [ProsecutionController::class, 'incompleteProsecution'])->name('incompleteProsecution');
    Route::get('/prosecution/newProsecutionWithoutCriminal/{id?}', [ProsecutionController::class, 'newProsecutionWithoutCriminal'])->name('newProsecutionWithoutCriminal');
    Route::get('/prosecution/incompleteProsecutionWithoutCriminal/{id?}', [ProsecutionController::class, 'incompleteProsecutionWithoutCriminal'])->name('incompleteProsecutionWithoutCriminal');
    Route::get('/prosecution/createsizedList/{id?}', [ProsecutionController::class, 'createsizedList'])->name('createsizedList');
    Route::get('/prosecution/editSeizedList/{id?}', [ProsecutionController::class, 'editSeizedList'])->name('editSeizedList');
    Route::post('/magistrate/getMagistrate/{id?}', [ProsecutionController::class, 'getMagistrate'])->name('getMagistrate');
    Route::get('/register_list/register', [RegisterListController::class, 'register'])->name('registerlist');
    Route::post('/register_list/printcitizenregister', [RegisterListController::class, 'printcitizenregister'])->name('printcitizenregister');
    Route::post('/register_list/printdailyregister', [RegisterListController::class, 'printdailyregister'])->name('printdailyregister');


    Route::post('/register_list/printPunishmentJailRegister', [RegisterListController::class, 'printPunishmentJailRegister'])->name('printPunishmentJailRegister');
    Route::post('/register_list/printmonthlystatisticsregister', [RegisterListController::class, 'printmonthlystatisticsregister'])->name('printmonthlystatisticsregister');
    Route::post('/register_list/printlawbasedReport', [RegisterListController::class, 'printlawbasedReport'])->name('printlawbasedReport');
    Route::post('/register_list/printPunishmentFineRegister', [RegisterListController::class, 'printPunishmentFineRegister'])->name('printPunishmentFineRegister');


    Route::get('/magistrate/caseTracker', [ProsecutionController::class, 'caseTracker'])->name('magistrate.caseTracker');
    Route::get('/magistrate/searchProsecutionforDashboard', [ProsecutionController::class, 'searchProsecutionforDashboard'])->name('magistrate.searchProsecutionforDashboard');
    Route::get('/magistrate/searchCitizenComplinforDashboard', [ProsecutionController::class, 'searchCitizenComplinforDashboard'])->name('magistrate.searchCitizenComplinforDashboard');

    Route::post('/magistrate/getDataForTracker', [ProsecutionController::class, 'getDataForTracker'])->name('magistrate.getDataForTracker');
    Route::get('/magistrate/criminalTracker', [ProsecutionController::class, 'criminalTracker'])->name('magistrate.criminalTracker');
    Route::post('/magistrate/getDataForCriminalTracker', [ProsecutionController::class, 'getDataForCriminalTracker'])->name('magistrate.getDataForCriminalTracker');
    Route::get('/profile_adm/caseTracker', [ProsecutionController::class, 'adm_caseTracker'])->name('adm.caseTracker');
    Route::post('/profile_adm/getDataForTracker', [ProsecutionController::class, 'adm_getDataForTracker'])->name('adm.getDataForTracker');

    Route::post('/register_list/printprosecutionreport', [RegisterListController::class, 'printprosecutionreport'])->name('printprosecutionreport');

    // adm dashboard 
    Route::get('/dashboard/monthlyReport', [DashboardController2::class, 'monthlyReport'])->name('monthlyReport');
    Route::post('/dashboard/ajaxDashboardCriminalInformation', [DashboardController2::class, 'ajaxDashboardCriminalInformation'])->name('ajaxDashboardCriminalInformation');
    Route::post('/dashboard/ajaxdashboardCaseStatistics', [DashboardController2::class, 'ajaxdashboardCaseStatistics'])->name('ajaxdashboardCaseStatistics');
    Route::post('/dashboard/ajaxCitizen', [DashboardController2::class, 'ajaxCitizen'])->name('ajaxCitizen');
    Route::post('/dashboard/ajaxDataFineVSCase', [DashboardController2::class, 'ajaxDataFineVSCase'])->name('ajaxDataFineVSCase');
    Route::post('/dashboard/ajaxDataLocationVSCase', [DashboardController2::class, 'ajaxDataLocationVSCase'])->name('ajaxDataLocationVSCase');
    Route::post('/dashboard/ajaxDataLawVSCase', [DashboardController2::class, 'ajaxDataLawVSCase'])->name('ajaxDataLawVSCase');
    Route::get('/citizen_complain/showCitizenComplain', [CitizenComplainController::class, 'showCitizenComplain'])->name('citizen_complain.showCitizenComplain');

    Route::get('/citizen_complain/showRequisition', [CitizenComplainController::class, 'showRequisition'])->name('citizen_complain.showRequisition');
    Route::get('/requisition/editRequisition/{id}', [RequisitionController::class, 'editRequisition'])->name('citizen_complain.editRequisition');
    Route::post('/requisition/getComplainforPrint', [RequisitionController::class, 'getComplainforPrint'])->name('citizen_complain.getComplainforPrint');
    Route::post('/requisition/createRequisition', [RequisitionController::class, 'createRequisition'])->name('citizen_complain.createRequisition');
    Route::get('/citizen_complain/ignore_complain_new/{id}', [CitizenComplainController::class, 'ignore_complain_new'])->name('citizen_complain.ignore_complain_new');
    Route::get('/citizen_complain/ignore_complain/{id}', [CitizenComplainController::class, 'ignore_complain'])->name('citizen_complain.ignore_complain');
    Route::get('/citizen_complain/edit/{id}', [CitizenComplainController::class, 'edit'])->name('citizen_complain.edit');
    Route::post('/citizen_complain/save', [CitizenComplainController::class, 'save'])->name('citizen_complain.save');
    Route::get('/deletecase/view', [CitizenComplainController::class, 'deletecaseview'])->name('deletecaseview');
    Route::post('/deletecase', [CitizenComplainController::class, 'deletecase'])->name('deletecase');
    Route::post('/requisition/changeMagistrate', [RequisitionController::class, 'changeMagistrate'])->name('changeMagistrate');
    Route::post('/citizen_complain/getCitizen_complainByReqId/{id?}', [CitizenComplainController::class, 'getCitizen_complainByReqId'])->name('magistrate.getCitizen_complainByReqId');


    Route::get('/monthly_report/report', [MonthlyReportController::class, 'report'])->name('m.report');
    Route::post('/monthly_report/getMisReportList', [MonthlyReportController::class, 'getMisReportList'])->name('m.getMisReportList');
    Route::post('/monthly_report/printmobilecourtreport', [MonthlyReportController::class, 'printmobilecourtreport'])->name('m.printmobilecourtreport');
    Route::post('/monthly_report/printappealcasereport', [MonthlyReportController::class, 'printappealcasereport'])->name('m.printappealcasereport');
    Route::post('/monthly_report/printadmcasereport', [MonthlyReportController::class, 'printadmcasereport'])->name('m.printadmcasereport');
    Route::post('/monthly_report/printemcasereport', [MonthlyReportController::class, 'printemcasereport'])->name('m.printemcasereport');
    Route::post('/monthly_report/printcourtvisitreport', [MonthlyReportController::class, 'printcourtvisitreport'])->name('m.printcourtvisitreport');
    Route::post('/monthly_report/printcaserecordreport', [MonthlyReportController::class, 'printcaserecordreport'])->name('m.printcaserecordreport');
    Route::post('/monthly_report/printapprovedreport', [MonthlyReportController::class, 'printapprovedreport'])->name('m.printapprovedreport');
    Route::post('/monthly_report/printmobilecourtstatisticreport', [MonthlyReportController::class, 'printmobilecourtstatisticreport'])->name('m.printmobilecourtstatisticreport');
    Route::get('/monthly_report/ajaxDataCourt', [MonthlyReportController::class, 'ajaxDataCourt'])->name('m.ajaxDataCourt');
    Route::get('/monthly_report/ajaxDataCase', [MonthlyReportController::class, 'ajaxDataCase'])->name('m.ajaxDataCase');
    Route::get('/monthly_report/ajaxDataFine', [MonthlyReportController::class, 'ajaxDataFine'])->name('m.ajaxDataFine');
    Route::get('/monthly_report/ajaxDataAppeal', [MonthlyReportController::class, 'ajaxDataAppeal'])->name('m.ajaxDataAppeal');
    Route::get('/monthly_report/ajaxDataEm', [MonthlyReportController::class, 'ajaxDataEm'])->name('m.ajaxDataEm');
    Route::get('/monthly_report/ajaxDataAdm', [MonthlyReportController::class, 'ajaxDataAdm'])->name('m.ajaxDataAdm');
    Route::any('/monthly_report/mobilecourtreport', [MonthlyReportController::class, 'mobilecourtreport'])->name('m.mobilecourtreport');
    Route::any('/monthly_report/appealcasereport', [MonthlyReportController::class, 'appealcasereport'])->name('m.appealcasereport');
    Route::any('/monthly_report/admcasereport', [MonthlyReportController::class, 'admcasereport'])->name('m.admcasereport');
    Route::any('/monthly_report/emcasereport', [MonthlyReportController::class, 'emcasereport'])->name('m.emcasereport');
    Route::any('/monthly_report/courtvisitreport', [MonthlyReportController::class, 'courtvisitreport'])->name('m.courtvisitreport');
    Route::any('/monthly_report/caserecordreport', [MonthlyReportController::class, 'caserecordreport'])->name('m.caserecordreport');
    Route::post('/monthly_report/getdata', [MonthlyReportController::class, 'getdata'])->name('m.getdata');

    Route::get('/monthly_report/approvemonth', [MonthlyReportController::class, 'approvemonth'])->name('m.approvemonth');
    Route::post('/monthly_report/approved', [MonthlyReportController::class, 'approved'])->name('m.approved');
    Route::get('/monthly_report/approvedreport/{id}', [MonthlyReportController::class, 'approvedreport'])->name('m.approvedreport');
    Route::post('monthly_report/cancelReport', [MonthlyReportController::class, 'cancelReport'])->name('m.cancelReport');
    Route::get('monthly_report/reportCorrectionList', [MonthlyReportController::class, 'reportCorrectionList'])->name('m.reportCorrectionList');

    Route::post('/punishment/getAllRemovedCaseBySystem', [PunishmentController::class, 'getAllRemovedCaseBySystem'])->name('mc.getAllRemovedCaseBySystem');
    Route::get('/punishment/removedCase', [PunishmentController::class, 'removedCase'])->name('mc.removedCase');

    // hello 


});
// sso
Route::get('/getLogin', [SSOController::class, 'getLogin']);

Route::get('/callback', [SSOController::class, 'getCallback']);

Route::get('/connectUser', [SSOController::class, 'connectUser'])->name('sso.connect');

Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
