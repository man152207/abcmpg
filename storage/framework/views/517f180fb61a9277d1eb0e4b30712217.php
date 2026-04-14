

<?php $__env->startSection('content'); ?>

<div class="container">
    <h2><?php echo e(isset($quotation) ? 'Edit Quotation' : 'Generate Quotation'); ?></h2>
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Multi-Step Form -->
    <form id="quotationForm" action="<?php echo e(isset($quotation) ? route('quotation.update', $quotation->id) : route('quotation.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if(isset($quotation)): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <!-- Step 1: Customer Information -->
        <div class="step">
            <h3>Step 1: Customer Information</h3>
            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo e($quotation->customer_name ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="company">Company</label>
                <input type="text" class="form-control" id="company" name="company" value="<?php echo e($quotation->company ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo e($quotation->email ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e($quotation->phone ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo e($quotation->address ?? ''); ?>">
            </div>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>

        <!-- Step 2: Select Services and Budget Package -->
        <div class="step" style="display:none;">
            <h3>Step 2: Select Services and Budget Package</h3>
            <div class="form-group">
                <label for="service_details">Service Details</label>
                <select class="form-control" id="service_details" name="service_details" required>
                    <option value="" disabled selected>Select a service</option>
                    <option value="Platform Advertising" <?php echo e((isset($quotation) && $quotation->service_details == 'Platform Advertising') ? 'selected' : ''); ?>>Platform Advertising</option>
                    <option value="Lead Generation" <?php echo e((isset($quotation) && $quotation->service_details == 'Lead Generation') ? 'selected' : ''); ?>>Lead Generation</option>
                    <option value="Content Strategy & Consultation" <?php echo e((isset($quotation) && $quotation->service_details == 'Content Strategy & Consultation') ? 'selected' : ''); ?>>Content Strategy & Consultation</option>
                    <option value="Graphic Design" <?php echo e((isset($quotation) && $quotation->service_details == 'Graphic Design') ? 'selected' : ''); ?>>Graphic Design</option>
                    <option value="Video Making and Editing" <?php echo e((isset($quotation) && $quotation->service_details == 'Video Making and Editing') ? 'selected' : ''); ?>>Video Making and Editing</option>
                </select>
            </div>
            <div class="form-group">
                <label for="budget">Budget</label>
                <input type="number" class="form-control" id="budget" name="budget" value="<?php echo e($quotation->budget ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="duration">Duration (days)</label>
                <input type="number" class="form-control" id="duration" name="duration" value="<?php echo e($quotation->duration ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="target_location">Target Location</label>
                <input type="text" class="form-control" id="target_location" name="target_location" value="<?php echo e($quotation->target_location ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="age_range">Age Range</label>
                <select class="form-control" id="age_range" name="age_range" required>
                    <option value="18-24" <?php echo e((isset($quotation) && $quotation->age_range == '18-24') ? 'selected' : ''); ?>>18-24</option>
                    <option value="25-34" <?php echo e((isset($quotation) && $quotation->age_range == '25-34') ? 'selected' : ''); ?>>25-34</option>
                    <option value="35-44" <?php echo e((isset($quotation) && $quotation->age_range == '35-44') ? 'selected' : ''); ?>>35-44</option>
                    <option value="45-54" <?php echo e((isset($quotation) && $quotation->age_range == '45-54') ? 'selected' : ''); ?>>45-54</option>
                    <option value="55+" <?php echo e((isset($quotation) && $quotation->age_range == '55+') ? 'selected' : ''); ?>>55+</option>
                </select>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="all" <?php echo e((isset($quotation) && $quotation->gender == 'all') ? 'selected' : ''); ?>>All</option>
                    <option value="male" <?php echo e((isset($quotation) && $quotation->gender == 'male') ? 'selected' : ''); ?>>Male</option>
                    <option value="female" <?php echo e((isset($quotation) && $quotation->gender == 'female') ? 'selected' : ''); ?>>Female</option>
                </select>
            </div>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>

        <!-- Step 3: Define Campaign Objectives -->
        <div class="step" style="display:none;">
            <h3>Step 3: Define Campaign Objectives</h3>
            <div class="form-group">
                <label for="campaign_objectives">Campaign Objectives</label>
                <select class="form-control" id="campaign_objectives" name="campaign_objectives" required>
                    <option value="" disabled selected>Select an objective</option>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>

        <!-- Step 4: Estimated Results -->
        <div class="step" style="display:none;">
            <h3>Step 4: Estimated Results</h3>
            <div id="estimatedResults"></div>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>

        <!-- Step 5: Payment and Feedback Preferences -->
        <div class="step" style="display:none;">
            <h3>Step 5: Payment and Feedback Preferences</h3>
            <div class="form-group">
                <label for="total_price">Total Price</label>
                <input type="number" class="form-control" id="total_price" name="total_price" value="<?php echo e($quotation->total_price ?? ''); ?>" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="pending" <?php echo e((isset($quotation) && $quotation->status == 'pending') ? 'selected' : ''); ?>>Pending</option>
                    <option value="sent" <?php echo e((isset($quotation) && $quotation->status == 'sent') ? 'selected' : ''); ?>>Sent</option>
                    <option value="approved" <?php echo e((isset($quotation) && $quotation->status == 'approved') ? 'selected' : ''); ?>>Approved</option>
                    <option value="rejected" <?php echo e((isset($quotation) && $quotation->status == 'rejected') ? 'selected' : ''); ?>>Rejected</option>
                </select>
            </div>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="submit" class="btn btn-primary"><?php echo e(isset($quotation) ? 'Update Quotation' : 'Generate Quotation'); ?></button>
        </div>
    </form>

    <hr>

    <h2>All Quotations</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Company</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Service Details</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $quotations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quotation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($quotation->customer_name); ?></td>
                    <td><?php echo e($quotation->company); ?></td>
                    <td><?php echo e($quotation->email); ?></td>
                    <td><?php echo e($quotation->phone); ?></td>
                    <td><?php echo e($quotation->service_details); ?></td>
                    <td>Rs. <?php echo e(number_format($quotation->total_price, 2)); ?></td>
                    <td><?php echo e(ucfirst($quotation->status)); ?></td>
                    <td>
                        <a href="<?php echo e(route('quotation.edit', $quotation->id)); ?>" class="btn btn-warning">Edit</a>
                        <a href="<?php echo e(route('quotation.pdf', $quotation->id)); ?>" class="btn btn-success">Download</a>
                        <a href="<?php echo e(route('quotation.view', $quotation->id)); ?>" class="btn btn-info">View</a>
                        <form action="<?php echo e(route('quotation.destroy', $quotation->id)); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this quotation?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<!-- JavaScript for Multi-Step Form -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentStep = 0;
        const steps = document.querySelectorAll('.step');
        const nextBtns = document.querySelectorAll('.next-step');
        const prevBtns = document.querySelectorAll('.prev-step');

        steps[currentStep].style.display = 'block';

        nextBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                steps[currentStep].style.display = 'none';
                currentStep = Math.min(steps.length - 1, currentStep + 1);
                steps[currentStep].style.display = 'block';
            });
        });

        prevBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                steps[currentStep].style.display = 'none';
                currentStep = Math.max(0, currentStep - 1);
                steps[currentStep].style.display = 'block';
            });
        });

        // Update objectives based on service details
        const serviceDetails = document.getElementById('service_details');
        const campaignObjectives = document.getElementById('campaign_objectives');
        
        const updateEstimatedResults = () => {
            const budget = document.getElementById('budget').value;
            const duration = document.getElementById('duration').value;
            const targetLocation = document.getElementById('target_location').value;
            const ageRange = document.getElementById('age_range').value;
            const gender = document.getElementById('gender').value;

            fetch('/calculate-estimated-results', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    budget,
                    duration,
                    target_location: targetLocation,
                    age_range: ageRange,
                    gender
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('estimatedResults').innerHTML = `
                    <p>Estimated Impressions: ${data.impressions}</p>
                    <p>Estimated Clicks: ${data.clicks}</p>
                `;
            });
        };

        serviceDetails.addEventListener('change', function () {
            const service = this.value;
            let options = '';

            if (['Platform Advertising', 'Lead Generation'].includes(service)) {
                options += '<option value="Brand Awareness">Brand Awareness</option>';
                options += '<option value="Reach">Reach</option>';
                options += '<option value="Traffic">Traffic</option>';
                options += '<option value="Engagement">Engagement</option>';
                options += '<option value="App Installs">App Installs</option>';
                options += '<option value="Video Views">Video Views</option>';
                options += '<option value="Lead Generation">Lead Generation</option>';
                options += '<option value="Conversions">Conversions</option>';
                options += '<option value="Store Traffic">Store Traffic</option>';
                options += '<option value="Get More Messages">Get More Messages</option>';
            } else if (['Content Strategy & Consultation', 'Graphic Design', 'Video Making and Editing'].includes(service)) {
                options += '<option value="Improve Content Engagement">Improve Content Engagement</option>';
                options += '<option value="Enhance Visual Branding">Enhance Visual Branding</option>';
                options += '<option value="Increase Video Viewership">Increase Video Viewership</option>';
            }

            campaignObjectives.innerHTML = '<option value="" disabled selected>Select an objective</option>' + options;

            updateEstimatedResults();
        });

        // Update estimated results whenever relevant fields change
        document.getElementById('budget').addEventListener('input', updateEstimatedResults);
        document.getElementById('duration').addEventListener('input', updateEstimatedResults);
        document.getElementById('target_location').addEventListener('input', updateEstimatedResults);
        document.getElementById('age_range').addEventListener('input', updateEstimatedResults);
        document.getElementById('gender').addEventListener('input', updateEstimatedResults);

        // Trigger initial objective update
        serviceDetails.dispatchEvent(new Event('change'));
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/item/quotation_generate.blade.php ENDPATH**/ ?>