<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CRM\LeadController;
use App\Http\Controllers\CRM\CustomerController;
use App\Http\Controllers\ProjectManagementController;
use App\Http\Controllers\TaskManagementController;
use App\Http\Controllers\TaskReportController;

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

//===============================================================================================================================

Route::controller(AdminController::class)->group(function(){
    Route::get('/', 'login')->name('admin.login');
    Route::post('/admin/check-login', 'checkLogin')->name('admin.check-login');
    Route::get('/admin/otp', 'otp')->name('admin.otp');
    Route::post('/admin/check-otp', 'checkOtp')->name('admin.check-otp');
    Route::get('/admin-logout', 'logout')->name('admin.logout');

    // password change and forgot password otp & update password start
    Route::post('/admin/send-otp', 'sendOtp')->name('admin.sendOtp');
    Route::post('/admin/verify-otp', 'verifyOtp')->name('admin.verifyOtp');
    Route::post('/admin/update-password', 'updatePassword')->name('admin.updatePassword');
    // password change and forgot password otp & update password end
});

Route::controller(AdminController::class)->middleware(['auth'])->group(function(){
    Route::get('/superadmin/dashboard', 'superadmindashboard')->name('superadmin.dashboard');
    Route::get('/projectmanager/dashboard', 'projectmanagerdashboard')->name('projectmanager.dashboard');
    Route::get('/admin/dashboard', 'admindashboard')->name('admin.dashboard');

    // profile start
    Route::get('/admin/profile', 'adminProfile')->name('admin.profile');
    Route::post('admin/fetch-profile', 'fetchProfile')->name('admin.fetchProfile');
    Route::post('admin/update-profile', 'updateProfile')->name('admin.updateProfile');
    // profile end

    // change password start
    Route::get('/admin/change-password', 'changePassword')->name('change.password');
    Route::post('/admin/check-current-password', 'checkCurrentPassword')->name('check-current-password');
    // change password end
});

//===============================================================================================================================

Route::controller(UserController::class)->middleware(['auth'])->prefix('admin/users')->group(function(){
    Route::get('/', 'index')->name('user.index');
    Route::post('/user-create', 'userCreate')->name('user.create');
    Route::post('/generate-username', 'generateUsername')->name('generate.username');
    Route::post('/user-submit', 'userSubmit')->name('user.submit');
    Route::get('/user-fetch', 'userFetch')->name('user.fetch');
    Route::post('/user-view', 'userView')->name('user.view');
    Route::post('/user-delete', 'userDelete')->name('user.delete');

    // add department
    Route::get('/department-create-load', 'departmentLoad')->name('department.load');
    Route::post('/department-create-form', 'departmentForm')->name('department.form');
    Route::post('/department-create-submit', 'departmentSubmit')->name('department.submit');
});

//===============================================================================================================================

Route::controller(LeadController::class)->middleware(['auth'])->prefix('admin/leads')->group(function(){
    Route::get('/', 'index')->name('lead.index');
    Route::post('/lead-create', 'leadCreate')->name('lead.create');
    Route::post('/lead-submit', 'leadSubmit')->name('lead.submit');
    Route::get('/lead-fetch', 'leadFetch')->name('lead.fetch');
    Route::post('/lead-view', 'leadView')->name('lead.view');
    Route::post('/lead-delete', 'leadDelete')->name('lead.delete');
    Route::post('/change-lead-status', 'changeLeadStatus')->name('change.lead.status');

    // call log start
    Route::post('/lead-call-log', 'leadCallLog')->name('lead.callLog');
    Route::post('/call-log-submit', 'callLogSubmit')->name('callLog.submit');
    Route::get('/call-log-fetch', 'callLogFetch')->name('callLog.fetch');
    Route::post('/call-log-edit', 'callLogEdit')->name('callLog.edit');
    Route::post('/call-log-delete', 'callLogDelete')->name('callLog.delete');
    // call log end
});

