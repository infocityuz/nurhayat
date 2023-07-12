@extends('forthebuilder::layouts.forthebuilder')

@section('title')
    {{ translate('Currency') }}
@endsection
<link rel="stylesheet" href="{{ asset('/backend-assets/forthebuilders/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('/backend-assets/forthebuilders/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('/backend-assets/forthebuilders/toastr/css/toastr.min.css') }}">

@section('content')
    <div class="d-flex aad">
        @include('forthebuilder::layouts.content.navigation')
        <div class="mainMargin">
            @include('forthebuilder::layouts.content.header')

            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <a href="{{ route('forthebuilder.settings.index') }}"
                        class="plus2 profileMaxNazadInformatsiyaKlient"><img
                            src="{{ asset('/backend-assets/forthebuilders/images/icons/arrow-left.png') }}"
                            alt=""></a>
                    <h2 class="panelUprText">{{ translate('Language') }}</h2>
                </div>
            </div>

            <div class="nastroykiData">
                <form class="form-horizontal" action="{{ route('env_key_update.update') }}" method="POST">
                    @csrf


                    <div class="d-flex">
                        <h2 class="panelUprText yazik_poUmolchaniya yazikPo_umolchaniya">{{ translate('Default language') }}
                        </h2>
                        <input type="hidden" name="types[]" value="DEFAULT_LANGUAGE">
                        <select class="yazikHeader demo-select2-placeholder" id="country" name="DEFAULT_LANGUAGE">
                            @foreach ($languages as $key => $language)
                                <option value="{{ $language->code }}" <?php if (env('DEFAULT_LANGUAGE') == $language->code) {
                                    echo 'selected';
                                } ?>>
                                    {{ $language->name }}
                                </option>
                            @endforeach
                        </select>


                        {{-- <select class="yazikHeader" id="country" name="country">
                            <option>{{ translate('English') }}</option>
                            <option>{{ translate('Uzbek') }}</option>
                            <option>{{ translate('Russian') }}</option>
                        </select> --}}

                        <button class="yazik_soxranitBtn">{{ translate('Save') }}</button>
                    </div>



                </form>

                <div class="sozdatRassrochkaDataUae">
                    <div class="checkboxDivInput">
                        №
                    </div>
                    <div class="checkboxDivTextInput3565">
                        {{ translate('Language') }}
                    </div>
                    <div class="checkboxDivTextInput3565">
                        {{ translate('Code') }}
                    </div>
                    <div class="checkboxDivTextInput35652">
                        {{ translate('Action') }}
                    </div>
                </div>

                @empty(!$languages)
                    @php
                        $i = 1;
                    @endphp
                    {{-- @dd($language) --}}
                    @foreach ($languages as $value)
                        <div class="sozdatRassrochkaDataUae2">
                            <div class="checkboxDivInput">
                                {{ $i++ }}
                            </div>
                            <div class="checkboxDivTextInput3565">
                                {{ $value->name }}
                            </div>
                            <div class="checkboxDivTextInput3565">
                                {{ $value->code }}
                            </div>
                            <div class="checkboxDivTextInput35652">
                                <div class="seaDiv">
                                    <a href="{{ route('forthebuilder.language.show', encrypt($value->id)) }}"
                                        title="{{ translate('Translation') }}">
                                        <img class="mt-1" width="20" height="20"
                                            src="{{ asset('/backend-assets/forthebuilders/images/translate.png') }}"
                                            alt="Trash">
                                    </a>
                                </div>
                                <div class="seaDiv">

                                    <a href="{{ route('forthebuilder.language.edit', encrypt($value->id)) }}">
                                        <img class="mt-1" width="20" height="20"
                                            src="{{ asset('/backend-assets/forthebuilders/images/edit.png') }}">
                                    </a>
                                </div>


                                @if ($value->code != 'en')
                                    <div class="seaDiv">
                                        {{-- @dd($language) --}}

                                        <a href="{{ route('forthebuilder.language.destroy', encrypt($value->id)) }}"
                                            @disabled(true)>
                                            <img class="mt-1" width="20" height="20"
                                                src="{{ asset('/backend-assets/forthebuilders/images/trash.png') }}"
                                                alt="Trash">
                                        </a>

                                    </div>
                                @endif


                            </div>
                        </div>
                    @endforeach
                @endempty
                <a href="{{ route('languages.create') }}" class="yazik_soxranitBtn2">
                    <img src="{{ asset('/backend-assets/forthebuilders/images/plus-circle.png') }}"
                        alt="Plus">{{ translate('Add language') }}
                </a>
            </div>
        </div>
    </div>
    <script>
        // function edit_tranlate(language) {
        //    console.log(language);
        //     document.getElementsByClassName('sozdatRassrochkaDataUae2').innerHTML ='';
        //     // location.reload();
        //     // document.getElementById('close_chat_group').style.backgroundColor="#17a2b8";
        //     // document.getElementById('close_chat').style.backgroundColor="#92b0e8";











        // //    console.log(language_name);

        // }
    </script>
    <script>
        let page_name = 'currency';
    </script>
