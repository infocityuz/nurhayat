<?php

namespace Modules\ForTheBuilder\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ForTheBuilder\Entities\Coupon;
use Modules\ForTheBuilder\Entities\Notification_;
use Modules\ForTheBuilder\Http\Requests\CouponRequest;

class CouponContoller extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function getNotification(){
        $notification = ['Booking', 'BookingPrepayment'];
        $all_task = Notification_::where('type', 'Task')->where(['read_at' => NULL,  'user_id' => Auth::user()->id])->orderBy('created_at', 'desc')->get();
        $all_booking = Notification_::whereIn('type', $notification)->where('read_at', NULL)->orderBy('created_at', 'desc')->get();
        $all_installment_plan = Notification_::where('type', 'Installment_plan')->where('read_at', NULL)->orderBy('created_at', 'desc')->get();
        return ['all_task' => $all_task, 'all_booking' => $all_booking, 'all_installment_plan' => $all_installment_plan];
    }

    public function index()
    {
        $model = Coupon::orderBy('id', 'desc')->paginate(config('params.pagination'));
        return view('forthebuilder::coupon.index')->with([
            'model' => $model,
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('forthebuilder::coupon.create', [
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CouponRequest $request)
    {
        $data = $request->validated();

        $model = new Coupon();
        $model->name = $data['name'];
        $model->percent = $data['percent'];
        if ($model->save())
            return true;

        // return redirect()->route('forthebuilder.coupon.index')->with('success', __('locale.successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $model = Coupon::find($id);
        return view('forthebuilder::coupon.edit')->with([
            'model' => $model,
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CouponRequest $request, $id)
    {
        $data = $request->validated();

        $model = Coupon::find($id);
        $model->name = $data['name'];
        $model->percent = $data['percent'];
        if ($model->save())
            return true;

        // return redirect()->route('forthebuilder.coupon.index')->with('success', __('locale.successfully'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $model = Coupon::find($id);
        $model->delete();
        return redirect()->route('forthebuilder.coupon.index')->with('deleted', translate('Data deleted successfuly'));
    }
}
