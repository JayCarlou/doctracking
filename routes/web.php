<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('Home')->middleware('auth');
Route::get('/generate_dashboard', 'HomeController@index')->middleware('auth');
Route::post('/generate_dashboard', 'HomeController@generateDashboard')->middleware('auth');

//document
Route::get('/new_document','NewDocumentController@newDocument')->name('new_document')->middleware('auth');
Route::post('/new_document_save', 'NewDocumentController@newDocumentSave')->middleware('auth');
Route::post('/document_search', 'NewDocumentController@documentSearch')->middleware('auth');
Route::get('/document_delete/{id}','NewDocumentController@documentDelete')->name('document_delete')->middleware('auth');
Route::get('/document_edit/{id}','NewDocumentController@documentEdit')->name('document_edit')->middleware('auth');
Route::post('/document_edit_save/{id}','NewDocumentController@documentEditSave')->middleware('auth');
Route::get('/document_attachment/{id}','NewDocumentController@documentAttachment')->name('document_attachment')->middleware('auth');
Route::post('/document_attachment_save','NewDocumentController@documentAttachmentSave')->middleware('auth');
Route::get('/document_download/{id}','NewDocumentController@documentDownload')->name('document_download')->middleware('auth');
Route::get('/document_attachment_delete/{id}','NewDocumentController@documentAttachmentDelete')->name('document_attachment_delete')->middleware('auth');
Route::get('/document_route/{id}','NewDocumentController@documentRoute')->name('document_route')->middleware('auth');
Route::get('/document_route_office/{id}/{officeCode}','NewDocumentController@documentRouteOffice')->name('document_route_office')->middleware('auth');
Route::get('/document_route_office_all/{id}','NewDocumentController@documentRouteOfficeAll')->name('document_route_office_all')->middleware('auth');
Route::get('/document_route_office_remove/{id}/{officeCode}','NewDocumentController@documentRouteOfficeRemove')->name('document_route_office_remove')->middleware('auth');
Route::get('/document_route_office_remove_all/{id}','NewDocumentController@documentRouteOfficeRemoveAll')->name('document_route_office_remove_all')->middleware('auth');

//transaction
Route::get('/transaction','TransactionController@transaction')->name('transaction')->middleware('auth');
Route::post('/transaction_result','TransactionController@transactionResult')->middleware('auth');
Route::get('/transaction_result','TransactionController@transaction')->name('transaction_end')->middleware('auth');
Route::post('/transaction_receive','TransactionController@transactionReceive')->middleware('auth');
Route::post('/transaction_release','TransactionController@transactionRelease')->middleware('auth');
Route::post('/transaction_release_records','TransactionController@transactionReleaseRecords')->middleware('auth');
Route::get('/transaction_end/{id}','TransactionController@transactionEnd')->name('transaction_end')->middleware('auth');
Route::post('/transaction_end_confirm','TransactionController@transactionEndConfirm')->middleware('auth');

//settings 
//offices 
Route::get('/office_entry','SettingsController@officeEntry')->name('office_entry')->middleware('auth');
Route::post('/office_entry_save','SettingsController@officeEntrySave')->middleware('auth');
Route::get('/office_delete/{id}','SettingsController@officeDelete')->name('office_delete')->middleware('auth');
Route::get('/office_edit/{id}','SettingsController@officeEdit')->name('office_edit')->middleware('auth');
Route::post('/office_edit_save','SettingsController@officeEditSave')->middleware('auth');

//document type
Route::get('/document_type_entry','SettingsController@documentTypeEntry')->name('document_type_entry')->middleware('auth');
Route::post('/document_type_entry_save','SettingsController@documentTypeEntrySave')->middleware('auth');
Route::get('/document_type_edit/{id}','SettingsController@documentTypeEdit')->name('document_type_edit')->middleware('auth');
Route::post('/document_type_edit_save','SettingsController@documentTypeEditSave')->middleware('auth');
Route::get('/document_type_delete/{id}','SettingsController@documentTypeDelete')->name('document_type_delete')->middleware('auth');

//delivery method
Route::get('/delivery_method_entry','SettingsController@deliveryMethodEntry')->name('delivery_method_entry')->middleware('auth');
Route::post('/delivery_method_entry_save','SettingsController@deliveryMethodEntrySave')->middleware('auth');
Route::get('/delivery_method_entry_edit/{id}','SettingsController@deliveryMethodEntryEdit')->name('delivery_method_entry_edit')->middleware('auth');
Route::post('/delivery_method_entry_edit_save','SettingsController@deliveryMethodEntryEditSave')->middleware('auth');
Route::get('/delivery_method_delete/{id}','SettingsController@deliveryMethodDelete')->name('delivery_method_delete')->middleware('auth');


