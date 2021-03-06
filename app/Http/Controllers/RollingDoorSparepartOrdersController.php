<?php

namespace App\Http\Controllers;
use App\User;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Models\RollingDoorSparepart;
use App\Http\Models\RollingDoorSparepartOrder;
use App\Http\Models\RollingDoorSparepartOrderDetail;
use App\Http\Models\Unit;

use Auth;
use PDF;

class RollingDoorSparepartOrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('rolling_door_sparepart_orders.index');
    }

    public function getAdd()
    {
        $option = RollingDoorSparepart::getDataAll();
        $data = [
                'option' => $option,
            ];
        return view('rolling_door_sparepart_orders.add', $data);
    }

    public function postAdd(Request $request)
    {
        $response = $request->all();
        $details = $response['order'];
        $array_check = array();

        if(!is_numeric($response['phone']))
        {
            flash('Nomor telepon harus angka')->error();
            return redirect()->route('rolling_door_sparepart_order');
        }

        foreach($details as $val)
        {
            if($val['rolling_door_sparepart_id'] == null)
            {
                continue;
            }
            if (in_array($val['rolling_door_sparepart_id'], $array_check)) {
                flash('Duplicate data found. Please try again')->error();
                return redirect()->route('rolling_door_sparepart_order');
            }
            else
            {
                $array_check[] = $val['rolling_door_sparepart_id'];
            }
        }

        $RollingDoorSparepartOrder = new RollingDoorSparepartOrder();
        $RollingDoorSparepartOrder->name = $response['name'];
        $RollingDoorSparepartOrder->date = date('Y-m-d', strtotime($response['date']));
        $RollingDoorSparepartOrder->address = $response['address'];
        $RollingDoorSparepartOrder->phone_number = $response['phone'];
        $RollingDoorSparepartOrder->delete_flag = 0;
        $RollingDoorSparepartOrder->created_by = Auth::user()->id;
        
        if($RollingDoorSparepartOrder->save()) 
        {
            $id = $RollingDoorSparepartOrder->id;
            $grand_total=0;
            foreach($details as $detail)
            {
                if($detail['rolling_door_sparepart_id']==null)
                {
                    continue;
                }
                $price = $detail['price'];
                $qty = $detail['qty']==null? 0 : $detail['qty'];
                $size = $detail['size']==null? 0 : $detail['size'];

                $RollingDoorSparepartOrderDetail = new RollingDoorSparepartOrderDetail();
                $RollingDoorSparepartOrderDetail->rolling_door_sparepart_order_id = $id;
                $RollingDoorSparepartOrderDetail->rolling_door_sparepart_id = $detail['rolling_door_sparepart_id'];
                $RollingDoorSparepartOrderDetail->price = $price;
                $RollingDoorSparepartOrderDetail->qty = $qty;
                $RollingDoorSparepartOrderDetail->size = $size;
                $subtotal = $price * $qty * $size;
                $grand_total += $subtotal;
                $RollingDoorSparepartOrderDetail->save();
            }
            $RollingDoorSparepartOrder::where('id', $id)
                                    ->update([
                                        'grand_total' => $grand_total
                                    ]);
            flash('Data berhasil disimpan')->success();
            return redirect()->route('rolling_door_sparepart_order');
        } else {
            flash('Data gagal disimpan')->error();
            return redirect()->route('rolling_door_sparepart_order');
        }
    }

    public function getEdit(Request $request)
    {
        $response = $request->all();
        if(!isset($response['id']))
        {
            flash('Data tidak ditemukan')->error();
            return redirect()->route('rolling_door_sparepart_order');
        }

        $parent = RollingDoorSparepartOrder::getData($response['id']);
        $child = RollingDoorSparepartOrderDetail::getData($response['id']);
        $option = RollingDoorSparepart::getDataAll();
        if(empty($parent))
        {
            flash('Data tidak ditemukan')->error();
            return redirect()->route('rolling_door_sparepart_order');
        }

        $data = [
                'parent' => $parent,
                'child' => $child,
                'option' => $option 
            ];

        return view('rolling_door_sparepart_orders.edit', $data);
    }


    public function postEdit(Request $request) {
        $response = $request->all();
        $details = $response['order'];
        $id = $response['id'];
        $array_check = array();
        
        if(!is_numeric($response['phone']))
        {
            flash('Nomor telepon harus angka')->error();
            return redirect()->route('rolling_door_sparepart_order');
        }

        foreach($details as $val)
        {
            if($val['rolling_door_sparepart_id'] == null)
            {
                continue;
            }
            if (in_array($val['rolling_door_sparepart_id'], $array_check)) {
                flash('Duplicate data found. Please try again')->error();
                return redirect()->route('rolling_door_sparepart_order');
            }
            else
            {
                $array_check[] = $val['rolling_door_sparepart_id'];
            }
        }
        $RollingDoorSparepartOrder = new RollingDoorSparepartOrder();
        $save = $RollingDoorSparepartOrder::where('id', $id)
                                    ->update([
                                        'name' => $response['name'],
                                        'date' => date('Y-m-d', strtotime($response['date'])),
                                        'address'  => $response['address'],
                                        'phone_number'  => $response['phone']
                                    ]);
        
        if($save) 
        {
            RollingDoorSparepartOrderDetail::where('rolling_door_sparepart_order_id', $id)->delete();
            $grand_total=0;
            foreach($details as $detail)
            {
                if($detail['rolling_door_sparepart_id']==null)
                {
                    continue;
                }
                $price = $detail['price'];
                $qty = $detail['qty']==null? 0 : $detail['qty'];
                $size = $detail['size']==null? 0 : $detail['size'];

                $RollingDoorSparepartOrderDetail = new RollingDoorSparepartOrderDetail();
                $RollingDoorSparepartOrderDetail->rolling_door_sparepart_order_id = $id;
                $RollingDoorSparepartOrderDetail->rolling_door_sparepart_id = $detail['rolling_door_sparepart_id'];
                $RollingDoorSparepartOrderDetail->price = $price;
                $RollingDoorSparepartOrderDetail->qty = $qty;
                $RollingDoorSparepartOrderDetail->size = $size;
                $subtotal = $price * $qty * $size;
                $grand_total += $subtotal;
                $RollingDoorSparepartOrderDetail->save();
            }
            $RollingDoorSparepartOrder::where('id', $id)
                                    ->update([
                                        'grand_total' => $grand_total
                                    ]);
            flash('Data berhasil diupdate')->success();
            return redirect()->route('rolling_door_sparepart_order');
        } else {
            flash('Data gagal diupdate')->error();
            return redirect()->route('rolling_door_sparepart_order');
        }
    }

    public function getDelete(Request $request)
    {
        $response = $request->all();
        if(!isset($response['id']))
        {
            flash('Data tidak ditemukan')->error();
            return redirect()->route('rolling_door_sparepart_order');
        }

        $parent = RollingDoorSparepartOrder::getData($response['id']);
        $child = RollingDoorSparepartOrderDetail::getData($response['id']);
        $option = RollingDoorSparepart::getDataAll();
        if(empty($parent))
        {
            flash('Data tidak ditemukan')->error();
            return redirect()->route('rolling_door_sparepart_order');
        }

        $data = [
                'parent' => $parent,
                'child' => $child,
                'option' => $option 
            ];

        return view('rolling_door_sparepart_orders.delete', $data);
    }

    public function postDelete(Request $request)
    {
        $respons = $request->all();
        $id = $respons['id'];

        $save = RollingDoorSparepartOrder::where('id', $id)
                                    ->update([
                                        'delete_flag' => 1
                                    ]);

        if($save) {
            flash('Data berhasi dihapus')->success();
            return redirect()->route('rolling_door_sparepart_order');
        } else {
            flash('Data gagal dihapus')->error();
            return redirect()->route('rolling_door_sparepart_order');
        }
    }

    public function getView(Request $request)
    {
        $response = $request->all();
        if(!isset($response['id']))
        {
            flash('Data tidak ditemukan')->error();
            return redirect()->route('rolling_door_sparepart_order');
        }

        $parent = RollingDoorSparepartOrder::getData($response['id']);
        $child = RollingDoorSparepartOrderDetail::getData($response['id']);
        $option = RollingDoorSparepart::getDataAll();
        if(empty($parent))
        {
            flash('Data tidak ditemukan')->error();
            return redirect()->route('rolling_door_sparepart_order');
        }

        $data = [
                'parent' => $parent,
                'child' => $child,
                'option' => $option 
            ];

        return view('rolling_door_sparepart_orders.view', $data);
    }


    public function getDatatables()
    {
        return Datatables::of(RollingDoorSparepartOrder::getDataTable())->make(true);
    }

    public function getPrint(Request $request)
    {
        $response = $request->all();
        $id = $response['id'];
        $header = RollingDoorSparepartOrder::getData($id);
        $child = RollingDoorSparepartOrderDetail::getData($id);
        $header['order_number'] = 'PART-RD-'.$header['id'];
        $header['date'] = date('d-m-Y', strtotime($header['date']));
        $header['grand_total'] = number_format($header['grand_total']);

        $data = [
            'header' => $header,
            'child' => $child
        ];
        $pdf = PDF::loadView('pdf.document', $data);
        return $pdf->stream('document.pdf');
    }
}
