<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\AddOn;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::check())
        {
            return redirect('dashboard');
        }
        else
        {
            if(!file_exists(storage_path() . "/installed"))
            {
                header('location:install');
                die;
            }
            else
            {
                if(admin_setting('landing_page') == 'on')
                {
                    if(module_is_active('LandingPage'))
                    {
                        return view('landingpage::layouts.landingpage');
                    }
                    else
                    {
                        return view('marketplace.landing');
                    }
                }
                else
                {
                    return redirect('login');
                }
            }
        }
    }

    public function Dashboard()
    {
        if(Auth::check())
        {
            if(Auth::user()->type == 'super admin')
            {
                $user                       = Auth::user();
                $user['total_user']         = $user->countCompany();
                $user['total_paid_user']    = $user->countPaidCompany();
                $user['total_orders']       = Order::total_orders();
                $user['total_orders_price'] = Order::total_orders_price();
                $chartData                  = $this->getOrderChart(['duration' => 'week']);
                $user['total_plans'] = Plan::all()->count();

                $popular_plan = DB::table('orders')
                ->select('orders.plan_id', 'plans.*', DB::raw('count(*) as count'))
                ->join('plans', 'orders.plan_id', '=', 'plans.id')
                ->groupBy('orders.plan_id')
                ->orderByDesc('count')
                ->first();

                $user['popular_plan'] = $popular_plan;

                return view('dashboard.dashboard', compact('user', 'chartData'));
            }
            else
            {
                $user = auth()->user();

                $menu = new \App\Classes\Menu($user);
                event(new \App\Events\CompanyMenuEvent($menu));
                $menu_items = $menu->menu;
                $dashboardItem = collect($menu_items)->first(function ($item) {
                    return $item['parent'] === 'dashboard';
                });

                if ($dashboardItem) {
                    $route = isset($dashboardItem['route']) ? $dashboardItem['route'] : null;
                    if($route)
                    {
                        return redirect()->route($route);
                    }
                }
                return view('dashboard');
            }
        }
        else
        {
            return redirect()->route('start');
        }
    }

    public function getOrderChart($arrParam)
    {
        $arrDuration = [];
        if($arrParam['duration'])
        {
            if($arrParam['duration'] == 'week')
            {
                $previous_week = strtotime("-2 week +1 day");
                for($i = 0; $i < 14; $i++)
                {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }
        // $arrTask          = [];
        // $arrTask['label'] = [];
        // $arrTask['data']  = [];
        // foreach($arrDuration as $date => $label)
        // {
        //     $data               = Order::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
        //     $arrTask['label'][] = $label;
        //     $arrTask['data'][]  = $data->total;
        // }
        // return $arrTask;

        // Create an array of dates from your $arrDuration array
        $dates = array_keys($arrDuration);

        $orders = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->whereIn(DB::raw('DATE(created_at)'), $dates)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();
        // Initialize an empty $arrTask array
        $arrTask = ['label' => [], 'data' => []];

        foreach ($dates as $date) {
            $label = $arrDuration[$date];
            $total = 0;

            foreach ($orders as $item) {
                if ($item->date == $date) {
                    $total = $item->total;
                    break;
                }
            }

            $arrTask['label'][] = $label;
            $arrTask['data'][] = $total;
        }
        return $arrTask;
    }
    public function SoftwareDetails($slug)
    {
        $modules_all = Module::getByStatus(1);
        $modules = [];
        if(count($modules_all) > 0)
        {
            $modules = array_intersect_key(
                $modules_all,  // the array with all keys
                array_flip(array_rand($modules_all,(count($modules_all) <  6) ? count($modules_all) : 6 )) // keys to be extracted
            );
        }
        $plan = Plan::first();
        $addon = AddOn::where('name',$slug)->first();
        if(!empty($addon) && !empty($addon->module))
        {
            $module = Module::find($addon->module);
            if(!empty($module))
            {
                try {
                    if(module_is_active('LandingPage'))
                    {
                        return view('landingpage::marketplace.index',compact('modules','module','plan'));
                    }
                    else{
                        return view($module->getLowerName().'::marketplace.index',compact('modules','module','plan'));
                    }
                } catch (\Throwable $th) {

                }
            }
        }

        if (module_is_active('LandingPage')) {
            $layout = 'landingpage::layouts.marketplace';
        } else {
            $layout = 'marketplace.marketplace';
        }

        return view('marketplace.detail_not_found',compact('modules','layout'));

    }

    public function Software(Request $request)
    {
        // Get the query parameter from the request
        $query = $request->query('query');
        // Get all modules (assuming Module::getByStatus(1) returns all modules)
        $modules = Module::getByStatus(1);

        // Filter modules based on the query parameter
        if ($query) {
            $modules = array_filter($modules, function ($module) use ($query) {
                // You may need to adjust this condition based on your requirements
                return stripos($module->getName(), $query) !== false;
            });
        }
        // Rest of your code
        if (module_is_active('LandingPage')) {
            $layout = 'landingpage::layouts.marketplace';
        } else {
            $layout = 'marketplace.marketplace';
        }

        return view('marketplace.software', compact('modules', 'layout'));
    }

    public function Pricing()
    {
        $admin_settings = getAdminAllSetting();
        if(module_is_active('GoogleCaptcha') && (isset($admin_settings['google_recaptcha_is_on']) ? $admin_settings['google_recaptcha_is_on'] : 'off') == 'on' )
        {
            config(['captcha.secret' => isset($admin_settings['google_recaptcha_secret']) ? $admin_settings['google_recaptcha_secret'] : '']);
            config(['captcha.sitekey' => isset($admin_settings['google_recaptcha_key']) ? $admin_settings['google_recaptcha_key'] : '']);
        }
        if(Auth::check())
        {
            if(Auth::user()->type == 'company')
            {
                return redirect('plans');
            }
            else
            {
                return redirect('dashboard');
            }
        }
        else
        {
            $plan = Plan::first();
            $modules = Module::getByStatus(1);

            if (module_is_active('LandingPage')) {
                $layout = 'landingpage::layouts.marketplace';
                return view('landingpage::layouts.pricing',compact('modules','plan','layout'));

            } else {
                $layout = 'marketplace.marketplace';
            }

            return view('marketplace.pricing',compact('modules','plan','layout'));
        }
    }

    public function CustomPage(Request $request)
    {
        $modules = Module::getByStatus(1);

        if (module_is_active('LandingPage')) {
            $layout = 'landingpage::layouts.marketplace';
        } else {
            $layout = 'marketplace.marketplace';
        }
        if($request['page'] == 'terms_and_conditions' || $request['page'] == 'privacy_policy')
        {
            return view('custompage.'.$request['page'],compact('modules','layout'));
        }
        else
        {
            return view('marketplace.detail_not_found',compact('modules','layout'));
        }

    }

}
