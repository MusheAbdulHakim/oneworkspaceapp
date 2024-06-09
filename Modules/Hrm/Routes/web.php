<?php
// hrm module
use Illuminate\Support\Facades\Route;
use Modules\Hrm\Http\Controllers\AllowanceController;
use Modules\Hrm\Http\Controllers\AllowanceOptionController;
use Modules\Hrm\Http\Controllers\AllowanceTaxController;
use Modules\Hrm\Http\Controllers\AnnouncementController;
use Modules\Hrm\Http\Controllers\AttendanceController;
use Modules\Hrm\Http\Controllers\AwardController;
use Modules\Hrm\Http\Controllers\AwardTypeController;
use Modules\Hrm\Http\Controllers\BranchController;
use Modules\Hrm\Http\Controllers\CommissionController;
use Modules\Hrm\Http\Controllers\CompanyContributionController;
use Modules\Hrm\Http\Controllers\CompanyPolicyController;
use Modules\Hrm\Http\Controllers\ComplaintController;
use Modules\Hrm\Http\Controllers\DeductionOptionController;
use Modules\Hrm\Http\Controllers\DepartmentController;
use Modules\Hrm\Http\Controllers\DesignationController;
use Modules\Hrm\Http\Controllers\DocumentController;
use Modules\Hrm\Http\Controllers\DocumentTypeController;
use Modules\Hrm\Http\Controllers\EmployeeController;
use Modules\Hrm\Http\Controllers\EventController;
use Modules\Hrm\Http\Controllers\HolidayController;
use Modules\Hrm\Http\Controllers\HrmController;
use Modules\Hrm\Http\Controllers\IpRestrictController;
use Modules\Hrm\Http\Controllers\LeaveController;
use Modules\Hrm\Http\Controllers\LeaveTypeController;
use Modules\Hrm\Http\Controllers\LoanController;
use Modules\Hrm\Http\Controllers\LoanOptionController;
use Modules\Hrm\Http\Controllers\OtherPaymentController;
use Modules\Hrm\Http\Controllers\OvertimeController;
use Modules\Hrm\Http\Controllers\PaySlipController;
use Modules\Hrm\Http\Controllers\PayslipTypeController;
use Modules\Hrm\Http\Controllers\PromotionController;
use Modules\Hrm\Http\Controllers\ReportController;
use Modules\Hrm\Http\Controllers\ResignationController;
use Modules\Hrm\Http\Controllers\SaturationDeductionController;
use Modules\Hrm\Http\Controllers\SetSalaryController;
use Modules\Hrm\Http\Controllers\TaxBracketController;
use Modules\Hrm\Http\Controllers\TaxRebateController;
use Modules\Hrm\Http\Controllers\TaxThresholdController;
use Modules\Hrm\Http\Controllers\TerminationController;
use Modules\Hrm\Http\Controllers\TerminationTypeController;
use Modules\Hrm\Http\Controllers\TransferController;
use Modules\Hrm\Http\Controllers\TravelController;
use Modules\Hrm\Http\Controllers\WarningController;
// testing
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


