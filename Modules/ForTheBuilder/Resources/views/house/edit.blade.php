@extends('forthebuilder::layouts.forthebuilder')
@php
    use Modules\ForTheBuilder\Entities\House;
@endphp
@section('title')
    {{ translate('JK') }}
@endsection
@section('content')
    <div class="d-flex aad">
        @include('forthebuilder::layouts.content.navigation')
        <div class="mainMargin">
            @include('forthebuilder::layouts.content.header')
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <a href="{{ route('forthebuilder.house.index') }}" class="plus2 profileMaxNazadInformatsiyaKlient"><img
                            src="{{ asset('backend-assets/forthebuilders/images/icons/arrow-left.png') }}" alt=""></a>
                    <h2 class="panelUprText">{{ translate('Create a new JK') }}</h2>
                </div>
            </div>
<div class="sozdatJkData">
    <form action="{{route('forthebuilder.house.update',$model->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="sozdatImyaSpsok">
                        <h3 class="sozdatImyaSpisokH3">{{ translate('Name of JK') }}</h3>
                        <input class="sozdatImyaSpisokInput @error('name') error-data-input is-invalid @enderror"
                            type="text" name="name" value="{{ $model->name }}">
                        <span class="error-data">
                            @error('name')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sozdatImyaSpsok">
                                <h3 class="sozdatImyaSpisokH3">{{ translate('Corpas') }}</h3>
                                <input class="sozdatImyaSpisokKorpus @error('corpus') error-data-input is-invalid @enderror"
                                    type="text" name="corpus" value="{{ $model->corpus }}">
                                <span class="error-data">
                                    @error('corpus')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sozdatImyaSpsok">
                                <h3 class="sozdatImyaSpisokH3">{{ translate('Number house') }}</h3>
                                <input class="sozdatImyaSpisokKorpus @error('house_number') error-data-input is-invalid @enderror"
                                    type="text" name="house_number" value="{{ $model->house_number }}">
                                <span class="error-data">
                                    @error('house_number')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    

                    <div class="form-group">
                        <h3 class="sozdatImyaSpisokH3">{{ translate('Object status') }}</h3>
                        <select
                            class="form-control sozdatImyaSpisokSelectOption2 @error('project_stage') errpr-data-input is-invalid @enderror"
                            id="exampleFormControlSelect1" name="project_stage">
                            <option value="{{ House::DESIGN }}" {{ ($model->project_stage == House::DESIGN) ? 'selected' : '' }}>{{ translate('Design') }}</option>
                            <option value="{{ House::AT_THE_FOUNDATION_STAGE }}" {{ ($model->project_stage == House::AT_THE_FOUNDATION_STAGE) ? 'selected' : '' }}>
                                {{ translate('At the foundation stage') }}</option>
                            <option value="{{ House::AT_THE_PRE_SALE_STAGE }}" {{ ($model->project_stage == House::AT_THE_PRE_SALE_STAGE) ? 'selected' : '' }}>
                                {{ translate('At the pre-sale stage') }}</option>
                            <option value="{{ House::START_OF_OFFICIAL_SALES }}" {{ ($model->project_stage == House::START_OF_OFFICIAL_SALES) ? 'selected' : '' }}>
                                {{ translate('Start of official sales') }}</option>
                            <option value="{{ House::STATUS_COMPLATED }}" {{ ($model->project_stage == House::STATUS_COMPLATED) ? 'selected' : '' }}>
                                {{ translate('Completed') }}</option>
                        </select>
                        <span class="error-data">
                            @error('project_stage')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>

                    <div class="sozdatImyaSpsok">
                        <h3 class="sozdatImyaSpisokH3">{{ translate('Description of the object') }}</h3>
                        <input class="sozdatImyaSpisokInput @error('description') error-data-input is-invalid @enderror"
                            type="text" name="description" value="{{ $model->description }}">
                        <span class="error-data">
                            @error('description')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sozdatImyaSpsok">
                                <h3 class="sozdatImyaSpisokH3">{{ translate('Company name') }}</h3>
                                <input class="sozdatImyaSpisokKorpus" style="width: 100%;" type="text" name="company" value="{{ $model->company }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h3 class="sozdatImyaSpisokH3">{{ translate('Address') }}</h3>
                                <input class="sozdatImyaSpisokKorpus" style="width: 100%;" type="text" name="address" value="{{ $model->address }}">
                        </div>
                        <div class="col-sm-6">
                            <h3 class="sozdatImyaSpisokH3">{{ translate('Settlement account') }}</h3>
                                <input class="sozdatImyaSpisokKorpus" style="width: 100%;" type="text" name="settlement" value="{{ $model->settlement }}">
                        </div>
                        <div class="col-sm-6">
                            <h3 class="sozdatImyaSpisokH3">{{ translate('Bank') }}</h3>
                                <input class="sozdatImyaSpisokKorpus" style="width: 100%;" type="text" name="bank" value="{{ $model->bank }}">
                        </div>
                        <div class="col-sm-6">
                            <h3 class="sozdatImyaSpisokH3">{{ translate('MFO') }}</h3>
                                <input class="sozdatImyaSpisokKorpus" style="width: 100%;" type="text" name="mfo" value="{{ $model->mfo }}">
                        </div>
                        <div class="col-sm-6">
                            <h3 class="sozdatImyaSpisokH3">{{ translate('INN number') }}</h3>
                                <input class="sozdatImyaSpisokKorpus" style="width: 100%;" type="text" name="inn" value="{{ $model->inn }}">
                        </div>
                        <div class="col-sm-6">
                            <h3 class="sozdatImyaSpisokH3">{{ translate('OKED') }}</h3>
                                <input class="sozdatImyaSpisokKorpus" style="width: 100%;" type="text" name="oked" value="{{ $model->oked }}">
                        </div>
                        <div class="col-sm-6">
                            <h3 class="sozdatImyaSpisokH3">{{ translate('Phone number') }}</h3>
                                <input class="sozdatImyaSpisokKorpus" style="width: 100%;" type="text" name="phone" value="{{ $model->phone }}">
                        </div>
                        <div class="col-sm-6">
                            <h3 class="sozdatImyaSpisokH3">{{ translate('Director') }}</h3>
                                <input class="sozdatImyaSpisokKorpus" style="width: 100%;" type="text" name="director" value="{{ $model->director }}">
                        </div>
                    </div>
      
                    



                    <button type="submit" class="sozdatImyaSpisokSozdatButton"
                        style="cursor: pointer;">{{ translate('Further') }}</button>

    </form>
    </div>
        </div>
    </div>
    <script>
        let page_name = 'house';
    </script>
@endsection

