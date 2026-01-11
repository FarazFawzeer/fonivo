

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Customer', 'subtitle' => 'Create'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">New Customer</h5>
        </div>

        <div class="card-body">
            <div id="message"></div> 

            <form id="createCustomerForm" action="<?php echo e(route('admin.customers.store')); ?>" method="POST">

                <?php echo csrf_field(); ?>

                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name')); ?>"
                            placeholder="Ex: John Doe" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo e(old('email')); ?>"
                            placeholder="Ex: john@example.com" required>
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="contact" class="form-label">Phone</label>
                        <input type="text" name="contact" id="contact" class="form-control"
                            value="<?php echo e(old('contact')); ?>" placeholder="Ex: +94771234567">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="other_phone" class="form-label">Other Phone</label>
                        <input type="text" name="other_phone" id="other_phone" class="form-control"
                            value="<?php echo e(old('other_phone')); ?>" placeholder="Ex: +94779876543">
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control"
                            value="<?php echo e(old('whatsapp_number')); ?>" placeholder="Ex: +94771234567">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                            value="<?php echo e(old('date_of_birth')); ?>">
                    </div>
                </div>


                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control"
                            value="<?php echo e(old('address')); ?>" placeholder="Ex: 123 Main Street, Colombo">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" name="country" id="country" class="form-control"
                            value="<?php echo e(old('country')); ?>" placeholder="Ex: Sri Lanka">
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="service" class="form-label">Service</label>
                        <select name="service" id="service" class="form-select">
                            <option value="">Select Service</option>
                            <option value="Tour Package" <?php echo e(old('service') == 'Tour Package' ? 'selected' : ''); ?>>Tour
                                Package</option>
                            <option value="Rent Vehicle" <?php echo e(old('service') == 'Rent Vehicle' ? 'selected' : ''); ?>>Rent
                                Vehicle</option>
                            <option value="Transportation" <?php echo e(old('service') == 'Transportation' ? 'selected' : ''); ?>>
                                Transportation</option>
                            <option value="Airline Ticketing"
                                <?php echo e(old('service') == 'Airline Ticketing' ? 'selected' : ''); ?>>Airline Ticketing</option>
                            <option value="Insurance Service"
                                <?php echo e(old('service') == 'Insurance Service' ? 'selected' : ''); ?>>Insurance Service</option>
                            <option value="Visa Assistance" <?php echo e(old('service') == 'Visa Assistance' ? 'selected' : ''); ?>>
                                Visa Assistance</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="heard_us" class="form-label">From where did you hear about us?</label>
                        <select name="heard_us" id="heard_us" class="form-select">
                            <option value="">Select</option>
                            <option value="Working Customer"
                                <?php echo e(old('heard_us') == 'Working Customer' ? 'selected' : ''); ?>>Working Customer</option>
                            <option value="Trip Advisor" <?php echo e(old('heard_us') == 'Trip Advisor' ? 'selected' : ''); ?>>Trip
                                Advisor</option>
                            <option value="Google" <?php echo e(old('heard_us') == 'Google' ? 'selected' : ''); ?>>Google</option>
                            <option value="Facebook" <?php echo e(old('heard_us') == 'Facebook' ? 'selected' : ''); ?>>Facebook
                            </option>
                            <option value="Instagram" <?php echo e(old('heard_us') == 'Instagram' ? 'selected' : ''); ?>>Instagram
                            </option>
                            <option value="TikTok" <?php echo e(old('heard_us') == 'TikTok' ? 'selected' : ''); ?>>TikTok</option>
                        </select>
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="Individual" <?php echo e(old('type') == 'Individual' ? 'selected' : ''); ?>>Individual
                            </option>
                            <option value="Corporate" <?php echo e(old('type') == 'Corporate' ? 'selected' : ''); ?>>Corporate
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="companyDiv" style="display: none;">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" name="company_name" id="company_name" class="form-control"
                            value="<?php echo e(old('company_name')); ?>" placeholder="Ex: ABC Travels Pvt Ltd">
                    </div>
                </div>

                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Customer</button>
                </div>
            </form>

        </div>
    </div>

    <script>
        // Show company field if type = Corporate
        document.getElementById('type').addEventListener('change', function() {
            const companyDiv = document.getElementById('companyDiv');
            if (this.value === 'Corporate') {
                companyDiv.style.display = 'block';
            } else {
                companyDiv.style.display = 'none';
            }
        });
    </script>
    
    <script>
        document.getElementById('createCustomerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let form = this;
            let formData = new FormData(form);

            fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    let messageBox = document.getElementById('message');
                    if (data.success) {
                        messageBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        form.reset();
                        setTimeout(() => {
                            messageBox.innerHTML = "";
                        }, 3000);
                    } else {
                        let errors = data.errors ? data.errors.join('<br>') : data.message;
                        messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                    }
                })
                .catch(error => {
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-danger">Something went wrong. Please try again.</div>`;
                    console.error(error);
                });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Customer Create'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/customer/create.blade.php ENDPATH**/ ?>