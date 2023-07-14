<?php

namespace Modules\ForTheBuilder\Http\Controllers;

use App\components\ImageResize;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Facade\FlareClient\Http\Client;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\File;
use Modules\ForTheBuilder\Entities\HouseFlat;
use Modules\ForTheBuilder\Entities\Leads;
use Modules\ForTheBuilder\Entities\Notification_;
use Modules\ForTheBuilder\Http\Requests\HouseRequest;
use Modules\ForTheBuilder\Entities\House;
use Modules\ForTheBuilder\Entities\StatusColors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\ForTheBuilder\Entities\BasketHouse;
use Modules\ForTheBuilder\Entities\BasketHouseDocument;
use Modules\ForTheBuilder\Entities\BasketHouseFlat;
use Modules\ForTheBuilder\Entities\Clients;
use Modules\ForTheBuilder\Entities\Constants;
use Modules\ForTheBuilder\Entities\HouseDocument;
use Modules\ForTheBuilder\Http\Requests\HouseFlatPricesRequest;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
        $models = House::orderBy('id', 'desc')->paginate(15);
        return view('forthebuilder::house.index', [
            'models' => $models,
            'status' => '',
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('forthebuilder::house.create', ['all_notifications' => $this->getNotification()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(HouseRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $model = House::create($data);
            // dd($model);
            $n = 1;
            for ($i = 1; $i <= $model->enterance_count; $i++) {
                for ($j = 1; $j <= $model->floor_count; $j++) {
                    for ($l = 1; $l <= ($model->number_apartment_one_floor / $model->enterance_count); $l++) {
                        $newHouseFlat = new HouseFlat();
                        $newHouseFlat->house_id = $model->id;
                        $newHouseFlat->number_of_flat = $n++;
                        $newHouseFlat->floor = $j;
                        $newHouseFlat->enterance = $i;
                        $newHouseFlat->room_count = 0;
                        $newHouseFlat->total_area = 0;
                        $newHouseFlat->area = 0;
                        $newHouseFlat->status = HouseFlat::STATUS_FREE;
                        $newHouseFlat->save();
                    }
                }
            }

            Log::channel('action_logs2')->info("пользователь создал новую house : ", ['info-data' => $model]);

            DB::commit();
            return redirect()->route('forthebuilder.house.index')->with('success', translate('Data successfully created'));
        } catch (\Exception $e) {
            die(',,,,,,,');
            DB::rollback();
            return $e->getMessage();
        }
    }

    /**
     * New-basket_house a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function newBasketHouse(HouseRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            // $model = BasketHouse::create($data);
            $has_basement = ($request->has_basement == 'on') ? true : false;
            $has_attic = ($request->has_attic == 'on') ? true : false;
            $model = BasketHouse::create([
                'name' => $request->name,
                'description' => $request->description,
                'corpus' => $request->corpus,
                'entrance_count' => $request->entrance_count,
                'floor_count' => $request->floor_count,
                'project_stage' => $request->project_stage,
                'total_flat' => $request->total_flat,
                'entrance_one_floor_count' => $request->entrance_one_floor_count,
                'has_basement' => $has_basement,
                'has_attic' => $has_attic
            ]);
            $n = 1;
            for ($i = 1; $i <= $model->entrance_count; $i++) {
                for ($j = 1; $j <= $model->floor_count; $j++) {
                    for ($l = 1; $l <= ($model->entrance_one_floor_count); $l++) {
                        $newHouseFlat = new BasketHouseFlat();
                        $newHouseFlat->basket_house_id = $model->id;
                        $newHouseFlat->number_of_flat = $n;
                        $newHouseFlat->doc_number = $n;
                        $newHouseFlat->floor = $j;
                        $newHouseFlat->entrance = $i;
                        $newHouseFlat->room_count = 0;
                        $newHouseFlat->additional_type = BasketHouseFlat::FLAT;
                        $newHouseFlat->save();
                        $n++;

                        if ($j == 1 && isset($request->has_basement) && $request->has_basement == true) {
                            $newBasementFlat = new BasketHouseFlat();
                            $newBasementFlat->basket_house_id = $model->id;
                            $newBasementFlat->number_of_flat = $n++;
                            $newBasementFlat->floor = 0;
                            $newBasementFlat->entrance = $i;
                            $newBasementFlat->room_count = 0;
                            $newBasementFlat->additional_type = BasketHouseFlat::BASEMENT;
                            $newBasementFlat->basket_house_flat_id = $newHouseFlat->id;
                            $newBasementFlat->save();
                        }

                        if ($j == $model->floor_count && isset($request->has_attic) && $request->has_attic == true) {
                            $newAtticFlat = new BasketHouseFlat();
                            $newAtticFlat->basket_house_id = $model->id;
                            $newAtticFlat->number_of_flat = $n++;
                            $newAtticFlat->floor = $j + 1;
                            $newAtticFlat->entrance = $i;
                            $newAtticFlat->room_count = 0;
                            $newAtticFlat->additional_type = BasketHouseFlat::ATTIC;
                            $newAtticFlat->basket_house_flat_id = $newHouseFlat->id;
                            $newAtticFlat->save();
                        }
                    }
                }
            }

            $oldModel = BasketHouse::where('id', '!=', $model->id)->delete();
            $oldModel = BasketHouseFlat::where('basket_house_id', '!=', $model->id)->delete();

            // Log::channel('action_logs2')->info("пользовател создал новую house : ", ['info-data' => $model]);

            DB::commit();
            return redirect()->route('forthebuilder.house.basket-show', ['id' => $model->id])->with('success', translate('Data successfully created'));
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = House::findOrFail($id);
        return view('forthebuilder::house.show', [
            'model' => $model,
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function showMoreNew($id)
    {
        $model = House::findOrFail($id);
        $flats = HouseFlat::select('id', 'floor', 'enterance', 'status', 'number_of_flat', 'price', 'total_area')->where('house_id', $model->id)->get();
        $statusColors = StatusColors::select('id', 'color', 'status')->get();

        $colors = [];
        if (!empty($statusColors)) {
            foreach ($statusColors as $value) {
                $colors[$value->status] = $value->color;
            }
        }

        return view('forthebuilder::house.show-more-new', [
            'model' => $model,
            'flats' => $flats,
            'colors' => $colors,
            'all_notifications' => $this->getNotification()
        ]);
    }

    // public function showMoreSecond($id)
    // {
    //     $model = House::findOrFail($id);
    //     $flats = HouseFlat::select('id', 'floor', 'enterance', 'status', 'number_of_flat', 'price', 'total_area')->where('house_id', $model->id)->orderBy('enterance', 'asc')->orderBy('floor', 'desc')->get();
    //     $statusColors = StatusColors::select('id', 'color', 'status')->get();
    //     $arr = [];
    //     for ($i = 1; $i <= $model->enterance_count; $i++) {
    //         for ($j = $model->floor_count; $j >= 1; $j--) {
    //             $arr['list'][$i]['list'][$j] = [];
    //         }
    //     }

    //     $count_all = 0;
    //     $count_bookings = 0;
    //     $count_free = 0;
    //     $count_solds = 0;

    //     $enterance_all = 0;
    //     $enterance_bookings = 0;
    //     $enterance_free = 0;
    //     $enterance_solds = 0;

    //     $enteranceArr = [];
    //     $floorArr = [];
    //     $n = 0;

    //     $model->enterance_count;
    //     $model->floor_count;
    //     // $enterance_count = 0;
    //     // $floor_count = 0;

    //     foreach ($flats as $val) {
    //         $count_all++;
    //         if ($val->status == HouseFlat::STATUS_BOOKING)
    //             $count_bookings++;
    //         else if ($val->status == HouseFlat::STATUS_FREE)
    //             $count_free++;
    //         else if ($val->status == HouseFlat::STATUS_SOLD)
    //             $count_solds++;

    //         if (!in_array($val->enterance, $enteranceArr)) {
    //             $enteranceArr[] = $val->enterance;
    //             $enterance_all = 0;
    //             $enterance_bookings = 0;
    //             $enterance_free = 0;
    //             $enterance_solds = 0;
    //         }

    //         $enterance_all++;
    //         if ($val->status == HouseFlat::STATUS_BOOKING)
    //             $enterance_bookings++;
    //         else if ($val->status == HouseFlat::STATUS_FREE)
    //             $enterance_free++;
    //         else if ($val->status == HouseFlat::STATUS_SOLD)
    //             $enterance_solds++;

    //         if (!in_array($val->floor, $floorArr)) {
    //             $floorArr[] = $val->floor;
    //             $n = 0;
    //         }

    //         $arr['list'][$val->enterance]['enterance_all'] = $enterance_all;
    //         $arr['list'][$val->enterance]['enterance_bookings'] = $enterance_bookings;
    //         $arr['list'][$val->enterance]['enterance_free'] = $enterance_free;
    //         $arr['list'][$val->enterance]['enterance_solds'] = $enterance_solds;
    //         $arr['list'][$val->enterance]['enterance'] = $val->enterance;
    //         $arr['list'][$val->enterance]['list'][$val->floor][$n]['id'] = $val->id;
    //         $arr['list'][$val->enterance]['list'][$val->floor][$n]['color_status'] = $val->status;
    //         $arr['list'][$val->enterance]['list'][$val->floor][$n]['number_of_flat'] = $val->number_of_flat;
    //         $arr['list'][$val->enterance]['list'][$val->floor][$n]['total_area'] = $val->total_area;
    //         $arr['list'][$val->enterance]['list'][$val->floor][$n]['price'] = $val->price;
    //         $arr['list'][$val->enterance]['list'][$val->floor][$n]['contract_number'] = $val->contract_number;

    //         $n++;
    //     }

    //     $arr['count_all'] = $count_all;
    //     $arr['count_bookings'] = $count_bookings;
    //     $arr['count_free'] = $count_free;
    //     $arr['count_solds'] = $count_solds;
    //     // pre($arr);

    //     $colors = [];
    //     if (!empty($statusColors)) {
    //         foreach ($statusColors as $value) {
    //             $colors[$value->status] = $value->color;
    //         }
    //     }

    //     return view('forthebuilder::house.show-more-second', [
    //         'model' => $model,
    //         'flats' => $flats,
    //         'arr' => $arr,
    //         'colors' => $colors
    //     ]);
    // }

    public function showMore($id)
    {
        $model = House::findOrFail($id);
        $flats = HouseFlat::select('id', 'floor', 'entrance', 'status', 'number_of_flat', 'price', 'areas', 'room_count')->where('house_id', $model->id)->orderBy('entrance', 'asc')->orderBy('floor', 'desc');
        if ($model->sort == 1) {
            $flats = $flats->orderBy('number_of_flat', 'desc');
        } else {
            $flats = $flats->orderBy('number_of_flat', 'asc');
        }
        $flats = $flats->get();
        // pre($flats);
        $statusColors = StatusColors::select('id', 'color', 'status')->get();
        $arr = [];
        $i_default = ($model->has_basement) ? 0 : 1;
        $j_default = ($model->has_attic) ? $model->floor_count + 1 : $model->floor_count;
        // dd($j_default);
        for ($i = 1; $i <= $model->entrance_count; $i++) {
            for ($j = $j_default; $j >= $i_default; $j--) {
                $f_j = $j;
                // echo $j . '>' . $model->floor_count . '<br>';
                if ($j > $model->floor_count)
                    $f_j = translate('attic');

                if ($j == 0)
                    $f_j = translate('basement');

                $arr['list'][$i]['list'][$f_j] = [];
                $arr['entrance_count'][$f_j] = $f_j;
            }
        }



        // for ($i = 1; $i <= $model->entrance_count; $i++)
        //     for ($j = $model->floor_count; $j >= 1; $j--)
        //         $arr['list'][$i]['list'][$j] = [];

        $count_all = 0;
        $count_bookings = 0;
        $count_free = 0;
        $count_solds = 0;
        $count_commercial = 0;
        $count_park = 0;

        $entrance_all = 0;
        $entrance_bookings = 0;
        $entrance_free = 0;
        $entrance_solds = 0;

        $entranceArr = [];
        $floorArr = [];
        $n = 0;

        $model->entrance_count;
        $model->floor_count;
        // $entrance_count = 0;
        // $floor_count = 0;
        // pre($flats);

        foreach ($flats as $val) {
            $count_all++;
            if ($val->status == HouseFlat::STATUS_BOOKING)
                $count_bookings++;
            else if ($val->status == HouseFlat::STATUS_FREE)
                $count_free++;
            else if ($val->status == HouseFlat::STATUS_SOLD)
                $count_solds++;
            if ($val->room_count == 'c')
                $count_commercial++;
            if ($val->room_count == 'p'){                
                $count_park++;
            }

            

            if (!in_array($val->entrance, $entranceArr)) {
                $entranceArr[] = $val->entrance;
                $entrance_all = 0;
                $entrance_bookings = 0;
                $entrance_free = 0;
                $entrance_solds = 0;
            }

            $entrance_all++;
            if ($val->status == HouseFlat::STATUS_BOOKING)
                $entrance_bookings++;
            else if ($val->status == HouseFlat::STATUS_FREE)
                $entrance_free++;
            else if ($val->status == HouseFlat::STATUS_SOLD)
                $entrance_solds++;

            if (!in_array($val->floor, $floorArr)) {
                $floorArr[] = $val->floor;
                $n = 0;
            }

            $f_j = $val->floor;
            if ($val->floor > $model->floor_count)
                $f_j = translate('attic');

            if ($val->floor == 0)
                $f_j = translate('basement');

            if ($val->room_count == 'c') {
                $f_j = translate('Commercial');                
            }

            if ($val->room_count == 'p') {
                $f_j = translate('Park');                
            }


            $arr['list'][$val->entrance]['entrance_all'] = $entrance_all;
            $arr['list'][$val->entrance]['entrance_bookings'] = $entrance_bookings;
            $arr['list'][$val->entrance]['entrance_free'] = $entrance_free;
            $arr['list'][$val->entrance]['entrance_solds'] = $entrance_solds;
            $arr['list'][$val->entrance]['entrance'] = $val->entrance;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['id'] = $val->id;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['color_status'] = $val->status;
            if ($val->room_count == 'c') {
                $arr['list'][$val->entrance]['list'][$f_j][$n]['color_status'] = 3;
            }
            if ($val->room_count == 'p') {
                $arr['list'][$val->entrance]['list'][$f_j][$n]['color_status'] = 4;
            }
            $arr['list'][$val->entrance]['list'][$f_j][$n]['number_of_flat'] = $val->number_of_flat;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['areas'] = $val->areas;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['price'] = $val->price;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['contract_number'] = $val->contract_number;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['room_count'] = $val->room_count;

            

            $n++;
        }

        $arr['count_all'] = $count_all;
        $arr['count_bookings'] = $count_bookings;
        $arr['count_free'] = $count_free;
        $arr['count_solds'] = $count_solds;
        $arr['count_commercial'] = $count_commercial;
        $arr['count_park'] = $count_park;
        
        // pre($arr);

        $colors = ['', '', ''];
        if (!empty($statusColors)) {
            foreach ($statusColors as $value) {
                $colors[$value->status] = $value->color;
            }
        }


        // return view('forthebuilder::house.show-more-second', [
        return view('forthebuilder::house.show-more', [
            'model' => $model,
            'flats' => $flats,
            'arr' => $arr,
            'colors' => $colors,
            'status' => '',
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function showDetails($house_id, $entrance, $flat_id)
    {
        $model = House::findOrFail($house_id);
        $flats = HouseFlat::select('id', 'floor', 'entrance', 'status', 'number_of_flat', 'price', 'areas', 'room_count', 'house_id', 'doc_number', 'ares_price')->where(['house_id' => $model->id, 'entrance' => $entrance])->orderBy('entrance', 'asc')->orderBy('floor', 'desc')->orderBy('number_of_flat', 'asc')->get();
        $statusColors = StatusColors::select('id', 'color', 'status')->get();
        $arr = [];
        // for ($i = 1; $i <= $model->entrance_count; $i++)
        //     for ($j = $model->floor_count; $j >= 1; $j--)
        //         $arr['list'][$i]['list'][$j] = [];

        $count_all = 0;
        $count_bookings = 0;
        $count_free = 0;
        $count_solds = 0;
        $count_commercial = 0;
        $count_park = 0;

        $entrance_all = 0;
        $entrance_bookings = 0;
        $entrance_free = 0;
        $entrance_solds = 0;

        $entranceArr = [];
        $floorArr = [];
        $n = 0;

        $model->entrance_count;
        $model->floor_count;
        // $entrance_count = 0;
        // $floor_count = 0;
        // pre($flats);
        foreach ($flats as $val) {
            $count_all++;
            if ($val->status == HouseFlat::STATUS_BOOKING)
                $count_bookings++;
            else if ($val->status == HouseFlat::STATUS_FREE)
                $count_free++;
            else if ($val->status == HouseFlat::STATUS_SOLD)
                $count_solds++;

            if ($val->room_count == 'c')
                $count_commercial++;
            if ($val->room_count == 'p'){                
                $count_park++;
            }

            if (!in_array($val->entrance, $entranceArr)) {
                $entranceArr[] = $val->entrance;
                $entrance_all = 0;
                $entrance_bookings = 0;
                $entrance_free = 0;
                $entrance_solds = 0;
            }

            $entrance_all++;
            if ($val->status == HouseFlat::STATUS_BOOKING)
                $entrance_bookings++;
            else if ($val->status == HouseFlat::STATUS_FREE)
                $entrance_free++;
            else if ($val->status == HouseFlat::STATUS_SOLD)
                $entrance_solds++;

            if (!in_array($val->floor, $floorArr)) {
                $floorArr[] = $val->floor;
                $n = 0;
            }

            $f_j = $val->floor;
            if ($val->floor > $model->floor_count)
                $f_j = translate('attic'); //Мансарда

            if ($val->floor == 0)
                $f_j = translate('basement'); //подвал

            if ($val->room_count == 'c') {
                $f_j = translate('Commercial');                
            }

            if ($val->room_count == 'p') {
                $f_j = translate('Park');                
            }

            $areas = json_decode($val->areas);
            // pre($val->ares_price);
            $arr['entrance_all'] = $entrance_all;
            $arr['entrance_bookings'] = $entrance_bookings;
            $arr['entrance_free'] = $entrance_free;
            $arr['entrance_solds'] = $entrance_solds;
            $arr['entrance'] = $val->entrance;
            $arr['list'][$f_j][$n]['id'] = $val->id;
            $arr['list'][$f_j][$n]['house_id'] = $val->house_id;
            $arr['list'][$f_j][$n]['house_house_name'] = $model->name;
            $arr['list'][$f_j][$n]['doc_number'] = $val->doc_number;
            $arr['list'][$f_j][$n]['color_status'] = $val->status;

            if ($val->room_count == 'c') {
                $arr['list'][$f_j][$n]['color_status'] = 3;
            }
            if ($val->room_count == 'p') {
                $arr['list'][$f_j][$n]['color_status'] = 4;
            }

            $arr['list'][$f_j][$n]['number_of_flat'] = $val->number_of_flat;
            $arr['list'][$f_j][$n]['areas'] = $areas->total;
            $arr['list'][$f_j][$n]['price'] = $val->price;
            $arr['list'][$f_j][$n]['contract_number'] = $val->contract_number;
            $arr['list'][$f_j][$n]['room_count'] = $val->room_count;
            $arr['list'][$f_j][$n]['ares_price'] = $val->ares_price;
            $arr['list'][$f_j][$n]['client'] = '';
            if ($val->status == Constants::STATUS_BOOKING) {
                $arr['list'][$f_j][$n]['client'] = (isset($val->booking->clients)) ? $val->booking->clients->last_name . ' ' . $val->booking->clients->first_name . ' ' . $val->booking->clients->middle_name : '';
            } else if ($val->status == Constants::STATUS_SOLD) {
                $arr['list'][$f_j][$n]['client'] = (isset($val->deal->client)) ? $val->deal->client->last_name . ' ' . $val->deal->client->first_name . ' ' . $val->deal->client->middle_name : '';
            }
            $arr['list'][$f_j][$n]['floor'] = $val->floor;
            $arr['list'][$f_j][$n]['doc'] = $val->image ? asset('/uploads/house-flat/' . $val->house_id . '/m_' . $val->image->guid) : asset('/backend-assets/forthebuilders/images/a6d5ae15f8f52bd6b9db53be7746c650 1.png');

            // [$val->entrance]

            $n++;
        }

        $arr['count_all'] = $count_all;
        $arr['count_bookings'] = $count_bookings;
        $arr['count_free'] = $count_free;
        $arr['count_solds'] = $count_solds;
        $arr['count_commercial'] = $count_commercial;
        $arr['count_park'] = $count_park;

        $colors = [];
        if (!empty($statusColors))
            foreach ($statusColors as $value)
                $colors[$value->status] = $value->color;

        
        return view('forthebuilder::house.show-details', [
            'model' => $model,
            'flats' => $flats,
            'arr' => $arr,
            'colors' => $colors,
            'house_id' => $house_id,
            'status' => '',
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function basketShow($id)
    {
        $model = BasketHouse::findOrFail($id);
        $flats = BasketHouseFlat::select('id', 'floor', 'entrance', 'number_of_flat', 'areas', 'room_count', 'additional_type')->where('basket_house_id', $model->id)->orderBy('entrance', 'asc')->orderBy('floor', 'desc')->orderBy('number_of_flat', 'asc')->get();
        // $statusColors = StatusColors::select('id', 'color', 'status')->get();
        $arr = [];
        $i_default = ($model->has_basement) ? 0 : 1;
        $j_default = ($model->has_attic) ? $model->floor_count + 1 : $model->floor_count;
        // dd($j_default);
        for ($i = 1; $i <= $model->entrance_count; $i++) {
            for ($j = $j_default; $j >= $i_default; $j--) {
                $f_j = $j;
                // echo $j . '>' . $model->floor_count . '<br>';
                if ($j > $model->floor_count)
                    $f_j = translate('M');

                if ($j == 0)
                    $f_j = translate('Ts');

                $arr['list'][$i]['list'][$f_j] = [];
                $arr['entrance_count'][$f_j] = $f_j;
            }
        }
        // pre($arr);

        $entrance_all = 0;
        $entrance_bookings = 0;
        $entrance_free = 0;
        $entrance_solds = 0;

        $floorArr = [];
        $n = 0;

        $model->entrance_count;
        $model->floor_count;

        $show_next_button = true;
        foreach ($flats as $val) {
            if ($val->room_count == 0)
                $show_next_button = false;

            if (!in_array($val->floor, $floorArr)) {
                $floorArr[] = $val->floor;
                $n = 0;
            }

            $f_j = $val->floor;
            if ($val->floor > $model->floor_count)
                $f_j = translate('M');
            if ($val->floor == 0)
                $f_j = translate('Ts');

            $areas = json_decode($val->areas);
            $arr['list'][$val->entrance]['entrance_all'] = $entrance_all;
            $arr['list'][$val->entrance]['entrance_bookings'] = $entrance_bookings;
            $arr['list'][$val->entrance]['entrance_free'] = $entrance_free;
            $arr['list'][$val->entrance]['entrance_solds'] = $entrance_solds;
            $arr['list'][$val->entrance]['entrance'] = $val->entrance;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['id'] = $val->id;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['color_status'] = $val->status;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['number_of_flat'] = $val->number_of_flat;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['areas'] = (isset($areas) && !empty($areas)) ? $areas->total : '';
            $arr['list'][$val->entrance]['list'][$f_j][$n]['price'] = $val->price;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['contract_number'] = $val->contract_number;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['room_count'] = $val->room_count;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['additional_type'] = $val->additional_type;

            $n++;
        }

        $colors = [];
        if (!empty($statusColors)) {
            foreach ($statusColors as $value) {
                $colors[$value->status] = $value->color;
            }
        }

        // pre($arr);
        return view('forthebuilder::house.basket-show-more', [
            'model' => $model,
            'flats' => $flats,
            'arr' => $arr,
            'colors' => $colors,
            'show_next_button' => $show_next_button,
            'basket_id' => $id,
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function basketToHouse(Request $request)
    {
        // dd($request);
        DB::beginTransaction();
        try {
            $model = BasketHouse::find($request->id);
            if (isset($model)) {
                $newHouse = new House();
                $newHouse->name = $model->name;
                $newHouse->description = $model->description;
                $newHouse->corpus = $model->corpus;
                $newHouse->entrance_count = $model->entrance_count;
                $newHouse->floor_count = $model->floor_count;
                $newHouse->project_stage = $model->project_stage;
                $newHouse->total_flat = $model->total_flat;
                $newHouse->entrance_one_floor_count = $model->entrance_one_floor_count;
                $newHouse->has_basement = $model->has_basement;
                $newHouse->has_attic = $model->has_attic;
                $newHouse->sort = $request->order;
                $newHouse->save();

                // $basketHouseFlats = BasketHouseFlat::where('basket_house_id', $model->id)->orderBy('entrance', 'asc')->orderBy('floor', 'asc')->get();
                if ($request->order == 2)
                    $basketHouseFlats = BasketHouseFlat::where('basket_house_id', $model->id)->orderBy('number_of_flat', 'asc')->get();
                else
                    $basketHouseFlats = BasketHouseFlat::where('basket_house_id', $model->id)->orderBy('entrance', 'desc')->orderBy('floor', 'asc')->orderBy('number_of_flat', 'desc')->get();

                if (!empty($basketHouseFlats)) {
                    $n = $request->from;
                    foreach ($basketHouseFlats as $val) {
                        $newHouseFlat = new HouseFlat();
                        $newHouseFlat->house_id = $newHouse->id;
                        $newHouseFlat->number_of_flat = $n;
                        $newHouseFlat->floor = $val->floor;
                        $newHouseFlat->entrance = $val->entrance;
                        $newHouseFlat->room_count = $val->room_count;
                        $newHouseFlat->price = $val->price;
                        $newHouseFlat->doc_number = $n;
                        $newHouseFlat->status = HouseFlat::STATUS_FREE;
                        $newHouseFlat->areas = $val->areas;
                        $newHouseFlat->additional_type = $val->additional_type;
                        $newHouseFlat->house_flat_id = $val->house_flat_id;
                        $newHouseFlat->free_start = date('Y-m-d H:i:s');
                        $newHouseFlat->free_end = '9999-12-31 23:59:59';
                        $newHouseFlat->save();
                        $n++;

                        $modelBasketDocument = BasketHouseDocument::where('basket_house_flat_id', $val->id)->first();
                        if (isset($modelBasketDocument)) {
                            $newFlatDocument = new HouseDocument();
                            $newFlatDocument->house_flat_id = $newHouseFlat->id;
                            $newFlatDocument->name = $modelBasketDocument->name;
                            $newFlatDocument->guid = $modelBasketDocument->guid;
                            $newFlatDocument->ext = $modelBasketDocument->ext;
                            $newFlatDocument->size = $modelBasketDocument->size;
                            $newFlatDocument->main_image = $modelBasketDocument->main_image;
                            $newFlatDocument->save();

                            // $sourcePath = public_path('uploads/house-flat/' . $model->id);

                            $sourcePath = __DIR__ . '/../../../../public/uploads/house-flat/b_' . $model->id;

                            if (file_exists($sourcePath)) {
                                $storageDestinationPath = __DIR__ . '/../../../../public/uploads/house-flat/' . $newHouse->id;
                                rename($sourcePath, $storageDestinationPath);
                            }
                           
                            $modelBasketDocument->delete();
                        }
                        $val->delete();
                    }
                }
                $model->delete();
            }
            return $newHouse->id;
        } catch (\Exception $e) {
            // die(',,,,,,,');
            DB::rollback();
            return $e->getMessage();
        }
    }

    // public function showMoreItemDetail($id)
    // {
    //     $flatItemDetail = HouseFlat::findOrFail($id);
    //     // Log::channel('action_logs2')->info("пользователь показал ".$model->number_of_flat." До",['info-data'=>$model]);
    //     $flatItemDetailImg = $flatItemDetail->main_image;
    //     return response()->json([
    //         'flatItemDetail'=>$flatItemDetail,
    //         'flatItemDetailImg'=>$flatItemDetailImg
    //     ]);
    // }

    public function showMoreItemDetail($id)
    {
        $flatItemDetail = HouseFlat::findOrFail($id);

        // Log::channel('action_logs2')->info("польователь показал ".$model->number_of_flat." Дом",['info-data'=>$model]);
        $flatItemDetailImg = $flatItemDetail->main_image;
        $flatItemDetailClient = $flatItemDetail->booking;
        return response()->json([
            'flatItemDetail' => $flatItemDetail,
            'flatItemDetailImg' => $flatItemDetailImg,
            'flatItemDetailClient' => $flatItemDetailClient,
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = House::findOrFail($id);
        return view('forthebuilder::house.edit', [
            'model' => $model,
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(HouseRequest $request, $id)
    {
        $data = $request->validated();
        $model = House::findOrFail($id);
        $model->name = $data['name'];
        $model->description = $data['description'];
        $model->project_stage = $data['project_stage'];
      	$model->corpus = $data['corpus'];
        $model->save();

        Log::channel('action_logs2')->info("пользователь обновил house", ['info-data' => $model]);
        return redirect()->route('forthebuilder.house.index')->with('updated', translate('Data successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = House::findOrFail($id);

        $modelFlats = HouseFlat::where('house_id', $model->id)->get();
        if (!empty($modelFlats))
            foreach ($modelFlats as $value)
                $value->delete();

        $model->delete();
        Log::channel('action_logs2')->info(translate('User deleted house'), ['info-data' => $model]);
        return back()->with('deleted', translate('Data deleted successfuly'));
    }

    public function searchByName($name)
    {
        // $searchedLeadList = Clients::select('id', 'first_name', 'last_name', 'middle_name', 'phone')
        $connect_for=Constants::FOR_1;
        $connect_new=Constants::NEW_1;
        $searchedLeadList = DB::table($connect_for.'.clients')->select($connect_for.'.clients.id', $connect_for.'.clients.first_name', $connect_for.'.clients.last_name', $connect_for.'.clients.middle_name', $connect_for.'.clients.phone', $connect_for.'.clients.additional_phone', $connect_for.'.clients.email', $connect_for.'.personal_informations.series_number', $connect_for.'.personal_informations.inn', $connect_for.'.personal_informations.issued_by', $connect_for.'.personal_informations.given_date')
            // ->join('personal_informations')
            ->leftJoin($connect_for.'.personal_informations', $connect_for.'.clients.id', '=', $connect_for.'.personal_informations.client_id')
            ->where($connect_for.'.clients.first_name', 'like', '%' . $name . '%')
            ->orWhere($connect_for.'.clients.last_name', 'like', '%' . $name . '%')
            ->orWhere($connect_for.'.clients.middle_name', 'like', '%' . $name . '%')
            ->orWhere($connect_for.'.clients.phone', 'like', '%' . $name . '%')
            ->orWhere($connect_for.'.personal_informations.series_number', 'like', '%' . $name . '%')
            ->whereNotNull($connect_for.'.clients.updated_at')->get();

        return response()->json([
            'searchedLeadList' => $searchedLeadList,
        ]);
    }

    public function updateFlatsData(Request $request)
    {
        // $formData = explode('&', $request->form);
        $formData = $request->form;
        // dd($request);
        if (isset($request['flats'])) {
            $room_count = $request['flats'][1]['room_count'];
            $flats = $request['flats'][0]['flats'];
            // $kv_m = $request['kv_m'];
            // dd($room_count = $request['flats'][1]);

            $n = 0;
            if (!empty($flats)) {
                foreach ($flats as $value) {
                    $modelFlats = BasketHouseFlat::find($value);

                    if (isset($modelFlats)) {
                        if ($n == 0) {
                            $n++;

                            if (!file_exists(public_path('uploads/house-flat/b_' . $modelFlats->basket_house_id))) {
                                $path = public_path('uploads/house-flat/b_' . $modelFlats->basket_house_id);
                                File::makeDirectory($path, $mode = 0777, true, true);
                            }
                            // if (!file_exists(public_path('/uploads/tmp_files/' . Auth::user()->id . '/house-flat'))) {
                            //     $pathTmp = public_path('/uploads/tmp_files/' . Auth::user()->id . '/house-flat');
                            //     File::makeDirectory($pathTmp, $mode = 0777, true, true);
                            // }
                            $files_saved = File::allFiles(public_path('/uploads/tmp_files/' . Auth::user()->id . '/house-flat'));
                            $j = 0;
                            foreach ($files_saved as $files_savedItem) {
                                $j++;
                                $sourcePath = public_path('uploads/tmp_files/' . Auth::user()->id . '/house-flat/' . $files_savedItem->getFilename());
                                $filenamehash = md5($files_savedItem->getFilename() . time()) . '.' . $files_savedItem->getExtension();
                                $filesize =  File::size($sourcePath);

                                $pathInfo = pathinfo($sourcePath);
                                if ($pathInfo['extension'] == 'jpg' || $pathInfo['extension'] == 'png' || $pathInfo['extension'] == 'jpeg') {
                                    $imageR = new ImageResize($sourcePath);
                                    $imageR->resizeToBestFit(config('params.large_image.width'), config('params.large_image.width'))->save(public_path('uploads/house-flat/b_' . $modelFlats->basket_house_id . '/l_' . $filenamehash));
                                    $imageR->resizeToWidth(config('params.medium_image.width'))->save(public_path('uploads/house-flat/b_' . $modelFlats->basket_house_id . '/m_' . $filenamehash));
                                    $imageR->crop(config('params.small_image.width'), config('params.small_image.height'))->save(public_path('uploads/house-flat/b_' . $modelFlats->basket_house_id . '/s_' . $filenamehash));
                                } else {
                                    $storageDestinationPath = public_path('uploads/house-flat/b_' . $modelFlats->basket_house_id . '/' . $filenamehash);
                                    File::move($sourcePath, $storageDestinationPath);
                                }

                                File::delete($sourcePath);
                            }
                        }

                        $areaArr = [
                            "total" => $formData['total_area'],
                            "housing" => $formData['living_space'] ?? 0,
                            "hotel" => $formData['hotel'] ?? 0,
                            "bedroom" => $formData['bedroom'] ?? 0,
                            "kitchen" => $formData['kitchen_area'] ?? 0,
                            "basement" => 0,
                            "terraca" => $formData['terassa'] ?? 0,
                            "attic" => 0,
                            "balcony" => $formData['balcony'] ?? 0,
                        ];
                        $modelFlats->room_count = $room_count;
                        $modelFlats->areas = json_encode($areaArr);

                        // $modelFlats->total_area = ;
                        // $modelFlats->kitchen_area = $formData['kitchen_area'];
                        // $modelFlats->area = $formData['living_space'];
                        // $modelFlats->terrace = $formData['terassa'] ?? 0;
                        // $modelFlats->balcony = $formData['balcony'] ?? 0;
                        $modelFlats->save();

                        if (isset($files_savedItem)) {
                            BasketHouseDocument::create([
                                'basket_house_flat_id' => $modelFlats->id,
                                'name' => $files_savedItem->getFilename(),
                                'guid' => $filenamehash,
                                'ext' => $files_savedItem->getExtension(),
                                'size' => $filesize ?? '',
                                'main_image' => $j == 1 ? 1 : 0,
                            ]);
                        }
                    }
                }
                return true;
            }
        } else {
            return false;
        }
    }

    public function removeBascketFloat(Request $request)
    {
        $house_id = $request->house_id;
        $entrance = $request->entrance;
        $floor = $request->floor;
        $additionalType = BasketHouseFlat::FLAT;
        if ($floor == translate('attic')) {
            $additionalType = BasketHouseFlat::ATTIC;
            $newModel = BasketHouseFlat::where(['basket_house_id' => $house_id, 'additional_type' => $additionalType])->first();
            if (isset($newModel)) {
                $floor = $newModel->floor;
            }
        } else if ($floor == translate('basement')) {
            $additionalType = BasketHouseFlat::BASEMENT;
            $floor = 0;
        }
        $model = BasketHouseFlat::where(['basket_house_id' => $house_id, 'entrance' => $entrance, 'additional_type' => $additionalType, 'floor' => $floor])->orderBy('created_at', 'desc')->first();
        $id = 0;
        if (isset($model)) {
            $id = $model->id;
            $model->delete();
        }

        return ['status' => 'success', 'id' => $id];
    }

    public function addBascketFloat(Request $request)
    {
        $house_id = $request->house_id;
        $entrance = $request->entrance;
        $floor = $request->floor;
        $additionalType = BasketHouseFlat::FLAT;
        if ($floor == translate('M')) {
            $additionalType = BasketHouseFlat::ATTIC;
            $newModel = BasketHouseFlat::where(['basket_house_id' => $house_id, 'additional_type' => $additionalType])->first();
            if (isset($newModel)) {
                $floor = $newModel->floor;
            }
        } else if ($floor == translate('Ts')) {
            $additionalType = BasketHouseFlat::BASEMENT;
            $floor = 0;
        }
        $model = new BasketHouseFlat();
        $model->basket_house_id = $house_id;
        $model->number_of_flat = 0;
        $model->floor = $floor;
        $model->entrance = $entrance;
        $model->room_count = 0;
        $model->areas = 0;
        $model->additional_type = $additionalType;
        $model->save();

        return 'success';
    }

    public function margeFlats(Request $request)
    {
        if (!empty($request)) {
            DB::beginTransaction();
            try {
                $flats = $request['data'][0]['flats'];
                $i = 0;
                $firstModel = BasketHouseFlat::find($flats[0]);
                $arr = ['status' => true, 'msg' => ''];
                foreach ($flats as $key => $value) {
                    if ($i != 0) {
                        $modelFlats = BasketHouseFlat::find($value);
                        if ($modelFlats->basket_house_id == $firstModel->basket_house_id && $modelFlats->floor == $firstModel->floor && $modelFlats->enterance == $firstModel->enterance && $modelFlats->additional_type == $firstModel->additional_type) {
                            if ($firstModel->additional_type != BasketHouseFlat::FLAT)
                                $firstModel->basket_house_flat_id = NULL;

                            $firstModel->areas = $modelFlats->areas;
                            $firstModel->save();

                            $modelFlats->delete();
                        } else {
                            $arr['status'] = false;
                            $arr['msg'] = translate('You cannot combine these apartments');
                        }
                    }
                    $i++;
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
            return $arr;
        }
        return ['status' => false, 'msg' => translate('No information')];
    }

    public function priceFormation()
    {
        $model = House::all();
        return view('forthebuilder::house.price-formation', [
            'model' => $model,
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function pricesHouseFlats(Request $request)
    {
        $model = House::findOrFail($request->house_id);
        $flats = HouseFlat::select('id', 'floor', 'entrance', 'status', 'number_of_flat', 'price', 'areas', 'room_count', 'ares_price')->where('house_id', $model->id)->orderBy('entrance', 'asc')->orderBy('floor', 'desc')->orderBy('number_of_flat', 'asc')->get();
        $statusColors = StatusColors::select('id', 'color', 'status')->get();
        $arr = [];
        $i_default = ($model->has_basement) ? 0 : 1;
        $j_default = ($model->has_attic) ? $model->floor_count + 1 : $model->floor_count;

        for ($i = 1; $i <= $model->entrance_count; $i++) {
            for ($j = $j_default; $j >= $i_default; $j--) {
                $f_j = $j;
                if ($j > $model->floor_count)
                    $f_j = translate('attic');

                if ($j == 0)
                    $f_j = translate('basement');

                $arr['list'][$i]['list'][$f_j] = [];
                $arr['entrance_count'][$f_j] = $f_j;
            }
        }

        $count_all = 0;
        $count_bookings = 0;
        $count_free = 0;
        $count_solds = 0;

        $entrance_all = 0;
        $entrance_bookings = 0;
        $entrance_free = 0;
        $entrance_solds = 0;

        $entranceArr = [];
        $floorArr = [];
        $n = 0;

        $model->entrance_count;
        $model->floor_count;
        foreach ($flats as $val) {
            $count_all++;
            if ($val->status == HouseFlat::STATUS_BOOKING)
                $count_bookings++;
            else if ($val->status == HouseFlat::STATUS_FREE)
                $count_free++;
            else if ($val->status == HouseFlat::STATUS_SOLD)
                $count_solds++;

            if (!in_array($val->entrance, $entranceArr)) {
                $entranceArr[] = $val->entrance;
                $entrance_all = 0;
                $entrance_bookings = 0;
                $entrance_free = 0;
                $entrance_solds = 0;
            }

            $entrance_all++;
            if ($val->status == HouseFlat::STATUS_BOOKING)
                $entrance_bookings++;
            else if ($val->status == HouseFlat::STATUS_FREE)
                $entrance_free++;
            else if ($val->status == HouseFlat::STATUS_SOLD)
                $entrance_solds++;

            if (!in_array($val->floor, $floorArr)) {
                $floorArr[] = $val->floor;
                $n = 0;
            }

            $f_j = $val->floor;
            if ($val->floor > $model->floor_count)
                $f_j = translate('attic');

            if ($val->floor == 0)
                $f_j = translate('basement');

            $arr['list'][$val->entrance]['entrance_all'] = $entrance_all;
            $arr['list'][$val->entrance]['entrance_bookings'] = $entrance_bookings;
            $arr['list'][$val->entrance]['entrance_free'] = $entrance_free;
            $arr['list'][$val->entrance]['entrance_solds'] = $entrance_solds;
            $arr['list'][$val->entrance]['entrance'] = $val->entrance;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['id'] = $val->id;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['color_status'] = $val->status;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['number_of_flat'] = $val->number_of_flat;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['areas'] = $val->areas;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['price'] = $val->price;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['areas_price'] = $val->ares_price;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['contract_number'] = $val->contract_number;
            $arr['list'][$val->entrance]['list'][$f_j][$n]['room_count'] = $val->room_count;

            $n++;
        }

        $arr['count_all'] = $count_all;
        $arr['count_bookings'] = $count_bookings;
        $arr['count_free'] = $count_free;
        $arr['count_solds'] = $count_solds;

        $colors = ['', '', ''];
        if (!empty($statusColors))
            foreach ($statusColors as $value)
                $colors[$value->status] = $value->color;

        return (string) view('forthebuilder::house.price-formation-flats', [
            'model' => $model,
            'flats' => $flats,
            'arr' => $arr,
            'colors' => $colors,
            'status' => '',
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function savePriceInformation(HouseFlatPricesRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            // dd($data);

            $flats = explode(',', $data['house_flats']);
            foreach ($flats as $key => $value) {
                $newPrice = [
                    "fifty" => ["attic" => 0, "total" => 0, "terraca" => 0, "basement" => 0],
                    "thirty" => ["attic" => 0, "total" => 0, "terraca" => 0, "basement" => 0],
                    "hundred" => ["attic" => 0, "total" => 0, "terraca" => 0, "basement" => 0],
                    "seventy" => ["attic" => 0, "total" => 0, "terraca" => 0, "basement" => 0]
                ];
                $price = 0;
                $houseFlat = HouseFlat::find($value);
                if ($houseFlat->ares_price == null) {
                    $houseFlat->ares_price = json_encode($newPrice);
                }
                $oldPrices = json_decode($houseFlat->ares_price);
                $areas = json_decode($houseFlat->areas);
                
                if (!empty($data['payment'])) {
                    foreach ($data['payment'] as $pay_val) {
                        $addToPrice = false;
                        // dd($pay_val);
                        switch ($pay_val['payment_type']) {
                            case Constants::PAYMENT_100:
                                $payment_type = 'hundred';
                                // $addToPrice = true;
                                break;
                            case Constants::PAYMENT_50:
                                $payment_type = 'fifty';
                                break;
                            case Constants::PAYMENT_30:
                                $payment_type = 'thirty';
                                break;
                            case Constants::PAYMENT_70:
                                $payment_type = 'seventy';
                                break;
                        }

                        // {"attic": 0, "total": "90", "balcony": 0, "housing": "42", "kitchen": "63", "terraca": 0, "basement": 0}

                        switch ($data['price_type']) {
                            case Constants::PRICE_M2:
                                $price_type = 'total';

                                // if ($addToPrice)
                                //     $price += $pay_val['amount'] * $areas->total;

                                break;
                            case Constants::PRICE_TERRACE:
                                $price_type = 'terraca';

                                // if ($addToPrice)
                                //     $price += $pay_val['amount'] * $areas->terraca;

                                break;
                            case Constants::PRICE_ATTIC:
                                $price_type = 'attic';

                                // if ($addToPrice)
                                //     $price += $pay_val['amount'] * $areas->attic;

                                break;
                            case Constants::PRICE_BASEMENT:
                                $price_type = 'basement';

                                // if ($addToPrice)
                                //     $price += $pay_val['amount'] * $areas->basement;

                                break;
                        }
                        
                        $oldPrices->$payment_type->$price_type = $pay_val['amount'];
                    }
                }
                // pre($oldPrices);
                $houseFlat->ares_price = json_encode($oldPrices);
                $areasDecode = json_decode($houseFlat->ares_price);
                $price = ($areasDecode->hundred->total * $areas->total) + ($areasDecode->hundred->terraca * $areas->terraca) + ($areasDecode->hundred->attic * $areas->attic) + ($areasDecode->hundred->basement * $areas->basement);
                $houseFlat->price = $price;
                $houseFlat->save();
            }

            // Log::channel('action_logs2')->info("пользователь создал новую house : ", ['info-data' => $model]);

            DB::commit();
            return redirect()->route('forthebuilder.house.price-formation')->with('success', translate('Data successfully created'));
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
