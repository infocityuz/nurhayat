<?php

namespace Modules\ForTheBuilder\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\components\ImageResize;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\ForTheBuilder\Entities\Deal;
use Modules\ForTheBuilder\Entities\House;
use Modules\ForTheBuilder\Entities\Notification_;
use Modules\ForTheBuilder\Entities\Task;
use Modules\ForTheBuilder\Entities\Currency;
use Modules\ForTheBuilder\Http\Requests\ForTheBuilderUserRequest;
use Modules\ForTheBuilder\Entities\Constants;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

function getBetweenDates($startDate, $endDate) {
    $rangArray = [];
 
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);
 
    for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
        $date = date('d.m', $currentDate);
        $rangArray[] = $date;
    }
 
    return $rangArray;
}

class UserController extends Controller
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
        // if(Gate::allows('isAdmin')){
        //     $models = User::where('status',2)->where('id', '!=', Auth::user()->id)->orderBy('id','desc')->paginate(config('params.pagination'));
        // }else{
        // }
        $models = User::where('status',2)->where('id', '!=', Auth::user()->id)->where('role_id','!=', Constants::SUPERADMIN)->orderBy('id','desc')->paginate(5);
        // dd($models);

        return view('forthebuilder::user.index',[
            'models' => $models,
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function settings(){
        return view('forthebuilder::settings.index', ['all_notifications' => $this->getNotification()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        dd(Storage::class);
        $roles = Role::all();

        return view('forthebuilder::user.create',[
            'roles' => $roles,
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ForTheBuilderUserRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 2;
        $data['password'] = Hash::make($data['password']);
        $image = $data['avatar'] ?? '';
        if (!empty($image)) {
            $imageName = md5(time().$image).'.'.$image->getClientOriginalExtension();
            $data['avatar'] = $imageName;
        }
        $data['birth_date'] = ((isset($data['birth_date']) && !empty($data['birth_date'])) ? date('Y-m-d', strtotime($data['birth_date'])) : NULL);
        $data['gender'] = (int)$_POST['gender'];
        $model = User::create($data);
        // $model->birth_date = ((isset($data['birth_date']) && !empty($data['birth_date'])) ? date('Y-m-d', strtotime($data['birth_date'])) : NULL);
        
        if (!empty($image)) {
            //bu yerda orginal rasm yuklanyapti ochilgan papkaga
            $image->move(public_path('uploads/user/'.$model->id),$imageName);

            //bu yerda orginal rasm  app/components/imageresize.php fayliga kesiladigan rasm manzili ko'rsatilyapti
            $imageR = new ImageResize( public_path('uploads/user/'.$model->id . '/' . $imageName));

            //bu yerda orginal rasm  app/components/imageresize.php fayli orqali kesilyapti
            $imageR->resizeToBestFit(config('params.medium_image.width'), config('params.medium_image.width'))->save(public_path('uploads/user/'.$model->id . '/s_' . $imageName));
            //bu yerda orginal rasm  o'chirilyapti.chunki endi bizga kerakmas orginali biz o'zimizga kerkligicha kesib oldik
            File::delete(public_path('uploads/user/'.$model->id.'/'.$imageName));

        }
        Log::channel('action_logs2')->info("пользователь создал новую Пользователь : " . $model->first_name."",['info-data'=>$model]);

        return redirect()->route('forthebuilder.user.index')->with('success', __('locale.successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $model = User::findOrfail($id);
        $user = Auth::user();
        if(Gate::allows('isAdmin')){
            $users = User::where('status',2)->where('id', '!=', $user->id)->orderBy('id','desc')->paginate(config('params.pagination'));
        }else{
            $users = User::where('status',2)->where('id', '!=', $user->id)->where('role_id', 2)->orderBy('id','desc')->paginate(config('params.pagination'));
        }
        $my_tasks = Task::where('performer_id', $id)->get();
        $tasks = count(Task::where('performer_id', $id)->get());
        $tasks_ended = count(Task::where('performer_id', $id)->where('status', 1)->get());
        $tasks_not_ended = count(Task::where('performer_id', $id)->where('status', NULL)->get());
        $task_count = [];
        $task_count['count'] = [];
        $task_count['task_date'] = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $monthly_count = [];
        if(!empty($my_tasks)){
            foreach($my_tasks as $task){
                $task_array = explode('-',$task->task_date);
                if($task_array[0] == date('Y')){
                    $task_count['task_date'][] = $task_array[1];
                    $taskcount = count(Task::where('task_date', $task->task_date)->get());
                    $task_count['count'][] = $taskcount;
                }
            }
            if(!empty($task_count['task_date'])){
                $monthly_count = array_count_values($task_count['task_date']);
            }
        }


        // statistics
        $connect_for=Constants::FOR_1;
        $connect_new=Constants::NEW_1;

            
        $new_users=DB::table($connect_for.'.deals as dt1')
        ->join($connect_for.'.clients as dt2', 'dt2.id', '=', 'dt1.client_id')
        ->where('dt1.type',Constants::FIRST_CONTACT)
        ->where('dt1.user_id',$id)
        ->count();
        

        $in_negotiations=DB::table($connect_for.'.deals as dt1')
        ->where('dt1.type',Constants::NEGOTIATION)
        ->where('dt1.user_id',$id)
        ->count();

        $make_deal=DB::table($connect_for.'.deals as dt1')
        ->where('dt1.type',Constants::MAKE_DEAL)
        ->where('dt1.user_id',$id)
        ->count();

        $date=Carbon::now()->format('Y-m-d');
        $today=DB::table($connect_for.'.task as task')
        ->where('task.task_date','=',$date)
        ->where('task.user_id',$id)
        ->where('task.status',0)
        ->count();

        $datetime = date("Y-m-d", strtotime('tomorrow'));
        $tomorrow=DB::table($connect_for.'.task as task')
        ->where('task.task_date','=',$datetime)
        ->where('task.user_id',$id)
        ->where('task.status',0)
        ->count();
        
        $day_after_a_week=Carbon::now()->addDay(7)->format('Y-m-d');
        $week=DB::table($connect_for.'.task as task')
        ->where('task.task_date','<',$day_after_a_week)
        ->where('task.user_id',$id)
        ->where('task.task_date','>=',$date)
        ->where('task.status',0)
        ->count();

        $full_task=DB::table($connect_for.'.task as task')
        ->where('task.task_date','>=',$date)
        ->where('task.user_id',$id)
        ->where('task.status',0)
        ->count();
        
        $overdue_tasks=DB::table($connect_for.'.task as task')
        ->where('task.task_date','<',$date)
        ->where('task.user_id',$id)
        ->where('task.status',0)
        ->count();


        $month_prices = DB::table($connect_for.'.deals as dt1')
        ->join($connect_for.'.house_flat as dt2', 'dt2.id', '=', 'dt1.house_flat_id')
        ->join($connect_new.'.users as dt3', 'dt3.id', '=', 'dt1.user_id')
        ->where('dt2.status',Constants::STATUS_SOLD)               
        ->where('dt1.user_id',$id)
        ->orderBy('dt3.id', 'desc')
        ->select('dt3.id','dt1.price_sell','dt1.date_deal','dt3.first_name','dt3.last_name')
        ->get();


        $price=0;
        $priceArr=[];
        for ($j=0; $j <= 11; $j++) { 
            $priceArr[$j] = 0;
        }
        
        $year=Carbon::now()->format('y');
        $month=Carbon::now()->format('m');
        $last_date = cal_days_in_month(CAL_GREGORIAN, $month,$year);

        $price_day_array = [];
        $month_day = [];
        for ($i=0; $i <= $last_date; $i++) { 
            $price_day_array[$i] = 0;
            $month_day[$i] = $i;
        }
        $core_chart="";
        $in = [];
        foreach ($month_prices as  $value) {
            $myDate = date('Y-m-d',strtotime($value->date_deal));
            $date_table = Carbon::createFromFormat('Y-m-d', $myDate);
            $month_code = $date_table->format('n');
            $date_day=Carbon::now()->format('m');
            $month_code_day = $date_table->format('j');
            if ($month_code==$date_day) {
                $in[$value->id]['first_name'] = $value->first_name;
                $in[$value->id]['price_sell'] = ($in[$value->id]['price_sell'] ?? 0) + $value->price_sell;
            }
            
            if ($month_code==$date_day) {
                $price_day_array[$month_code_day-1] +=$value->price_sell;
            }
            
            $priceArr[$month_code-1] += $value->price_sell;
            $price +=$value->price_sell;
        }
        
        $core_chart ="['".translate('New Clients')."',".$new_users."],['".translate('For a negotiation')."',".$in_negotiations."],['".translate('Making a deal')."',".$make_deal."]";

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

        $data=[
           'date_today'=>$date,
           'new_clients'=>$new_users, // Новые клиенты
           'in_negotiations'=>$in_negotiations, // На переговорах
           'make_deal'=>$make_deal, // Заключение сделки
           'today'=>$today,
           'tomorrow'=>$tomorrow,
           'week'=>$week,
           'full_task'=>$full_task,
           'overdue_tasks'=>$overdue_tasks,
           'price'=>$price,
           'month_sales_price'=>$priceArr,
           'month_day'=>$month_day,
           'price_day_array'=>$price_day_array,
           'core_chart'=>$core_chart
        ];

        $line_month = '';
        foreach ($months as $key => $value) {
            $line_month .= $value.",";
        }
        $line_month = rtrim($line_month,",");


        return view('forthebuilder::user.show',[
            'id' => $id,
            'data' => $data,
            'model' => $model,
            'users' => $users,
            'user' => $user,
            'tasks' => $tasks,
            'tasks_ended' => $tasks_ended,
            'tasks_not_ended' => $tasks_not_ended,
            'monthly_count' => $monthly_count,
            'my_tasks' => $my_tasks,
            'task_count' => $task_count,
            'line_month' => $line_month,
            'months'=> json_encode($months),
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = User::findOrfail($id);
        $roles = Role::all();

        return view('forthebuilder::user.edit',[
            'model' => $model,
            'roles' => $roles,
            'all_notifications' => $this->getNotification()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(ForTheBuilderUserRequest $request, $id)
    {
        $data = $request->validated();
        $user = Auth::user();
        $model = User::findOrFail($id);
        $deals = Deal::where('user_id',$user->id)->get();
        $model->first_name = $data['first_name'];
        $model->last_name = $data['last_name'];
        $model->middle_name = $data['middle_name'];
        if ($model->status == 10) $model->status = 10;
        else $model->status = $data['status'];
        $model->email = $data['email'];
        $model->role_id = $data['role_id'];
        
        $data['birth_date'] = ((isset($data['birth_date']) && !empty($data['birth_date'])) ? date('Y-m-d', strtotime($data['birth_date'])) : NULL);
        $model->birth_date = $data['birth_date'];
        
        $model->phone_number = $data['phone_number'];
        $data['gender'] = (int)$_POST['gender'];
        $model->gender = $data['gender'];
        $model->save();

        if(!empty($request->input('current_password')) && !empty($request->input('password'))) {
            if(!Hash::check($request->input('current_password'), $model->password)){
                return back()->with('current_password', 'Current password does not match!');
            }else{
                $model->fill(['password' => Hash::make($request->input('password'))])->save();
            }
        }
        if (isset($data['avatar']))
        {
            $image = $data['avatar'];
            $image_old = $model->avatar;
            $imageName = md5(time().$image).'.'.$image->extension();

            //bu yerda orginal rasm yuklanyapti ochilgan papkaga
            $image->move(public_path('uploads/user/'.$model->id),$imageName);

            //bu yerda orginal rasm  app/components/imageresize.php fayliga kesiladigan rasm manzili ko'rsatilyapti
            $imageR = new ImageResize( public_path('uploads/user/'.$model->id . '/' . $imageName));

            //bu yerda orginal rasm  app/components/imageresize.php fayli orqali kesilyapti
            $imageR->resizeToBestFit(config('params.medium_image.width'), config('params.medium_image.width'))->save(public_path('uploads/user/'.$model->id . '/s_' . $imageName));
            //bu yerda orginal rasm  o'chirilyapti.chunki endi bizga kerakmas orginali biz o'zimizga kerkligicha kesib oldik
            File::delete(public_path('uploads/user/'.$model->id.'/'.$imageName));

            if (!empty($image_old)) {
                File::delete(public_path('uploads/user/'.$model->id.'/s_'.$image_old));
            }
            $model->avatar = $imageName;
            $model->save();
        }
        else{
            $data['avatar'] = NULL;
            $imageName = "";
        }
        if(count($deals)>0){
            foreach ($deals as $deal){
                if($model->first_name != $data['first_name'] || $model->avatar != $data['avatar']) {
                    $deal_history_array = [];
                    if (!is_array($deal->history)) {
                        $deal_history = json_decode($deal->history);
                        if(isset($deal_history) && count($deal_history)>0){
                            foreach ($deal_history as $d_history){
                                $deal_history_array[] = ['date' => $d_history->date, 'user' => $data['first_name'], 'user_id' => $user->id, 'user_photo' => $imageName, 'new_type' => $d_history->new_type??'', 'old_type' => $d_history->old_type??''];
                            }
                        }
                    }else{
                        $deal_history = $deal->history;
                        if(isset($deal_history) && count($deal_history)>0){
                            foreach ($deal_history as $d_history){
                                $deal_history_array[] = ['date' => $d_history->date, 'user' => $data['first_name'], 'user_id' => $user->id, 'user_photo' => $imageName, 'new_type' => $d_history->new_type??'', 'old_type' => $d_history->old_type??''];
                            }
                        }
                    }
                    $deal->history = json_encode($deal_history_array);
                    $deal->save();
                }
            }
        }

        Log::channel('action_logs2')->info("пользователь обновил ".$model->first_name." Пользователь",['info-data'=>$model]);

        return redirect()->route('forthebuilder.user.index')->with('success', __('locale.successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if($user->id != Auth::user()->id) $user->delete();

        Log::channel('action_logs2')->info("пользователь удалил ".$user->first_name." Пользователь",['info-data'=>$user]);

        return redirect()->route('forthebuilder.user.index')->with('success', __('locale.deleted'));
    }


    public function chat()
    {
        $user = Auth::user();
        $id = $user->id;
        $model = User::findOrfail($id);
        if(Gate::allows('isAdmin')){
            $users = User::where('status',2)->where('id', '!=', $user->id)->orderBy('id','desc')->paginate(config('params.pagination'));
        }else{
            $users = User::where('status',2)->where('id', '!=', $user->id)->where('role_id', 2)->orderBy('id','desc')->paginate(config('params.pagination'));
        }
        $my_tasks = Task::where('performer_id', $id)->get();
        $tasks = count(Task::where('performer_id', $id)->get());
        $tasks_ended = count(Task::where('performer_id', $id)->where('status', 1)->get());
        $tasks_not_ended = count(Task::where('performer_id', $id)->where('status', NULL)->get());
        $task_count = [];
        $task_count['count'] = [];
        $task_count['task_date'] = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $monthly_count = [];
        if(!empty($my_tasks)){
            foreach($my_tasks as $task){
                $task_array = explode('-',$task->task_date);
                if($task_array[0] == date('Y')){
                    $task_count['task_date'][] = $task_array[1];
                    $taskcount = count(Task::where('task_date', $task->task_date)->get());
                    $task_count['count'][] = $taskcount;
                }
            }
            if(!empty($task_count['task_date'])){
                $monthly_count = array_count_values($task_count['task_date']);
            }
        }

        return view('forthebuilder::user.chat',[
            'model' => $model,
            'users' => $users,
            'user' => $user,
            'tasks' => $tasks,
            'tasks_ended' => $tasks_ended,
            'tasks_not_ended' => $tasks_not_ended,
            'monthly_count' => $monthly_count,
            'my_tasks' => $my_tasks,
            'task_count' => $task_count,
            'all_notifications' => $this->getNotification()
        ]);
    }


    public function filtr($arr)
    {
        
        if (!empty($arr)) {
            $main_arr = explode(',',$arr);

            $date = $main_arr[1];
            $id = (int)$main_arr[0];

            $arr = explode(' - ', $date);
            $start = date('Y-m-d 00:00:00', strtotime($arr[0]));
            $end = date('Y-m-d 23:59:59', strtotime($arr[1]));

            // pre($start.'---'.$end);

            $model = User::findOrfail($id);
            $user = Auth::user();
            if(Gate::allows('isAdmin')){
                $users = User::where('status',2)->where('id', '!=', $user->id)->orderBy('id','desc')->paginate(config('params.pagination'));
            }else{
                $users = User::where('status',2)->where('id', '!=', $user->id)->where('role_id', 2)->orderBy('id','desc')->paginate(config('params.pagination'));
            }
            $my_tasks = Task::where('performer_id', $id)->where('task_date','>=',$start)->where('task_date','<=',$end)->get();
            $tasks = count(Task::where('performer_id', $id)->where('task_date','>=',$start)->where('task_date','<=',$end)->get());
            $tasks_ended = count(Task::where('performer_id', $id)->where('status', 1)->where('task_date','>=',$start)->where('task_date','<=',$end)->get());
            $tasks_not_ended = count(Task::where('performer_id', $id)->where('status', NULL)->where('task_date','>=',$start)->where('task_date','<=',$end)->get());
            $task_count = [];
            $task_count['count'] = [];
            $task_count['task_date'] = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
            $monthly_count = [];
            if(!empty($my_tasks)){
                foreach($my_tasks as $task){
                    $task_array = explode('-',$task->task_date);
                    if($task_array[0] == date('Y')){
                        $task_count['task_date'][] = $task_array[1];
                        $taskcount = count(Task::where('task_date', $task->task_date)->get());
                        $task_count['count'][] = $taskcount;
                    }
                }
                if(!empty($task_count['task_date'])){
                    $monthly_count = array_count_values($task_count['task_date']);
                }
            }

            // Deal
            $connect_for=Constants::FOR_1;
            $connect_new=Constants::NEW_1;

             //  ----- deal ------
            $new_users = DB::table($connect_for.'.deals as dt1')->select('history')
            ->where('dt1.user_id',$id)
            ->whereNotNull('dt1.history',)
            ->get();

            $new_users_count = 0;
            $negotiation_users_count = 0;
            $making_a_deal_users_count = 0;
            
            $new_users_arr = [];
            if (!empty($new_users)) {
                foreach ($new_users as $key => $value) {
                    $new_arr = json_decode($value->history);
                    foreach ($new_arr as $keyn => $valuen) {
                        // new users
                        if (($valuen->date >= $start && $valuen->date <= $end) && isset($valuen->new_type) && $valuen->new_type == 'First contact') {
                            $new_users_count++;
                        }

                        // negotiation_users
                        if (($valuen->date >= $start && $valuen->date <= $end) && isset($valuen->new_type) && $valuen->new_type == 'Negotiation') {
                            $negotiation_users_count++;
                        }

                        // Making a deal
                        if (($valuen->date >= $start && $valuen->date <= $end) && isset($valuen->new_type) && $valuen->new_type == 'Making a deal') {
                            $making_a_deal_users_count++;
                        }
                    }
                }    
            }

            // ----- tasks ------

            // Задачи
            $full_task = DB::table($connect_for.'.task as task')
            ->where('task.task_date','>=',$start)
            ->where('task.task_date','<=',$end)
            ->where('task.status',0)
            ->where('task.user_id',$id)
            ->count();

            // Просроченные задачи
            $overdue_tasks = DB::table($connect_for.'.task as task')
            ->where('task.task_date','<',$start)
            ->where('task.status',0)
            ->where('task.user_id',$id)
            ->count();

            $today = $full_task;
            $tomorrow = 0;
            $week = 0;


            // graph, price
            $month_prices = DB::table($connect_for.'.deals as dt1')
            ->join($connect_for.'.house_flat as dt2', 'dt2.id', '=', 'dt1.house_flat_id')
            ->join($connect_new.'.users as dt3', 'dt3.id', '=', 'dt1.user_id')
            ->where('dt2.status',Constants::STATUS_SOLD)
            ->where('dt2.sold_date','>=',$start)
            ->where('dt2.sold_date','<=',$end)
            ->where('dt1.user_id',$id)
            ->orderBy('dt3.id', 'desc')
            // ->where('dt3.role_id',2)
            ->select('dt3.id','dt1.price_sell','dt1.date_deal','dt3.first_name','dt3.last_name')
            ->get();


            

            $price=0;
            $priceArr=[];
            for ($j=0; $j <= 11; $j++) { 
                $priceArr[$j] = 0;
            }
            
            $dates = getBetweenDates(date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end)));
            

            $price_day_array = [];
            $month_day = $dates;
            for ($i=0; $i <= count($dates); $i++) { 
                $price_day_array[$i] = 0;
                // $month_day[$i] = $i;
            }
            $core_chart="";
            $in = [];

            
            
            foreach ($month_prices as  $value) {
                // dd($value);
                $myDate=$value->date_deal;
                $date_table = Carbon::createFromFormat('Y-m-d', $myDate);
                $month_code = $date_table->format('n');
                $date_day=Carbon::now()->format('m');

                $month_code_day = $date_table->format('j');
                
                if ($month_code == $date_day) {
                    $in[$value->id]['first_name'] = $value->first_name;
                    $in[$value->id]['price_sell'] = ($in[$value->id]['price_sell'] ?? 0) + $value->price_sell;
                    // $core_chart.="['".$value->first_name."',     ".$value->price_sell."],";
                }
                // dd($month_code);
                
                if ($month_code==$date_day) {
                    $price_day_array[$month_code_day-1] +=$value->price_sell;
                }
                
                // $mont_code = $date_table->format('m');
                $priceArr[$month_code-1] += $value->price_sell;
                $price +=$value->price_sell;
            }
            // dd($in);
            foreach ($in as $key => $value) {
                // dd($value);
                $core_chart.="['".$value['first_name']."',".$value['price_sell']."],";
            }      
            

            


            $data = [
               'start' => $start,
               'end' => $end,
               'new_clients'=>$new_users_count, // Новые клиенты
               'in_negotiations'=>$negotiation_users_count, // На переговорах
               'make_deal'=>$making_a_deal_users_count, // Заключение сделки
               'today'=>$today,
               'tomorrow'=>$tomorrow,
               'week'=>$week,
               'full_task'=>$full_task,
               'overdue_tasks'=>$overdue_tasks,
               'price'=>$price,
               'month_sales_price'=>$priceArr,
               'month_day'=>$month_day,
               'price_day_array'=>$price_day_array,
               'core_chart'=>$core_chart
            ];

        }

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


        return view('forthebuilder::user.filtr',[
            'id' => $id,
            'data' => $data,
            'model' => $model,
            'users' => $users,
            'user' => $user,
            'tasks' => $tasks,
            'tasks_ended' => $tasks_ended,
            'tasks_not_ended' => $tasks_not_ended,
            'monthly_count' => $monthly_count,
            'my_tasks' => $my_tasks,
            'task_count' => $task_count,
            'months' => json_encode($months),
            'all_notifications' => $this->getNotification()
        ]);
    }


    public function report()
    {
        return view('forthebuilder::user.report',[
            'all_notifications' => $this->getNotification()
        ]);
    }

    // reportClients
    public function reportClients()
    {
        $models = House::orderBy('id', 'desc')->paginate(15);

        return view('forthebuilder::user.report-clients',[
            'status' => 'report-clients',
            'models' => $models,
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function reportClientsIndex($id)
    {
        $connect_for=Constants::FOR_1;
        $connect_new=Constants::NEW_1;

        $new_users=DB::table($connect_for.'.deals as dt1')
            ->join($connect_for.'.clients as dt2', 'dt2.id', '=', 'dt1.client_id')
            ->where('dt1.type',Constants::FIRST_CONTACT)
            ->where('dt1.house_id',$id)
            ->count();
            
        $all_clients_count=DB::table($connect_for.'.clients')
        ->where('status',1)->count();
        
        $all_deals_count = DB::table($connect_for.'.deals')
        ->where('deleted_at',NULL)
        ->where('house_id',$id)
        ->count();
        
        $in_negotiations=DB::table($connect_for.'.deals as dt1')
        ->where('dt1.type',Constants::NEGOTIATION)
        ->where('dt1.house_id',$id)
        ->count();

        $make_deal=DB::table($connect_for.'.deals as dt1')
        ->where('dt1.type',Constants::MAKE_DEAL)
        ->where('dt1.house_id',$id)
        ->count();

        $source = DB::table($connect_for.'.clients')
        ->select('source', DB::raw('count(*) as total'))
        ->groupBy('source')
        ->where('source','!=',null)
        ->get();
        
        $source_name = [];
        $source_data = [];
        $source_color = [];
        $sources = '';
        if(!empty($source)){
             foreach ($source as $key => $value) {
                array_push($source_name, $value->source);
                array_push($source_data, $value->total);
                array_push($source_color, 'rgb('.rand(0,255).', '.rand(0,255).', '.rand(0,255).')');
                $sources .="['".$value->source."',".$value->total."],";
            }
        }

        $month_prices = DB::table($connect_for.'.deals as dt1')
            ->join($connect_for.'.house_flat as dt2', 'dt2.id', '=', 'dt1.house_flat_id')
            ->join($connect_new.'.users as dt3', 'dt3.id', '=', 'dt1.user_id')
            ->where('dt2.status',Constants::STATUS_SOLD)
            ->orderBy('dt3.id', 'desc')
            ->where('dt1.house_id',$id)
            ->select('dt3.id','dt1.price_sell','dt1.date_deal','dt3.first_name','dt3.last_name')
            ->get();


        $names = [];
        $counts = [];
        $colors = [];

        $price=0;
        $priceArr=[];
        for ($j=0; $j <= 11; $j++) { 
            $priceArr[$j] = 0;
        }
        
        $year=Carbon::now()->format('y');
        $month=Carbon::now()->format('m');
        $last_date = cal_days_in_month(CAL_GREGORIAN, $month,$year);

        $price_day_array = [];
        $month_day = [];
        for ($i=0; $i <= $last_date; $i++) { 
            $price_day_array[$i] = 0;
            $month_day[$i] = $i;
        }
        $core_chart="";
        $in = [];


        foreach ($month_prices as  $value) {
            
            $myDate = date('Y-m-d', strtotime($value->date_deal));
            $date_table = Carbon::createFromFormat('Y-m-d', $myDate);
            $month_code = $date_table->format('n');
            $date_day=Carbon::now()->format('m');
            $month_code_day = $date_table->format('j');
            if ($month_code==$date_day) {
                $in[$value->id]['first_name'] = $value->first_name;
                $in[$value->id]['price_sell'] = ($in[$value->id]['price_sell'] ?? 0) + $value->price_sell;
                // $core_chart.="['".$value->first_name."',     ".$value->price_sell."],";
            }
            // dd($month_code);
            
            if ($month_code==$date_day) {
                $price_day_array[$month_code_day-1] +=$value->price_sell;
            }
            
            // $mont_code = $date_table->format('m');
            $priceArr[$month_code-1] += $value->price_sell;
            $price +=$value->price_sell;
        }
        // dd($in);
        

        foreach ($in as $key => $value) {
            // dd($value);
            $core_chart.="['".$value['first_name']."',".$value['price_sell']."],";
            array_push($names, $value['first_name']);
            array_push($counts, $value['price_sell']);
            array_push($colors, 'rgb('.rand(0,255).', '.rand(0,255).', '.rand(0,255).')');
        }

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
        

        $data=[
           'new_clients'=>$new_users, // Новые клиенты
           'in_negotiations'=>$in_negotiations, // На переговорах
           'make_deal'=>$make_deal, // Заключение сделки
           'all_clients_count' => $all_clients_count,
           'all_deals_count' => $all_deals_count,
           'names' => json_encode($names),
           'counts' => json_encode($counts),
           'colors' => json_encode($colors),
           'source_name' => json_encode($source_name),
           'source_data' => json_encode($source_data),
           'source_color' => json_encode($source_color),
           'core_chart' => $core_chart,
           'sources' => $sources

        ];


        return view('forthebuilder::user.report-clients-index',[
            'status' => 'report-clients',
            'data'=>$data,
            'months' => $months,
            'line_month' => $line_month,
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function reportDeals()
    {
        $models = House::orderBy('id', 'desc')->paginate(15);

        return view('forthebuilder::user.report-clients',[
            'status' => 'report-deals',
            'models' => $models,
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function reportHouses()
    {
        $models = House::orderBy('id', 'desc')->paginate(15);

        return view('forthebuilder::user.report-clients',[
            'status' => 'report-houses',
            'models' => $models,
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function reportDealsIndex($id)
    {
        $connect_for=Constants::FOR_1;
        $connect_new=Constants::NEW_1;

        $new_users=DB::table($connect_for.'.deals as dt1')
            ->join($connect_for.'.clients as dt2', 'dt2.id', '=', 'dt1.client_id')
            ->where('dt1.type',Constants::FIRST_CONTACT)
            ->where('dt1.house_id',$id)
            ->count();
            
        $all_clients_count=DB::table($connect_for.'.clients')
        ->where('status',1)->count();
        
        $all_deals_count = DB::table($connect_for.'.deals')
        ->where('deleted_at',NULL)
        ->where('house_id',$id)
        ->count();
        
        $in_negotiations=DB::table($connect_for.'.deals as dt1')
        ->where('dt1.type',Constants::NEGOTIATION)
        ->where('dt1.house_id',$id)
        ->count();

        $make_deal=DB::table($connect_for.'.deals as dt1')
        ->where('dt1.type',Constants::MAKE_DEAL)
        ->where('dt1.house_id',$id)
        ->count();

        $source = DB::table($connect_for.'.clients')
        ->select('source', DB::raw('count(*) as total'))
        ->groupBy('source')
        ->where('source','!=',null)
        ->get();
        
        $source_name = [];
        $source_data = [];
        $source_color = [];
        $sources = '';

        if(!empty($source)){
             foreach ($source as $key => $value) {
                array_push($source_name, $value->source);
                array_push($source_data, $value->total);
                array_push($source_color, 'rgb('.rand(0,255).', '.rand(0,255).', '.rand(0,255).')');
                $sources .="['".$value->source."',".$value->total."],";
            }
        }

        $month_prices = DB::table($connect_for.'.deals as dt1')
            ->join($connect_for.'.house_flat as dt2', 'dt2.id', '=', 'dt1.house_flat_id')
            ->join($connect_new.'.users as dt3', 'dt3.id', '=', 'dt1.user_id')
            ->where('dt2.status',Constants::STATUS_SOLD)
            ->orderBy('dt3.id', 'desc')
            ->where('dt1.house_id',$id)
            ->select('dt3.id','dt1.price_sell','dt1.date_deal','dt3.first_name','dt3.last_name')
            ->get();


        $names = [];
        $counts = [];
        $colors = [];
        $price=0;
        $priceArr=[];
        for ($j=0; $j <= 11; $j++) { 
            $priceArr[$j] = 0;
        }
        
        $year=Carbon::now()->format('y');
        $month=Carbon::now()->format('m');
        $last_date = cal_days_in_month(CAL_GREGORIAN, $month,$year);

        $price_day_array = [];
        $month_day = [];
        for ($i=0; $i <= $last_date; $i++) { 
            $price_day_array[$i] = 0;
            $month_day[$i] = $i;
        }
        $core_chart="";
        $in = [];


        foreach ($month_prices as  $value) {
            
            $myDate = date('Y-m-d', strtotime($value->date_deal));
            $date_table = Carbon::createFromFormat('Y-m-d', $myDate);
            $month_code = $date_table->format('n');
            $date_day=Carbon::now()->format('m');
            $month_code_day = $date_table->format('j');
            if ($month_code==$date_day) {
                $in[$value->id]['first_name'] = $value->first_name;
                $in[$value->id]['price_sell'] = ($in[$value->id]['price_sell'] ?? 0) + $value->price_sell;
                
            }
            // dd($month_code);
            
            if ($month_code==$date_day) {
                $price_day_array[$month_code_day-1] +=$value->price_sell;
            }
            
            // $mont_code = $date_table->format('m');
            $priceArr[$month_code-1] += $value->price_sell;
            $price +=$value->price_sell;
        }
        // dd($in);
        foreach ($in as $key => $value) {
            // dd($value);
            $core_chart.="['".$value['first_name']."',".$value['price_sell']."],";
        }

        foreach ($in as $key => $value) {
            // dd($value);
            $core_chart.="['".$value['first_name']."',".$value['price_sell']."],";
            array_push($names, $value['first_name']);
            array_push($counts, $value['price_sell']);
            array_push($colors, 'rgb('.rand(0,255).', '.rand(0,255).', '.rand(0,255).')');
        }
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

        $data=[
           'new_clients'=>$new_users, // Новые клиенты
           'in_negotiations'=>$in_negotiations, // На переговорах
           'make_deal'=>$make_deal, // Заключение сделки
           'all_clients_count' => $all_clients_count,
           'all_deals_count' => $all_deals_count,
           'names' => json_encode($names),
           'counts' => json_encode($counts),
           'colors' => json_encode($colors),
           'source_name' => json_encode($source_name),
           'source_data' => json_encode($source_data),
           'source_color' => json_encode($source_color),
           'core_chart' => $core_chart,
           'sources' => $sources

        ];

        return view('forthebuilder::user.report-deals-index',[
            'data'=>$data,
            'months' => $months,
            'line_month' => $line_month,
            'all_notifications' => $this->getNotification()
        ]);
    }

    public function reportHousesIndex($id)
    {
        $connect_for=Constants::FOR_1;
        $connect_new=Constants::NEW_1;

        $house_count=DB::table($connect_for.'.house_flat as hf')
        ->where('hf.status',2)
        ->where('hf.house_id', $id)
        ->count();
        
        $house_flat_status_free=DB::table($connect_for.'.house_flat as house_flat')
        ->where('house_flat.deleted_at',null)
        ->where('house_flat.status',Constants::STATUS_FREE)
        ->where('house_flat.house_id', $id)
        ->count();
        
        $house_flat_status_booking=DB::table($connect_for.'.house_flat as house_flat')
        ->where('house_flat.deleted_at',null)
        ->where('house_flat.status',Constants::STATUS_BOOKING)
        ->where('house_flat.house_id', $id)
        ->count();

        $house_flat_status_sold=DB::table($connect_for.'.house_flat as dt1')
        ->join($connect_for.'.deals as dt2', 'dt2.house_flat_id', '=', 'dt1.id')
        ->where('dt1.deleted_at',null)
        ->where('dt1.status',Constants::STATUS_SOLD)
        ->where('dt1.house_id', $id)
        ->count();

        $installment_count = Deal::where('installment_plan_id', '!=', NULL)                
        ->where('house_id',$id)
        ->count();

        $source = DB::table($connect_for.'.clients')
        ->select('source', DB::raw('count(*) as total'))
        ->groupBy('source')
        ->where('source','!=',null)
        ->get();
        
        $source_name = [];
        $source_data = [];
        $source_color = [];
        $sources = '';
        if(!empty($source)){
             foreach ($source as $key => $value) {
                array_push($source_name, $value->source);
                array_push($source_data, $value->total);
                array_push($source_color, 'rgb('.rand(0,255).', '.rand(0,255).', '.rand(0,255).')');
                $sources .="['".$value->source."',".$value->total."],";
            }
        }

        $month_prices = DB::table($connect_for.'.deals as dt1')
            ->join($connect_for.'.house_flat as dt2', 'dt2.id', '=', 'dt1.house_flat_id')
            ->join($connect_new.'.users as dt3', 'dt3.id', '=', 'dt1.user_id')
            ->where('dt2.status',Constants::STATUS_SOLD)
            ->orderBy('dt3.id', 'desc')
            ->where('dt1.house_id',$id)
            ->select('dt3.id','dt1.price_sell','dt1.date_deal','dt3.first_name','dt3.last_name')
            ->get();


        $names = [];
        $counts = [];
        $colors = [];

        $price=0;
        $priceArr=[];
        for ($j=0; $j <= 11; $j++) { 
            $priceArr[$j] = 0;
        }
        
        $year=Carbon::now()->format('y');
        $month=Carbon::now()->format('m');
        $last_date = cal_days_in_month(CAL_GREGORIAN, $month,$year);

        $price_day_array = [];
        $month_day = [];
        for ($i=0; $i <= $last_date; $i++) { 
            $price_day_array[$i] = 0;
            $month_day[$i] = $i;
        }
        $core_chart="";
        $in = [];


        foreach ($month_prices as  $value) {
            
            $myDate = date('Y-m-d', strtotime($value->date_deal));
            $date_table = Carbon::createFromFormat('Y-m-d', $myDate);
            $month_code = $date_table->format('n');
            $date_day=Carbon::now()->format('m');
            $month_code_day = $date_table->format('j');
            if ($month_code==$date_day) {
                $in[$value->id]['first_name'] = $value->first_name;
                $in[$value->id]['price_sell'] = ($in[$value->id]['price_sell'] ?? 0) + $value->price_sell;
                // $core_chart.="['".$value->first_name."',     ".$value->price_sell."],";
            }
            // dd($month_code);
            
            if ($month_code==$date_day) {
                $price_day_array[$month_code_day-1] +=$value->price_sell;
            }
            
            // $mont_code = $date_table->format('m');
            $priceArr[$month_code-1] += $value->price_sell;
            $price +=$value->price_sell;
        }
        // dd($in);
        foreach ($in as $key => $value) {
            // dd($value);
            $core_chart.="['".$value['first_name']."',".$value['price_sell']."],";
        }

        foreach ($in as $key => $value) {
            // dd($value);
            $core_chart.="['".$value['first_name']."',".$value['price_sell']."],";
            array_push($names, $value['first_name']);
            array_push($counts, $value['price_sell']);
            array_push($colors, 'rgb('.rand(0,255).', '.rand(0,255).', '.rand(0,255).')');
        }

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

        $data=[
           
           'names' => json_encode($names),
           'counts' => json_encode($counts),
           'colors' => json_encode($colors),
           'source_name' => json_encode($source_name),
           'source_data' => json_encode($source_data),
           'source_color' => json_encode($source_color),
           'house_count'=>$house_count,
           'house_flat_status_free'=>$house_flat_status_free,
           'house_flat_status_booking'=>$house_flat_status_booking,
           'house_flat_status_sold'=>$house_flat_status_sold,
           'installment_count'=>$installment_count,
           'price' => $price,
           'core_chart' => $core_chart,
           'sources' => $sources,

        ];

        return view('forthebuilder::user.report-houses-index',[
            'data'=>$data,
            'months'=>$months,
            'line_month'=>$line_month,
            'id'=>$id,
            'all_notifications' => $this->getNotification()
        ]);
    }

    // filtrReportClients
    public function filtrReportClients($date)
    {
        $connect_for=Constants::FOR_1;
        $connect_new=Constants::NEW_1;

        $arr = explode(' - ', $date);
        $start = date('Y-m-d 00:00:00', strtotime($arr[0]));
        $end = date('Y-m-d 23:59:59', strtotime($arr[1]));

        $new_users = DB::table($connect_for.'.deals as dt1')->select('history')
        ->whereNotNull('dt1.history',)
        ->get();

        $new_users_count = 0;
        $negotiation_users_count = 0;
        $making_a_deal_users_count = 0;
        
        $new_users_arr = [];
        if (!empty($new_users)) {
            foreach ($new_users as $key => $value) {
                $new_arr = json_decode($value->history);
                foreach ($new_arr as $keyn => $valuen) {
                    // new users
                    if (($valuen->date >= $start && $valuen->date <= $end) && isset($valuen->new_type) && $valuen->new_type == 'First contact') {
                        $new_users_count++;
                    }

                    // negotiation_users
                    if (($valuen->date >= $start && $valuen->date <= $end) && isset($valuen->new_type) && $valuen->new_type == 'Negotiation') {
                        $negotiation_users_count++;
                    }

                    // Making a deal
                    if (($valuen->date >= $start && $valuen->date <= $end) && isset($valuen->new_type) && $valuen->new_type == 'Making a deal') {
                        $making_a_deal_users_count++;
                    }
                }
            }    
        }

        // graph, price
        $month_prices = DB::table($connect_for.'.deals as dt1')
        ->join($connect_for.'.house_flat as dt2', 'dt2.id', '=', 'dt1.house_flat_id')
        ->join($connect_new.'.users as dt3', 'dt3.id', '=', 'dt1.user_id')
        ->where('dt2.status',Constants::STATUS_SOLD)
        ->where('dt2.sold_date','>=',$start)
        ->where('dt2.sold_date','<=',$end)
        ->orderBy('dt3.id', 'desc')
        ->select('dt3.id','dt1.price_sell','dt1.date_deal','dt3.first_name','dt3.last_name')
        ->get();

        

        $price=0;
        $priceArr=[];
        for ($j=0; $j <= 11; $j++) { 
            $priceArr[$j] = 0;
        }
        
        $dates = getBetweenDates(date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end)));
        

        $price_day_array = [];
        $month_day = $dates;
        for ($i=0; $i <= count($dates); $i++) { 
            $price_day_array[$i] = 0;
            // $month_day[$i] = $i;
        }
        $core_chart="";
        $in = [];

        
        
        foreach ($month_prices as  $value) {
            // dd($value);
            $myDate = date('Y-m-d',strtotime($value->date_deal));
            $date_table = Carbon::createFromFormat('Y-m-d', $myDate);
            $month_code = $date_table->format('n');
            $date_day=Carbon::now()->format('m');

            $month_code_day = $date_table->format('j');
            
            if ($month_code == $date_day) {
                $in[$value->id]['first_name'] = $value->first_name;
                $in[$value->id]['price_sell'] = ($in[$value->id]['price_sell'] ?? 0) + $value->price_sell;
                // $core_chart.="['".$value->first_name."',     ".$value->price_sell."],";
            }
            // dd($month_code);
            
            if ($month_code==$date_day) {
                $price_day_array[$month_code_day-1] +=$value->price_sell;
            }
            
            // $mont_code = $date_table->format('m');
            $priceArr[$month_code-1] += $value->price_sell;
            $price +=$value->price_sell;
        }
        // dd($in);
        foreach ($in as $key => $value) {
            // dd($value);
            $core_chart.="['".$value['first_name']."',".$value['price_sell']."],";
        }

        $source = DB::table($connect_for.'.clients')
        ->select('source', DB::raw('count(*) as total'))
        ->groupBy('source')
        ->where('source','!=',null)
        ->where('created_at','>=',$start)
        ->where('created_at','<=',$end)
        ->get();
        
        $source_name = [];
        $source_data = [];
        $source_color = [];
        $sources = '';
        if(!empty($source)){
             foreach ($source as $key => $value) {
                array_push($source_name, $value->source);
                array_push($source_data, $value->total);
                array_push($source_color, 'rgb('.rand(0,255).', '.rand(0,255).', '.rand(0,255).')');
                $sources .="['".$value->source."',".$value->total."],";
            }
        }

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

        $data = [
            'start' => $start,
            'end' => $end,
            'new_clients'=>$new_users_count, // Новые клиенты
            'in_negotiations'=>$negotiation_users_count, // На переговорах
            'make_deal'=>$making_a_deal_users_count, // Заключение сделки
            'price'=>$price,
            'month_sales_price'=>$priceArr,
            'month_day'=>$month_day,
            'price_day_array'=>$price_day_array,
            'core_chart'=>$core_chart,
            'sources' => $sources,
        ];

        $line_month = '';
        foreach ($months as $key => $value) {
            $line_month .= $value.",";
        }
        $line_month = rtrim($line_month,",");
        $currency = Currency::first();



         return view('forthebuilder::user.filtr-report-clients',[
            'data' => $data,
            'currency' => $currency,
            'line_month' => $line_month,
            'months' => json_encode($months),
            'all_notifications' => $this->getNotification()
        ]);

    }

    public function filtrReportDeals($date)
    {
        $connect_for=Constants::FOR_1;
        $connect_new=Constants::NEW_1;

        $arr = explode(' - ', $date);
        $start = date('Y-m-d 00:00:00', strtotime($arr[0]));
        $end = date('Y-m-d 23:59:59', strtotime($arr[1]));

        $new_users_count = 0;
        $negotiation_users_count = 0;
        $making_a_deal_users_count = 0;
        $new_users_arr = [];
        if (!empty($new_users)) {
            foreach ($new_users as $key => $value) {
                $new_arr = json_decode($value->history);
                foreach ($new_arr as $keyn => $valuen) {
                    // new users
                    if (($valuen->date >= $start && $valuen->date <= $end) && isset($valuen->new_type) && $valuen->new_type == 'First contact') {
                        $new_users_count++;
                    }
                    
                    // negotiation_users
                    if (($valuen->date >= $start && $valuen->date <= $end) && isset($valuen->new_type) && $valuen->new_type == 'Negotiation') {
                        $negotiation_users_count++;
                    }

                     // Making a deal
                    if (($valuen->date >= $start && $valuen->date <= $end) && isset($valuen->new_type) && $valuen->new_type == 'Making a deal') {
                        $making_a_deal_users_count++;
                    }
                }
            }    
        }
            
        $house_count=DB::table($connect_for.'.house_flat as hf')
        ->where('hf.deleted_at',null)
        ->where('hf.created_at','>=',$start)
        ->where('hf.created_at','<=',$end)
        ->where('hf.status',2)
        ->count();

        // ----- house flat free -----
        $house_flat_status_free = DB::table($connect_for.'.house_flat as house_flat')
        ->where('house_flat.deleted_at',null)
        ->where('house_flat.free_end','>=',$start)
        ->count();

        // ---- house flat booking -----
        $house_flat_status_booking = DB::table($connect_for.'.house_flat as house_flat')
        ->where('house_flat.deleted_at',null)
        ->where('house_flat.booking_end','>=',$start)
        ->count();
        
        // ---- house flat sold -----
        $house_flat_status_sold = DB::table($connect_for.'.house_flat as house_flat')
        ->where('house_flat.deleted_at',null)
        ->where('house_flat.sold_date','>=',$start)
        ->where('house_flat.sold_date','<=',$end)
        ->count();

        // ----- installment_plan count ---- 
        $installment_count = Deal::where('installment_plan_id', '!=', NULL)
        ->where('date_deal','>=',$start)
        ->where('date_deal','<=',$end)
        ->count();


        // graph, price
        $month_prices = DB::table($connect_for.'.deals as dt1')
        ->join($connect_for.'.house_flat as dt2', 'dt2.id', '=', 'dt1.house_flat_id')
        ->join($connect_new.'.users as dt3', 'dt3.id', '=', 'dt1.user_id')
        ->where('dt2.status',Constants::STATUS_SOLD)
        ->where('dt2.sold_date','>=',$start)
        ->where('dt2.sold_date','<=',$end)
        ->orderBy('dt3.id', 'desc')
        // ->where('dt3.role_id',2)
        ->select('dt3.id','dt1.price_sell','dt1.date_deal','dt3.first_name','dt3.last_name')
        ->get();


        

        $price=0;
        $priceArr=[];
        for ($j=0; $j <= 11; $j++) { 
            $priceArr[$j] = 0;
        }
        
        $dates = getBetweenDates(date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end)));
        

        $price_day_array = [];
        $month_day = $dates;
        for ($i=0; $i <= count($dates); $i++) { 
            $price_day_array[$i] = 0;
            // $month_day[$i] = $i;
        }
        $core_chart="";
        $in = [];

        
        
        foreach ($month_prices as  $value) {
            // dd($value);
            $myDate = date('Y-m-d',strtotime($value->date_deal));
            $date_table = Carbon::createFromFormat('Y-m-d', $myDate);
            $month_code = $date_table->format('n');
            $date_day=Carbon::now()->format('m');

            $month_code_day = $date_table->format('j');
            
            if ($month_code == $date_day) {
                $in[$value->id]['first_name'] = $value->first_name;
                $in[$value->id]['price_sell'] = ($in[$value->id]['price_sell'] ?? 0) + $value->price_sell;
                // $core_chart.="['".$value->first_name."',     ".$value->price_sell."],";
            }
            // dd($month_code);
            
            if ($month_code==$date_day) {
                $price_day_array[$month_code_day-1] +=$value->price_sell;
            }
            
            // $mont_code = $date_table->format('m');
            $priceArr[$month_code-1] += $value->price_sell;
            $price +=$value->price_sell;
        }
        // dd($in);
        foreach ($in as $key => $value) {
            // dd($value);
            $core_chart.="['".$value['first_name']."',".$value['price_sell']."],";
        }

        $source = DB::table($connect_for.'.clients')
        ->select('source', DB::raw('count(*) as total'))
        ->groupBy('source')
        ->where('source','!=',null)
        ->where('created_at','>=',$start)
        ->where('created_at','<=',$end)
        ->get();
        
        $source_name = [];
        $source_data = [];
        $source_color = [];
        $sources = '';
        if(!empty($source)){
             foreach ($source as $key => $value) {
                array_push($source_name, $value->source);
                array_push($source_data, $value->total);
                array_push($source_color, 'rgb('.rand(0,255).', '.rand(0,255).', '.rand(0,255).')');
                $sources .="['".$value->source."',".$value->total."],";
            }
        }

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


        $data = [
           'start' => $start,
           'end' => $end,
           'new_clients'=>$new_users_count, // Новые клиенты
           'in_negotiations'=>$negotiation_users_count, // На переговорах
           'make_deal'=>$making_a_deal_users_count, // Заключение сделки
           'house_count'=>$house_count,
           'house_flat_status_free'=>$house_flat_status_free,
           'house_flat_status_booking'=>$house_flat_status_booking,
           'house_flat_status_sold'=>$house_flat_status_sold,
           'installment_count'=>$installment_count,
           'price'=>$price,
           'month_sales_price'=>$priceArr,
           'month_day'=>$month_day,
           'price_day_array'=>$price_day_array,
           'core_chart'=>$core_chart,
           'sources'=>$sources,
        ];

        $line_month = '';
        foreach ($months as $key => $value) {
            $line_month .= $value.",";
        }
        $line_month = rtrim($line_month,",");
        $currency = Currency::first();

        return view('forthebuilder::user.filtr-report-deals',[
            'data'=>$data,
            'months' => $months,
            'line_month' => $line_month,
            'all_notifications' => $this->getNotification()
        ]);
    }


    public function filtrReportHouses($date)
    {
        $connect_for=Constants::FOR_1;
        $connect_new=Constants::NEW_1;

        $arr = explode(' - ', $date);


        $start = date('Y-m-d 00:00:00', strtotime($arr[0]));
        $end = date('Y-m-d 23:59:59', strtotime($arr[1]));
        $id = $arr['2'];

        $house_count=DB::table($connect_for.'.house_flat as hf')
        ->where('hf.status',2)
        ->where('hf.created_at','>=',$start)
        ->where('hf.created_at','<=',$end)
        ->where('hf.house_id', $id)
        ->count();
        
        $house_flat_status_free=DB::table($connect_for.'.house_flat as house_flat')
        ->where('house_flat.deleted_at',null)
        ->where('house_flat.status',Constants::STATUS_FREE)
        ->where('house_flat.house_id', $id)
        ->where('house_flat.created_at','>=',$start)
        ->where('house_flat.created_at','<=',$end)
        ->count();
        
        $house_flat_status_booking=DB::table($connect_for.'.house_flat as house_flat')
        ->where('house_flat.deleted_at',null)
        ->where('house_flat.status',Constants::STATUS_BOOKING)
        ->where('house_flat.house_id', $id)
        ->where('house_flat.created_at','>=',$start)
        ->where('house_flat.created_at','<=',$end)
        ->count();

        $house_flat_status_sold=DB::table($connect_for.'.house_flat as dt1')
        ->join($connect_for.'.deals as dt2', 'dt2.house_flat_id', '=', 'dt1.id')
        ->where('dt1.deleted_at',null)
        ->where('dt1.status',Constants::STATUS_SOLD)
        ->where('dt1.house_id', $id)
        ->where('dt1.created_at','>=',$start)
        ->where('dt1.created_at','<=',$end)
        ->count();

        $installment_count = Deal::where('installment_plan_id', '!=', NULL)                
        ->where('house_id',$id)
        ->where('date_deal','>=',$start)
        ->where('date_deal','<=',$end)
        ->count();

        $source = DB::table($connect_for.'.clients')
        ->select('source', DB::raw('count(*) as total'))
        ->groupBy('source')
        ->where('source','!=',null)
        ->where('created_at','>=',$start)
        ->where('created_at','<=',$end)
        ->get();
        
        $source_name = [];
        $source_data = [];
        $source_color = [];
        $sources = '';
        if(!empty($source)){
             foreach ($source as $key => $value) {
                array_push($source_name, $value->source);
                array_push($source_data, $value->total);
                array_push($source_color, 'rgb('.rand(0,255).', '.rand(0,255).', '.rand(0,255).')');
                $sources .="['".$value->source."',".$value->total."],";
            }
        }

        $month_prices = DB::table($connect_for.'.deals as dt1')
            ->join($connect_for.'.house_flat as dt2', 'dt2.id', '=', 'dt1.house_flat_id')
            ->join($connect_new.'.users as dt3', 'dt3.id', '=', 'dt1.user_id')
            ->where('dt2.status',Constants::STATUS_SOLD)
            ->orderBy('dt3.id', 'desc')
            ->where('dt1.house_id',$id)
            ->where('dt1.date_deal','>=',$start)
            ->where('dt1.date_deal','<=',$end)
            ->select('dt3.id','dt1.price_sell','dt1.date_deal','dt3.first_name','dt3.last_name')
            ->get();


        $names = [];
        $counts = [];
        $colors = [];

        $price=0;
        $priceArr=[];
        for ($j=0; $j <= 11; $j++) { 
            $priceArr[$j] = 0;
        }
        
        $year=Carbon::now()->format('y');
        $month=Carbon::now()->format('m');
        $last_date = cal_days_in_month(CAL_GREGORIAN, $month,$year);

        $price_day_array = [];
        $month_day = [];
        for ($i=0; $i <= $last_date; $i++) { 
            $price_day_array[$i] = 0;
            $month_day[$i] = $i;
        }
        $core_chart="";
        $in = [];


        foreach ($month_prices as  $value) {
            
            $myDate = date('Y-m-d', strtotime($value->date_deal));
            $date_table = Carbon::createFromFormat('Y-m-d', $myDate);
            $month_code = $date_table->format('n');
            $date_day=Carbon::now()->format('m');
            $month_code_day = $date_table->format('j');
            if ($month_code==$date_day) {
                $in[$value->id]['first_name'] = $value->first_name;
                $in[$value->id]['price_sell'] = ($in[$value->id]['price_sell'] ?? 0) + $value->price_sell;
                // $core_chart.="['".$value->first_name."',     ".$value->price_sell."],";
            }
            // dd($month_code);
            
            if ($month_code==$date_day) {
                $price_day_array[$month_code_day-1] +=$value->price_sell;
            }
            
            // $mont_code = $date_table->format('m');
            $priceArr[$month_code-1] += $value->price_sell;
            $price +=$value->price_sell;
        }
        // dd($in);
        foreach ($in as $key => $value) {
            // dd($value);
            $core_chart.="['".$value['first_name']."',".$value['price_sell']."],";
        }

        foreach ($in as $key => $value) {
            // dd($value);
            $core_chart.="['".$value['first_name']."',".$value['price_sell']."],";
            array_push($names, $value['first_name']);
            array_push($counts, $value['price_sell']);
            array_push($colors, 'rgb('.rand(0,255).', '.rand(0,255).', '.rand(0,255).')');
        }

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

        $data=[
           'start' => $start,
           'end' => $end,
           'names' => json_encode($names),
           'counts' => json_encode($counts),
           'colors' => json_encode($colors),
           'source_name' => json_encode($source_name),
           'source_data' => json_encode($source_data),
           'source_color' => json_encode($source_color),
           'house_count'=>$house_count,
           'house_flat_status_free'=>$house_flat_status_free,
           'house_flat_status_booking'=>$house_flat_status_booking,
           'house_flat_status_sold'=>$house_flat_status_sold,
           'installment_count'=>$installment_count,
           'price' => $price,
           'core_chart' => $core_chart,
           'sources' => $sources,

        ];

        return view('forthebuilder::user.filtr-report-houses',[
            'data'=>$data,
            'months'=>$months,
            'line_month'=>$line_month,
            'id'=>$id,
            'all_notifications' => $this->getNotification()
        ]);
    }


}
