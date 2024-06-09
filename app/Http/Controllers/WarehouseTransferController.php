<?php

namespace App\Http\Controllers;



use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pos\Entities\Pos;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Models\WarehouseTransfer;


class WarehouseTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $warehouse_transfers = WarehouseTransfer::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->with('fromWarehouse','toWarehouse','product')->get();

        return view('warehouses-transfer.index',compact('warehouse_transfers'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $from_warehouses      = Warehouse ::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();
        $to_warehouses     = warehouse::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
        $to_warehouses->prepend('Select Warehouse', '');
        $ware_pro= WarehouseProduct::join('product_services', 'warehouse_products.product_id', '=', 'product_services.id')
            ->pluck('name','product_id');
        $ware_pro->prepend('Select products', '');
        return view('warehouses-transfer.create', compact('from_warehouses','to_warehouses','ware_pro'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        if(\Auth::user()->isAbleTo('warehouse create'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'from_warehouse' => 'required',
                    'to_warehouse' => 'required',
                    'product_id' => 'required',
                    'quantity' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $fromWarehouse    = WarehouseProduct::where('warehouse_id',$request->from_warehouse)
                ->where('product_id',$request->product_id)->first();

            if($request->quantity <= $fromWarehouse->quantity)
            {
                $warehouse_transfer                  = new WarehouseTransfer();
                $warehouse_transfer->from_warehouse  = $request->from_warehouse;
                $warehouse_transfer->to_warehouse    = $request->to_warehouse;
                $warehouse_transfer->product_id      = $request->product_id;
                $warehouse_transfer->quantity        = $request->quantity;
                $warehouse_transfer->date            = $request->date;
                $warehouse_transfer->workspace       = getActiveWorkSpace();
                $warehouse_transfer->created_by      = creatorId();
                $warehouse_transfer->save();
            }
            else
            {
                return redirect()->route('warehouses-transfer.index')->with('error', __('Product out of stock!.'));
            }
            WarehouseTransfer::warehouse_transfer_qty($request->from_warehouse,$request->to_warehouse,$request->product_id,$request->quantity);

            return redirect()->route('warehouses-transfer.index')->with('success', __('Warehouse Transfer successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getproduct(Request $request)
    {
        if($request->warehouse_id == 0)
        {
            $ware_products= WarehouseProduct::join('product_services', 'warehouse_products.product_id', '=', 'product_services.id')
                ->get()
                ->pluck('name', 'product_id')->toArray();
            $to_warehouses     = warehouse::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->pluck('name', 'id');
        }
        else
        {
            $ware_products= WarehouseProduct::join('product_services', 'warehouse_products.product_id', '=', 'product_services.id')
                ->where('warehouse_products.warehouse_id', $request->warehouse_id)
                ->get()
                ->pluck('name', 'product_id')->toArray();
            $to_warehouses     = warehouse::where('id','!=',$request->warehouse_id)->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->pluck('name', 'id');
        }
        $result = [];
        $result['ware_products'] = $ware_products;
        $result['to_warehouses'] = $to_warehouses;
        return response()->json($result);
    }

    public function getquantity(Request $request)
    {
        if($request->product_id == 0)
        {
            $pro_qty = WarehouseProduct::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())
                ->get()->pluck('quantity', 'product_id')->toArray();
        }
        else
        {
            $pro_qty = WarehouseProduct::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())
                ->where('product_id', $request->product_id)
                ->get()->pluck('quantity');
        }
        return response()->json($pro_qty);
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->route('warehouses-transfer.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('pos::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */


    public function destroy($id)
    {
        if(\Auth::user()->isAbleTo('warehouse delete'))
        {
            $warehouseTransfer = WarehouseTransfer::find($id);
            if($warehouseTransfer->created_by == creatorId()  && $warehouseTransfer->workspace == getActiveWorkSpace())
            {
                WarehouseTransfer::warehouse_transfer_qty($warehouseTransfer->to_warehouse,$warehouseTransfer->from_warehouse,$warehouseTransfer->product_id,$warehouseTransfer->quantity,'delete');

                $warehouseTransfer->delete();

                return redirect()->route('warehouses-transfer.index')->with('success', __('Warehouse Transfer successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
