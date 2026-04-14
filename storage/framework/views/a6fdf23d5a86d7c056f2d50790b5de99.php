
<?php $__env->startSection('title', 'Card Management | MPG Solution'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .table-container {
        display: flex;
        flex-wrap: wrap;
        /* Allow tables to wrap to the next line */
    }

    .table-responsive {
        width: 100%;
        /* Occupy full width on smaller screens */
        margin-bottom: 20px;
        /* Add some margin between tables */
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    h3 {
        margin-bottom: 10px;
    }
</style>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Card Summary</h3>
        </div>
        <div class="card-body">
            <!-- <h3>Total summary</h3> -->
            <div class="table-container">
                <div class="table-responsive">
                    <h3>Total summary</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Total USD IN Cards</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo e($summary->totalUSD); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/card/card-details.blade.php ENDPATH**/ ?>