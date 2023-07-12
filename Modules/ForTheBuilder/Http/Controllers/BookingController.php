<?php

namespace Modules\ForTheBuilder\Http\Controllers;

use App\Models\Notifications;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Modules\ForTheBuilder\Entities\Booking;
use Modules\ForTheBuilder\Entities\Clients;
use Modules\ForTheBuilder\Entities\House;
use Modules\ForTheBuilder\Entities\HouseFlat;
use Modules\ForTheBuilder\Entities\Leads;
use Modules\ForTheBuilder\Entities\Notification_;
use Modules\ForTheBuilder\Entities\Notifications_;
use Modules\ForTheBuilder\Events\NotificationEvent;
use Modules\ForTheBuilder\Events\RealTimeMessage;
use Modules\ForTheBuilder\Http\Requests\BookingRequest;
use Modules\ForTheBuilder\Notifications\BeforePrepayment;
use Modules\ForTheBuilder\Notifications\BookingNotification;
use Modules\ForTheBuilder\Notifications\TaskNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Modules\ForTheBuilder\Entities\Constants;
use Modules\ForTheBuilder\Entities\Deal;
use Modules\ForTheBuilder\Entities\PersonalInformations;
use App\Utils\Paginate;


class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function getNotification()
    {
        $notification = ['Booking', 'BookingPrepayment'];
        $all_task = Notification_::where('type', 'Task')->where(['read_at' => NULL,  'user_id' => Auth::user()->id])->orderBy('created_at', 'desc')->get();
        $all_booking = Notification_::whereIn('type', $notification)->where('read_at', NULL)->orderBy('created_at', 'desc')->get();
        $all_installment_plan = Notification_::where('type', 'Installment_plan')->where('read_at', NULL)->orderBy('created_at', 'desc')->get();
        return ['all_task' => $all_task, 'all_booking' => $all_booking, 'all_installment_plan' => $all_installment_plan];
    }

    public function index()
    {
        // $data=DB::table('forthebiulder.booking')->get();
        // dd($data);
        $user=Auth::user();
        // $user->role_id==Constants::MANAGER
        if ($user->role_id==Constants::MANAGER) {
            $booking = Booking::where('user_id',$user->id)->paginate(15);
        }else {
            $booking = Booking::paginate(15);
        }
        
        // $list = $this->paginate($list, 10);
        // $list->path('');
        // $models = Paginate::paginate($list,1);
        // $users(array $list);

        // // $users = $list->toArray();
        // $users = User::all()->toArray();
        // $users = Paginate::paginate($users,3);
        // $users = Paginate::paginate((array $list),1);
        // $list->path('/?page=2');
        // dd($users);

        // event(new NotificationEvent('hello world!'));

        date_default_timezone_set("Asia/Tashkent");
        return view('forthebuilder::booking.index', compact('booking') , ['all_notifications' => $this->getNotification()]);
    }
    public function bookingApi()
    {
        $user = Auth::user();
        $models = Booking::all();
        $response = [];
        if (!empty($models)) {
            foreach ($models as $model) {
                $expire_date = json_decode($model->expire_dates);
                if(isset($model->notification)){
                    $response[] = [
                        'id' => $model->id,
                        'first_name' => $model->clients->first_name,
                        'last_name' => $model->clients->last_name,
                        'is_notify' => $model->notification->is_notify ?? NULL,
                        'is_notify_before' => $model->notification->is_notify_before ?? NULL,
                        'expire_dates' => strtotime(end($expire_date)->date),
                        'notification_date' => strtotime($model->notification_date),
                    ];
                }else{
                    $response[] = [];
                }
            }
        } else {
            $response[] = [];
        }

        return response()->json($response);
    }

    public function TheDayBeforeNotification($id)
    {
        $model = Booking::find($id);
        $user = Auth::user();
        $notifications = Notifications_::where(['relation_id'=> $id, 'relation_type' =>NULL, 'user_id'=>$user->id])->first();
        if(isset($notifications->id)){
            $notification = new Notification_();
            $expire_date = json_decode($model->expire_dates);
            $notifications->is_notify_before = 1;
            $notifications->save();
            $booking_array = [
                'id' => $model->id,
                'first_name' => $model->clients->first_name,
                'last_name' => $model->clients->last_name,
                'middle_name' => $model->clients->middle_name,
                'expire_dates' => strtotime(end($expire_date)->date),
                'updated_at' => $model->updated_at
            ];
            $notification->data = json_encode($booking_array);
            $notification->notifiable_id = $model->id;
            $notification->type = 'BookingPrepayment';
            $notification->save();
            return response()->json($notification);
        }else{
            return response()->json('no');
        }
    }

    public function bookingNotification($id)
    {
        $model = Booking::find($id);
        $user = Auth::user();
        $notifications = Notifications_::where(['relation_id' => $id, 'relation_type' => NULL, 'user_id'=>$user->id])->first();
        if(isset($notifications->id)){
            $notification = new Notification_();
            $notifications->is_notify = 1;
            $expire_date = json_decode($model->expire_dates);
            $notifications->save();
            $booking_array = [
                'id' => $model->id,
                'first_name' => $model->clients->first_name,
                'last_name' => $model->clients->last_name,
                'middle_name' => $model->clients->middle_name,
                'expire_dates' => strtotime(end($expire_date)->date),
                'updated_at' => $model->updated_at
            ];
            $notification->data = json_encode($booking_array);
            $notification->notifiable_id = $model->id;
            $notification->type = 'Booking';
            $notification->save();
            return response()->json($notification);
        }else{
            return response()->json('no');
        }
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $booking = Booking::where(['house_flat_id' => $request->house_flat_id, 'status' => Constants::BOOKING_ACTIVE])->first();
        // $booking_any = House::first();
        // $house_flat = HouseFlat::find($request->house_flat_id);
        date_default_timezone_set("Asia/Tashkent");
        if ($booking) {
            return redirect()->back()->with('warning', translate('This apartment had been booked by another person'));
        } else {
            DB::beginTransaction();
            try {
                // dd('22222');
                $client_id = $request->client_id;
                if (isset($request->client_id) && $request->client_id != null && $request->client_id != 'null') {
                    $existPersonalInfo = PersonalInformations::where(['client_id' => $request->client_id, 'series_number' => $request->series_number])->first();

                    if (isset($existPersonalInfo)) {
                        $existClient = Clients::find($request->client_id);
                        $existClient->first_name = $request->first_name;
                        $existClient->last_name = $request->last_name;
                        $existClient->middle_name = $request->middle_name;
                        $existClient->phone = $request->phone;
                        $existClient->additional_phone = $request->additional_phone;
                        $existClient->save();
                        $client_id = $existClient->id;
                    }
                } else {
                    $newClient = new Clients();
                    $newClient->user_id = $user->id;
                    $newClient->first_name = $request->first_name;
                    $newClient->last_name = $request->last_name;
                    $newClient->middle_name = $request->middle_name;
                    $newClient->phone = $request->phone;
                    $newClient->additional_phone = $request->additional_phone;
                    $newClient->status = Constants::CLIENT_ACTIVE;
                    $newClient->save();

                    $client_id = $newClient->id;
                    if (isset($request->series_number)) {
                        $newPersonalInfo = new PersonalInformations();
                        $newPersonalInfo->client_id = $newClient->id;
                        $newPersonalInfo->series_number = $request->series_number;
                        $newPersonalInfo->save();
                    }
                }

                $newDeal = new Deal();
                $newDeal->user_id = $user->id;
                $newDeal->house_flat_id = $request->house_flat_id;
                $newDeal->house_id = $request->house_house_id;
                $newDeal->client_id = $client_id;
                $newDeal->date_deal = date('Y-m-d H:i:s');
                $newDeal->type = Constants::NEGOTIATION;
                $newDeal->history = json_encode([['date' => date('Y-m-d H:i:s'), 'user' => $user->first_name, 'user_id' => $user->id, 'user_photo' => $user->avatar, 'new_type' => 'Negotiation', 'old_type' => NULL]]);
                $newDeal->status = Constants::ACTIVE;
                $newDeal->save();

                // dd('1111');
                $model = new Booking();
                $model->user_id = $user->id;
                $model->client_id = $client_id;
                $model->house_flat_id = $request->house_flat_id;
                $model->house_id = $request->house_house_id;
                $model->deal_id = $newDeal->id;
                $model->status = Constants::BOOKING_ACTIVE;
                $model->expire_dates = json_encode([['comment' => '', 'date' => date('Y-m-d', strtotime('+5 days'))]]);
                $model->notification_date = date('Y-m-d H:i:s', strtotime('+4 days'));
                $model->prepayment = ($request->prepayment) ? $request->prepayment_summa : 0;
                $model->save();

                $notifications = new Notifications_();
                $notifications->relation_id = $model->id;
                $notifications->user_id = $user->id;
                $notifications->save();

                $house_flat = HouseFlat::find($request->house_flat_id);
                $house_flat->status = Constants::STATUS_BOOKING;
                $time = time()-1;
                $house_flat->free_end = date("Y-m-d H:i:s", $time);
                $house_flat->booking_start = date("Y-m-d H:i:s");
                $house_flat->booking_end = '9999-12-31 23:59:59';
                $house_flat->save();
                // dd($house_flat);
                DB::commit();
                return redirect()->route('forthebuilder.booking.index')->with('success', __('locale.Prepayment has been added'));
            } catch (\Exception $e) {
                dd($e->getMessage());
                DB::rollback();
                return $e->getMessage();
            }
        }
    }

    public function bookingPeriodUpdate(Request $request)
    {

        // dd($request->all());
        // return redirect()->back();
        date_default_timezone_set("Asia/Tashkent");

        $booking = Booking::where('id', $request->booking_id)->first();


        $datetime = $request->date_deal;
        $encode = json_decode($booking->expire_dates);

        $encode[] = ['comment' => '', 'date' => $request->date_deal];
        // dd(json_encode($encode));

        $booking->expire_dates = json_encode($encode);
        $booking->prepayment = $request->prepayment_summa;
        $booking->notification_date = Carbon::parse($datetime)->addDays(-1);

        // return $booking->notification_date;
        $booking->save();
        return redirect()->route('forthebuilder.booking.index')->with('success', __('locale.Prepayment has been added'));
    }

    public function extend($booking_id, $notification_id)
    {
        date_default_timezone_set("Asia/Tashkent");
        $model = Booking::find($booking_id);
        $model->notification_date = strtotime('+4 days');
        $old_expire_date = json_decode($model->expire_date);
        $new_expire_date = [['comment' => '', 'date' => date('Y-m-d h:m:s', strtotime('+5 days'))]];
        $expire_date = array_push($old_expire_date, $new_expire_date);
        $model->expire_date = json_encode($expire_date);
        $notification = Notifications_::where(['relation_id' => $booking_id, 'relation_type' => NULL])->first();
        $notification->is_notify = NULL;
        $notification->is_read = NULL;
        $notification->is_read_before = NULL;
        $notification->is_notify_before = NULL;
        $notification->save();
        $model->save();
        //        $notify = Notifications::find($notification_id);
        //        $notify->read_at = NULL;
        //        $notify->save();
        return redirect()->back()->with('success', __('locale.Prepayment has been updated'));
    }

    public function extendBooking(Request $request)
    {
        // return 'came';
        date_default_timezone_set("Asia/Tashkent");
        $model = Booking::find($request->booking_id);
        $model->notification_date = strtotime('+4 days');
        $old_expire_date = json_decode($model->expire_date);
        $new_expire_date = [['comment' => '', 'date' => date('Y-m-d h:m:s', strtotime('+5 days'))]];
        $expire_date = array_push($old_expire_date, $new_expire_date);
        $model->expire_date = json_encode($expire_date);
        $notification = Notifications_::where(['relation_id' => $request->booking_id, 'relation_type' => NULL])->first();
        $notification->is_notify = NULL;
        $notification->is_read = NULL;
        $notification->is_read_before = NULL;
        $notification->is_notify_before = NULL;
        $notification->save();
        $model->save();
        return redirect()->back()->with('success', __('locale.Booking extended'));
    }

    public function finishBooking(Request $request)
    {
        date_default_timezone_set("Asia/Tashkent");
        $model = Booking::find($request->booking_id);
        $model->notification_date = strtotime('-4 days');
        $old_expire_date = json_decode($model->expire_date);
        $new_expire_date = [['comment' => '', 'date' => date('Y-m-d h:m:s', strtotime('-5 days'))]];
        $expire_date = array_push($old_expire_date, $new_expire_date);
        $model->expire_date = json_encode($expire_date);
        $notification = Notifications_::where('relation_id', $request->booking_id)->first();
        $notification->is_notify = 1;
        $notification->is_read = 1;
        $notification->is_read_before = 1;
        $notification->is_notify_before = 1;
        $notification->save();
        $model->save();
        return redirect()->back()->with('success', __('locale.Booking finished'));
    }

    public function read($booking_id, $notification_id)
    {
        date_default_timezone_set("Asia/Tashkent");
        $model = Notifications_::where('relation_id', $booking_id)->first();
        $notify = Notifications::find($notification_id);
        if (isset($model)) {
            $house = HouseFlat::find($model->house_flat_id);
            if (strtotime('now') > $model->expire_date) {
                $house->status = 0;
                $house->save();
            }
            $model->read = 1;
            $model->save();
        }
        $notify->read_at = date("y-m-d h:i:s", time());
        $notify->save();
        return redirect()->back();
    }

    public function readBefore($booking_id, $notification_id)
    {
        date_default_timezone_set("Asia/Tashkent");
        $model = Notifications_::where('relation_id', $booking_id)->first();
        $notify = Notifications::find($notification_id);
        if (isset($model)) {
            $model->read_before = 1;
            $model->save();
        }
        $notify->read_at = date("y-m-d h:i:s", time());
        $notify->save();
        return redirect()->back();
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $model = Booking::find($id);

        $booking = ['Booking', 'BookingPrepayment'];
        $notifications = Notification_::whereIn('type', $booking)->where('notifiable_id', $id)->get();
        if (isset($notifications)) {
            foreach ($notifications as $notification) {
                $notification->read_at = date('Y-m-d');
                $notification->save();
            }
        }
        // $models = Booking::all();
        // $client=Clients::where('id',$model->client_id)->first();
        $connect_for=Constants::FOR_1;
        $connect_new=Constants::NEW_1;
        $new_user = DB::table($connect_for.'.booking as dt1')
            ->leftJoin($connect_for.'.clients as dt2', 'dt2.id', '=', 'dt1.client_id')
            ->leftJoin($connect_for.'.personal_informations as dt3', 'dt3.client_id', '=', 'dt1.client_id')
            ->leftJoin($connect_for.'.house_flat as dt4', 'dt4.id', '=', 'dt1.house_flat_id')
            ->leftJoin($connect_for.'.house as ho', 'ho.id', '=', 'dt4.house_id')
            ->leftJoin($connect_for.'.house_document as dt5', 'dt5.house_flat_id', '=', 'dt1.house_flat_id')
            ->leftJoin($connect_new.'.users as dt6', 'dt6.id', '=', 'dt1.user_id')
            ->where('dt1.id', $id)
            ->select('dt1.id', 'dt1.prepayment', 'dt1.expire_dates', 'dt1.status', 'dt2.first_name as client_first_name', 'dt2.last_name as client_last_name', 'dt2.middle_name as client_middle_name', 'dt2.phone', 'dt2.additional_phone', 'dt3.series_number', 'ho.id as house_id','ho.name as house_name', 'dt4.number_of_flat', 'dt4.id as house_flat_id','dt4.doc_number','dt4.price','dt4.ares_price', 'dt5.guid', 'dt6.first_name as manager_first_name', 'dt6.last_name as manager_last_name', 'dt6.middle_name as manager_middle_name', 'ho.name as house_name', 'ho.corpus', 'dt4.entrance', 'dt4.floor')
            ->first();
        // ->distinct('dt1.user_id')
        // ->count();
        // dd($new_user);
        // img src="{{ asset('/uploads/house-flat/' . $img->house_flat_id . '/m_' . $img->guid) }}"
        //                                         class="img-fluid mb-2" alt="red sample" />



        // $data=[
        //     'id'=>$model->id,
        //     // 'full_name'=>$client->first_name. ' ' .$client->last_name. ' ' .$client->middle_name,
        //     // 'phone'=>$client->phone,
        //     'status'=>$model->status,
        //     'prepayment'=>$model->prepayment,
        // ] ;

        return view('forthebuilder::booking.show', ['all_notifications' => $this->getNotification()])->with(['model' => $new_user]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $model = Booking::find($id);
        $this->getNotification();
        return view('forthebuilder::booking.edit', ['all_notifications' => $this->getNotification()])->with(['model' => $model]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(BookingRequest $request, $id)
    {
        $user = Auth::user();
        date_default_timezone_set("Asia/Tashkent");
        $model = Booking::find($id);
        //        $model->name = $request->name;
        //        $model->surname = $request->surname;
        //        $model->patronymic = $request->patronymic;
        //        $model->phone = $request->phone;
        //        $model->series_number = str_replace(' ', '', $request->series_number);
        //        $model->prepayment = $request->prepayment_summa;
        //        $model->house_flat_id = $request->house_flat_id;
        //        $model->admin_name = $user->first_name . ' ' . $user->last_name;
        //        $model->admin_id = $user->id;
        //
        //        $model->user_id = $user->id;
        //        $model->client_id = $client_id;
        //        $model->house_flat_id = $request->house_flat_id;
        //        $model->house_id = $request->house_house_id;
        //        $model->deal_id = $newDeal->id;
        //        $model->status = Constants::BOOKING_ACTIVE;
        $model->expire_dates = json_encode([['comment' => '', 'date' => date('Y-m-d', strtotime('+5 days'))]]);
        $model->notification_date = date('Y-m-d H:i:s', strtotime('+4 days'));
        $model->prepayment = ($request->prepayment) ? $request->prepayment_summa : 0;
        $notification = Notifications_::where('relation_id', $id)->first();
        $notification->is_notify = NULL;
        $notification->is_read = NULL;
        $notification->is_read_before = NULL;
        $notification->is_notify_before = NULL;
        $notification->save();
        $model->save();
        return redirect()->route('forthebuilder.booking.index')->with('success', __('locale.Prepayment has been updated'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        // return 'came';
        // $data=[
        //         [
        //             'comment'=>"text",
        //             'date'=>'03-01-2023'
        //         ],
        //         [
        //             'comment'=>"text",
        //             'date'=>'04-01-2023'
        //         ]
        //     ];

        //     // [{"date": "03-01-2023", "comment": "text"}, {"date": "04-01-2023", "comment": "text"}]

        //     $data=json_encode($data);   
        //     $model = Booking::find($id);
        //     $model->expire_dates=$data;
        //     $model->save();
        //     return $data;
        $model = Booking::find($id);
        $house_flat = HouseFlat::find($model->house_flat_id);
        $house_flat->status = 0;
        $house_flat->save();
        $model->delete();
        return redirect()->route('forthebuilder.booking.index')->with('deleted', translate('Data deleted successfuly'));
    }

    public function statusUpdate(Request $request)
    {
        // return $request->all(); 

        $model = Booking::where('id', $request->booking_id)->first();
        $model->status = $request->booking_status;
        // return $model;
        $model->save();
        // return 'true';;
        $house_flat = HouseFlat::where('id', $model->house_flat_id)->first();
        $house_flat->update([
            'status' => $model->status
        ]);
        // return $house_flat;

        return 'true';
        // return redirect()->route('forthebuilder.booking.index')->with('warning', __('locale.Prepayment has been deleted'));
    }


    public function paginate($items, $perPage = 4, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($items);
        $currentpage = $page;
        $offset = ($currentpage * $perPage) - $perPage;
        $itemstoshow = array_slice($items, $offset, $perPage);
        return new LengthAwarePaginator($itemstoshow, $total, $perPage);
    }
}
