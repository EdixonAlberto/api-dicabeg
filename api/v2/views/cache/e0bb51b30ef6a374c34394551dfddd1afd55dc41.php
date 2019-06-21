<?php echo $__env->make('header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('title', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <table class="content-title" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td-title">
                <p class="p-content p-bold" >
                    txt1
                </p>
            </td>
            <td id="td-image">
                <img id="image-title"
                    src="https://marketing-image-production.s3.amazonaws.com/uploads/6c4762dd02d31c09466f2271a4bea39d1ba7e4c43c3c8dfe0576251fdde04cac0fa4dd3871fee780a5e1a5c112a512c31db336580389384e276268a4984335fb.png"
                    alt="image-phone" />
            </td>
        </tr>
    </table>

    <table class="content" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2">
                <p class="p-content" style="margin-top:0px;">
                    txt2
                </p>
                <p class="p-bold" id="activation-code"> <?php echo e($code); ?> </p>

                <p class="p-content">txt3</p>

                <p class="p-content" style="margin-bottom:0px;">

                    [El equipo de <strong>Dicapp</strong>]
                </p>
            </td>
        </tr>
    </table>
<?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\api-dicabeg\api\v2\views\templates/emailUpdate.blade.php ENDPATH**/ ?>