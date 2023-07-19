@extends('forthebuilder::layouts.forthebuilder')
@php
    $areas=json_decode($model->areas);
    $ares_prices=json_decode($model->ares_price);

    $price_100 = $ares_prices->hundred->total ?? 0 ;
    $price_50 = $ares_prices->fifty->total ?? 0;
    $price_30 = $ares_prices->thirty->total ?? 0;
    $price_70 = $ares_prices->seventy->total ?? 0;

    // dd(number_format($areas->total));
    $full_price_100=($areas->total)*($price_100); //382 536 000
    $full_price_50=($areas->total)*($price_50); // 418 968 000
    $full_price_30=($areas->total)*($price_30); // 455 400 000    
    $full_price_70=($areas->total)*($price_70); // 455 400 000    
    $sale_100=(($areas->total)*($price_30))-(($areas->total)*($price_100)); // 72864000
    $sale_50=(($areas->total)*($price_30))-(($areas->total)*($price_50));  // 36432000
    $sale_70=(($areas->total)*($price_70))-(($areas->total)*($price_70));  // 36432000


    if ($price_30 == 0 || $price_50 == 0 || $price_70 == 0) {
        $sele_100=0;
        $sale_50=0;
        $sale_70=0;
        $price_50=0;
        $price_30=0;
        $price_70=0;
        $full_price_50=0;
        $full_price_30=0;
        $full_price_70=0;
    }
@endphp


