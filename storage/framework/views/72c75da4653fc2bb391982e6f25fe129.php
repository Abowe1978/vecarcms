<?php $__env->startSection('title', $page->title . ' - ' . settings('site_name', 'VeCarCMS')); ?>
<?php $__env->startSection('meta_description', $page->meta_description ?? 'Get in touch with us'); ?>

<?php $__env->startSection('content'); ?>


<section class="py-4 bg-light border-bottom">
    <div class="container">
        <?php echo render_breadcrumbs($page); ?>

    </div>
</section>


<?php if($page->show_title ?? true): ?>
<section class="py-10 py-lg-12 bg-gradient-to-br from-primary-50 to-secondary-50">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-lg-8">
                <h1 class="display-4 fw-bold mb-4"><?php echo e($page->title); ?></h1>
                <?php if($page->excerpt): ?>
                    <p class="lead text-muted"><?php echo e($page->excerpt); ?></p>
                <?php else: ?>
                    <p class="lead text-muted">We'd love to hear from you. Get in touch with us!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="py-10">
    <div class="container">
        <div class="row g-4 mb-10">
            
            
            <?php if(settings('contact_address')): ?>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4 hover-lift transition-all">
                    <div class="f-w-20 text-primary mb-3 mx-auto">
                        <i class="ri-map-pin-line"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Address</h5>
                    <p class="text-muted mb-0 small"><?php echo nl2br(e(settings('contact_address'))); ?></p>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if(settings('contact_email')): ?>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4 hover-lift transition-all">
                    <div class="f-w-20 text-primary mb-3 mx-auto">
                        <i class="ri-mail-line"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Email</h5>
                    <a href="mailto:<?php echo e(settings('contact_email')); ?>" class="text-muted text-decoration-none small">
                        <?php echo e(settings('contact_email')); ?>

                    </a>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if(settings('contact_phone')): ?>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4 hover-lift transition-all">
                    <div class="f-w-20 text-primary mb-3 mx-auto">
                        <i class="ri-phone-line"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Phone</h5>
                    <a href="tel:<?php echo e(str_replace(' ', '', settings('contact_phone'))); ?>" class="text-muted text-decoration-none small">
                        <?php echo e(settings('contact_phone')); ?>

                    </a>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if(settings('contact_hours')): ?>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4 hover-lift transition-all">
                    <div class="f-w-20 text-primary mb-3 mx-auto">
                        <i class="ri-time-line"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Working Hours</h5>
                    <p class="text-muted mb-0 small"><?php echo nl2br(e(settings('contact_hours'))); ?></p>
                </div>
            </div>
            <?php endif; ?>

        </div>

        <div class="row g-8">
            
            
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm p-5">
                    <h3 class="fw-bold mb-4">Send us a Message</h3>
                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-check-line me-2"></i><?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line me-2"></i><?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('contact.submit')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="name" 
                                       name="name" 
                                       value="<?php echo e(old('name')); ?>"
                                       required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="email" 
                                       name="email" 
                                       value="<?php echo e(old('email')); ?>"
                                       required>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" 
                                       class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="phone" 
                                       name="phone" 
                                       value="<?php echo e(old('phone')); ?>">
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="subject" 
                                       name="subject" 
                                       value="<?php echo e(old('subject')); ?>"
                                       required>
                                <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          id="message" 
                                          name="message" 
                                          rows="6" 
                                          required><?php echo e(old('message')); ?></textarea>
                                <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <?php if(settings('recaptcha_enabled')): ?>
                            <div class="col-12">
                                <div class="g-recaptcha" data-sitekey="<?php echo e(config('services.recaptcha.site_key')); ?>"></div>
                                <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small mt-2"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <?php endif; ?>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="ri-send-plane-line me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="col-12 col-lg-6">
                
                
                <?php if(settings('contact_map_embed')): ?>
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="ratio ratio-16x9">
                        <?php echo settings('contact_map_embed'); ?>

                    </div>
                </div>
                <?php endif; ?>

                
                <?php if($page->content): ?>
                <div class="card border-0 shadow-sm p-5">
                    <h3 class="fw-bold mb-4">More Information</h3>
                    <div class="prose">
                        <?php echo do_shortcode($page->content); ?>

                    </div>
                </div>
                <?php endif; ?>

                
                <?php if(settings('social_facebook') || settings('social_twitter') || settings('social_instagram') || settings('social_linkedin')): ?>
                <div class="card border-0 shadow-sm p-5 mt-4">
                    <h4 class="fw-bold mb-4">Follow Us</h4>
                    <div class="d-flex flex-wrap gap-3">
                        <?php if(settings('social_facebook')): ?>
                            <a href="<?php echo e(settings('social_facebook')); ?>" target="_blank" class="btn btn-outline-primary">
                                <i class="ri-facebook-line me-2"></i>Facebook
                            </a>
                        <?php endif; ?>
                        <?php if(settings('social_twitter')): ?>
                            <a href="<?php echo e(settings('social_twitter')); ?>" target="_blank" class="btn btn-outline-primary">
                                <i class="ri-twitter-line me-2"></i>Twitter
                            </a>
                        <?php endif; ?>
                        <?php if(settings('social_instagram')): ?>
                            <a href="<?php echo e(settings('social_instagram')); ?>" target="_blank" class="btn btn-outline-primary">
                                <i class="ri-instagram-line me-2"></i>Instagram
                            </a>
                        <?php endif; ?>
                        <?php if(settings('social_linkedin')): ?>
                            <a href="<?php echo e(settings('social_linkedin')); ?>" target="_blank" class="btn btn-outline-primary">
                                <i class="ri-linkedin-line me-2"></i>LinkedIn
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>

        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php if(settings('recaptcha_enabled')): ?>
<?php $__env->startPush('scripts'); ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php $__env->stopPush(); ?>
<?php endif; ?>



<?php echo $__env->make('themes.dwntheme::layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/views/page-contact.blade.php ENDPATH**/ ?>