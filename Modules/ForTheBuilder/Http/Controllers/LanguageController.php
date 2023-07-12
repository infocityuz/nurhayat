<?php

namespace Modules\ForTheBuilder\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\ForTheBuilder\Entities\Deal;
use Modules\ForTheBuilder\Entities\HouseFlat;
use Modules\ForTheBuilder\Entities\InstallmentPlan;
use Modules\ForTheBuilder\Entities\Leads;
use Modules\ForTheBuilder\Entities\Notification_;
use Modules\ForTheBuilder\Entities\PayStatus;
use Modules\ForTheBuilder\Http\Requests\InstallmentPlanRequest;
use Modules\ForTheBuilder\Entities\Language;
use Modules\ForTheBuilder\Entities\Translation;
use Modules\ForTheBuilder\Entities\LanguageTranslation;
use Stichoza\GoogleTranslate\GoogleTranslate;

use Illuminate\Pagination\Paginator;
// use Illuminate\Database\Query\Builder;
class LanguageController extends Controller
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

    public function changeLanguage(Request $request)
    {
         $request->session()->put('locale', $request->locale);
        $language = Language::where('code', $request->locale)->first();
        //  flash(translate('Language changed to ') . $language->name)->success();
    }



    public function env_key_update(Request $request)
    {
        // dd($request->all());
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        // flash(translate("Settings updated successfully"))->success();

        return back();
    }



    public function overWriteEnvFile($type, $val)
    {
        //TODO::fixing server base_path
        try{
            // if(env('DEMO_MODE') != 'On'){
                $type=str_replace(' ', '_', $type);
                $path = base_path('.env');
                // dd($type);
                // dd($val);
                if (file_exists($path)) {
                    $val = '"'.trim($val).'"';
                    // dd($val);
                    if(is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0){
                        file_put_contents($path, str_replace(
                            $type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)
                        ));
                        // file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
                    }
                    else{
                        file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
                    }
                }
            // }
        }catch(Exception $e){

        }

    }



    
    // public function defaultLanguage(Request $request)
    // {
    //     return $request->all();
    //     $default_language=env('DEFAULT_LANGUAGE');
    //     $default_language = $language->code;
    //     $default_language->save();

    //     $request->session()->put('locale', $request->locale);
    //     $language = Language::where('code', $request->locale)->first();
    //     return redirect()->back();
    //     //  flash(translate('Language changed to ') . $language->name)->success();
    // }

    public function index()
    {

        $languages = Language::get();
        // dd($languages);
        return view('forthebuilder::language.index', [
            'languages' => $languages,
            'all_notifications' => $this->getNotification()
        ]);

        // return 'came';

    }
    public function show(Request $request, $id)
    {
        $sort_search = null;
        $language = Language::findOrFail(decrypt($id));
        // dd();
        $lang_keys = Translation::where('lang', env('DEFAULT_LANGUAGE', 'en'))->get();
        if ($request->has('search')) {
            $sort_search = $request->search;
            // dd($sort_search);
            // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
            $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
            // dd(request()->input('search'));
        }
        // $lang_keys = $lang_keys->paginate(10);
        // $lang_keys = $lang_keys->orderByDesc()->paginate(10);
        $lang_keys = $lang_keys->paginate(10);
        // dd($lang_keys);



        // dd($lang_keys);
        return view('forthebuilder::language.show', [
            'language' => $language,
            'lang_keys' => $lang_keys,
            'sort_search' => $sort_search,
            'all_notifications' => $this->getNotification()
        ]);
    }











    public function translation_save(Request $request)
    {
        $language = Language::findOrFail($request->id);
        foreach ($request->values as $key => $value) {
            $translation_def = Translation::where('lang_key', $key)->where('lang', $language->code)->first();
            if ($translation_def == null) {
                $translation_def = new Translation;
                $translation_def->lang = $language->code;
                $translation_def->lang_key = $key;
                $translation_def->lang_value = $value;
                $translation_def->save();
            } else {
                $translation_def->lang_value = $value;
                $translation_def->save();
            }
        }

        return back();
    }




    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // return 'came';

        $languages = Language::get();
        return view('forthebuilder::language.create', [
            'languages'=>$languages,
            'all_notifications' => $this->getNotification()
            ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $language = Language::updateOrCreate(
            ['name' => $request->name, 'code' => $request->code]
        );
        // $language->name = $request->name;
        if ($language->save()) {
            // dd($language->all());

            foreach (Language::all() as $language) {
                // Language Translations
                $language_translations = LanguageTranslation::firstOrNew(['lang' => $language->code, 'language_id' => $language->id]);
                $language_translations->name = $language->name;
                $language_translations->save();
            }


            return redirect()->route('forthebuilder.language.index');
        }
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function languageEdit($id)
    {
        // return 'came';
        // dd()

        $languages = Language::get();
        $first_language = Language::findOrFail(decrypt($id));
        // dd($first_language);
        return view('forthebuilder::language.edit', [
            'languages'=>$languages,
            'first_language'=>$first_language,
            'all_notifications' => $this->getNotification()
        ]);



        // return view('forthebuilder::language.create');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {



        // dd($request->all());

        $language = Language::where('id', $request->language_id)->first();
        $language->name = $request->name;
        $language->code = $request->code;
        if ($language->save()) {


            // dd(default_language());
            if (LanguageTranslation::where('language_id', $language->id)->where('lang', default_language())->first()) {
                foreach (Language::all() as $language) {
                    $language_translations = LanguageTranslation::firstOrNew(['lang' => $language->code, 'language_id' => $language->id]);
                    $language_translations->name = $request->name;
                    $language_translations->save();
                }
            }
            return redirect()->route('forthebuilder.language.index');
        }
    }

    public function languageDestroy($id)
    {
        $language = Language::findOrFail(decrypt($id));
        // dd(env('DEFAULT_LANGUAGE','ru'));
        // dd($language);
        if (env('DEFAULT_LANGUAGE', 'ru') == $language->code) {
            return back();
            // return error();
        } else {
            $language->delete();
            // flash(translate('Language has been deleted successfully'))->success();
        }
        return redirect()->route('forthebuilder.language.index');
    }





    public function updateValue(Request $request)
    {
        // return $request->all();
        $tr = new GoogleTranslate;
        return GoogleTranslate::trans($request->status, $request->code);
    }
}