//transaction type
Route::get('/transaction_type_entry','SettingsController@transactionTypeEntry')->name('transaction_type_entry')->middleware('auth');
Route::post('/transaction_type_entry_save','SettingsController@transactionTypeEntrySave')->middleware('auth');
Route::get('/transaction_type_edit/{id}','SettingsController@transactionTypeEdit')->name('transaction_type_edit')->middleware('auth');
Route::post('/transaction_type_edit_save','SettingsController@transactionTypeEditSave')->middleware('auth');
Route::get('/transaction_type_delete/{id}','SettingsController@transactionTypeDelete')->name('transaction_type_delete')->middleware('auth');

//performance standard
Route::get('/performance_standards','SettingsController@performanceStandards')->name('performance_standards')->middleware('auth');
Route::post('/performance_standards_update','SettingsController@performanceStandardsUpdate')->middleware('auth');

//reactive
Route::get('/reactivate_ended_transaction','SettingsController@reactivateEndedTransaction')->name('reactivate_ended_transaction')->middleware('auth');
Route::post('/reactivate_transaction','SettingsController@reactivateTransaction')->middleware('auth');

//correct 
Route::get('/correct_release_route','SettingsController@correctReleaseRoute')->name('correct_release_route')->middleware('auth');
Route::post('/correct_route','SettingsController@correctRoute')->middleware('auth');

//create user
Route::get('/create_user','SettingsController@createUser')->name('create_user')->middleware('auth');
Route::post('/create_user_save','SettingsController@createUserSave')->middleware('auth');
Route::get('/user_delete/{id}','SettingsController@userDelete')->name('user_delete')->middleware('auth');
Route::get('/edit_user/{id}','SettingsController@editUser')->name('edit_user')->middleware('auth');
Route::post('/edit_user_save','SettingsController@editUserSave')->middleware('auth');

//reset password
Route::get('/reset_password/{id}/{email}','SettingsController@resetPassword')->name('reset_password')->middleware('auth');
Route::post('/reset_password','SettingsController@resetPasswordSave')->middleware('auth');

//change password
Route::get('/change_password','SettingsController@changePassword')->name('change_password')->middleware('auth');
Route::post('/change_password_save','SettingsController@changePasswordSave')->middleware('auth');

//archive
Route::get('/archive','ArchiveController@archive')->name('archive')->middleware('auth');
Route::post('/archive','ArchiveController@archiveSearch')->middleware('auth');

//tracking
Route::get('/view_tracking/{id}','ArchiveController@viewTracking')->name('view_tracking')->middleware('auth');

//password reset
Route::get('/password/reset','SettingsController@redirect')->name('password')->middleware('auth');
Route::get('/register','SettingsController@redirect')->name('register')->middleware('auth');

//report
Route::get('/report_custom','ReportController@reportCustom')->name('report_custom')->middleware('auth');
Route::post('/report_custom','ReportController@reportCustomGenerate')->middleware('auth');
Route::get('/office_performance','ReportController@officePerformance')->name('office_performance')->middleware('auth');
Route::post('/office_performance','ReportController@officePerformanceGenerate')->middleware('auth');
Route::get('/summary','ReportController@summary')->name('summary')->middleware('auth');
Route::post('/summary','ReportController@summaryGenerate')->middleware('auth');
Route::get('/office_transaction_summary','ReportController@officeTransactionSummary')->name('office_transaction_summary')->middleware('auth');
Route::post('/office_transaction_summary','ReportController@officeTransactionSummaryGenerate')->middleware('auth');
Route::get('/unended_transaction','ReportController@unendedTransaction')->name('unended_transaction')->middleware('auth');
Route::post('/unended_transaction','ReportController@unendedTransactionGenerate')->middleware('auth');

//dashboard link report
Route::get('/show_report/{b}/{ds}/{tid}/{cm}/{lbl}','ReportController@showReport')->name('show_report')->middleware('auth');
Route::get('/for_due/{oc}','ReportController@forDueGenerate')->name('for_due')->middleware('auth');
Route::get('/delinquents_report/{cm}','ReportController@delinquentsReport')->name('delinquents_report')->middleware('auth');
Route::get('/delinquents_report_detailed/{cm}','ReportController@delinquentsReportDetailed')->name('delinquents_report_detailed')->middleware('auth');
Route::get('/document_list/{cm}','ReportController@documentList')->name('document_list')->middleware('auth');
Route::get('/to_do_list/{cm}','ReportController@toDoList')->name('to_do_list')->middleware('auth');
Route::get('/client_source/{type}/{cm}/{b}','ReportController@clientSource')->name('client_source')->middleware('auth');

//charts
// Route::get('/clients_chart','chartController@index')->name('clients_chart')->middleware('auth');

//holidays
Route::get('/holidays','SettingsController@holidays')->name('holidays')->middleware('auth');
Route::post('/holiday_save','SettingsController@holidaySave')->middleware('auth');
Route::get('/holiday_delete/{id}','SettingsController@holidayDelete')->name('holiday_delete')->middleware('auth');

//correction
Route::get('/correct_transaction_status/{id}/{bid}','SettingsController@correctTransactionStatus')->name('correct_transaction_status')->middleware('auth');