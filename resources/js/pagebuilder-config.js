/**
 * GrapesJS Configuration for VeCarCMS Page Builder
 * This file contains all custom blocks, components, and styles
 */

export const pageBuilderConfig = {
    // Canvas settings
    canvas: {
        styles: [
            'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
        ],
    },

    // Storage manager configuration
    storageManager: false,

    // Style manager configuration
    styleManager: {
        sectors: [
            {
                name: 'General',
                open: true,
                buildProps: ['width', 'height', 'margin', 'padding'],
            },
            {
                name: 'Typography',
                open: false,
                buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align'],
            },
            {
                name: 'Decorations',
                open: false,
                buildProps: ['background-color', 'border-radius', 'border', 'box-shadow'],
            },
            {
                name: 'Extra',
                open: false,
                buildProps: ['opacity', 'transition', 'transform'],
            },
        ],
    },

    // Device manager
    deviceManager: {
        devices: [
            {
                name: 'Desktop',
                width: '',
            },
            {
                name: 'Tablet',
                width: '768px',
                widthMedia: '992px',
            },
            {
                name: 'Mobile',
                width: '320px',
                widthMedia: '480px',
            },
        ],
    },

    // Plugins
    plugins: ['gjs-blocks-basic', 'grapesjs-plugin-forms'],
    pluginsOpts: {
        'gjs-blocks-basic': {},
        'grapesjs-plugin-forms': {},
    },
};

/**
 * Custom blocks for VeCarCMS
 */
export const customBlocks = (editor) => {
    const blockManager = editor.BlockManager;

    // Container Block
    blockManager.add('container', {
        label: '<i class="fa fa-square"></i> Container',
        category: 'Layout',
        content: `
            <div class="container mx-auto px-4 py-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold mb-4">Container Block</h2>
                    <p class="text-gray-600">Start building your content here</p>
                </div>
            </div>
        `,
        attributes: { class: 'block-container' },
    });

    // Hero Section
    blockManager.add('hero-section', {
        label: '<i class="fa fa-image"></i> Hero Section',
        category: 'Sections',
        content: `
            <section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-20">
                <div class="container mx-auto px-4 text-center">
                    <h1 class="text-5xl font-bold mb-6">Welcome to Your Page</h1>
                    <p class="text-xl mb-8">Create amazing content with our page builder</p>
                    <a href="#" class="inline-block bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Get Started
                    </a>
                </div>
            </section>
        `,
        attributes: { class: 'block-hero' },
    });

    // Two Columns
    blockManager.add('two-columns', {
        label: '<i class="fa fa-columns"></i> Two Columns',
        category: 'Layout',
        content: `
            <div class="container mx-auto px-4 py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-6 bg-gray-50 rounded-lg">
                        <h3 class="text-2xl font-bold mb-4">Column 1</h3>
                        <p class="text-gray-600">Add your content here</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-lg">
                        <h3 class="text-2xl font-bold mb-4">Column 2</h3>
                        <p class="text-gray-600">Add your content here</p>
                    </div>
                </div>
            </div>
        `,
        attributes: { class: 'block-columns' },
    });

    // Three Columns
    blockManager.add('three-columns', {
        label: '<i class="fa fa-th"></i> Three Columns',
        category: 'Layout',
        content: `
            <div class="container mx-auto px-4 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 bg-white shadow-lg rounded-lg">
                        <h3 class="text-xl font-bold mb-3">Column 1</h3>
                        <p class="text-gray-600">Add your content here</p>
                    </div>
                    <div class="p-6 bg-white shadow-lg rounded-lg">
                        <h3 class="text-xl font-bold mb-3">Column 2</h3>
                        <p class="text-gray-600">Add your content here</p>
                    </div>
                    <div class="p-6 bg-white shadow-lg rounded-lg">
                        <h3 class="text-xl font-bold mb-3">Column 3</h3>
                        <p class="text-gray-600">Add your content here</p>
                    </div>
                </div>
            </div>
        `,
        attributes: { class: 'block-three-columns' },
    });

    // Card Component
    blockManager.add('card', {
        label: '<i class="fa fa-id-card"></i> Card',
        category: 'Components',
        content: `
            <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-sm">
                <img src="https://via.placeholder.com/400x200" alt="Card image" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Card Title</h3>
                    <p class="text-gray-600 mb-4">This is a card description. Add your content here.</p>
                    <a href="#" class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">
                        Learn More
                    </a>
                </div>
            </div>
        `,
        attributes: { class: 'block-card' },
    });

    // Feature Box
    blockManager.add('feature-box', {
        label: '<i class="fa fa-star"></i> Feature Box',
        category: 'Components',
        content: `
            <div class="text-center p-8">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-star text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Feature Title</h3>
                <p class="text-gray-600">Description of your amazing feature goes here.</p>
            </div>
        `,
        attributes: { class: 'block-feature' },
    });

    // Call to Action
    blockManager.add('cta-section', {
        label: '<i class="fa fa-bullhorn"></i> Call to Action',
        category: 'Sections',
        content: `
            <section class="bg-purple-600 text-white py-16">
                <div class="container mx-auto px-4 text-center">
                    <h2 class="text-4xl font-bold mb-4">Ready to Get Started?</h2>
                    <p class="text-xl mb-8">Join thousands of satisfied users today</p>
                    <div class="flex justify-center gap-4">
                        <a href="#" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                            Start Free Trial
                        </a>
                        <a href="#" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition">
                            Learn More
                        </a>
                    </div>
                </div>
            </section>
        `,
        attributes: { class: 'block-cta' },
    });

    // Testimonial
    blockManager.add('testimonial', {
        label: '<i class="fa fa-quote-right"></i> Testimonial',
        category: 'Components',
        content: `
            <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl">
                <div class="flex items-center mb-4">
                    <img src="https://via.placeholder.com/60" alt="Author" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-bold">John Doe</h4>
                        <p class="text-gray-600 text-sm">CEO, Company Inc.</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">"This is an amazing product. It has completely transformed how we work!"</p>
            </div>
        `,
        attributes: { class: 'block-testimonial' },
    });

    // Spacer
    blockManager.add('spacer', {
        label: '<i class="fa fa-arrows-alt-v"></i> Spacer',
        category: 'Layout',
        content: '<div class="py-8"></div>',
        attributes: { class: 'block-spacer' },
    });

    // Divider
    blockManager.add('divider', {
        label: '<i class="fa fa-minus"></i> Divider',
        category: 'Layout',
        content: '<hr class="my-8 border-gray-300">',
        attributes: { class: 'block-divider' },
    });
};

/**
 * Initialize custom components
 */
export const initCustomComponents = (editor) => {
    // Add custom traits for links
    editor.DomComponents.addType('link', {
        isComponent: el => el.tagName === 'A',
        model: {
            defaults: {
                traits: [
                    'title',
                    'href',
                    {
                        type: 'select',
                        label: 'Target',
                        name: 'target',
                        options: [
                            { value: '', name: 'Same Window' },
                            { value: '_blank', name: 'New Window' },
                        ],
                    },
                ],
            },
        },
    });
};