//===============================================================================================================================

Route::controller(CustomerController::class)->middleware(['auth'])->prefix('admin/customers')->group(function(){
    Route::get('/', 'index')->name('customer.index');
    Route::post('/customer-create', 'customerCreate')->name('customer.create');
    Route::post('/customer-submit', 'customerSubmit')->name('customer.submit');
    Route::get('/customer-fetch', 'customerFetch')->name('customer.fetch');
    Route::post('/customer-view', 'customerView')->name('customer.view');
    Route::post('/customer-delete', 'customerDelete')->name('customer.delete');

    // add service type
    Route::get('/service-create-load', 'serviceTypeLoad')->name('serviceType.load');
    Route::post('/service-create-form', 'serviceTypeForm')->name('serviceType.form');
    Route::post('/service-create-submit', 'serviceTypeSubmit')->name('serviceType.submit');

    // call log start
    Route::post('/customer-call-log', 'customerCallLog')->name('customer.callLog');
    Route::post('/call-log-submit', 'callLogSubmit')->name('customerCallLog.submit');
    Route::get('/call-log-fetch', 'callLogFetch')->name('customerCallLog.fetch');
    Route::post('/call-log-edit', 'callLogEdit')->name('customerCallLog.edit');
    Route::post('/call-log-delete', 'callLogDelete')->name('customerCallLog.delete');
    // call log end
});

//===============================================================================================================================

Route::controller(ProjectManagementController::class)->middleware(['auth'])->prefix('admin/project-management')->group(function(){
    Route::get('/', 'index')->name('project-management.index');
    Route::post('/create', 'projectManagementCreate')->name('project-management.create');
    Route::post('/submit', 'projectManagementSubmit')->name('project-management.submit');
    Route::get('/fetch', 'projectManagementFetch')->name('project-management.fetch');
    Route::post('/view', 'projectManagementView')->name('project-management.view');
    Route::post('/delete', 'projectManagementDelete')->name('project-management.delete');
    Route::post('/change-project-status', 'changeProjectStatus')->name('change.project.status');
});

//===============================================================================================================================

Route::controller(TaskManagementController::class)->middleware(['auth'])->prefix('admin/task-management')->group(function(){
    Route::get('/', 'index')->name('task-management.index');
    Route::post('/ckeditor-fileupload', 'ckeditorFileUpload')->name('taskManagement.ckeditor.fileupload');
    Route::post('/create', 'taskManagementCreate')->name('task-management.create');
    Route::post('/fetch-team-members', 'fetchTeamMembers')->name('task-management.fetch-team-members');
    Route::post('/submit', 'taskManagementSubmit')->name('task-management.submit');
    Route::get('/fetch', 'taskManagementFetch')->name('task-management.fetch');
    Route::post('/view', 'taskManagementView')->name('task-management.view');
    Route::post('/delete', 'taskManagementDelete')->name('task-management.delete');
    Route::post('/change-task-status', 'changeTaskStatus')->name('change.task.status');
    // if task completed
    Route::post('/documents', 'taskManagementDocuments')->name('task-management.documents');
    Route::post('/documents-submit', 'taskManagementDocumentsSubmit')->name('task-management-documents.submit');
    // if task verified
    Route::post('/marks', 'taskManagementMarks')->name('task-management.marks');
    Route::post('/marks-submit', 'taskManagementMarksSubmit')->name('task-management-marks.submit');
    // if task cancelled or hold
    Route::post('/revoke', 'taskManagementRevoke')->name('task-management.revoke');
    Route::post('/revoke-submit', 'taskManagementRevokeSubmit')->name('task-management-revoke.submit');
});

//===============================================================================================================================

Route::controller(TaskReportController::class)->middleware(['auth'])->prefix('admin/task-report')->group(function(){
    Route::get('/', 'index')->name('task-report.index');
    Route::any('/fetch-data', 'fetch')->name('task-report.fetch');
});

//===============================================================================================================================
