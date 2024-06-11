<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Lead\Entities\Deal;
use Modules\Lead\Entities\Lead;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\ProductService;
use Modules\PropertyManagement\Entities\Property;
use Modules\Taskly\Entities\Project;
use Modules\VideoHub\Entities\VideoHubModule;
use Modules\VideoHub\Events\CreateVideoHubVideo;

class CreateVideoHubVideoLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreateVideoHubVideo $event)
    {
        if (module_is_active('PabblyConnect')) {
            $video = $event->video;

            $sub_module = VideoHubModule::find($video->sub_module_id);
            $pabbly_array = [];

            if ($video->module == 'CRM') {
                if ($sub_module->sub_module == 'Lead') {
                    $lead = Lead::find($video->item_id);

                    $pabbly_array = [
                        'Video Title' => $video->title,
                        'Video Module' => $video->module,
                        'Video Sub module' => $sub_module->sub_module,
                        'Lead Name' => $lead->name,
                        'Lead Email' => $lead->email,
                        'Lead Subject' => $lead->subject,
                        'Lead Phone Number' => $lead->phone,
                        'Lead Date' => $lead->date,
                        'Lead Follow Up Date' => $lead->follow_up_date,
                        'Video Thumbnail' => get_file($video->thumbnail),
                        'Video Type' => $video->type,
                        'Video' => $video->type == 'video_file' ? get_file($video->video) : $video->video,
                        'Video Description' => !empty ($video->description) ? $video->description : '',
                    ];
                } else {
                    $deal = Deal::find($video->item_id);

                    $pabbly_array = [
                        'Video Title' => $video->title,
                        'Video Module' => $video->module,
                        'Video Sub module' => $sub_module->sub_module,
                        'Deal Title' => $deal->name,
                        'Deal Price' => $deal->price,
                        'Deal Status' => $deal->status,
                        'Video Thumbnail' => get_file($video->thumbnail),
                        'Vide Type' => $video->type,
                        'Video' => $video->type == 'video_file' ? get_file($video->video) : $video->video,
                        'Video Description' => !empty ($video->description) ? $video->description : '',
                    ];
                }
            }

            if ($video->module == 'Product Service') {
                if ($sub_module->sub_module == 'Products') {
                    $product = ProductService::find($video->item_id);

                    $pabbly_array = [
                        'Video Title' => $video->title,
                        'Video Module' => $video->module,
                        'Video Sub module' => $sub_module->sub_module,
                        'Product Title' => $product->name,
                        'Product SKU' => $product->sku,
                        'Product Sale Price' => $product->sale_price,
                        'Product Purchase Price' => $product->purchase_price,
                        'Product Quantity' => $product->quantity,
                        'Video Thumbnail' => get_file($video->thumbnail),
                        'Vide Type' => $video->type,
                        'Video' => $video->type == 'video_file' ? get_file($video->video) : $video->video,
                        'Video Description' => !empty ($video->description) ? $video->description : '',
                    ];
                } else if ($sub_module->sub_module == 'Services') {
                    $service = ProductService::find($video->item_id);

                    $pabbly_array = [
                        'Video Title' => $video->title,
                        'Video Module' => $video->module,
                        'Video Sub Module' => $sub_module->sub_module,
                        'Service Title' => $service->name,
                        'Service SKU' => $service->sku,
                        'Service Sale Price' => $service->sale_price,
                        'Service Purchase Price' => $service->purchase_price,
                        'Video Thumbnail' => get_file($video->thumbnail),
                        'Vide Type' => $video->type,
                        'Video' => $video->type == 'video_file' ? get_file($video->video) : $video->video,
                        'Video Description' => !empty ($video->description) ? $video->description : '',
                    ];
                } else if ($sub_module->sub_module == 'Parts') {
                    $part = ProductService::find($video->item_id);

                    $pabbly_array = [
                        'Video Title' => $video->title,
                        'Video Module' => $video->module,
                        'Video Sub Module' => $sub_module->sub_module,
                        'Part Title' => $part->name,
                        'Part SKU' => $part->sku,
                        'Part Sale Price' => $part->sale_price,
                        'Part Purchase Price' => $part->purchase_price,
                        'Part Quantity' => $part->quantity,
                        'Video Thumbnail' => get_file($video->thumbnail),
                        'Vide Type' => $video->type,
                        'Video' => $video->type == 'video_file' ? get_file($video->video) : $video->video,
                        'Video Description' => !empty ($video->description) ? $video->description : '',
                    ];
                } else if ($sub_module->sub_module == 'Rent') {
                    $rent = ProductService::find($video->item_id);

                    $pabbly_array = [
                        'Video Title' => $video->title,
                        'Video Module' => $video->module,
                        'Video Sub Module' => $sub_module->sub_module,
                        'Rent Title' => $rent->name,
                        'Rent SKU' => $rent->sku,
                        'Sale Price' => $rent->sale_price,
                        'Purchase Price' => $rent->purchase_price,
                        'Quantity' => $rent->quantity,
                        'Video Thumbnail' => get_file($video->thumbnail),
                        'Vide Type' => $video->type,
                        'Video' => $video->type == 'video_file' ? get_file($video->video) : $video->video,
                        'Video Description' => !empty ($video->description) ? $video->description : '',
                    ];
                } else if ($sub_module->sub_module == 'Music Institute') {
                    $instrument = ProductService::find($video->item_id);

                    $pabbly_array = [
                        'Video Title' => $video->title,
                        'Video Module' => $video->module,
                        'Video Sub Module' => $sub_module->sub_module,
                        'Instrument Title' => $instrument->name,
                        'Instrument SKU' => $instrument->sku,
                        'Instrument Sale Price' => $instrument->sale_price,
                        'Instrument Purchase Price' => $instrument->purchase_price,
                        'Video Thumbnail' => get_file($video->thumbnail),
                        'Vide Type' => $video->type,
                        'Video' => $video->type == 'video_file' ? get_file($video->video) : $video->video,
                        'Video Description' => !empty ($video->description) ? $video->description : '',
                    ];
                }
            }

            if ($video->module == 'Project') {
                $project = Project::find($video->item_id);

                $pabbly_array = [
                    'Video Title' => $video->title,
                    'Video Module' => $video->module,
                    'Project Title' => $project->name,
                    'Project Status' => $project->status,
                    'Project Description' => $project->description,
                    'Project Start Date' => $project->start_date,
                    'Project End Date' => $project->end_date,
                    'Video Thumbnail' => get_file($video->thumbnail),
                    'Video Type' => $video->type,
                    'Video' => $video->type == 'video_file' ? get_file($video->video) : $video->video,
                    'Video Description' => !empty ($video->description) ? $video->description : '',
                ];
            }

            if ($video->module == 'Property Management') {
                $proprty = Property::find($video->item_id);

                $pabbly_array = [
                    'Video Title' => $video->title,
                    'Video Module' => $video->module,
                    'Property Type' => $proprty->name,
                    'Property Address' => $proprty->address,
                    'Property Country' => $proprty->country,
                    'Property State' => $proprty->state,
                    'Property City' => $proprty->city,
                    'Property Pincode' => $proprty->pincode,
                    'Property Latitude' => $proprty->latitude,
                    'Property Longitude' => $proprty->longitude,
                    'Video Thumbnail' => get_file($video->thumbnail),
                    'Video Type' => $video->type,
                    'Video' => $video->type == 'video_file' ? get_file($video->video) : $video->video,
                    'Video Description' => !empty ($video->description) ? $video->description : '',
                ];
            }

            if ($video->module == 'vCard' || $video->module == 'Contract' || $video->module == 'Appointment' || $video->module == 'Feedback' || $video->module == 'Sales Agent' || $video->module == 'Insurance Management' || $video->module == 'Rental Management' || $video->module == 'Custom Field' || $video->module == 'Assets' || $video->module == 'portfolio' || $video->module == 'Business Process Mapping') {
                $pabbly_array = [
                    'Video Title' => $video->title,
                    'Video Module' => $video->module,
                    'Video Thumbnail' => get_file($video->thumbnail),
                    'Video Type' => $video->type,
                    'Video' => $video->type == 'video_file' ? get_file($video->video) : $video->video,
                    'Video Description' => !empty ($video->description) ? $video->description : '',
                ];
            }

            $action = 'New Video';
            $module = 'VideoHub';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
