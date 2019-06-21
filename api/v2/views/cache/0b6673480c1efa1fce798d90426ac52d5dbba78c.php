<?php echo $__env->make('header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('title', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <table class="content" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2">
                <p class="p-content" style="margin-top:0px;">
                    Dentro de la app, escribe el siguiente código para activar tu cuenta.
                </p>
                <p id="activation-code"> <?php echo e($code); ?> </p>
                <p class="p-content">Le damos la bienvenida a la comunidad.</p>
                <p class="p-content" style="margin-bottom:0px;">
                    [El equipo de <strong>Dicapp</strong>]
                </p>
            </td>
        </tr>
    </table>
<?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH D:\projects\api-dicabeg\api\v2\views\templates/accountActivation.blade.php ENDPATH**/ ?>