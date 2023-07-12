<?php $__env->startSection('title'); ?>
    <?php echo e(translate('Currency')); ?>

<?php $__env->stopSection(); ?>
<link rel="stylesheet" href="<?php echo e(asset('/backend-assets/forthebuilders/datatables/css/dataTables.bootstrap4.min.css')); ?>">
<link rel="stylesheet"
    href="<?php echo e(asset('/backend-assets/forthebuilders/datatables-responsive/css/responsive.bootstrap4.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('/backend-assets/forthebuilders/toastr/css/toastr.min.css')); ?>">

<?php $__env->startSection('content'); ?>
    <div class="d-flex aad">
        <?php echo $__env->make('forthebuilder::layouts.content.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="mainMargin">
            <?php echo $__env->make('forthebuilder::layouts.content.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <a href="<?php echo e(route('forthebuilder.settings.index')); ?>"
                        class="plus2 profileMaxNazadInformatsiyaKlient"><img
                            src="<?php echo e(asset('/backend-assets/forthebuilders/images/icons/arrow-left.png')); ?>"
                            alt=""></a>
                    <h2 class="panelUprText"><?php echo e(translate('Currencies')); ?></h2>
                    <a href="#" class="plus2 addNewCurrency">+</a>
                    
                </div>
            </div>

            <div class="kursValyutaDataBig">
                <div class="kursValyutaData">
                    <div class="d-flex">
                        <div class="kursValyutaUsd">
                            <img height="30"
                                src="<?php echo e(asset('/backend-assets/forthebuilders/images/2560px-Flag_of_the_United_States.png')); ?>"
                                alt="Usa">
                        </div>
                        <div class="kursValyutaUsd">
                            <img height="30"
                                src="<?php echo e(asset('/backend-assets/forthebuilders/images/1200px-Flag_of_Uzbekistan.png')); ?>"
                                alt="Uzb">
                        </div>
                    </div>

                    <div>
                        <div class="kursValyutaUsd">
                            <?php echo e(translate('Date')); ?>

                        </div>
                    </div>
                </div>

                <div class="kursValyutaData">
                    <?php if($model): ?>
                        <div class="d-flex">
                            <input type="hidden" id="currencyId" class="" value="<?php echo e($model->id); ?>">
                            <div class="kursValyutaUsd">
                                <input type="text" value="<?php echo e($model->USD); ?>" class="kursValyutaWhite currencyUpdate"
                                    disabled data-status="USD">
                                
                            </div>
                            <div class="kursValyutaUsd">
                                <input type="text" value="<?php echo e($model->SUM); ?>" class="kursValyutaWhite currencyUpdate"
                                    disabled data-status="SUM">
                                
                            </div>
                        </div>

                        <div>
                            <div class="kursValyutaUsd">
                                <div class="kursValyutaWhite"><?php echo e(date('d.m.Y H:i', strtotime($model->created_at))); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="kursValyutaData formNewCurrency d-none">
                    <div class="d-flex">
                        <input type="hidden" id="currencyId" class="" value="">
                        <div class="kursValyutaUsd">
                            <input type="text" value="" class="kursValyutaWhite currencyUsd" data-status="USD">
                            
                        </div>
                        <div class="kursValyutaUsd">
                            <input type="text" value="" class="kursValyutaWhite currencyUzs" data-status="SUM">
                            
                        </div>
                    </div>

                    <div>
                        <div class="kursValyutaUsd">
                            <div class="checkboxDivTextInput35652">
                                <div class="seaDiv currencySave" style="cursor: pointer;">
                                    <img class="mt-1" width="20" height="20"
                                        src="<?php echo e(asset('/backend-assets/forthebuilders/images/Verifed.png')); ?>"
                                        alt="Trash">
                                </div>
                                <div class="seaDiv removeFormCurrency" style="cursor: pointer;">
                                    <img class="mt-1" width="20" height="20"
                                        src="<?php echo e(asset('/backend-assets/forthebuilders/images/trash.png')); ?>"
                                        alt="Trash">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="#" class="seaDiv" id="currencyUpdateButton" title="update"
                        style="margin-right: 10px; text-align: center">
                        
                        <img class="mt-1" width="20" height="20"
                            src="<?php echo e(asset('/backend-assets/forthebuilders/images/edit.png')); ?>" alt="Edit">
                    </a>
                    <form style="display: inline-block;" action="<?php echo e(route('forthebuilder.currency.destroy')); ?>"
                        method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <div style="margin-right: 10px; text-align: center" type="submit" class="seaDiv" title="delete">
                            <img class="mt-1" width="20" height="20"
                                src="<?php echo e(asset('/backend-assets/forthebuilders/images/trash.png')); ?>" alt="Trash">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        page_name = 'page-currency';
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('forthebuilder::layouts.forthebuilder', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/nurhayat.icstroy.uz/public_html/Modules/ForTheBuilder/Resources/views/currency/index.blade.php ENDPATH**/ ?>