Route::group(['middleware' => 'PlanModuleCheck:Hrm'], function () {
    Route::prefix('hrm')->group(function () {
        Route::get('/', [HrmController::class, 'index'])->middleware(['auth']);
    });
    Route::get('dashboard/hrm', [HrmController::class, 'index'])->name('hrm.dashboard')->middleware(['auth']);
    Route::resource('document', DocumentController::class)->middleware(
        [
            'auth'
        ]
    );
    Route::resource('document-type', DocumentTypeController::class)->middleware(
        [
            'auth'
        ]
    );
    // Attendance
    Route::resource('attendance', AttendanceController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::get('bulkattendance', [AttendanceController::class, 'BulkAttendance'])->name('attendance.bulkattendance')->middleware(
        [
            'auth',
        ]
    );
    Route::post('bulkattendance', [AttendanceController::class, 'BulkAttendanceData'])->name('attendance.bulkattendance')->middleware(
        [
            'auth',
        ]
    );
    Route::post('attendance/attendance', [AttendanceController::class, 'attendance'])->name('attendance.attendance')->middleware(
        [
            'auth',
        ]
    );

    // Attendance import

    Route::get('attendance/import/export', [AttendanceController::class, 'fileImportExport'])->name('attendance.file.import');
    Route::post('attendance/import', [AttendanceController::class, 'fileImport'])->name('attendance.import');
    Route::get('attendance/import/modal', [AttendanceController::class, 'fileImportModal'])->name('attendance.import.modal');
    Route::post('attendance/data/import/', [AttendanceController::class, 'AttendanceImportdata'])->name('attendance.import.data');


    // branch
    Route::resource('branch', BranchController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::get('branchnameedit', [BranchController::class, 'BranchNameEdit'])->middleware(
        [
            'auth',
        ]
    )->name('branchname.edit');
    Route::post('branch-settings', [BranchController::class, 'saveBranchName'])->middleware(
        [
            'auth',
        ]
    )->name('branchname.update');
    // department
    Route::resource('department', DepartmentController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::get('departmentnameedit', [DepartmentController::class, 'DepartmentNameEdit'])->middleware(
        [
            'auth',
        ]
    )->name('departmentname.edit');
    Route::post('department-settings', [DepartmentController::class, 'saveDepartmentName'])->middleware(
        [
            'auth',
        ]
    )->name('departmentname.update');
    // designation
    Route::resource('designation', DesignationController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::get('designationnameedit', [DesignationController::class, 'DesignationNameEdit'])->middleware(
        [
            'auth',
        ]
    )->name('designationname.edit');
    Route::post('designation-settings', [DesignationController::class, 'saveDesignationName'])->middleware(
        [
            'auth',
        ]
    )->name('designationname.update');
    // employee
    Route::resource('employee', EmployeeController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::get('employee-grid', [EmployeeController::class, 'grid'])->name('employee.grid')->middleware(
        [
            'auth'
        ]
    );

    Route::post('employee/getdepartmentes', [EmployeeController::class, 'getDepartment'])->name('employee.getdepartments')->middleware(
        [
            'auth',
        ]
    );
    Route::post('employee/getdesignationes', [EmployeeController::class, 'getdDesignation'])->name('employee.getdesignations')->middleware(
        [
            'auth',
        ]
    );

    //employee import
    Route::get('employee/import/export', [EmployeeController::class, 'fileImportExport'])->name('employee.file.import')->middleware(['auth']);
    Route::post('employee/import', [EmployeeController::class, 'fileImport'])->name('employee.import')->middleware(['auth']);
    Route::get('employee/import/modal', [EmployeeController::class, 'fileImportModal'])->name('employee.import.modal')->middleware(['auth']);
    Route::post('employee/data/import/', [EmployeeController::class, 'employeeImportdata'])->name('employee.import.data')->middleware(['auth']);

    // settig in hrm
    Route::post('hrm/setting/store', [HrmController::class, 'setting'])->name('hrm.setting.store')->middleware(['auth']);
    Route::resource('company-policy', CompanyPolicyController::class)->middleware(
        [
            'auth',
        ]
    );

    Route::resource('iprestrict', IpRestrictController::class)->middleware(
        [
            'auth',
        ]
    );

    // Leave and Leave type
    Route::resource('leavetype', LeaveTypeController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::get('leave/{id}/action', [LeaveController::class, 'action'])->name('leave.action')->middleware(
        [
            'auth',
        ]
    );
    Route::post('leave/changeaction', [LeaveController::class, 'changeaction'])->name('leave.changeaction')->middleware(
        [
            'auth',
        ]
    );
    Route::post('leave/jsoncount', [LeaveController::class, 'jsoncount'])->name('leave.jsoncount')->middleware(
        [
            'auth',
        ]
    );
    Route::resource('leave', LeaveController::class)->middleware(
        [
            'auth',
        ]
    );

    // award
    Route::resource('awardtype', AwardTypeController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::resource('award', AwardController::class)->middleware(
        [
            'auth',
        ]
    );
    // transfer
    Route::resource('transfer', TransferController::class)->middleware(
        [
            'auth',
        ]
    );

    // Resignation
    Route::resource('resignation', ResignationController::class)->middleware(
        [
            'auth',
        ]
    );

    // Travel || Trip
    Route::resource('trip', TravelController::class)->middleware(
        [
            'auth',
        ]
    );

    // Promotion
    Route::resource('promotion', PromotionController::class)->middleware(
        [
            'auth',
        ]
    );
    //complaint
    Route::resource('complaint', ComplaintController::class)->middleware(
        [
            'auth',
        ]
    );
    //warning
    Route::resource('warning', WarningController::class)->middleware(
        [
            'auth',
        ]
    );
    
    // tax bracket
    Route::resource('taxbracket', TaxBracketController::class)->middleware(
        [
            'auth',
        ]
    );

    // tax rebate
    Route::resource('taxrebate', TaxRebateController::class)->middleware(
        [
            'auth',
        ]
    );

    // tax threshold
    Route::resource('taxthreshold', TaxThresholdController::class)->middleware(
        [
            'auth',
        ]
    );

    // allowance tax
    Route::resource('allowancetax', AllowanceTaxController::class)->middleware(
        [
            'auth',
        ]
    );
    
    // Termination and Terminationtype

    Route::resource('terminationtype', TerminationTypeController::class)->middleware(
        [
            'auth',
        ]
    );

    Route::get('termination/{id}/description', [TerminationController::class, 'description'])->name('termination.description');

    Route::resource('termination', TerminationController::class)->middleware(
        [
            'auth',
        ]
    );

    // Announcement
    Route::post('announcement/getemployee', [AnnouncementController::class, 'getemployee'])->name('announcement.getemployee')->middleware(
        [
            'auth',
        ]
    );
    Route::resource('announcement', AnnouncementController::class)->middleware(
        [
            'auth',
        ]
    );
    // Holiday
    Route::get('holiday/calender', [HolidayController::class, 'calender'])->name('holiday.calender')->middleware(
        [
            'auth',
        ]
    );
    Route::resource('holiday', HolidayController::class)->middleware(
        [
            'auth',
        ]
    );

    // Holiday import
    Route::get('holiday/import/export', [HolidayController::class, 'fileImportExport'])->name('holiday.file.import')->middleware(['auth']);
    Route::post('holiday/import', [HolidayController::class, 'fileImport'])->name('holiday.import')->middleware(['auth']);
    Route::get('holiday/import/modal', [HolidayController::class, 'fileImportModal'])->name('holiday.import.modal')->middleware(['auth']);
    Route::post('holiday/data/import/', [HolidayController::class, 'holidayImportdata'])->name('holiday.import.data')->middleware(['auth']);

    // Report
    Route::get('report/monthly/attendance', [ReportController::class, 'monthlyAttendance'])->name('report.monthly.attendance')->middleware(
        [
            'auth',
        ]
    );
    Route::post('report/getdepartment', [ReportController::class, 'getdepartment'])->name('report.getdepartment')->middleware(
        [
            'auth',
        ]
    );
    Route::post('report/getemployee', [ReportController::class, 'getemployee'])->name('report.getemployee')->middleware(
        [
            'auth',
        ]
    );
    Route::get('report/leave', [ReportController::class, 'leave'])->name('report.leave')->middleware(
        [
            'auth',
        ]
    );
    Route::get('employee/{id}/leave/{status}/{type}/{month}/{year}', [ReportController::class, 'employeeLeave'])->name('report.employee.leave')->middleware(
        [
            'auth',
        ]
    );
    Route::get('report/payroll', [ReportController::class, 'Payroll'])->name('report.payroll')->middleware(
        [
            'auth',
        ]
    );
    //payslip type
    Route::resource('paysliptype', PayslipTypeController::class)->middleware(
        [
            'auth',
        ]
    );
    //allowance option
    Route::resource('allowanceoption', AllowanceOptionController::class)->middleware(
        [
            'auth',
        ]
    );
    // loan option
    Route::resource('loanoption', LoanOptionController::class)->middleware(
        [
            'auth',
        ]
    );
    //deduction option
    Route::resource('deductionoption', DeductionOptionController::class)->middleware(
        [
            'auth',
        ]
    );
    // Payroll
    Route::resource('setsalary', SetSalaryController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::get('employee/salary/{eid}', [SetSalaryController::class, 'employeeBasicSalary'])->name('employee.basic.salary')->middleware(
        [
            'auth',
        ]
    );
    Route::post('employee/update/sallary/{id}', [SetSalaryController::class, 'employeeUpdateSalary'])->name('employee.salary.update')->middleware(
        [
            'auth',
        ]
    );
    // Allowance
    Route::resource('allowance', AllowanceController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::get('allowances/create/{eid}', [AllowanceController::class, 'allowanceCreate'])->name('allowances.create')->middleware(
        [
            'auth',
        ]
    );
    // commissions
    Route::get('commissions/create/{eid}', [CommissionController::class, 'commissionCreate'])->name('commissions.create')->middleware(
        [
            'auth',
        ]
    );
    Route::resource('commission', CommissionController::class)->middleware(
        [
            'auth',
        ]
    );
    // loan
    Route::get('loans/create/{eid}', [LoanController::class, 'loanCreate'])->name('loans.create')->middleware(
        [
            'auth',
        ]
    );
    Route::resource('loan', LoanController::class)->middleware(
        [
            'auth',
        ]
    );
    // saturationdeduction
    Route::get('saturationdeductions/create/{eid}', [SaturationDeductionController::class, 'saturationdeductionCreate'])->name('saturationdeductions.create')->middleware(
        [
            'auth',
        ]
    );
    Route::resource('saturationdeduction', SaturationDeductionController::class)->middleware(
        [
            'auth',
        ]
    );
    // otherpayment
    Route::get('otherpayments/create/{eid}', [OtherPaymentController::class, 'otherpaymentCreate'])->name('otherpayments.create')->middleware(
        [
            'auth',
        ]
    );
    Route::resource('otherpayment', OtherPaymentController::class)->middleware(
        [
            'auth',
        ]
    );
    // companycontribution
    Route::get('companycontribution/create/{eid}', [CompanyContributionController::class, 'companycontributionCreate'])->name('companycontributions.create')->middleware(
        [
            'auth',
        ]
    );
    Route::resource('companycontribution', CompanyContributionController::class)->middleware(
        [
            'auth',
        ]
    );
    // overtime
    Route::get('overtimes/create/{eid}', [OvertimeController::class, 'overtimeCreate'])->name('overtimes.create')->middleware(
        [
            'auth',
        ]
    );
    Route::resource('overtime', OvertimeController::class)->middleware(
        [
            'auth',
        ]
    );
    // Payslip
    Route::resource('payslip', PaySlipController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::post('payslip/search_json', [PaySlipController::class, 'search_json'])->name('payslip.search_json')->middleware(
        [
            'auth',
        ]
    );
    Route::get('payslip/delete/{id}', [PaySlipController::class, 'destroy'])->name('payslip.delete')->middleware(
        [
            'auth',
        ]
    );
    Route::get('payslip/pdf/{id}/{m}', [PaySlipController::class, 'pdf'])->name('payslip.pdf')->middleware(
        [
            'auth',
        ]
    );
    Route::get('payslip/payslipPdf/{id}', [PaySlipController::class, 'payslipPdf'])->name('payslip.payslipPdf')->middleware(
        [
            'auth',
        ]
    );
    Route::get('payslip/paysalary/{id}/{date}', [PaySlipController::class, 'paysalary'])->name('payslip.paysalary')->middleware(
        [
            'auth',
        ]
    );
    Route::get('payslip/send/{id}/{m}', [PaySlipController::class, 'send'])->name('payslip.send')->middleware(
        [
            'auth',
        ]
    );
    Route::get('payslip/editemployee/{id}', [PaySlipController::class, 'editemployee'])->name('payslip.editemployee')->middleware(
        [
            'auth',
        ]
    );

    Route::post('payslip/editemployee/{id}', [PaySlipController::class, 'updateEmployee'])->name('payslip.updateemployee')->middleware(
        [
            'auth',
        ]
    );

    //Event
    Route::get('event/data/{id}', [EventController::class, 'showData'])->name('eventsshow');
    Route::post('event/getdepartment', [EventController::class, 'getdepartment'])->name('event.getdepartment')->middleware(
        [
            'auth',
        ]
    );
    Route::post('event/getemployee', [EventController::class, 'getemployee'])->name('event.getemployee')->middleware(
        [
            'auth',
        ]
    );
    Route::resource('event', EventController::class)->middleware(
        [
            'auth',
        ]
    );
    // //joining Letter
    Route::post('setting/joiningletter/{lang?}', [HrmController::class, 'joiningletterupdate'])->name('joiningletter.update');
    Route::get('employee/pdf/{id}', [EmployeeController::class, 'joiningletterPdf'])->name('joiningletter.download.pdf');
    Route::get('employee/doc/{id}', [EmployeeController::class, 'joiningletterDoc'])->name('joininglatter.download.doc');

    // //Experience Certificate
    Route::post('setting/exp/{lang?}', [HrmController::class, 'experienceCertificateupdate'])->name('experiencecertificate.update');
    Route::get('employee/exppdf/{id}', [EmployeeController::class, 'ExpCertificatePdf'])->name('exp.download.pdf');
    Route::get('employee/expdoc/{id}', [EmployeeController::class, 'ExpCertificateDoc'])->name('exp.download.doc');

    // //NOC
    Route::post('setting/noc/{lang?}', [HrmController::class, 'NOCupdate'])->name('noc.update');
    Route::get('employee/nocpdf/{id}', [EmployeeController::class, 'NocPdf'])->name('noc.download.pdf');
    Route::get('employee/nocdoc/{id}', [EmployeeController::class, 'NocDoc'])->name('noc.download.doc');
});
