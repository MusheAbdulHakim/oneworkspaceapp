<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'city_zip',
        'workspace',
        'created_by',
    ];

    public static function warehouse_id($warehouse_name)
    {
        $warehouse = Warehouse::where(['id'=>$warehouse_name, 'created_by'=> creatorId(), 'workspace' => getActiveWorkSpace() ])->first();
        return $warehouse->id;
    }

    public static function defaultdata($company_id = null,$workspace_id = null)
    {
        $company_setting = [
            "purchase_prefix" => "#PUR",
            "pos_prefix" => "#POS",
            "low_product_stock_threshold" => "1",
            "purchase_template" => "template1",
            "pos_template" => "template1",
        ];
        $default_warehouses = [
            0=>[
                'name' => 'North Warehouse',
                'address' => '723 N. Tillamook Street Portland, OR Portland, United States',
                'city' => 'Portland',
                'city_zip' => 97227,
                'created_by' => 2,
            ],
        ];
        if($company_id == Null)
        {
            $companys = User::where('type','company')->get();
            foreach($companys as $company)
            {
                $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
                foreach($WorkSpaces as $WorkSpace)
                {

                    foreach ($company_setting as $key => $value) {
                        // Define the data to be updated or inserted
                        $data = [
                            'key' => $key,
                            'workspace' => !empty($WorkSpace->id) ? $WorkSpace->id : 0,
                            'created_by' => $company->id,
                        ];

                        // Check if the record exists, and update or insert accordingly
                        Setting::updateOrInsert($data, ['value' => $value]);
                    }
                    foreach($default_warehouses as $default_warehouse)
                    {
                        $warehouse= Warehouse::where('created_by',$company_id)->where('workspace',$WorkSpace->id)->first();
                        if($warehouse==null){
                            $new = new Warehouse();
                            $new->name = $default_warehouse['name'];
                            $new->address = $default_warehouse['address'];
                            $new->city = $default_warehouse['city'];
                            $new->city_zip = $default_warehouse['city_zip'];
                            $new->created_by = !empty($company->id) ? $company->id : 2;
                            $new->workspace = !empty($WorkSpace->id) ? $WorkSpace->id : 0 ;
                            $new->save();
                        }
                    }
                    if(\Auth::check())
                    {
                    // Settings Cache forget
                    comapnySettingCacheForget();
                    }
                }
            }
        }elseif($workspace_id == Null){
            $company = User::where('type','company')->where('id',$company_id)->first();
            $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
            foreach($WorkSpaces as $WorkSpace)
            {

                foreach ($company_setting as $key => $value) {
                    // Define the data to be updated or inserted
                    $data = [
                        'key' => $key,
                        'workspace' => !empty($WorkSpace->id) ? $WorkSpace->id : 0,
                        'created_by' => $company->id,
                    ];

                    // Check if the record exists, and update or insert accordingly
                    Setting::updateOrInsert($data, ['value' => $value]);
                }
                foreach($default_warehouses as $default_warehouse)
                {
                    $warehouse= Warehouse::where('created_by',$company_id)->where('workspace',$WorkSpace->id)->first();
                    if($warehouse==null){
                        $new = new Warehouse();
                        $new->name = $default_warehouse['name'];
                        $new->address = $default_warehouse['address'];
                        $new->city = $default_warehouse['city'];
                        $new->city_zip = $default_warehouse['city_zip'];
                        $new->created_by = !empty($company->id) ? $company->id : 2;
                        $new->workspace = !empty($WorkSpace->id) ? $WorkSpace->id : 0 ;
                        $new->save();
                    }
                }
                if(\Auth::check())
                {
                // Settings Cache forget
                comapnySettingCacheForget();
                }
            }
        }else{
            $company = User::where('type','company')->where('id',$company_id)->first();
            $WorkSpace = WorkSpace::where('created_by',$company->id)->where('id',$workspace_id)->first();

            foreach ($company_setting as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => !empty($WorkSpace->id) ? $WorkSpace->id : 0,
                    'created_by' => $company->id,
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }
            foreach($default_warehouses as $default_warehouse)
            {
                $warehouse= Warehouse::where('created_by',$company_id)->where('workspace',$WorkSpace->id)->first();
                if($warehouse==null){
                    $new = new Warehouse();
                    $new->name = $default_warehouse['name'];
                    $new->address = $default_warehouse['address'];
                    $new->city = $default_warehouse['city'];
                    $new->city_zip = $default_warehouse['city_zip'];
                    $new->created_by = !empty($company->id) ? $company->id : 2;
                    $new->workspace = !empty($WorkSpace->id) ? $WorkSpace->id : 0 ;
                    $new->save();
                }
            }
            if(\Auth::check())
            {
            // Settings Cache forget
            comapnySettingCacheForget();
            }
        }
    }

}