@endsection











{{-- <div class="sozdatRassrochkaDataUae2">
                    <div class="checkboxDivInput">
                        2
                    </div>
                    <div class="checkboxDivTextInput3565">
                        Russian
                    </div>
                    <div class="checkboxDivTextInput3565">
                        RU
                    </div>
                    <div class="checkboxDivTextInput35652">
                        <div class="seaDiv">
                            <img class="mt-1" width="20" height="20" src="../images/translate.png" alt="Trash">
                        </div>
                        <div class="seaDiv">
                            <img class="mt-1" width="20" height="20" src="../images/edit.png" alt="Trash">
                        </div>
                        <div class="seaDiv">
                            <img class="mt-1" width="20" height="20" src="../images/trash.png" alt="Trash">
                        </div>
                    </div>
                </div>

                <div class="sozdatRassrochkaDataUae2">
                    <div class="checkboxDivInput">
                        3
                    </div>
                    <div class="checkboxDivTextInput3565">
                        Uzbek
                    </div>
                    <div class="checkboxDivTextInput3565">
                        UZ
                    </div>
                    <div class="checkboxDivTextInput35652">
                        <div class="seaDiv">
                            <img class="mt-1" width="20" height="20" src="../images/translate.png" alt="Trash">
                        </div>
                        <div class="seaDiv">
                            <img class="mt-1" width="20" height="20" src="../images/edit.png" alt="Trash">
                        </div>
                        <div class="seaDiv">
                            <img class="mt-1" width="20" height="20" src="../images/trash.png" alt="Trash">
                        </div>
                    </div>
                </div> --}}

