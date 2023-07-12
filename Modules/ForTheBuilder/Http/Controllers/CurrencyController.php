<?php

namespace Modules\ForTheBuilder\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ForTheBuilder\Entities\Currency;
use Modules\ForTheBuilder\Entities\Notification_;

class CurrencyController extends Controller
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
        $model = Currency::first();
        return view('forthebuilder::currency.index')->with([
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
        $model = Currency::first();
        return view('forthebuilder::currency.create', [
            'model' => $model,
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $model = Currency::findOrFail($id);
        if ($request->attr == 'USD')
            $model->USD = $request->val;
        else
            $model->SUM = $request->val;

        $model->save();

        return true;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $model = Currency::first();
        if ($model) {
            $model->USD = $request->USD;
            $model->SUM = $request->sum_uzb;
        } else {
            $model = new Currency();
            $model->USD = $request->USD;
            $model->SUM = $request->sum_uzb;
        }
        $model->save();
        return redirect()->route('forthebuilder.currency.index');
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy()
    {
        $models = Currency::all();
        foreach ($models as $model) {
            $model->delete();
        }
        return redirect()->route('forthebuilder.currency.index');
    }
}
