<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NDoptorUserData;
use App\Http\Controllers\NDoptorUserManagementAdmin;

 Route::group(['prefix' => 'admin/doptor/management', 'as' => 'admin.doptor.management.'], function () {
    Route::post('/import/dortor/offices', [NDoptorUserData::class, 'import_doptor_office'])->name('import.offices');

    Route::get('/dropdownlist/getdependentdistrict/{id}', [NDoptorUserData::class, 'getDependentDistrictForDoptor']);
    Route::get('/dropdownlist/getdependentupazila/{id}', [NDoptorUserData::class, 'getDependentUpazilaForDoptor']);

    Route::get('/import/dortor/offices/search', [NDoptorUserData::class, 'imported_doptor_office_search'])->name('import.offices.search');

    Route::get('/user_list/segmented/all/{office_id}', [NDoptorUserManagementAdmin::class, 'all_user_list_from_doptor_segmented'])->name('user_list.segmented.all');

    Route::get('/search/user_list/segmented/all/{office_id}', [NDoptorUserManagementAdmin::class, 'all_user_list_from_doptor_segmented_search'])->name('search.all.members');

    Route::post('/divisional/commissioner/create', [NDoptorUserManagementAdmin::class, 'divisional_commissioner_create_by_admin'])->name('divisional.commissioner.create');

    Route::post('/district/commissioner/create', [NDoptorUserManagementAdmin::class, 'district_commissioner_create_by_admin'])->name('dictrict.commissioner.create');

    Route::post('/dc/office/em', [NDoptorUserManagementAdmin::class, 'em_dc_create_by_admin'])->name('em.dc.create');

    Route::post('/dc/office/em/peshkar', [NDoptorUserManagementAdmin::class, 'peskar_em_dc_create_by_admin'])->name('em.peshkar.dc.create');

    Route::post('/dc/office/adm', [NDoptorUserManagementAdmin::class, 'adm_create_by_admin'])->name('adm.create');

    Route::post('/dc/office/adm/peshkar', [NDoptorUserManagementAdmin::class, 'peskar_adm_dc_create_by_admin'])->name('adm.peshkar.dc.create');

    Route::post('/uno/office/em', [NDoptorUserManagementAdmin::class, 'em_uno_create_by_admin'])->name('em.uno.create');

    Route::post('/uno/office/em/peshkar', [NDoptorUserManagementAdmin::class, 'peskar_em_uno_create_by_admin'])->name('em.peshkar.uno.create');
});