<div id="DivIdToPrint" style="width: 100%; position: relative;">
    <img style="position: absolute; top: 0; left: 0; width: 100%; z-index: -1" src="{{'/backend-assets/img/word/nurhayat.png'}}" alt="">
    
    <div style="width: 85%; margin: 200px auto;">
        <table style="border-collapse: collapse;width: 100%;">
            <tr>
                <td style="font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold;">Дата составления : {{ date('d.m.Y') }}</td>
                <td style="font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold;text-align: right;">
                    Прайс актуален до: {{ date('d.m.Y', strtotime($date)) }}
                </td>
            </tr>
        </table>
        <br>
        <table border="1" style="border-collapse: collapse; width: 100%;">
            <tr>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold;text-align: right; border: 0;padding-right: 10px;">
                    Блок: {{ $house->corpus ?? '' }}
                </td>
                
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: left; border: 0;padding-left: 10px;">
                    Этаж: {{ $model->floor }}
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold;text-align: right; border: 0;padding-right: 10px;">
                    Площадь квартиры: {{ $areas->total }} 
                </td>
                
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: left; border: 0;padding-left: 10px;">
                    Количество комнат: {{ $model->room_count }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold;text-align: center; border: 0;">
                    Цена за 1 м2: {{ number_format($price_100,0,'',' ') }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold;text-align: center;">
                    100% Оплата <span style="color: #CEA87E;">(cкидка 15%)</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    Цена за 1м2: 
                </td>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    {{ number_format($price_100,0,'',' ') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    Общая стоимость: 
                </td>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    @php 
                        $full_price_100 = $full_price_100 - (($full_price_100/100)*15);
                    @endphp
                    {{number_format($full_price_100,0,'',' ')}}
                </td>
            </tr>

            <tr>
                <td colspan="2" style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold;text-align: center;">
                    70% Оплата <span style="color: #CEA87E;">(cкидка 10%)</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    Цена за 1м2: 
                </td>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    {{ number_format($price_70,0,'',' ') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    Общая стоимость: 
                </td>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    @php 
                        // $full_price_70 = $full_price_70 - (($full_price_70/100)*10);
                    @endphp
                    {{number_format($full_price_70,0,'',' ')}}
                </td>
            </tr>

            <tr>
                <td colspan="2" style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold;text-align: center;">
                    50% Оплата <span style="color: #CEA87E;">(cкидка 5%)</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    Цена за 1м2: 
                </td>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    {{ number_format($price_50,0,'',' ') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    Общая стоимость: 
                </td>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    @php 
                        // $full_price_70 = $full_price_70 - (($full_price_70/100)*10);
                    @endphp
                    {{number_format($full_price_50,0,'',' ')}}
                </td>
            </tr>

            <tr>
                <td colspan="2" style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold;text-align: center;">
                    30% Оплата
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    Цена за 1м2: 
                </td>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    {{ number_format($price_30,0,'',' ') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    Общая стоимость: 
                </td>
                <td style="padding: 5px 5px; font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: center;">
                    @php 
                        // $full_price_70 = $full_price_70 - (($full_price_70/100)*10);
                    @endphp
                    {{number_format($full_price_30,0,'',' ')}}
                </td>
            </tr>
        </table> 
        
        <div style="position: absolute; width:85%; left: 7.5%; bottom: 160px;">
            <div style="width: 100%;font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: right;">
                Менеджер: {{ $user_name }}
            </div>   
            <div style="width: 100%;font-family: Times New 'Times New Roman', Times, serif; font-size: 16px;font-weight: bold; text-align: right;">
                Телефон: {{ (($phone_number) ? $phone_number : '+998781475050') }}
            </div>
            
            <table style="width: 100%;">
                <tr>
                    <td style="width: 20%; text-align: center;">
                        <br>
                        <br>
                        <img width="120px" src="{{ asset('/backend-assets/img/word/5.png') }}" alt="">
                        <br>
                        <br>
                        <div style="width: 100%;font-family: Times New 'Times New Roman', Times, serif; font-size: 14px;font-weight: bold; text-align: center;">
                            Большой <br> выбор <br> планировок
                        </div>
                    </td>
                    

                    <td style="width: 20%; text-align: center;">
                        <br>
                        
                        <img width="120px" src="{{ asset('/backend-assets/img/word/4.png') }}" alt="">
                        <br>
                        <br>
                        <div style="width: 100%;font-family: Times New 'Times New Roman', Times, serif; font-size: 14px;font-weight: bold; text-align: center;">
                            Надёжный <br> ЖК
                        </div>
                    </td>
                    
                    
                    <td style="width: 20%; text-align: center;">
                        <br>
                        
                        <img width="120px" src="{{ asset('/backend-assets/img/word/3.png') }}" alt="">
                        <br>
                        <br>
                        <div style="width: 100%;font-family: Times New 'Times New Roman', Times, serif; font-size: 14px;font-weight: bold; text-align: center;">
                            Удобная <br> локация
                        </div>
                    </td>


                    <td style="width: 20%; text-align: center;">
                        <br>
                        <br>
                        
                        <img width="120px" src="{{ asset('/backend-assets/img/word/1.png') }}" alt="">
                        <br>
                        <br>
                        <div style="width: 100%;font-family: Times New 'Times New Roman', Times, serif; font-size: 14px;font-weight: bold; text-align: center;">
                            Зеленая <br> зона
                        </div>
                    </td>
                    
                    <td style="width: 20%; text-align: center;">
                        <br>
                        <br>
                        <img width="120px" src="{{ asset('/backend-assets/img/word/2.png') }}" alt="">
                        <br>
                        <br>
                        <div style="width: 100%;font-family: Times New 'Times New Roman', Times, serif; font-size: 14px;font-weight: bold; text-align: center;">
                            Круглосуточная охрана и видеонаблюдение
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
</div>

<input type="hidden" id="showId" value="{{ $model->id }}">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('/backend-assets/forthebuilders/javascript/jquery-3.6.1.js') }}"></script>

<script>
    $(document).ready(function() {
        var divToPrint = document.getElementById('DivIdToPrint');
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML +
            '</body></html>');
        newWin.document.close();
        newWin.onafterprint = window.close;

        setTimeout(function() {
            var showId = $('#showId').val();
            location.href = "/forthebuilder/house-flat/show/" + showId;
        }, 10);
        // setTimeout(function() {
        //     newWin.close();
        // }, 50);
    })

    $(document).ready(function() {
        window.print();
    })
</script>
