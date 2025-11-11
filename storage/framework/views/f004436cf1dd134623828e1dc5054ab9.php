<?php
    $defaultMembers = [
        [
            'name' => 'Jack Johnston',
            'role' => 'Founder & CEO',
            'bio' => 'Serial angel investor and entrepreneur, Jack ha guidato più startup di successo prima di VeCarCMS.',
            'avatar' => 'profile-small-2.jpeg',
            'social' => [
                ['icon' => 'ri-linkedin-box-fill', 'url' => '#'],
                ['icon' => 'ri-facebook-box-fill', 'url' => '#'],
                ['icon' => 'ri-twitter-fill', 'url' => '#'],
            ],
        ],
        [
            'name' => 'JP Laurent',
            'role' => 'Executive Chairman',
            'bio' => 'Guida la strategia globale e supporta i team prodotto con focus sulla crescita.',
            'avatar' => 'profile-small-3.jpeg',
            'social' => [
                ['icon' => 'ri-linkedin-box-fill', 'url' => '#'],
                ['icon' => 'ri-facebook-box-fill', 'url' => '#'],
                ['icon' => 'ri-twitter-fill', 'url' => '#'],
            ],
        ],
        [
            'name' => 'Gary Waite',
            'role' => 'Founder & CTO',
            'bio' => 'Responsabile dell’architettura tecnica e della roadmap prodotto.',
            'avatar' => 'profile-small-4.jpeg',
            'social' => [
                ['icon' => 'ri-linkedin-box-fill', 'url' => '#'],
                ['icon' => 'ri-facebook-box-fill', 'url' => '#'],
                ['icon' => 'ri-twitter-fill', 'url' => '#'],
            ],
        ],
        [
            'name' => 'Patricia Smith',
            'role' => 'VP Marketing',
            'bio' => 'Guida le campagne globali e coordina la community dei partner.',
            'avatar' => 'profile-small-5.jpeg',
            'social' => [
                ['icon' => 'ri-linkedin-box-fill', 'url' => '#'],
                ['icon' => 'ri-facebook-box-fill', 'url' => '#'],
                ['icon' => 'ri-twitter-fill', 'url' => '#'],
            ],
        ],
        [
            'name' => 'Samanth Rowson',
            'role' => 'Head Designer',
            'bio' => 'Gestisce il design system e l’esperienza utente multi-piattaforma.',
            'avatar' => 'profile-small-6.jpeg',
            'social' => [
                ['icon' => 'ri-linkedin-box-fill', 'url' => '#'],
                ['icon' => 'ri-facebook-box-fill', 'url' => '#'],
                ['icon' => 'ri-twitter-fill', 'url' => '#'],
            ],
        ],
        [
            'name' => 'Jack Smith',
            'role' => 'Technical Lead',
            'bio' => 'Coordina i team di sviluppo e garantisce qualità e performance.',
            'avatar' => 'profile-small-7.jpeg',
            'social' => [
                ['icon' => 'ri-linkedin-box-fill', 'url' => '#'],
                ['icon' => 'ri-facebook-box-fill', 'url' => '#'],
                ['icon' => 'ri-twitter-fill', 'url' => '#'],
            ],
        ],
    ];

    $members = (is_array($members) && count($members)) ? $members : $defaultMembers;

    $resolveAvatar = function ($avatar) {
        if (empty($avatar)) {
            return theme_asset('assets/images/profile-small-2.jpeg');
        }

        return filter_var($avatar, FILTER_VALIDATE_URL)
            ? $avatar
            : theme_asset('assets/images/' . ltrim($avatar, '/'));
    };
?>

<section class="py-8">
    <div class="container">
        <h2 class="display-5 fw-bold mb-6 text-center"><?php echo e($title); ?></h2>
        <div class="row g-6">
            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card rounded shadow-lg h-100">
                        <div class="card-body d-flex align-items-center flex-column justify-content-center text-center p-5">
                            <picture class="avatar">
                                <img class="img-fluid rounded-circle" src="<?php echo e($resolveAvatar($member['avatar'] ?? null)); ?>" alt="<?php echo e($member['name'] ?? ''); ?>">
                            </picture>
                            <p class="lead fw-bolder mb-0 mt-4"><?php echo e($member['name'] ?? ''); ?></p>
                            <?php if(!empty($member['role'])): ?>
                                <p class="text-primary small fw-bold mb-4"><?php echo e($member['role']); ?></p>
                            <?php endif; ?>
                            <?php if(!empty($member['bio'])): ?>
                                <p class="text-muted"><?php echo e($member['bio']); ?></p>
                            <?php endif; ?>
                            <?php if(!empty($member['social']) && is_array($member['social'])): ?>
                                <ul class="list-unstyled d-flex align-items-center justify-content-center mb-0">
                                    <?php $__currentLoopData = $member['social']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="mx-2">
                                            <a href="<?php echo e($social['url'] ?? '#'); ?>" class="text-decoration-none">
                                                <i class="<?php echo e($social['icon'] ?? 'ri-linkedin-box-fill'); ?> ri-2x"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/shortcodes/dwn-about-team.blade.php ENDPATH**/ ?>