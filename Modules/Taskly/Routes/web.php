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
use Illuminate\Support\Facades\Route;
use Modules\Taskly\Http\Controllers\DashboardController;
use Modules\Taskly\Http\Controllers\ProjectController;
use Modules\Taskly\Http\Controllers\ProjectReportController;

Route::group(['middleware' => 'PlanModuleCheck:Taskly'], function ()
{
    Route::get('dashboard/taskly',[DashboardController::class,'index'])->name('taskly.dashboard')->middleware(['auth']);

    Route::get('/project/copy/{id}',[ProjectController::class,'copyproject'])->name('project.copy')->middleware(['auth']);
    Route::post('/project/copy/store/{id}',[ProjectController::class,'copyprojectstore'])->name('project.copy.store')->middleware(['auth']);

    Route::resource('projects', 'ProjectController')->middleware(['auth']);
    Route::resource('stages', 'StageController')->middleware(['auth']);
    Route::get('projects-list', [ProjectController::class,'List'])->name('projects.list')->middleware(['auth']);

    //project import
    Route::get('project/import/export', [ProjectController::class,'fileImportExport'])->name('project.file.import')->middleware(['auth']);
    Route::post('project/import', [ProjectController::class,'fileImport'])->name('project.import')->middleware(['auth']);
    Route::get('project/import/modal', [ProjectController::class,'fileImportModal'])->name('project.import.modal')->middleware(['auth']);
    Route::post('project/data/import/', [ProjectController::class,'projectImportdata'])->name('project.import.data')->middleware(['auth']);
    
    //project Setting
    Route::get('project/setting/{id}', [ProjectController::class,'CopylinkSetting'])->name('project.setting')->middleware(['auth']);
    Route::post('project/setting/save{id}', [ProjectController::class,'CopylinkSettingSave'])->name('project.setting.save')->middleware(['auth']);

    Route::post('send-mail', [ProjectController::class,'sendMail'])->name('send.mail')->middleware(['auth']);
    // Task Board
    Route::get('projects/{id}/task-board',[ProjectController::class,'taskBoard'])->name('projects.task.board')->middleware(['auth']);
    Route::get('projects/{id}/task-board/create',[ProjectController::class,'taskCreate'])->name('tasks.create')->middleware(['auth']);
    Route::post('projects/{id}/task-board',[ProjectController::class,'taskStore'])->name('tasks.store')->middleware(['auth']);
    Route::post('projects/{id}/task-board/order-update',[ProjectController::class,'taskOrderUpdate'])->name('tasks.update.order')->middleware(['auth']);
    Route::get('projects/{id}/task-board/edit/{tid}',[ProjectController::class,'taskEdit'])->name('tasks.edit')->middleware(['auth']);
    Route::post('projects/{id}/task-board/{tid}/update',[ProjectController::class,'taskUpdate'])->name('tasks.update')->middleware(['auth']);
    Route::delete('projects/{id}/task-board/{tid}',[ProjectController::class,'taskDestroy'])->name('tasks.destroy')->middleware(['auth']);
    Route::get('projects/{id}/task-board/{tid}/{cid?}',[ProjectController::class,'taskShow'])->name('tasks.show')->middleware(['auth']);
    Route::get('projects/{id}/task-board-list', [ProjectController::class,'TaskList'])->name('projecttask.list')->middleware(['auth']);

    // Gantt Chart
    Route::get('projects/{id}/gantt/{duration?}',[ProjectController::class,'gantt'])->name('projects.gantt')->middleware(['auth']);
    Route::post('projects/{id}/gantt',[ProjectController::class,'ganttPost'])->name('projects.gantt.post')->middleware(['auth']);


    // bug report
    Route::get('projects/{id}/bug_report',[ProjectController::class,'bugReport'])->name('projects.bug.report')->middleware(['auth']);
    Route::get('projects/{id}/bug_report/create',[ProjectController::class,'bugReportCreate'])->name('projects.bug.report.create')->middleware(['auth']);
    Route::post('projects/{id}/bug_report',[ProjectController::class,'bugReportStore'])->name('projects.bug.report.store')->middleware(['auth']);
    Route::post('projects/{id}/bug_report/order-update',[ProjectController::class,'bugReportOrderUpdate'])->name('projects.bug.report.update.order')->middleware(['auth']);
    Route::get('projects/{id}/bug_report/{bid}/show',[ProjectController::class,'bugReportShow'])->name('projects.bug.report.show')->middleware(['auth']);
    Route::get('projects/{id}/bug_report/{bid}/edit',[ProjectController::class,'bugReportEdit'])->name('projects.bug.report.edit')->middleware(['auth']);
    Route::post('projects/{id}/bug_report/{bid}/update',[ProjectController::class,'bugReportUpdate'])->name('projects.bug.report.update')->middleware(['auth']);
    Route::delete('projects/{id}/bug_report/{bid}',[ProjectController::class,'bugReportDestroy'])->name('projects.bug.report.destroy')->middleware(['auth']);
    Route::get('projects/{id}/bug_report-list', [ProjectController::class,'BugList'])->name('projectbug.list')->middleware(['auth']);


    Route::get('projects/invite/{id}',[ProjectController::class,'popup'])->name('projects.invite.popup')->middleware(['auth']);
    Route::get('projects/share/{id}',[ProjectController::class,'sharePopup'])->name('projects.share.popup')->middleware(['auth']);
    Route::get('projects/share/vender/{id}',[ProjectController::class,'sharePopupVender'])->name('projects.share.vender.popup')->middleware(['auth']);
    Route::post('projects/share/vender/store/{id}',[ProjectController::class,'sharePopupVenderStore'])->name('projects.share.vender')->middleware(['auth']);
    Route::get('projects/milestone/{id}',[ProjectController::class,'milestone'])->name('projects.milestone')->middleware(['auth']);
    Route::post('projects/{id}/file',[ProjectController::class,'fileUpload'])->name('projects.file.upload')->middleware(['auth']);
    Route::post('projects/share/{id}',[ProjectController::class,'share'])->name('projects.share')->middleware(['auth']);


    // stages.index
    // project
    Route::get('projects/milestone/{id}',[ProjectController::class,'milestone'])->name('projects.milestone')->middleware();
    Route::post('projects/milestone/{id}/store',[ProjectController::class,'milestoneStore'])->name('projects.milestone.store')->middleware();
    Route::get('projects/milestone/{id}/show',[ProjectController::class,'milestoneShow'])->name('projects.milestone.show')->middleware(['auth']);
    Route::get('projects/milestone/{id}/edit',[ProjectController::class,'milestoneEdit'])->name('projects.milestone.edit')->middleware(['auth']);
    Route::post('projects/milestone/{id}/update',[ProjectController::class,'milestoneUpdate'])->name('projects.milestone.update')->middleware(['auth']);
    Route::delete('projects/milestone/{id}',[ProjectController::class,'milestoneDestroy'])->name('projects.milestone.destroy')->middleware(['auth']);
    Route::delete('projects/{id}/file/delete/{fid}',[ProjectController::class,'fileDelete'])->name('projects.file.delete')->middleware(['auth']);


    Route::post('projects/invite/{id}/update',[ProjectController::class,'invite'])->name('projects.invite.update')->middleware(['auth']);

    Route::resource('bugstages', 'BugStageController')->middleware(['auth']);

    Route::post('projects/{id}/comment/{tid}/file/{cid?}',[ProjectController::class,'commentStoreFile'])->name('comment.store.file');
    Route::delete('projects/{id}/comment/{tid}/file/{fid}',[ProjectController::class,'commentDestroyFile'])->name('comment.destroy.file');
    Route::post('projects/{id}/comment/{tid}/{cid?}',[ProjectController::class,'commentStore'])->name('comment.store');
    Route::delete('projects/{id}/comment/{tid}/{cid}',[ProjectController::class,'commentDestroy'])->name('comment.destroy');
    Route::post('projects/{id}/sub-task/update/{stid}',[ProjectController::class,'subTaskUpdate'])->name('subtask.update');
    Route::post('projects/{id}/sub-task/{tid}/{cid?}',[ProjectController::class,'subTaskStore'])->name('subtask.store');
    Route::delete('projects/{id}/sub-task/{stid}',[ProjectController::class,'subTaskDestroy'])->name('subtask.destroy');

    Route::post('projects/{id}/bug_comment/{tid}/file/{cid?}',[ProjectController::class,'bugStoreFile'])->name('bug.comment.store.file');
    Route::delete('projects/{id}/bug_comment/{tid}/file/{fid}',[ProjectController::class,'bugDestroyFile'])->name('bug.comment.destroy.file');
    Route::post('projects/{id}/bug_comment/{tid}/{cid?}',[ProjectController::class,'bugCommentStore'])->name('bug.comment.store');
    Route::delete('projects/{id}/bug_comment/{tid}/{cid}',[ProjectController::class,'bugCommentDestroy'])->name('bug.comment.destroy');
    Route::delete('projects/{id}/client/{uid}',[ProjectController::class,'clientDelete'])->name('projects.client.delete')->middleware(['auth']);
    Route::delete('projects/{id}/user/{uid}',[ProjectController::class,'userDelete'])->name('projects.user.delete')->middleware(['auth']);
    Route::delete('projects/{id}/vendor/{uid}',[ProjectController::class,'vendorDelete'])->name('projects.vendor.delete')->middleware(['auth']);

    // Project Report
    Route::resource('project_report','ProjectReportController')->middleware(['auth']);
    Route::post('project_report_data',[ProjectReportController::class,'ajax_data'])->name('projects.ajax')->middleware(['auth']);
    Route::post('project_report/tasks/{id}',[ProjectReportController::class,'ajax_tasks_report'])->name('tasks.report.ajaxdata')->middleware(['auth']);
});
Route::get('projects/{id}/file/{fid}',[ProjectController::class,'fileDownload'])->name('projects.file.download');

Route::post('project/password/check/{id}/{lang?}', [ProjectController::class,'PasswordCheck'])->name('project.password.check');
Route::get('project/shared/link/{id}/{lang?}', [ProjectController::class,'ProjectSharedLink'])->name('project.shared.link');
Route::get('projects/{id}/link/task/show/{tid}/',[ProjectController::class,'ProjectLinkTaskShow'])->name('Project.link.task.show');
Route::get('projects/{id}/link/bug_report/{bid}/show',[ProjectController::class,'ProjectLinkbugReportShow'])->name('projects.link.bug.report.show');
