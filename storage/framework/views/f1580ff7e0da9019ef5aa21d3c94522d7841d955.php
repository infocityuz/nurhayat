<?php
    use Modules\ForTheBuilder\Entities\Constants;
?>
<?php $__env->startSection('content'); ?>
    <div class="d-flex aad">
        <?php echo $__env->make('forthebuilder::layouts.content.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="mainMargin">
            <?php echo $__env->make('forthebuilder::layouts.content.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <a href="<?php echo e(route('forthebuilder.clients.index')); ?>" class="plus2 profileMaxNazadInformatsiyaKlient">
                        <img src="<?php echo e(asset('backend-assets/forthebuilders/images/icons/arrow-left.png')); ?>" alt="">
                    </a>
                    <h2 class="panelUprText"><?php echo e(translate('Creating a new client')); ?></h2>
                </div>
            </div>

            <div class="sozdatJkData">
                <form id="" action="<?php echo e(route('forthebuilder.clients.store')); ?>" method="POST"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field("POST"); ?>
                    <input type="hidden" name="client_id" class="booking-client_id" id="">
                    <div class="sozdatImyaSpsok">
                        <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('First name')); ?></h3>
                        <input
                            class="sozdatImyaSpisokInput keyUpName booking-first_name <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            type="text" name="first_name" id="first_name" autocomplete="off"
                            value="<?php echo e($data['first_name'] ?? old('first_name')); ?>">
                        <div class="keyUpNameResult d-none"
                            style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                        </div>
                        <span class="error-data">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <?php echo e($message); ?>

                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </span>
                    </div>

                    <div class="sozdatImyaSpsok">
                        <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('Last name')); ?></h3>
                        <input
                            class="sozdatImyaSpisokInput keyUpName booking-last_name <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            type="text" name="last_name" id="last_name" autocomplete="off"
                            value="<?php echo e($data['last_name'] ?? old('last_name')); ?>">
                        <div class="keyUpNameResult d-none"
                            style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                        </div>
                        <span class="error-data">
                            <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <?php echo e($message); ?>

                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </span>
                    </div>

                    <div class="sozdatImyaSpsok">
                        <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('Middle name')); ?></h3>
                        <input
                            class="sozdatImyaSpisokInput keyUpName booking-middle_name <?php $__errorArgs = ['middle_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            type="text" name="middle_name" id="middle_name" autocomplete="off"
                            value="<?php echo e($data['middle_name'] ?? old('middle_name')); ?>">
                        <div class="keyUpNameResult d-none"
                            style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                        </div>
                        <span class="error-data">
                            <?php $__errorArgs = ['middle_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <?php echo e($message); ?>

                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </span>
                    </div>

                    <div class="sozdatImyaSpsok">
                        <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('Phone number')); ?></h3>
                        <div class="d-flex">
                            <div>
                                <img src="<?php echo e(asset('backend-assets/forthebuilders/images/region.png')); ?>" alt="Region">
                            </div>
                            <div>
                                <label
                                    style="margin-bottom: -35px;z-index: 99;width: 50px;margin-left: 5px;margin-right: -55px;position: sticky;margin-top: 13px;padding-left: 6px;"
                                    for="+998">+998</label>
                                <input
                                    class="sozdatImyaSpisokInputTel keyUpName booking-phone <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    type="tel" id="phone" name="phone" 
                                    value="<?php echo e($data['phone'] ?? old('phone')); ?>">
                                <div class="keyUpNameResult d-none"
                                    style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                                </div>
                                <span class="error-data">
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <?php echo e($message); ?>

                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="sozdatImyaSpsok">
                        <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('Additional phone number')); ?></h3>
                        <div class="d-flex">
                            <div>
                                <img src="<?php echo e(asset('backend-assets/forthebuilders/images/region.png')); ?>" alt="Region">
                            </div>
                            <div>
                                <label
                                    style="margin-bottom: -35px;z-index: 99;width: 50px;margin-left: 5px;margin-right: -55px;position: sticky;margin-top: 13px;padding-left: 6px;"
                                    for="+998">+998</label>
                                <input
                                    class="sozdatImyaSpisokInputTel keyUpName booking-additional_phone <?php $__errorArgs = ['additional_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    type="tel" id="additional_phone" name="additional_phone" 
                                    value="<?php echo e($data['additional_phone'] ?? old('additional_phone')); ?>">
                                <div class="keyUpNameResult d-none"
                                    style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                                </div>
                                <span class="error-data">
                                    <?php $__errorArgs = ['additional_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <?php echo e($message); ?>

                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="sozdatImyaSpsok">
                        <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('email')); ?></h3>
                        <input class="sozdatImyaSpisokInput <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            type="email" name="email" id="email" value="<?php echo e($data['email'] ?? old('email')); ?>">
                        <span class="error-data">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <?php echo e($message); ?>

                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </span>
                    </div>

                    <div class="sozdatImyaSpsok">
                        <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('Source')); ?></h3>
                        <input class="sozdatImyaSpisokInput <?php $__errorArgs = ['source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            type="text" name="source" id="source" value="<?php echo e($data['source'] ?? old('source')); ?>">
                        <span class="error-data">
                            <?php $__errorArgs = ['source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <?php echo e($message); ?>

                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="form-group width_45">
                            <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('status')); ?></h3>
                            <select class="form-control width_100 sozdatImyaSpisokSelectOption <?php $__errorArgs = ['lead_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid error-data-input <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="exampleFormControlSelect1" name="lead_status" data-placeholder="<?php echo e(translate('select')); ?>">
                                <option value="<?php echo e(Constants::FIRST_CONTACT); ?>"><?php echo e(translate('First contact')); ?></option>
                                <option value="<?php echo e(Constants::NEGOTIATION); ?>"><?php echo e(translate('Negotiation')); ?></option>
                            </select>
                            <span class="error-data">
                                <?php $__errorArgs = ['lead_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <?php echo e($message); ?>

                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </span>
                        </div>
                        <div class="sozdatImyaSpsok1 width_45">
                            <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('What is looking for')); ?></h3>
                            <input class="sozdatImyaSpisokInput <?php $__errorArgs = ['looking_for'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   type="text" name="looking_for" id="looking_for"
                                   value="<?php echo e($data['looking_for'] ?? old('looking_for')); ?>">
                            <span class="error-data">
                                <?php $__errorArgs = ['looking_for'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <?php echo e($message); ?>

                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="sozdatImyaSpsok width_45">
                            <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('Serial number of the passport')); ?></h3>
                            <input
                                class="sozdatImyaSpisokServerniyNomer keyUpName booking-series_number <?php $__errorArgs = ['series_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                type="text" placeholder="AA1234567" name="series_number" id="series_number"
                                value="<?php echo e($data['series_number'] ?? old('series_number')); ?>" autocomplete="off">
                            <div class="keyUpNameResult d-none"
                                style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                            </div>
                            <span class="error-data">
                                <?php $__errorArgs = ['series_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <?php echo e($message); ?>

                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </span>
                        </div>

                        <div class="sozdatImyaSpsok width_45">
                            <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('Issued by')); ?></h3>
                            <input class="sozdatImyaSpisokInput <?php $__errorArgs = ['issued_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                type="text" name="issued_by" id="issued_by"
                                value="<?php echo e($data['issued_by'] ?? old('issued_by')); ?>">
                            <span class="error-data">
                                <?php $__errorArgs = ['issued_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <?php echo e($message); ?>

                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="sozdatImyaSpsok width_45">
                            <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('PINFL or INN')); ?></h3>
                            <input class="sozdatImyaSpisokPinfl width_100 <?php $__errorArgs = ['inn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                type="text" name="inn" id="inn" value="<?php echo e($data['inn'] ?? old('inn')); ?>">
                            <span class="error-data">
                                <?php $__errorArgs = ['inn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <?php echo e($message); ?>

                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </span>
                        </div>

                        <div class="sozdatImyaSpsok1 width_45 d-none" id="budget_modal">
                            <h3 class="sozdatImyaSpisokH3"><?php echo e(translate('Budget')); ?></h3>
                            <input class="sozdatImyaSpisokInput <?php $__errorArgs = ['budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error-data-input is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   type="number" name="budget" id="budget"
                                   value="<?php echo e($data['budget'] ?? old('budget')); ?>">
                            <span class="error-data">
                                <?php $__errorArgs = ['budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <?php echo e($message); ?>

                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </span>
                        </div>
                    </div>
                    <?php if($house_flat != ''): ?>
                        <input type="hidden" value="<?php echo e($house_flat->house_id); ?>" name="house_id">
                        <input type="hidden" value="<?php echo e($house_flat->id); ?>" name="house_flat_id">
                    <?php else: ?>
                        <input type="hidden" value="" name="house_id">
                        <input type="hidden" value="" name="house_flat_id">
                    <?php endif; ?>
                    <div class="sozdatImyaSpsok width_45 d-none" id="flat_modal">
                        <div class="d-flex mt-3">
                            <a href="<?php echo e(route('forthebuilder.client.house', '0')); ?>" class="plusFlexModalInformation color_black" id="select_flat">+</a>
                            <h3 class="plusFlexModalInformationDobavitCvartir"> <?php echo e(translate('Change apartment')); ?>

                            </h3>
                            <?php if($house_flat != ''): ?>
                                <span class="plusFlexModalInformationDobavitCvartir" id="interested_flat"><?php echo e($house_flat->house->name . ': ' . translate('entrance') . ': ' . $house_flat->entrance .'  '. translate('flat') . ': ' . $house_flat->number_of_flat); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="submit" class="sozdatImyaSpisokSozdatButton btn" id="create"><?php echo e(translate('create')); ?></button>
                    
                </form>
            </div>

        </div>
    </div>
    <script src="<?php echo e(asset('/backend-assets/plugins/select2/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('/backend-assets/plugins/moment/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('/backend-assets/plugins/bootstrap-datetimepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('/backend-assets/plugins/inputmask/jquery.inputmask.min.js')); ?>"></script>
    <script src="<?php echo e(asset('/backend-assets/plugins/jquery.maskedinput.min.js')); ?>"></script>
    <script src="<?php echo e(asset('/backend-assets/plugins/toastr/toastr.min.js')); ?>"></script>

    <script>
        let page_name = 'clients';
        $(document).ready(function() {
            $('#sent').datetimepicker({
                format: 'Y-M-D',
            });
            $('input[type=tel]').mask("(99) 999-99-99");

            let sessionWarning = "<?php echo e(session('warning')); ?>";
            if (sessionWarning) {
                toastr.warning(sessionWarning)
            }
            if($('#exampleFormControlSelect1').val() == 2){
                if($('#flat_modal').hasClass('d-none')){
                    $('#flat_modal').removeClass('d-none');
                }
                if($('#budget_modal').hasClass('d-none')){
                    $('#budget_modal').removeClass('d-none');
                }
            }
            $('#exampleFormControlSelect1').on('change', function(){
                if($(this).val() == 2){
                    if($('#flat_modal').hasClass('d-none')){
                        $('#flat_modal').removeClass('d-none');
                    }
                    if($('#budget_modal').hasClass('d-none')){
                        $('#budget_modal').removeClass('d-none');
                    }
                }else{
                    if(!$('#flat_modal').hasClass('d-none')){
                        $('#flat_modal').addClass('d-none');
                    }
                    if(!$('#budget_modal').hasClass('d-none')){
                        $('#budget_modal').addClass('d-none');
                    }
                }
            });
            <?php if($house_flat != ''): ?>
                if(localStorage.getItem('first_name') != undefined){
                    $('#first_name').val(localStorage.getItem('first_name'))
                }
                if(localStorage.getItem('last_name') != undefined){
                    $('#last_name').val(localStorage.getItem('last_name'))
                }
                if(localStorage.getItem('middle_name') != undefined){
                    $('#middle_name').val(localStorage.getItem('middle_name'))
                }
                if(localStorage.getItem('phone') != undefined){
                    $('#phone').val(localStorage.getItem('phone'))
                }
                if(localStorage.getItem('additional_phone') != undefined){
                    $('#additional_phone').val(localStorage.getItem('additional_phone'))
                }
                if(localStorage.getItem('email') != undefined){
                    $('#email').val(localStorage.getItem('email'))
                }
                if(localStorage.getItem('source') != undefined){
                    $('#source').val(localStorage.getItem('source'))
                }
                if(localStorage.getItem('looking_for') != undefined){
                    $('#looking_for').val(localStorage.getItem('looking_for'))
                }
                if(localStorage.getItem('series_number') != undefined){
                    $('#series_number').val(localStorage.getItem('series_number'))
                }
                if(localStorage.getItem('issued_by') != undefined){
                    $('#issued_by').val(localStorage.getItem('issued_by'))
                }
                if(localStorage.getItem('inn') != undefined){
                    $('#inn').val(localStorage.getItem('inn'))
                }
                if(localStorage.getItem('budget') != undefined){
                    $('#budget').val(localStorage.getItem('budget'))
                }
            <?php endif; ?>
            $('#select_flat').on('click', function() {
                if($('#first_name').val() != undefined){
                    localStorage.setItem('first_name', $('#first_name').val())
                }
                if($('#last_name').val() != undefined){
                    localStorage.setItem('last_name', $('#last_name').val())
                }
                if($('#middle_name').val() != undefined){
                    localStorage.setItem('middle_name', $('#middle_name').val())
                }
                if($('#phone').val() != undefined){
                    localStorage.setItem('phone', $('#phone').val())
                }
                if($('#additional_phone').val() != undefined){
                    localStorage.setItem('additional_phone', $('#additional_phone').val())
                }
                if($('#email').val() != undefined){
                    localStorage.setItem('email', $('#email').val())
                }
                if($('#source').val() != undefined){
                    localStorage.setItem('source', $('#source').val())
                }
                if($('#looking_for').val() != undefined){
                    localStorage.setItem('looking_for', $('#looking_for').val())
                }
                if($('#series_number').val() != undefined){
                    localStorage.setItem('series_number', $('#series_number').val())
                }
                if($('#issued_by').val() != undefined){
                    localStorage.setItem('issued_by', $('#issued_by').val())
                }
                if($('#inn').val() != undefined){
                    localStorage.setItem('inn', $('#inn').val())
                }
                if($('#budget').val() != undefined){
                    localStorage.setItem('budget', $('#budget').val())
                }
            });
            $('#create').on('click', function(){
                localStorage.removeItem('first_name')
                localStorage.removeItem('last_name')
                localStorage.removeItem('middle_name')
                localStorage.removeItem('phone')
                localStorage.removeItem('additional_phone')
                localStorage.removeItem('email')
                localStorage.removeItem('source')
                localStorage.removeItem('looking_for')
                localStorage.removeItem('series_number')
                localStorage.removeItem('issued_by')
                localStorage.removeItem('inn')
                localStorage.removeItem('budget')
            });
        });
    </script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('forthebuilder::layouts.forthebuilder', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/nurhayat.icstroy.uz/public_html/Modules/ForTheBuilder/Resources/views/clients/create.blade.php ENDPATH**/ ?>