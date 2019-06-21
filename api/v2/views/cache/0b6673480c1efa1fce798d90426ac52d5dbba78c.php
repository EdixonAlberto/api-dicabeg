<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.title',[
        'description' => 'Estas a solo un paso de abrir tu cuenta Dicabeg',
        'imagePath' => 'public/img/phone.png'
    ]); ?><?php echo $__env->renderComponent(); ?>

    <table class="content" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2">
                <p>Dentro de la app, escribe el siguiente código para activar tu cuenta.</p>

                <div id="code">
                    <p class="p-bold" id="button"><?php echo e($code); ?></p>
                </div>

                <p>Le damos la bienvenida a la comunidad.</p>
                <p>[El equipo de <strong>Dicapp</strong>]</p>
            </td>
        </tr>
    </table>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('emailLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\api-dicabeg\api\v2\views\templates/accountActivation.blade.php ENDPATH**/ ?>