<?php

namespace Modules\ForTheBuilder\Http\Controllers;

use App\components\ImageResize;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\ForTheBuilder\Entities\Currency;
use Modules\ForTheBuilder\Entities\Deal;
use Modules\ForTheBuilder\Entities\DealsFile;
use Modules\ForTheBuilder\Entities\House;
use Modules\ForTheBuilder\Entities\HouseFlat;
use Modules\ForTheBuilder\Entities\InstallmentPlan;
use Modules\ForTheBuilder\Entities\LeadComment;
use Modules\ForTheBuilder\Entities\Clients;
use Modules\ForTheBuilder\Entities\LeadStatus;
use Modules\ForTheBuilder\Entities\Notification_;
use Modules\ForTheBuilder\Entities\PersonalInformations;
use Modules\ForTheBuilder\Entities\StatusColors;
use Modules\ForTheBuilder\Exports\LeadsExport;
use Modules\ForTheBuilder\Http\Requests\ClientsRequest;
use Modules\ForTheBuilder\Imports\LeadsImport;
use Modules\ForTheBuilder\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Auth;
use Modules\ForTheBuilder\Entities\Task;
use Modules\ForTheBuilder\Events\RealTimeMessage;
use Modules\ForTheBuilder\Notifications\TaskNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Modules\ForTheBuilder\Entities\Booking;
use Modules\ForTheBuilder\Entities\Chat;
use Modules\ForTheBuilder\Entities\Constants;
use Modules\ForTheBuilder\Entities\PayStatus;
use PHPUnit\TextUI\XmlConfiguration\Constant;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    // public function index()
    // {
    //     $models = Clients::orderBy('id', 'desc')->paginate(config('params.pagination'));

    //     return view('forthebuilder::clients.index', [
    //         'models' => $models
    //     ]);
    // }

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
        // $models = Deal::orderBy('id', 'desc')->paginate(config('params.pagination'));

        $user=Auth::user();
        $connect_for=Constants::FOR_1;
        // $user->role_id==Constants::MANAGER
        if ($user->role_id==Constants::MANAGER) {
            $models=DB::table($connect_for.'.deals as dt1')
            ->leftJoin($connect_for.'.clients as dt2', 'dt2.id', '=', 'dt1.client_id')
            ->leftJoin($connect_for.'.task as dt3', 'dt3.deal_id', '=', 'dt1.id')
            ->leftJoin($connect_for.'.house as dt4', 'dt4.id', '=', 'dt1.house_id')
            ->select('dt1.id as deal_id', 'dt1.price_sell','dt1.type as deal_type', 'dt2.id as client_id',  'dt2.first_name as client_first_name', 'dt2.last_name as client_last_name', 'dt2.middle_name as client_middle_name',  'dt3.title as task_title', 'dt4.name as house_name')
            ->where('dt2.status',Constants::CLIENT_ACTIVE)
            ->where('dt1.user_id',$user->id)
            ->orderByDesc('dt1.created_at')
            ->paginate(15);
        }else {
            

            $models=DB::table($connect_for.'.deals as dt1')
            ->leftJoin($connect_for.'.clients as dt2', 'dt2.id', '=', 'dt1.client_id')
            ->leftJoin($connect_for.'.task as dt3', 'dt3.deal_id', '=', 'dt1.id')
            ->leftJoin($connect_for.'.house as dt4', 'dt4.id', '=', 'dt1.house_id')
            ->select('dt1.id as deal_id', 'dt1.price_sell','dt1.type as deal_type', 'dt2.id as client_id',  'dt2.first_name as client_first_name', 'dt2.last_name as client_last_name', 'dt2.middle_name as client_middle_name',  'dt3.title as task_title', 'dt3.id as task_id', 'dt4.name as house_name')
            ->where('dt2.status',Constants::CLIENT_ACTIVE)
            // ->where('dt1.user_id',$user->id)
            ->orderByDesc('dt1.created_at')
            ->paginate(15);

            // $models = Deal::with('house_flat', 'user')

            // ->where('user_id',$user->id)
        }
        // dd($models);
        

        $defaultAction = [
            Constants::FIRST_CONTACT => translate('First contact'),
            Constants::NEGOTIATION => translate('Negotiation'),
            Constants::MAKE_DEAL => translate('Making a deal'),
        ];
        // pre($defaultAction);
        return view('forthebuilder::clients.index', [
            'models' => $models,
            'defaultAction' => $defaultAction,
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function allClients()
    {
        $models = Clients::orderBy('id', 'desc')->paginate(15); //->paginate(config('params.pagination'));

        // $defaultAction = [
        //     Constants::FIRST_CONTACT => translate('First contact'),
        //     Constants::NEGOTIATION => translate('Negotiation'),
        //     Constants::MAKE_DEAL => translate('Making a deal'),
        // ];
        return view('forthebuilder::clients.all-clients', [
            'models' => $models,
            'active' => Constants::CLIENT_ACTIVE,
            'archive' => Constants::CLIENT_DELETED,
            'all_notifications' => $this->getNotification()
            // 'defaultAction' => $defaultAction,
        ]);
    }

    public function calendar()
    {
        $user = Auth::user();
        $models = Task::where('deleted_at', NULL)->get();
        $my_models = Task::where('performer_id', $user->id)->where('deleted_at', NULL)->get();
        
        if ($user->role_id == Constants::MANAGER) {
            $users = User::where('id',$user->id)->get();
        }
        else{
            $users = User::all();
        }

        // foreach($models as $model_){
        //     pre($model_);
        // }
        // pre($models);

        $deals = Deal::where('status', 1)->get();
        return view('forthebuilder::clients.calendar', [
            'models' => $models,
            'users' => $users,
            'deals' => $deals,
            'my_models' => $my_models,
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function indexLeadListNew()
    {

        $leadStatuses = LeadStatus::all();

        return view('forthebuilder::clients.index-lead-list-new', [
            'leadStatuses' => $leadStatuses,
            'all_notifications' => $this->getNotification()
        ]);
    }

    // public function indexClientList()
    // {
    //     $model = Deal::where('status', Constants::ACTIVE)->orderBy('date_deal', 'desc')->get();

    //     $arr = [
    //         translate('First contact') => [],
    //         translate('Negotiation') => [],
    //         translate('Making a deal') => [],
    //     ];

    //     if (!empty($model)) {
    //         $n = 0;
    //         foreach ($model as $key => $value) {
    //             switch ($value->type) {
    //                 case Constants::FIRST_CONTACT:
    //                     $key = translate('First contact');
    //                     break;
    //                 case Constants::NEGOTIATION:
    //                     $key = translate('Negotiation');
    //                     break;
    //                 case Constants::MAKE_DEAL:
    //                     $key = translate('Making a deal');
    //                     break;
    //                 default:
    //                     $key = translate('First contact');
    //                     break;
    //             }

    //             $arr[$key]['type'] = $value->type;
    //             $arr[$key]['list'][$n]['id'] = $value->id;
    //             $arr[$key]['list'][$n]['responsible'] = (isset($value->user)) ? $value->user->last_name . ' ' . $value->user->first_name . ' ' . $value->user->middle_name : '';
    //             $arr[$key]['list'][$n]['client'] = (isset($value->client)) ? $value->client->last_name . ' ' . $value->client->first_name . ' ' . $value->client->middle_name : '';
    //             $arr[$key]['list'][$n]['client_id'] = $value->client->id ?? 0;
    //             $arr[$key]['list'][$n]['day'] = date('d.m.Y', strtotime($value->date_deal));
    //             $arr[$key]['list'][$n]['time'] = date('H:i:s', strtotime($value->date_deal));
    //             $n++;
    //         }
    //     }

    //     return view('forthebuilder::clients.index-client-list', [
    //         'model' => $arr,
    //     ]);
    // }

    public function indexNewClients()
    {

        $lead_status_id = LeadStatus::where('name', 'новй')->first();

        $models = [];
        if (isset($lead_status_id))
            $models = Clients::where('lead_status_id', $lead_status_id->id)->paginate(config('params.pagination'));

        return view('forthebuilder::clients.index-new-leads', [
            'models' => $models,
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function getLeadList(Request $request)
    {

        $lead_status_id = LeadStatus::where('name', 'Пустой')->first();

        if ($request->ajax()) {
            $models = Clients::where('lead_status_id', $lead_status_id)->paginate(20);
        }

        return response()->json($models);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($house_flat_id)
    {

        // $models = Clients::all();
        // $leadStatuses = LeadStatus::all();
        $house_flat = '';
        if($house_flat_id != '0'){
            $house_flat = HouseFlat::find($house_flat_id);
        }
        $request_status = LeadStatus::NEW_STATUS;
        return view('forthebuilder::clients.create', [
            // 'models' => $models,
            'request_status' => $request_status,
            'house_flat' => $house_flat,
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

    public function store(ClientsRequest $request)
    {
        $data = $request->validated();
        $auth_user_id = Auth::user()->id;
        $client_id = $data['client_id'];
        if (isset($data['client_id']) && $data['client_id'] != null && $data['client_id'] != 'null') {
            $existPersonalInfo = PersonalInformations::where(['client_id' => $data['client_id'], 'series_number' => $data['series_number']])->first();
            if (isset($existPersonalInfo)) {
                $existClient = Clients::find($data['client_id']);
                $existClient->first_name = $data['first_name'];
                $existClient->last_name = $data['last_name'];
                $existClient->middle_name = $data['middle_name'];
                $existClient->phone = $data['phone'];
                $existClient->additional_phone = $data['additional_phone'];
                $existClient->email = $data['email'];
                $existClient->source = $data['source'];
                $existClient->lead_status = $data['lead_status'];
                $existClient->save();
                $client_id = $existClient->id;
            }
        } else {
            $newClient = new Clients();
            $newClient->user_id = $auth_user_id;
            $newClient->first_name = $data['first_name'];
            $newClient->last_name = $data['last_name'];
            $newClient->middle_name = $data['middle_name'];
            $newClient->phone = $data['phone'];
            $newClient->additional_phone = $data['additional_phone'];
            $newClient->email = $data['email'];
            $newClient->source = $data['source'];
            $newClient->status = Constants::CLIENT_ACTIVE;
            $newClient->save();

            $client_id = $newClient->id;
            if (isset($data['series_number'])) {
                $newPersonalInfo = new PersonalInformations();
                $newPersonalInfo->client_id = $newClient->id;
                $newPersonalInfo->issued_by = $data['issued_by'];
                $newPersonalInfo->series_number = $data['series_number'];
                $newPersonalInfo->inn = $data['inn'];
                $newPersonalInfo->save();
            }
        }

        $model = new Deal();
        $model->user_id = $auth_user_id;
        $model->client_id = $client_id;
        $model->date_deal = date('Y-m-d');
        $model->status = Constants::ACTIVE;
        $model->type = $data['lead_status'];
        $model->looking_for = $data['looking_for'];
        $model->house_id = $data['house_id'];
        $model->house_flat_id = $data['house_flat_id'];
        $model->budget = $data['budget'];
        $model->save();
        if (isset($model->id)) {
            return redirect()->route('forthebuilder.clients.index')->with('success', translate('successfully'));
        }


        // // $leads = Clients::all();
        // // $leads = Clients::where('series_number', str_replace(' ','', $data['series_number']))->first();
        // // foreach ($leads as $lead){
        // //     $series_number[] = str_replace(' ','', $lead->series_number);
        // // }
        // // if(!isset($leads)){
        // $model = new Clients();
        // $model->first_name = $data['first_name'];
        // $model->last_name = $data['last_name'];
        // $model->middle_name = $data['middle_name'];
        // $model->phone = $data['phone'];
        // $model->additional_phone = $data['additional_phone'];
        // $model->email = $data['email'];
        // $model->source = $data['source'];
        // $model->lead_status = $data['lead_status'];
        // $model->issued_by = $data['issued_by'];
        // $model->inn = str_replace(' ', '', $data['inn']);
        // // $model->referer = $data['referer'];
        // // $model->created_at = $data['created_at'];
        // // $model->requestid = $data['requestid'];
        // // $model->lead_status_id = $data['lead_status_id'];
        // $model->user_id = Auth::user()->id;
        // if ($model->save()) {
        //     Log::channel('action_logs2')->info("ползователь создал овую Лиды : " . $model->name . "", ['info-data' => $model]);

        //     return redirect()->route('forthebuilder.clients.index')->with('success', translate('successfully'));
        // }
        // }else{

        $leadStatuses = LeadStatus::all();
        return view('forthebuilder::clients.create', [
            'leadStatuses' => $leadStatuses,
            'data' => $data,
        ])->with('warning', translate('This lead is exist'));

        // $model = Clients::where('series_number', str_replace(' ','', $data['series_number']))->first();
        // return redirect()->route('forthebuilder.clients.create', $leads->id)->with('warning', translate('This lead is exist'));
        // }
    }
    public function clientHouse($client_id)
    {

        $models = House::orderBy('id', 'desc')->paginate(config('params.pagination'));
        return view('forthebuilder::house.index', [
            'models' => $models,
            'status' => 'client',
            'client_id' => $client_id,
            'all_notifications' => $this->getNotification()
        ]);
    }
    public function clientHouseFlat($id, $client_id)
    {
        $model = House::findOrFail($id);
        $flats = HouseFlat::select('id', 'floor', 'entrance', 'status', 'number_of_flat', 'price', 'areas', 'room_count')->where('house_id', $model->id)->orderBy('entrance', 'asc')->orderBy('floor', 'desc')->orderBy('created_at', 'asc')->get();
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

            if ($val->room_count == 'c')
                $count_commercial++;
            if ($val->room_count == 'p'){                
                $count_park++;
            }

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
        // pre($flats);

        $colors = [];
        if (!empty($statusColors)) {
            foreach ($statusColors as $value) {
                $colors[$value->status] = $value->color;
            }
        }
        // pre($arr);
        // return view('forthebuilder::house.show-more-second', [
        return view('forthebuilder::house.show-more', [
            'model' => $model,
            'flats' => $flats,
            'arr' => $arr,
            'colors' => $colors,
            'status' => 'client',
            'client_id' => $client_id,
            'all_notifications' => $this->getNotification()
        ]);
    }
    public function showDetails($house_id, $entrance, $flat_id, $client_id)
    {
        $model = House::findOrFail($house_id);
        $flats = HouseFlat::select('id', 'floor', 'entrance', 'status', 'number_of_flat', 'price', 'areas', 'room_count', 'house_id', 'doc_number')->where(['house_id' => $model->id, 'entrance' => $entrance])->orderBy('entrance', 'asc')->orderBy('floor', 'desc')->get();
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
                $f_j = translate('attic');

            if ($val->floor == 0)
                $f_j = translate('basement');

            if ($val->room_count == 'c') {
                $f_j = translate('Commercial');                
            }

            if ($val->room_count == 'p') {
                $f_j = translate('Park');                
            }

            $areas = json_decode($val->areas);
            // pre($areas->total);
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
            $arr['list'][$f_j][$n]['status'] = $val->status;
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

        // pre($arr);
        return view('forthebuilder::house.show-details', [
            'model' => $model,
            'flats' => $flats,
            'arr' => $arr,
            'colors' => $colors,
            'status' => 'client',
            'client_id' => $client_id,
            'all_notifications' => $this->getNotification()
        ]);
    }
    public function storeBudget(Request $request, $client_id)
    {
        $user = Auth::user();
        $model = Deal::find($request->deal_id);
        date_default_timezone_set("Asia/Tashkent");

        if (isset($request->budget)) {
            $model->budget = (float)$request->budget;
        }
        if (isset($request->looking_for)) {
            $model->looking_for = $request->looking_for;
        }
        if ($request->house_id != NULL) {
            $model->house_id = $request->house_id;
        }
        if ($request->house_flat_id != NULL) {
            $model->house_flat_id = $request->house_flat_id;
        }
        switch ($model->type) {
            case 1:
                $old_type = 'First contact';
                break;
            case 2:
                $old_type = 'Negotiation';
                break;
            case 3:
                $old_type = 'Making a deal';
                break;
        }
        switch ($request->type) {
            case 1:
                $new_type = 'First contact';
                break;
            case 2:
                $new_type = 'Negotiation';
                break;
            case 3:
                $new_type = 'Making a deal';
                break;
        }
        if($model->type == 'First contact' && $request->type == 'Negotiation'){
            if(isset($request->house_flat_id)){
                $house_flat = HouseFlat::find($request->house_flat_id);
                $house_flat->status = Constants::STATUS_BOOKING;
                $house_flat->save();
            }
        }
        $model->type = $request->type;
        if ($model->history == NULL) {
            $model->history = json_encode([['date' => date('Y-m-d H:i:s'), 'user' => $user->first_name, 'user_id' => $user->id, 'user_photo' => $user->avatar, 'new_type' => $new_type, 'old_type' => $old_type]]);
        } else {
            $old_history = json_decode($model->history);
            $old_history[] = ['date' => date('Y-m-d H:i:s'), 'user' => $user->first_name, 'user_id' => $user->id,  'user_photo' => $user->avatar, 'new_type' => $new_type, 'old_type' => $old_type];
            $model->history = json_encode($old_history);
        }
        if (isset($request->series_number) || isset($request->issued_by) || isset($request->inn)) {
            if (isset($request->personal_id)) {
                $personal = PersonalInformations::find($request->personal_id);
                $personal->series_number = $request->series_number;
                $personal->issued_by = $request->issued_by;
                $personal->inn = $request->inn;
            } else {
                $personal = new PersonalInformations();
                $personal->series_number = $request->series_number;
                $personal->issued_by = $request->issued_by;
                $personal->inn = $request->inn;
                $personal->client_id = $client_id;
            }
            $personal->save();
        }
        $model->save();

        return redirect()->route('forthebuilder.clients.show', [$model->client_id, "0", "0"])->with('status', translate('successfully'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function taskAnswer()
    {
        $model = Task::find();
    }

    public function show($id, $house_flat_id = 0, $task_id = 0)
    {

        $user = Auth::user();
        $connect_for=Constants::FOR_1;
        $connect_new=Constants::NEW_1;
        $data = DB::table($connect_for.'.clients as c')
            ->leftJoin($connect_for.'.deals as d', 'd.client_id', '=', 'c.id')
            //            ->leftJoin('forthebuilder.task as t', 't.deal_id', '=', 'd.id')
            ->leftJoin($connect_for.'.house as h', 'd.house_id', '=', 'h.id')
            ->leftJoin($connect_for.'.house_flat as h_f', 'd.house_flat_id', '=', 'h_f.id')
            ->leftJoin($connect_for.'.personal_informations as pi', 'c.id', '=', 'pi.client_id')
            ->leftJoin('icstroyc_newhouse_test.users as nu', 'nu.id', '=', 'd.user_id')
            ->leftJoin($connect_for.'.deals_files as df', 'df.deal_id', '=', 'd.id')
            ->where('c.id', $id)
            ->select(
                'c.id AS client_id',
                'c.first_name',
                'c.last_name',
                'c.middle_name',
                'c.phone',
                'c.additional_phone',
                'c.email',
                'c.gender',
                'c.status',
                'd.type',
                'd.status AS deal_status',
                'd.id AS deal_id',
                'd.budget AS budget',
                'd.looking_for AS looking_for',
                'd.history AS history',
                'df.guid AS deal_file',
                'h.name AS house_name',
                'h.id AS house_id',
                'h_f.id AS house_flat_id',
                'h_f.number_of_flat AS flat_number',
                'h_f.entrance AS flat_entrance',
                'h_f.areas AS flat_area',
                'nu.id AS user_id',
                'nu.first_name AS userFirstName',
                'nu.last_name AS userLastName',
                'nu.middle_name AS userMiddleName',
                'nu.email AS userEmail',
                'pi.id AS personal_id',
                'pi.series_number',
                'pi.inn',
                'pi.issued_by',
                'pi.given_date',
                'pi.address'
            )->get();
        // pre($data);
        $task_array = [];
        foreach ($data as $dat) {
            $task_array[] = $dat->deal_id;
        }
        $tasks = Task::select('id', 'performer_id', 'title', 'deal_id', 'type', 'task_date', 'answer', 'created_at')->whereIn('deal_id', $task_array)->get();
        $chats = Chat::whereIn('deal_id', $task_array)->get();
        if ($house_flat_id != '0') {
            $house_flat = HouseFlat::findOrFail($house_flat_id);
        } else {
            $house_flat = "";
        }
        $time = date('Y-m-d');

        if ($task_id != 0) {
            $task = Notification_::where('notifiable_id', $task_id)->first();
            $task->read_at = $time;
            $task->save();
        }

        $users = User::all();
        if ($user->role_id == Constants::MANAGER) {
            $users = User::where('id',$user->id)->get();
        }

        $client = Clients::find($id);
        // $comments = LeadComment::where('lead_id', $id)->get();
        // $leadStatuses = LeadStatus::all();
        // $deals = Deal::where('series_number', $model->series_number)->get();
        // $all_deal_id = [];
        // foreach ($deals as $deal) {
        //     $all_deal_id[] = $deal->id;
        // }
        // $personalinfo = PersonalInformations::where('series_number', $model->series_number)->first();
        // if (count($all_deal_id) > 0) {
        //     $installmentplans = InstallmentPlan::whereIn('deal_id', $all_deal_id)->get();
        // }
        // $users = User::all();

        // $listTasks = Task::where('lead_id', $id)->orderBy('id', 'desc')->paginate(config('params.pagination'));

        $months = [
            translate('January'), 
            translate('February'), 
            translate('March'), 
            translate('April'), 
            translate('May'), 
            translate('June'), 
            translate('July'), 
            translate('August'), 
            translate('September'), 
            translate('October'), 
            translate('November'), 
            translate('December')
        ];

        $line_month = '';
        foreach ($months as $key => $value) {
            $line_month .= $value.",";
        }
        $line_month = rtrim($line_month,",");
        
        return view('forthebuilder::clients.show', [
            // 'model' => $model,
            // 'modelDeals' => $modelDeals,
            'data' => $data,
            'house_flat' => $house_flat,
            'users' => $users,
            'tasks' => $tasks,
            'client' => $client,
            'chats' => $chats,
            'line_month' => $line_month,
            'all_notifications' => $this->getNotification()
            // 'leadStatuses' => $leadStatuses,
            // 'personalinfo' => $personalinfo,
            // 'installmentplans' => $installmentplans ?? [],
            // 'deals' => $deals ?? [],
            // 'users' => $users,
            // 'listTasks' => $listTasks,
        ]);
    }

    // /**
    //  * Show the specified resource.
    //  * @param int $id
    //  * @return Renderable
    //  */
    // public function show($id)
    // {
    //     $model = Clients::findOrFail($id);
    //     $comments = LeadComment::where('lead_id', $id)->get();
    //     $leadStatuses = LeadStatus::all();
    //     $deals = Deal::where('series_number', $model->series_number)->get();
    //     $all_deal_id = [];
    //     foreach ($deals as $deal) {
    //         $all_deal_id[] = $deal->id;
    //     }
    //     $personalinfo = PersonalInformations::where('series_number', $model->series_number)->first();
    //     if (count($all_deal_id) > 0) {
    //         $installmentplans = InstallmentPlan::whereIn('deal_id', $all_deal_id)->get();
    //     }
    //     $users = User::all();

    //     $listTasks = Task::where('lead_id', $id)->orderBy('id', 'desc')->paginate(config('params.pagination'));

    //     return view('forthebuilder::clients.show', [
    //         'model' => $model,
    //         'comments' => $comments,
    //         'leadStatuses' => $leadStatuses,
    //         'personalinfo' => $personalinfo,
    //         'installmentplans' => $installmentplans ?? [],
    //         'deals' => $deals ?? [],
    //         'users' => $users,
    //         'listTasks' => $listTasks,
    //     ]);
    // }

    public function storePhoto(Request $request){
        //=================== file yuklanyapti ===================
        $store_file = $request->file('store_file');
        $store_file_array = explode('.', $store_file->getClientOriginalName());
        $store_file_name = $store_file_array[0];
        $store_file_ext = $store_file_array[1];
        $file_name =  md5($store_file_name . time()) . '.' . $store_file_ext;
        $deals_file = DealsFile::where('deal_id', $request->deal_id)->get();
//        if (file_exists(storage_path('app/public/uploads/client_show/' . $request->deal_id))) {
//            $path = asset("storage/app/public/uploads/client_show/" . $request->deal_id);
//            foreach ($deals_file as $deal_file){
//                Storage::delete($deal_file);
//            }
//        }
        if(isset($deals_file) && count($deals_file)){
            foreach ($deals_file as $deal_file){
                $file_url = storage_path('app/public/uploads/client_show/' . $request->deal_id.'/'.$deal_file->guid);
                if(file_exists($file_url)){
                    unlink($file_url);
                }
            }
        }
        $store_file->storeAs('public/uploads/client_show/' . $request->deal_id, $file_name);
        $filesize =  round($store_file->getSize()/1024*100)/100;
        if(isset($deals_file) && count($deals_file)){
            $model = DealsFile::where('deal_id', $request->deal_id)->first();
            $model->deal_id = $request->deal_id;
            $model->name = $store_file_name;
            $model->guid = $file_name;
            $model->ext = $store_file_ext;
            $model->size = $filesize ?? '';
            $model->main_image = 1;
            $model->save();
        }else{
            DealsFile::create([
                'deal_id' => $request->deal_id,
                'name' => $store_file_name,
                'guid' => $file_name,
                'ext' => $store_file_ext,
                'size' => $filesize ?? '',
                'main_image' => 1,
            ]);
        }
        return redirect()->route('forthebuilder.clients.show', ['id'=>$request->client_id, 'house_flat_id'=>0, 'task_id'=>0]);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $model = Clients::findOrFail($id);
        $leadStatuses = LeadStatus::all();
        return view('forthebuilder::clients.edit', [
            'model' => $model,
            'leadStatuses' => $leadStatuses,
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ClientsRequest $request, $id)
    {
        $data = $request->validated();

        $model = Clients::find($id);
        $model->first_name = $data['first_name'];
        $model->last_name = $data['last_name'];
        $model->middle_name = $data['middle_name'];
        $model->phone = $data['phone'];
        $model->additional_phone = $data['additional_phone'];
        $model->email = $data['email'];
        $model->source = $data['source'];
        $model->lead_status = $data['lead_status'];
        $model->save();

        Log::channel('action_logs2')->info("пользователь обнови " . $model->first_name . " Киент", ['info-data' => $model]);

        return redirect()->route('forthebuilder.clients.index')->with('success', translate('successfully'));
    }

    // public function updateType(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'type' => 'required|integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->route('forthebuilder.client-lists.indexClientList')->withErrors($validator);
    //     }

    //     if ($request->ajax()) {
    //         $model = Deal::findOrFail($id);
    //         $model->type = $request->type;
    //         $model->save();

    //         $mStatus = Deal::select('type', DB::raw('count(*) as total'))
    //             ->groupBy('type')
    //             ->pluck('total', 'type')
    //             ->toArray();
    //         //            $leadstatus = LeadStatus::all();
    //         //            $mStatus = $leadstatus->leads->count();
    //         return response()->json([
    //             'mStatus' => $mStatus,
    //             'success' => 'Статус измeнён'
    //         ]);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $leads = Clients::findOrFail($id);
            $leads->status = Constants::CLIENT_DELETED;

            $deals = Deal::select('id', 'client_id')->where('client_id', $id);
            $activeDeals = $deals->whereIn('status', [Constants::ACTIVE, Constants::NOT_IMPLEMENTED])->where('type', Constants::MAKE_DEAL)->first();
            if (isset($activeDeals) && !empty($activeDeals))
                return redirect()->route('forthebuilder.clients.index')->with('delete_warning', translate('The client has active deals'));

            $deals = $deals->get();
            $deal_id = [];
            foreach ($deals as $deal) {
                $deal_id[] = $deal->id;
                $deal->deleted_at = date("Y-m-d H:i:s");
                $deal->status = Constants::NOT_IMPLEMENTED;
                $deal->save();
            }

            $payStatus = PayStatus::whereIn('deal_id', $deal_id)->whereIn('status', [Constants::NOT_PAID, Constants::HALF_PAY])->first();
            if (isset($payStatus) && !empty($payStatus))
                return redirect()->route('forthebuilder.clients.index')->with('delete_warning', translate('The client has active installment plan'));

            $tasks = Task::whereIn('deal_id', $deal_id)->get();
            foreach ($tasks as $task) {
                $task->deleted_at = date("Y-m-d H:i:s");
                $task->status = Constants::DID_NOT_DO_IT;
                $task->save();
            }

            $bookings = Booking::whereIn('deal_id', $deal_id);
            $issetBooking = $bookings->where('status', Constants::BOOKING_ACTIVE)->first();
            if (isset($issetBooking))
                return redirect()->route('forthebuilder.clients.index')->with('delete_warning', translate('The client has active bookings'));

            $bookings = $bookings->where('status', Constants::BOOKING_ACTIVE)->get();
            foreach ($bookings as $booking) {
                $booking->deleted_at = date("Y-m-d H:i:s");
                $booking->status = Constants::BOOKING_ARCHIVE;
                $booking->save();
            }

            $chats = Chat::whereIn('deal_id', $deal_id)->get();
            foreach ($chats as $chat) {
                $chat->deleted_at = date("Y-m-d H:i:s");
                $chat->save();
            }
            $leads->save();

            Log::channel('action_logs2')->info("пользователь удаил " . $leads->name . " Лиы", ['info-data' => $leads]);
            DB::commit();
            return redirect()->route('forthebuilder.clients.index')->with('deleted', translate('Data deleted successfuly'));
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function import(Request $request)
    {
        $request->validate(
            [
                'lead_file' => 'required'
            ],
            // ['lead_file.required' => 'this is my custom error message for required']
        );
        // dd($request->file('lead_file'));
        Excel::import(new LeadsImport, $request->file('lead_file'));

        return back()->with('success', 'Success!');
    }

    public function addTask(TaskRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'Новый';
        $data['user_id'] = Auth::user()->id;
        $title = $data['title'];

        $model = Task::create($data);
        Log::channel('action_logs2')->info("ползователь создал овую Task : " . $model->title . "", ['info-data' => $model]);

        $userIdTask = User::findOrFail($data['user_task_id']);

        event(new RealTimeMessage($title, $userIdTask));
        Notification::send($userIdTask, new TaskNotification($model));

        return redirect()->route('forthebuilder.clients.show', ['id' => $data['lead_id'], "0", "0"]);

        // return redirect()->route('forthebuilder.clients.show')->with('success', translate('successfully'));
    }
}