{{-- <div class="sozdatRassrochkaDataUae2">
                    <div class="checkboxDivInput">
                        {{ $i }}
                    </div>
                    <input type="text" class="checkboxDivTextInput3565">

                    <select class="checkboxDivTextInput3565" id="country" name="country">
                        <option disabled selected hidden>select country</option>
                        <option value="AF">Afghanistan</option>
                        <option value="AX">Aland Islands</option>
                        <option value="AL">Albania</option>
                        <option value="DZ">Algeria</option>
                        <option value="AS">American Samoa</option>
                        <option value="AD">Andorra</option>
                        <option value="AO">Angola</option>
                        <option value="AI">Anguilla</option>
                        <option value="AQ">Antarctica</option>
                        <option value="AG">Antigua and Barbuda</option>
                        <option value="AR">Argentina</option>
                        <option value="AM">Armenia</option>
                        <option value="AW">Aruba</option>
                        <option value="AU">Australia</option>
                        <option value="AT">Austria</option>
                        <option value="AZ">Azerbaijan</option>
                        <option value="BS">Bahamas</option>
                        <option value="BH">Bahrain</option>
                        <option value="BD">Bangladesh</option>
                        <option value="BB">Barbados</option>
                        <option value="BY">Belarus</option>
                        <option value="BE">Belgium</option>
                        <option value="BZ">Belize</option>
                        <option value="BJ">Benin</option>
                        <option value="BM">Bermuda</option>
                        <option value="BT">Bhutan</option>
                        <option value="BO">Bolivia</option>
                        <option value="BQ">Bonaire</option>
                        <option value="BA">Bosnia</option>
                        <option value="BW">Botswana</option>
                        <option value="BV">Bouvet Island</option>
                        <option value="BR">Brazil</option>
                        <option value="IO">British</option>
                        <option value="BN">Brunei Darussalam</option>
                        <option value="BG">Bulgaria</option>
                        <option value="BF">Burkina Faso</option>
                        <option value="BI">Burundi</option>
                        <option value="KH">Cambodia</option>
                        <option value="CM">Cameroon</option>
                        <option value="CA">Canada</option>
                        <option value="CV">Cape Verde</option>
                        <option value="KY">Cayman Islands</option>
                        <option value="CF">Central</option>
                        <option value="TD">Chad</option>
                        <option value="CL">Chile</option>
                        <option value="CN">China</option>
                        <option value="CX">Christmas Island</option>
                        <option value="CC">Cocos</option>
                        <option value="CO">Colombia</option>
                        <option value="KM">Comoros</option>
                        <option value="CG">Congo</option>
                        <option value="CD">Congo</option>
                        <option value="CK">Cook Islands</option>
                        <option value="CR">Costa Rica</option>
                        <option value="CI">Cote D'Ivoire</option>
                        <option value="HR">Croatia</option>
                        <option value="CU">Cuba</option>
                        <option value="CW">Curacao</option>
                        <option value="CY">Cyprus</option>
                        <option value="CZ">Czech Republic</option>
                        <option value="DK">Denmark</option>
                        <option value="DJ">Djibouti</option>
                        <option value="DM">Dominica</option>
                        <option value="DO">Dominican Republic</option>
                        <option value="EC">Ecuador</option>
                        <option value="EG">Egypt</option>
                        <option value="SV">El Salvador</option>
                        <option value="GQ">Equatorial Guinea</option>
                        <option value="ER">Eritrea</option>
                        <option value="EE">Estonia</option>
                        <option value="ET">Ethiopia</option>
                        <option value="FK">Falkland</option>
                        <option value="FO">Faroe Islands</option>
                        <option value="FJ">Fiji</option>
                        <option value="FI">Finland</option>
                        <option value="FR">France</option>
                        <option value="GF">French Guiana</option>
                        <option value="PF">French Polynesia</option>
                        <option value="TF">French</option>
                        <option value="GA">Gabon</option>
                        <option value="GM">Gambia</option>
                        <option value="GE">Georgia</option>
                        <option value="DE">Germany</option>
                        <option value="GH">Ghana</option>
                        <option value="GI">Gibraltar</option>
                        <option value="GR">Greece</option>
                        <option value="GL">Greenland</option>
                        <option value="GD">Grenada</option>
                        <option value="GP">Guadeloupe</option>
                        <option value="GU">Guam</option>
                        <option value="GT">Guatemala</option>
                        <option value="GG">Guernsey</option>
                        <option value="GN">Guinea</option>
                        <option value="GW">Guinea-Bissau</option>
                        <option value="GY">Guyana</option>
                        <option value="HT">Haiti</option>
                        <option value="HM">Heard</option>
                        <option value="VA">Holy See</option>
                        <option value="HN">Honduras</option>
                        <option value="HK">Hong Kong</option>
                        <option value="HU">Hungary</option>
                        <option value="IS">Iceland</option>
                        <option value="IN">India</option>
                        <option value="ID">Indonesia</option>
                        <option value="IR">Iran</option>
                        <option value="IQ">Iraq</option>
                        <option value="IE">Ireland</option>
                        <option value="IM">Isle of Man</option>
                        <option value="IL">Israel</option>
                        <option value="IT">Italy</option>
                        <option value="JM">Jamaica</option>
                        <option value="JP">Japan</option>
                        <option value="JE">Jersey</option>
                        <option value="JO">Jordan</option>
                        <option value="KZ">Kazakhstan</option>
                        <option value="KE">Kenya</option>
                        <option value="KI">Kiribati</option>
                        <option value="KP">Korea</option>
                        <option value="KR">Korea, Republic of</option>
                        <option value="XK">Kosovo</option>
                        <option value="KW">Kuwait</option>
                        <option value="KG">Kyrgyzstan</option>
                        <option value="LA">Lao People's</option>
                        <option value="LV">Latvia</option>
                        <option value="LB">Lebanon</option>
                        <option value="LS">Lesotho</option>
                        <option value="LR">Liberia</option>
                        <option value="LY">Libyan</option>
                        <option value="LI">Liechtenstein</option>
                        <option value="LT">Lithuania</option>
                        <option value="LU">Luxembourg</option>
                        <option value="MO">Macao</option>
                        <option value="MK">Macedonia</option>
                        <option value="MG">Madagascar</option>
                        <option value="MW">Malawi</option>
                        <option value="MY">Malaysia</option>
                        <option value="MV">Maldives</option>
                        <option value="ML">Mali</option>
                        <option value="MT">Malta</option>
                        <option value="MH">Marshall Islands</option>
                        <option value="MQ">Martinique</option>
                        <option value="MR">Mauritania</option>
                        <option value="MU">Mauritius</option>
                        <option value="YT">Mayotte</option>
                        <option value="MX">Mexico</option>
                        <option value="FM">Micronesia</option>
                        <option value="MD">Moldova, Republic of</option>
                        <option value="MC">Monaco</option>
                        <option value="MN">Mongolia</option>
                        <option value="ME">Montenegro</option>
                        <option value="MS">Montserrat</option>
                        <option value="MA">Morocco</option>
                        <option value="MZ">Mozambique</option>
                        <option value="MM">Myanmar</option>
                        <option value="NA">Namibia</option>
                        <option value="NR">Nauru</option>
                        <option value="NP">Nepal</option>
                        <option value="NL">Netherlands</option>
                        <option value="AN">Netherlands Antilles</option>
                        <option value="NC">New Caledonia</option>
                        <option value="NZ">New Zealand</option>
                        <option value="NI">Nicaragua</option>
                        <option value="NE">Niger</option>
                        <option value="NG">Nigeria</option>
                        <option value="NU">Niue</option>
                        <option value="NF">Norfolk Island</option>
                        <option value="MP">Northern Mariana Islands</option>
                        <option value="NO">Norway</option>
                        <option value="OM">Oman</option>
                        <option value="PK">Pakistan</option>
                        <option value="PW">Palau</option>
                        <option value="PS">Palestinian Territory</option>
                        <option value="PA">Panama</option>
                        <option value="PG">Papua New Guinea</option>
                        <option value="PY">Paraguay</option>
                        <option value="PE">Peru</option>
                        <option value="PH">Philippines</option>
                        <option value="PN">Pitcairn</option>
                        <option value="PL">Poland</option>
                        <option value="PT">Portugal</option>
                        <option value="PR">Puerto Rico</option>
                        <option value="QA">Qatar</option>
                        <option value="RE">Reunion</option>
                        <option value="RO">Romania</option>
                        <option value="RU">Russian Federation</option>
                        <option value="RW">Rwanda</option>
                        <option value="BL">Saint Barthelemy</option>
                        <option value="SH">Saint Helena</option>
                        <option value="KN">Saint Kitts and Nevis</option>
                        <option value="LC">Saint Lucia</option>
                        <option value="MF">Saint Martin</option>
                        <option value="PM">Saint Pierre</option>
                        <option value="VC">Saint Vincent</option>
                        <option value="WS">Samoa</option>
                        <option value="SM">San Marino</option>
                        <option value="ST">Sao Tome and Principe</option>
                        <option value="SA">Saudi Arabia</option>
                        <option value="SN">Senegal</option>
                        <option value="RS">Serbia</option>
                        <option value="CS">Serbia and Montenegro</option>
                        <option value="SC">Seychelles</option>
                        <option value="SL">Sierra Leone</option>
                        <option value="SG">Singapore</option>
                        <option value="SX">Sint Maarten</option>
                        <option value="SK">Slovakia</option>
                        <option value="SI">Slovenia</option>
                        <option value="SB">Solomon Islands</option>
                        <option value="SO">Somalia</option>
                        <option value="ZA">South Africa</option>
                        <option value="GS">South Georgia</option>
                        <option value="SS">South Sudan</option>
                        <option value="ES">Spain</option>
                        <option value="LK">Sri Lanka</option>
                        <option value="SD">Sudan</option>
                        <option value="SR">Suriname</option>
                        <option value="SJ">Svalbard</option>
                        <option value="SZ">Swaziland</option>
                        <option value="SE">Sweden</option>
                        <option value="CH">Switzerland</option>
                        <option value="SY">Syrian Arab Republic</option>
                        <option value="TW">Taiwan</option>
                        <option value="TJ">Tajikistan</option>
                        <option value="TZ">Tanzania</option>
                        <option value="TH">Thailand</option>
                        <option value="TL">Timor-Leste</option>
                        <option value="TG">Togo</option>
                        <option value="TK">Tokelau</option>
                        <option value="TO">Tonga</option>
                        <option value="TT">Trinidad</option>
                        <option value="TN">Tunisia</option>
                        <option value="TR">Turkey</option>
                        <option value="TM">Turkmenistan</option>
                        <option value="TC">Turks</option>
                        <option value="TV">Tuvalu</option>
                        <option value="UG">Uganda</option>
                        <option value="UA">Ukraine</option>
                        <option value="AE">United Arab Emirates</option>
                        <option value="GB">United Kingdom</option>
                        <option value="US">United States</option>
                        <option value="UM">United States Minor</option>
                        <option value="UY">Uruguay</option>
                        <option value="UZ">Uzbekistan</option>
                        <option value="VU">Vanuatu</option>
                        <option value="VE">Venezuela</option>
                        <option value="VN">Viet Nam</option>
                        <option value="VG">Virgin Islands</option>
                        <option value="VI">Virgin Islands</option>
                        <option value="WF">Wallis and Futuna</option>
                        <option value="EH">Western Sahara</option>
                        <option value="YE">Yemen</option>
                        <option value="ZM">Zambia</option>
                        <option value="ZW">Zimbabwe</option>
                    </select>
                    <div class="checkboxDivTextInput35652">
                        <div class="seaDiv">
                            <img class="mt-1" width="20" height="20"
                                src="{{ asset('/backend-assets/forthebuilders/images/Verifed.png') }}" alt="Trash">
                        </div>
                        <div class="seaDiv">
                            <img class="mt-1" width="20" height="20"
                                src="{{ asset('/backend-assets/forthebuilders/images/trash.png') }}" alt="Trash">
                        </div>
                    </div>
                </div> --}}
{{-- <a href="./perevod.html" class="yazik_soxranitBtn2">
                    <img src="{{ asset('/backend-assets/forthebuilders/images/plus-circle.png') }}"
                        alt="Plus">{{ translate('Add language') }}
                </a> --}}
