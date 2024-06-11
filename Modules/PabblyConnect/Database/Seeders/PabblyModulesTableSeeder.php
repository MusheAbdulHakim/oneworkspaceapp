<?php

namespace Modules\PabblyConnect\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\PabblyConnect\Entities\PabblyModules;

class PabblyModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $modules = [
            'general' => ['Create User', 'New Invoice', 'Invoice Status Updated', 'New Proposal', 'Proposal Status Updated'],
            'Account' => ['New Customer', 'New Bill', 'New Vendor', 'New Revenue', 'New Payment'],
            'Pos' => ['New Purchase', 'New Warehouse'],
            'Hrm' => ['New Award', 'New Announcement', 'New Holidays', 'New Monthly Payslip', 'New Event', 'New Company Policy'],
            'Recruitment' => ['New Job', 'New Job Application', 'Interview Schedule', 'Convert To Employee'],
            'Retainer' => ['New Retainer', 'New Retainer Payment'],
            'Training' => ['New Training', 'New Trainer'],
            'SupportTicket' => ['New Ticket', 'New Ticket Reply'],
            'Rotas' => ['New Rota', 'New Availabilitys'],
            'ZoomMeeting' => ['New Zoom Meeting'],
            'Taskly' => ['New Project', 'New Milestone', 'New Task', 'Task Stage Update', 'New Task Comment', 'New Bug'],
            'Lead' => ['New Lead', 'New Deal', 'Lead Moved', 'Deal Moved', 'Convert To Deal'],
            'Contract' => ['New Contract'],
            'Sales' => ['New Quote', 'New Sales Order', 'New Sales Invoice', 'New Meeting', 'New Sales Invoice Payment'],
            'ProductService' => ['New Product'],
            'LMS' => ['New Course', 'New Chapter', 'New Blog', 'New Ratting'],
            'VCard' => ['New Appointment', 'New Contact', 'New Business', 'Business Status Update'],
            'CMMS' => ['New Location', 'New Workorder', 'New Component', 'New Part', 'New Pms', 'New Supplier', 'New Pos', 'New Workrequest'],
            'Appointment' => ['New Appointment', 'Appointment Status Update'],
            'Feedback' => ['New Template', 'New Rating'],
            'Fleet' => ['New Driver', 'New Customer', 'New Vehicle', 'New Booking', 'New Fleet Payment', 'New Insurance', 'New Maintenance', 'New Fuel'],
            'DoubleEntry' => ['New Journal Entry'],
            'Workflow' => ['New Workflow'],
            'Commission' => ['New Commission Plan', 'New Commission Receipt'],
            'Spreadsheet' => ['New Spreadsheet'],
            'WordpressWoocommerce' => ['New Product', 'New Product Category', 'New Tax'],
            'SalesAgent' => ['New Sales Agent', 'New Sales Agent Program', 'Sales Agent Request Accept', 'Sales Agent Request Reject', 'New Sales Agent Order'],
            'Holidayz' => ['New Hotel', 'New Customer', 'New Room', 'New Features', 'New Facilities', 'New Hotel Services', 'New Room Booking', 'New Booking Coupon', 'New Page Option'],
            'FixEquipment' => ['New Asset', 'New Accessories', 'New Category', 'New Component', 'New Consumables', 'New Depreciation', 'New Licence', 'New Location', 'New Maintenance', 'New Manufacturer', 'New Pre Defined Kit', 'New Status', 'New Audit'],
            'Portfolio' => ['New Portfolio', 'New Portfolio Category', 'Update Portfolio Status'],
            'AgricultureManagement' => ['New Agriculture Fleet', 'New Agriculture Process', 'New Agriculture Equipment', 'New Agriculture Claim Type', 'New Agriculture Cycle', 'New Agriculture Department', 'New Agriculture Office', 'New Agriculture Canal', 'New Agriculture Season', 'New Agriculture Season Type', 'New Agriculture Service Product', 'New Agriculture Crop', 'New Agriculture User', 'New Agriculture Cultivation', 'Update Agriculture Cultivation Status', 'Assign Cultivation Activity', 'New Agriculture Activities', 'New Agriculture Services'],
            'TourTravelManagement' => ['New Season', 'New Person Detail', 'New Tour Booking', 'New Tour Booking Payment', 'New Tour', 'New Tour Detail', 'New Tour Inquiry', 'New Transport Type'],
            'Newspaper' => ['New Newspaper Category', 'New Newspaper Varient', 'New Newspaper Type', 'New Journalist Type', 'New Newspaper Tax', 'New Newspaper Distribution Center', 'New Agent', 'New Journalist', 'New Journalist Information', 'New Advertisement', 'New Newspaper', 'New Newspaper Invoice', 'Update Newspaper Invoice Status'],
            'School' => ['New Employee', 'New Addmissions', 'New Parents', 'New Students', 'New Classroom', 'New Homework', 'New Subject', 'New Time Table'],
            'WasteManagement' => ['New Collection Request', 'Update Collection Request', 'Update Waste Inspection Status', 'Collection Converted To Trip'],
            'LaundryManagement' => ['New Laundry Service', 'New Laundry Location', 'New Laundry Request'],
            'CleaningManagement' => ['New Cleaning Team', 'New Cleaning Booking', 'New Cleaning Invoice'],
            'MachineRepairManagement' => ['New Machine', 'New Repair Request', 'New Diagnosis', 'New Diagnosis Payment'],
            'PropertyManagement' => ['New Property', 'New Property Units', 'New Tenant', 'New Property Invoice', 'New Property Invoice Payment'],
            'VehicleInspectionManagement' => ['New Inspection Vehicle', 'New Vehicle Inspection Request', 'New Inspection List', 'New Defects And Repairs'],
            'ChildcareManagement' => ['New Parent', 'New Child', 'New Class', 'New Activity', 'New Child Attendance', 'Update Child Care', 'New Fee', 'New Fee Payment', 'New Inquiry', 'New Nutrition'],
            'ConsignmentManagement' => ['New Consignment', 'New Product', 'New Purchase Order', 'New Sale Order'],
            'HospitalManagement' => ['New Specialization', 'New Medicine Category', 'New Bed Type', 'New Ward', 'New Hospital Bed', 'New Doctor', 'New Patient', 'New Hospital Appointment', 'New Hospital Medicine', 'New Medical Records'],
            'TimeTracker' => ['New Tracker'],
            'ParkingManagement' => ['New Parking', 'New Slot', 'New Slot Type'],
            'BeverageManagement' => ['New Manufacturing', 'New Raw Material', 'New Packaging', 'New Collection Center', 'New Bill of Material', 'New Bill Item Material'],
            'LegalCaseManagement' => ['New Advocate', 'New Case Initiator', 'New Case', 'New Court', 'New Division', 'New Expense', 'New Fee Recieve', 'New Fee Reciept', 'New Hearing', 'New High Court', 'New Fee Payment'],
            'VisitorManagement' => ['New Visitor', 'New Visit Reason'],
            'CourierManagement' => ['New Courier Branch', 'New Courier Service Type', 'New Courier Tracking Status', 'New Courier Package Category', 'New Courier', 'New Courier Payment'],
            'CateringManagement' => ['New Customer', 'New Menu Selection', 'New Event', 'New Event Details', 'New Menu Items', 'New Catering Invoice'],
            'MedicalLabManagement' => ['New Test Unit', 'New Test Content', 'New Lab Test', 'New Lab Patient', 'New Patient Card', 'New Lab Request', 'New Medical Appointment'],
            'GymManagement' => ['New Body Part', 'New Diet', 'New Equipment', 'New Exercise', 'New GYM Trainer', 'New Measurement', 'New Membership Plan', 'New Skill', 'New Workout Plan'],
            'RepairManagementSystem' => ['New Repair Order Request', 'New Repair Part', 'New Repair Invoice'],
            'PharmacyManagement' => ['New Medicine Category', 'New Medicine', 'New Medicine Type', 'New Pharmacy Bill', 'New Pharmacy Invoice'],
            'CarDealership' => ['New Car Category', 'New Tax', 'New Dealership Product', 'New Car Purchase', 'New Car Purchase Payment', 'New Car Sale', 'New Car Sale Payment'],
            'FreightManagementSystem' => ['New Freight Booking Request', 'New Freight Container', 'New Freight Customer', 'New Freight Price', 'New Freight Service', 'New Freight Route', 'New Freight Invoice', 'Update Freight Shipping Order', 'Update Freight Shipping Service', 'Update Freight Shipping Container'],
            'DairyCattleManagement' => ['New Animal', 'New Health', 'New Breeding', 'New Weight', 'New Daily Milk Sheet', 'New Milk Inventory'],
            'Sage' => ['New Ledger Account'],
            'VehicleBookingManagement' => ['New Vehicle Route', 'New Vehicle Booking'],
            'ToDo' => ['New To Do Stage', 'New To Do'],
            'ContractTemplate' => ['New Contract Template', 'Duplicate Contract Template'],
            'InnovationCenter' => ['New Challenge Category', 'New Creativity Stage', 'New Creativity Status', 'New Challenges', 'New Creativity'],
            'FileSharing' => ['New File'],
            'Internalknowledge' => ['New Book', 'New Artical'],
            'SideMenuBuilder' => ['New Side Menu'],
            'MusicInstitute' => ['New Music Class', 'New Music Lesson', 'New Music Instrument', 'New Music Student', 'New Music Teacher'],
            'RentalManagement' => ['New Rental', 'Duplicate Rental'],
            'GarageManagement' => ['New Fule Type', 'New Garage Category', 'New Garage Vehicle', 'New Service', 'New Vehicle Brand', 'New Vehicle Color', 'New Vehicle Type', 'New Job Card'],
            'BeautySpaManagement' => ['New Beauty Service', 'New Beauty Booking', 'Update Working Hours'],
            'MovieShowBookingSystem' => ['New Cast Type', 'New Show Type', 'New Certificate', 'New Movie Crew', 'New Movie Cast', 'New Seating Template', 'New Seating Template Details', 'New Movie Show', 'New Movie Event'],
            'Exam' => ['New Exam Grade', 'New Exam Hall', 'New Exam Hall Receipt', 'New Exam List', 'New Exam Time Table', 'New Mange Marks'],
            'InsuranceManagement' => ['New Insurance', 'New Insurance Accept', 'New Policy', 'New Policy Type', 'New Insurance Invoice', 'New Insurance Claim', 'Accept Insurance Claim', 'Reject Insurance Claim', 'New Insurance Claim Payment'],
            'MobileServiceManagement' => ['New Mobile Service', 'New Mobile Service Request Accept', 'New Mobile Service Request Reject', 'New Mobile Service Technician Assign', 'New Mobile Service Invoice', 'New Mobile Service Traking Status'],
            'Documents' => ['New Document', 'New Document Type'],
            'DocumentTemplate' => ['New Document Template', 'Duplicate Document Template', 'Convert Document Template'],
            'BusinessProcessMapping' => ['New Business Process Mapping'],
            'VideoHub' => ['New Video', 'New Video Comment'],
            'CallHub' => ['New Call List'],
            'MeetingHub' => ['New Meeting', 'New Meeting Task', 'New Meeting Minute', 'New Meeting Note']
        ];

        $super_admin_module = ['Create User', 'New Subscriber'];

        foreach ($super_admin_module as $sm) {
            $check = PabblyModules::where('module', 'general')->where('submodule', $sm)->first();
            if (!$check) {
                $new = new PabblyModules();
                $new->module = 'general';
                $new->submodule = $sm;
                $new->type = 'super admin';
                $new->save();
            }
        }

        foreach ($modules as $module_name => $actions) {
            foreach ($actions as $action) {
                $ntfy = PabblyModules::where('submodule', $action)->where('module', $module_name)->count();
                if ($ntfy == 0) {
                    $new = new PabblyModules();
                    $new->module = $module_name;
                    $new->submodule = $action;
                    $new->save();
                }
            }
        }

        // $this->call("OthersTableSeeder");
    }
}
