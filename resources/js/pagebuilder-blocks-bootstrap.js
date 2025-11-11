/**
 * Bootstrap 5 Blocks for GrapesJS Page Builder
 * Optimized for Sigma Theme
 */

export const bootstrapBlocks = (editor) => {
    console.log('📦 Adding Bootstrap blocks...');
    const blockManager = editor.BlockManager;

    // Container Block
    blockManager.add('bs-container', {
        label: '<i class="ri-layout-line"></i><div>Container</div>',
        category: 'Layout',
        content: `
            <div class="container py-5">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="display-5 fw-bold mb-3">Container Block</h2>
                        <p class="text-muted">Start building your content here</p>
                    </div>
                </div>
            </div>
        `,
    });

    // Hero Section
    blockManager.add('bs-hero', {
        label: '<i class="ri-image-line"></i><div>Hero</div>',
        category: 'Sections',
        content: `
            <section class="bg-primary text-white py-10 py-lg-15">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-5 mb-lg-0">
                            <h1 class="display-3 fw-bold mb-4">Welcome to Your Page</h1>
                            <p class="lead mb-5">Create amazing content with our page builder</p>
                            <a href="#" class="btn btn-light btn-lg">Get Started</a>
                        </div>
                        <div class="col-lg-6">
                            <img src="https://via.placeholder.com/600x400" alt="Hero" class="img-fluid rounded-3 shadow">
                        </div>
                    </div>
                </div>
            </section>
        `,
    });

    // Two Columns
    blockManager.add('bs-2-columns', {
        label: '<i class="ri-layout-column-line"></i><div>2 Columns</div>',
        category: 'Layout',
        content: `
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-4 bg-light rounded">
                            <h3 class="h4 fw-bold mb-3">Column 1</h3>
                            <p class="text-muted">Add your content here</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 bg-light rounded">
                            <h3 class="h4 fw-bold mb-3">Column 2</h3>
                            <p class="text-muted">Add your content here</p>
                        </div>
                    </div>
                </div>
            </div>
        `,
    });

    // Three Columns
    blockManager.add('bs-3-columns', {
        label: '<i class="ri-layout-grid-line"></i><div>3 Columns</div>',
        category: 'Layout',
        content: `
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h3 class="h5 fw-bold mb-3">Column 1</h3>
                                <p class="card-text text-muted">Add your content here</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h3 class="h5 fw-bold mb-3">Column 2</h3>
                                <p class="card-text text-muted">Add your content here</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h3 class="h5 fw-bold mb-3">Column 3</h3>
                                <p class="card-text text-muted">Add your content here</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `,
    });

    // Card Component
    blockManager.add('bs-card', {
        label: '<i class="ri-file-list-3-line"></i><div>Card</div>',
        category: 'Components',
        content: `
            <div class="card border-0 shadow-sm" style="max-width: 400px;">
                <img src="https://via.placeholder.com/400x250" class="card-img-top" alt="Card image">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Card Title</h5>
                    <p class="card-text text-muted">This is a card description. Add your content here.</p>
                    <a href="#" class="btn btn-primary">Learn More</a>
                </div>
            </div>
        `,
    });

    // Feature Box
    blockManager.add('bs-feature', {
        label: '<i class="ri-star-line"></i><div>Feature</div>',
        category: 'Components',
        content: `
            <div class="text-center p-5">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 80px; height: 80px;">
                    <i class="ri-star-fill text-primary" style="font-size: 2rem;"></i>
                </div>
                <h3 class="h5 fw-bold mb-3">Feature Title</h3>
                <p class="text-muted">Description of your amazing feature goes here.</p>
            </div>
        `,
    });

    // Call to Action
    blockManager.add('bs-cta', {
        label: '<i class="ri-megaphone-line"></i><div>CTA</div>',
        category: 'Sections',
        content: `
            <section class="bg-primary text-white py-10">
                <div class="container">
                    <div class="row justify-content-center text-center">
                        <div class="col-lg-8">
                            <h2 class="display-5 fw-bold mb-4">Ready to Get Started?</h2>
                            <p class="lead mb-5">Join thousands of satisfied users today</p>
                            <div class="d-flex flex-wrap gap-3 justify-content-center">
                                <a href="#" class="btn btn-light btn-lg">Get Started Free</a>
                                <a href="#" class="btn btn-outline-light btn-lg">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        `,
    });

    // Testimonial
    blockManager.add('bs-testimonial', {
        label: '<i class="ri-chat-quote-line"></i><div>Testimonial</div>',
        category: 'Components',
        content: `
            <div class="card border-0 shadow-sm p-5" style="max-width: 500px;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <img src="https://ui-avatars.com/api/?name=John+Doe&size=64" class="rounded-circle me-3" width="64" height="64" alt="Avatar">
                        <div>
                            <h5 class="fw-bold mb-0">John Doe</h5>
                            <small class="text-muted">CEO, Company Inc.</small>
                        </div>
                    </div>
                    <p class="text-muted fst-italic mb-3">"This product has completely transformed how we work. Highly recommended!"</p>
                    <div class="text-warning">
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                    </div>
                </div>
            </div>
        `,
    });

    // Pricing Table
    blockManager.add('bs-pricing', {
        label: '<i class="ri-price-tag-3-line"></i><div>Pricing</div>',
        category: 'Components',
        content: `
            <div class="card border-0 shadow-sm text-center" style="max-width: 350px;">
                <div class="card-body p-5">
                    <h5 class="text-muted text-uppercase mb-3">Basic Plan</h5>
                    <div class="mb-4">
                        <span class="display-4 fw-bold">$29</span>
                        <span class="text-muted">/month</span>
                    </div>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-3"><i class="ri-check-line text-success me-2"></i>Feature One</li>
                        <li class="mb-3"><i class="ri-check-line text-success me-2"></i>Feature Two</li>
                        <li class="mb-3"><i class="ri-check-line text-success me-2"></i>Feature Three</li>
                        <li class="mb-3 text-muted"><i class="ri-close-line text-danger me-2"></i>Premium Feature</li>
                    </ul>
                    <a href="#" class="btn btn-primary w-100">Get Started</a>
                </div>
            </div>
        `,
    });

    // Alert/Notice
    blockManager.add('bs-alert', {
        label: '<i class="ri-alert-line"></i><div>Alert</div>',
        category: 'Components',
        content: `
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                <i class="ri-information-line me-3 fs-4"></i>
                <div>
                    <strong class="fw-bold">Heads up!</strong> This is an important message for your visitors.
                </div>
            </div>
        `,
    });

    // Button Group
    blockManager.add('bs-buttons', {
        label: '<i class="ri-links-line"></i><div>Buttons</div>',
        category: 'Components',
        content: `
            <div class="d-flex flex-wrap gap-3">
                <a href="#" class="btn btn-primary">Primary Button</a>
                <a href="#" class="btn btn-secondary">Secondary Button</a>
                <a href="#" class="btn btn-outline-primary">Outline Button</a>
            </div>
        `,
    });

    // Image with Caption
    blockManager.add('bs-image', {
        label: '<i class="ri-image-line"></i><div>Image</div>',
        category: 'Media',
        content: `
            <figure class="figure">
                <img src="https://via.placeholder.com/800x400" class="figure-img img-fluid rounded shadow" alt="Image">
                <figcaption class="figure-caption text-center mt-2">Image caption goes here</figcaption>
            </figure>
        `,
    });

    // List Group
    blockManager.add('bs-list', {
        label: '<i class="ri-list-check"></i><div>List</div>',
        category: 'Components',
        content: `
            <div class="list-group shadow-sm">
                <div class="list-group-item">
                    <i class="ri-check-line text-success me-2"></i>First item in the list
                </div>
                <div class="list-group-item">
                    <i class="ri-check-line text-success me-2"></i>Second item in the list
                </div>
                <div class="list-group-item">
                    <i class="ri-check-line text-success me-2"></i>Third item in the list
                </div>
            </div>
        `,
    });

    // Accordion
    blockManager.add('bs-accordion', {
        label: '<i class="ri-folder-line"></i><div>Accordion</div>',
        category: 'Components',
        content: `
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            First Question
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Answer to the first question goes here.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            Second Question
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Answer to the second question goes here.
                        </div>
                    </div>
                </div>
            </div>
        `,
    });

    // Stats / Counter
    blockManager.add('bs-stats', {
        label: '<i class="ri-bar-chart-line"></i><div>Stats</div>',
        category: 'Sections',
        content: `
            <div class="container py-5">
                <div class="row g-4 text-center">
                    <div class="col-6 col-lg-3">
                        <div class="p-4">
                            <div class="display-4 fw-bold text-primary mb-2">1000+</div>
                            <div class="text-muted">Happy Customers</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="p-4">
                            <div class="display-4 fw-bold text-primary mb-2">50+</div>
                            <div class="text-muted">Team Members</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="p-4">
                            <div class="display-4 fw-bold text-primary mb-2">100%</div>
                            <div class="text-muted">Satisfaction</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="p-4">
                            <div class="display-4 fw-bold text-primary mb-2">24/7</div>
                            <div class="text-muted">Support</div>
                        </div>
                    </div>
                </div>
            </div>
        `,
    });

    // Features Grid
    blockManager.add('bs-features', {
        label: '<i class="ri-grid-line"></i><div>Features</div>',
        category: 'Sections',
        content: `
            <div class="container py-10">
                <div class="text-center mb-8">
                    <h2 class="display-5 fw-bold mb-3">Our Features</h2>
                    <p class="lead text-muted">Everything you need in one place</p>
                </div>
                <div class="row g-5">
                    <div class="col-md-6 col-lg-4">
                        <div class="text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 80px; height: 80px;">
                                <i class="ri-smartphone-line text-primary fs-2"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Responsive Design</h4>
                            <p class="text-muted">Looks great on all devices</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 80px; height: 80px;">
                                <i class="ri-palette-line text-primary fs-2"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Beautiful UI</h4>
                            <p class="text-muted">Modern and clean interface</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 80px; height: 80px;">
                                <i class="ri-rocket-line text-primary fs-2"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Fast Performance</h4>
                            <p class="text-muted">Optimized for speed</p>
                        </div>
                    </div>
                </div>
            </div>
        `,
    });

    // Team Member
    blockManager.add('bs-team', {
        label: '<i class="ri-team-line"></i><div>Team</div>',
        category: 'Components',
        content: `
            <div class="card border-0 shadow-sm text-center" style="max-width: 300px;">
                <div class="card-body p-4">
                    <img src="https://ui-avatars.com/api/?name=John+Smith&size=128" class="rounded-circle mb-3" width="128" height="128" alt="Team member">
                    <h5 class="fw-bold mb-1">John Smith</h5>
                    <p class="text-muted mb-3">CEO & Founder</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="ri-twitter-line"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="ri-linkedin-line"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="ri-github-line"></i></a>
                    </div>
                </div>
            </div>
        `,
    });

    // Divider
    blockManager.add('bs-divider', {
        label: '<i class="ri-separator"></i><div>Divider</div>',
        category: 'Basic',
        content: `<hr class="my-5 border-2 opacity-10">`,
    });

    // Spacer
    blockManager.add('bs-spacer', {
        label: '<i class="ri-space"></i><div>Spacer</div>',
        category: 'Basic',
        content: `<div class="py-5"></div>`,
    });

    // Quote
    blockManager.add('bs-quote', {
        label: '<i class="ri-double-quotes-l"></i><div>Quote</div>',
        category: 'Typography',
        content: `
            <blockquote class="blockquote p-5 bg-light rounded border-start border-5 border-primary">
                <p class="mb-3 fs-5">"This is an inspiring quote that will motivate your visitors."</p>
                <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
            </blockquote>
        `,
    });
    
    const totalBlocks = blockManager.getAll().length;
    console.log(`✅ ${totalBlocks} blocks added to BlockManager`);
};

