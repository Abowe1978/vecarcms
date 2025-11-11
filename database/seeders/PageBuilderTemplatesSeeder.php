<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageBuilderTemplate;
use App\Models\User;

class PageBuilderTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creator = User::first();

        // Template 1: Homepage Hero with Features
        PageBuilderTemplate::create([
            'name' => 'Homepage - Hero & Features',
            'description' => 'A modern homepage with hero section and 3-column features',
            'thumbnail' => '/images/templates/homepage-hero.jpg',
            'content' => json_encode([
                'html' => '<section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-20"><div class="container mx-auto px-4 text-center"><h1 class="text-5xl font-bold mb-6">Welcome to VeCarCMS</h1><p class="text-xl mb-8">Create amazing websites with our powerful page builder</p><a href="/blog" class="inline-block bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">Get Started</a></div></section><div class="container mx-auto px-4 py-16"><div class="grid grid-cols-1 md:grid-cols-3 gap-8"><div class="text-center p-6"><div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-rocket text-purple-600 text-2xl"></i></div><h3 class="text-xl font-bold mb-3">Fast & Modern</h3><p class="text-gray-600">Built with Laravel and modern technologies</p></div><div class="text-center p-6"><div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-paint-brush text-purple-600 text-2xl"></i></div><h3 class="text-xl font-bold mb-3">Page Builder</h3><p class="text-gray-600">Visual drag & drop page builder like Elementor</p></div><div class="text-center p-6"><div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-shield-alt text-purple-600 text-2xl"></i></div><h3 class="text-xl font-bold mb-3">Secure</h3><p class="text-gray-600">Enterprise-level security and permissions</p></div></div></div>',
                'css' => '',
            ]),
            'created_by' => $creator->id ?? null,
            'is_active' => true,
        ]);

        // Template 2: About Page
        PageBuilderTemplate::create([
            'name' => 'About Us - Team & Mission',
            'description' => 'About page with mission statement and team section',
            'thumbnail' => '/images/templates/about.jpg',
            'content' => json_encode([
                'html' => '<div class="container mx-auto px-4 py-16"><div class="max-w-3xl mx-auto text-center mb-16"><h1 class="text-4xl font-bold mb-6">About Us</h1><p class="text-xl text-gray-600">We are passionate about creating amazing web experiences</p></div><div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16"><div><h2 class="text-3xl font-bold mb-4">Our Mission</h2><p class="text-gray-600 mb-4">To provide a powerful, flexible CMS that combines the best of WordPress and Laravel.</p><p class="text-gray-600">We believe in open-source technology and community-driven development.</p></div><div><h2 class="text-3xl font-bold mb-4">Our Values</h2><ul class="space-y-3"><li class="flex items-start"><i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i><span class="text-gray-600">Innovation and continuous improvement</span></li><li class="flex items-start"><i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i><span class="text-gray-600">User-friendly design and experience</span></li><li class="flex items-start"><i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i><span class="text-gray-600">Security and performance first</span></li></ul></div></div></div>',
                'css' => '',
            ]),
            'created_by' => $creator->id ?? null,
            'is_active' => true,
        ]);

        // Template 3: Contact Page
        PageBuilderTemplate::create([
            'name' => 'Contact - Form & Info',
            'description' => 'Contact page with form and contact information',
            'thumbnail' => '/images/templates/contact.jpg',
            'content' => json_encode([
                'html' => '<div class="container mx-auto px-4 py-16"><div class="max-w-4xl mx-auto"><h1 class="text-4xl font-bold text-center mb-12">Get In Touch</h1><div class="grid grid-cols-1 md:grid-cols-2 gap-12"><div><h2 class="text-2xl font-bold mb-6">Contact Information</h2><div class="space-y-4"><div class="flex items-start"><i class="fas fa-map-marker-alt text-purple-600 text-xl mr-4 mt-1"></i><div><h3 class="font-semibold">Address</h3><p class="text-gray-600">123 Main Street, City, Country</p></div></div><div class="flex items-start"><i class="fas fa-phone text-purple-600 text-xl mr-4 mt-1"></i><div><h3 class="font-semibold">Phone</h3><p class="text-gray-600">+1 (555) 123-4567</p></div></div><div class="flex items-start"><i class="fas fa-envelope text-purple-600 text-xl mr-4 mt-1"></i><div><h3 class="font-semibold">Email</h3><p class="text-gray-600">info@vecarcms.com</p></div></div></div></div><div><h2 class="text-2xl font-bold mb-6">Send a Message</h2><form class="space-y-4"><div><input type="text" placeholder="Your Name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></div><div><input type="email" placeholder="Your Email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></div><div><textarea rows="4" placeholder="Your Message" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea></div><button type="submit" class="w-full bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition">Send Message</button></form></div></div></div></div>',
                'css' => '',
            ]),
            'created_by' => $creator->id ?? null,
            'is_active' => true,
        ]);

        // Template 4: Pricing Page
        PageBuilderTemplate::create([
            'name' => 'Pricing - 3 Tiers',
            'description' => 'Pricing page with 3 pricing tiers',
            'thumbnail' => '/images/templates/pricing.jpg',
            'content' => json_encode([
                'html' => '<div class="bg-gradient-to-br from-purple-50 to-indigo-50 py-16"><div class="container mx-auto px-4"><h1 class="text-4xl font-bold text-center mb-4">Choose Your Plan</h1><p class="text-center text-gray-600 mb-12">Select the perfect plan for your needs</p><div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto"><div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition"><h3 class="text-2xl font-bold mb-4">Starter</h3><div class="mb-6"><span class="text-4xl font-bold">$9</span><span class="text-gray-600">/month</span></div><ul class="space-y-3 mb-8"><li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i><span>5 Pages</span></li><li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i><span>10 Posts</span></li><li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i><span>Basic Support</span></li></ul><button class="w-full bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition">Get Started</button></div><div class="bg-purple-600 text-white rounded-lg shadow-xl p-8 transform scale-105"><h3 class="text-2xl font-bold mb-4">Professional</h3><div class="mb-6"><span class="text-4xl font-bold">$29</span><span class="text-purple-200">/month</span></div><ul class="space-y-3 mb-8"><li class="flex items-center"><i class="fas fa-check mr-3"></i><span>Unlimited Pages</span></li><li class="flex items-center"><i class="fas fa-check mr-3"></i><span>Unlimited Posts</span></li><li class="flex items-center"><i class="fas fa-check mr-3"></i><span>Priority Support</span></li><li class="flex items-center"><i class="fas fa-check mr-3"></i><span>Page Builder</span></li></ul><button class="w-full bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">Get Started</button></div><div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition"><h3 class="text-2xl font-bold mb-4">Enterprise</h3><div class="mb-6"><span class="text-4xl font-bold">$99</span><span class="text-gray-600">/month</span></div><ul class="space-y-3 mb-8"><li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i><span>Everything in Pro</span></li><li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i><span>Custom Development</span></li><li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i><span>24/7 Support</span></li></ul><button class="w-full bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition">Contact Sales</button></div></div></div></div>',
                'css' => '',
            ]),
            'created_by' => $creator->id ?? null,
            'is_active' => true,
        ]);

        // Template 5: Features Page
        PageBuilderTemplate::create([
            'name' => 'Features - Grid Layout',
            'description' => 'Features page with icon grid and descriptions',
            'thumbnail' => '/images/templates/features.jpg',
            'content' => json_encode([
                'html' => '<div class="container mx-auto px-4 py-16"><h1 class="text-4xl font-bold text-center mb-4">Powerful Features</h1><p class="text-center text-gray-600 mb-16 max-w-2xl mx-auto">Everything you need to build amazing websites</p><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"><div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition"><div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4"><i class="fas fa-paint-brush text-purple-600 text-xl"></i></div><h3 class="text-xl font-bold mb-3">Page Builder</h3><p class="text-gray-600">Visual drag & drop builder like Elementor</p></div><div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition"><div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4"><i class="fas fa-mobile-alt text-purple-600 text-xl"></i></div><h3 class="text-xl font-bold mb-3">Responsive</h3><p class="text-gray-600">Mobile-first responsive design</p></div><div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition"><div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4"><i class="fas fa-search text-purple-600 text-xl"></i></div><h3 class="text-xl font-bold mb-3">SEO Optimized</h3><p class="text-gray-600">Built-in SEO tools and optimization</p></div><div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition"><div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4"><i class="fas fa-th-large text-purple-600 text-xl"></i></div><h3 class="text-xl font-bold mb-3">Widget System</h3><p class="text-gray-600">Flexible widget areas like WordPress</p></div><div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition"><div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4"><i class="fas fa-palette text-purple-600 text-xl"></i></div><h3 class="text-xl font-bold mb-3">Theme Customizer</h3><p class="text-gray-600">Live preview theme customization</p></div><div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition"><div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4"><i class="fas fa-chart-line text-purple-600 text-xl"></i></div><h3 class="text-xl font-bold mb-3">Analytics</h3><p class="text-gray-600">Built-in analytics and statistics</p></div></div></div>',
                'css' => '',
            ]),
            'created_by' => $creator->id ?? null,
            'is_active' => true,
        ]);

        // Template 6: Landing Page with CTA
        PageBuilderTemplate::create([
            'name' => 'Landing Page - Full CTA',
            'description' => 'High-converting landing page with multiple CTAs',
            'thumbnail' => '/images/templates/landing.jpg',
            'content' => json_encode([
                'html' => '<section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-24"><div class="container mx-auto px-4 text-center max-w-4xl"><h1 class="text-6xl font-bold mb-6">Transform Your Business</h1><p class="text-2xl mb-8 text-purple-100">Join thousands of satisfied customers worldwide</p><div class="flex justify-center gap-4"><a href="#" class="bg-white text-purple-600 px-10 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition">Start Free Trial</a><a href="#" class="border-2 border-white text-white px-10 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-purple-600 transition">Watch Demo</a></div></div></section><div class="container mx-auto px-4 py-16"><div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16"><div><img src="https://via.placeholder.com/600x400" alt="Product" class="rounded-lg shadow-xl"></div><div><h2 class="text-3xl font-bold mb-4">Increase Productivity</h2><p class="text-gray-600 mb-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p><ul class="space-y-3"><li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-3"></i><span>Feature number one</span></li><li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-3"></i><span>Feature number two</span></li><li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-3"></i><span>Feature number three</span></li></ul></div></div></div><section class="bg-purple-600 text-white py-20"><div class="container mx-auto px-4 text-center"><h2 class="text-4xl font-bold mb-6">Ready to Get Started?</h2><p class="text-xl mb-8 text-purple-100">Join us today and see the difference</p><a href="#" class="inline-block bg-white text-purple-600 px-10 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition">Start Your Free Trial</a></div></section>',
                'css' => '',
            ]),
            'created_by' => $creator->id ?? null,
            'is_active' => true,
        ]);

        // Template 7: Services Page
        PageBuilderTemplate::create([
            'name' => 'Services - Cards Layout',
            'description' => 'Services page with card-based layout',
            'thumbnail' => '/images/templates/services.jpg',
            'content' => json_encode([
                'html' => '<div class="bg-gradient-to-br from-purple-50 to-indigo-50 py-16"><div class="container mx-auto px-4"><h1 class="text-4xl font-bold text-center mb-4">Our Services</h1><p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">Comprehensive solutions for your business needs</p><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"><div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition"><img src="https://via.placeholder.com/400x200" alt="Service" class="w-full h-48 object-cover"><div class="p-6"><h3 class="text-xl font-bold mb-3">Web Development</h3><p class="text-gray-600 mb-4">Custom web applications built with modern technologies</p><a href="#" class="text-purple-600 hover:text-purple-700 font-semibold">Learn More →</a></div></div><div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition"><img src="https://via.placeholder.com/400x200" alt="Service" class="w-full h-48 object-cover"><div class="p-6"><h3 class="text-xl font-bold mb-3">Design Services</h3><p class="text-gray-600 mb-4">Beautiful, user-friendly interfaces that convert</p><a href="#" class="text-purple-600 hover:text-purple-700 font-semibold">Learn More →</a></div></div><div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition"><img src="https://via.placeholder.com/400x200" alt="Service" class="w-full h-48 object-cover"><div class="p-6"><h3 class="text-xl font-bold mb-3">Consulting</h3><p class="text-gray-600 mb-4">Expert advice to grow your online presence</p><a href="#" class="text-purple-600 hover:text-purple-700 font-semibold">Learn More →</a></div></div></div></div></div>',
                'css' => '',
            ]),
            'created_by' => $creator->id ?? null,
            'is_active' => true,
        ]);

        // Template 8: FAQ Page
        PageBuilderTemplate::create([
            'name' => 'FAQ - Accordion Style',
            'description' => 'FAQ page with expandable questions',
            'thumbnail' => '/images/templates/faq.jpg',
            'content' => json_encode([
                'html' => '<div class="container mx-auto px-4 py-16"><div class="max-w-3xl mx-auto"><h1 class="text-4xl font-bold text-center mb-4">Frequently Asked Questions</h1><p class="text-center text-gray-600 mb-12">Find answers to common questions</p><div class="space-y-4"><div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition"><h3 class="text-lg font-bold mb-2 flex items-center"><i class="fas fa-question-circle text-purple-600 mr-3"></i>What is VeCarCMS?</h3><p class="text-gray-600 ml-9">VeCarCMS is a modern content management system built with Laravel, combining the best features of WordPress with the power and flexibility of Laravel.</p></div><div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition"><h3 class="text-lg font-bold mb-2 flex items-center"><i class="fas fa-question-circle text-purple-600 mr-3"></i>How is it different from WordPress?</h3><p class="text-gray-600 ml-9">While we provide WordPress-like features (Page Builder, Widgets, Menus), VeCarCMS is built on Laravel, offering better performance, security, and modern PHP practices.</p></div><div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition"><h3 class="text-lg font-bold mb-2 flex items-center"><i class="fas fa-question-circle text-purple-600 mr-3"></i>Is it suitable for e-commerce?</h3><p class="text-gray-600 ml-9">Yes! VeCarCMS includes e-commerce capabilities with product management, shopping cart, and payment integration.</p></div><div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition"><h3 class="text-lg font-bold mb-2 flex items-center"><i class="fas fa-question-circle text-purple-600 mr-3"></i>Can I customize the design?</h3><p class="text-gray-600 ml-9">Absolutely! Use our Theme Customizer for live preview changes, or create custom themes with full control over HTML, CSS, and JavaScript.</p></div></div></div></div>',
                'css' => '',
            ]),
            'created_by' => $creator->id ?? null,
            'is_active' => true,
        ]);

        // Template 9: Testimonials Page
        PageBuilderTemplate::create([
            'name' => 'Testimonials - Customer Reviews',
            'description' => 'Testimonials page with customer reviews and ratings',
            'thumbnail' => '/images/templates/testimonials.jpg',
            'content' => json_encode([
                'html' => '<div class="bg-gradient-to-br from-purple-50 to-indigo-50 py-16"><div class="container mx-auto px-4"><h1 class="text-4xl font-bold text-center mb-4">What Our Customers Say</h1><p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">Real feedback from real customers</p><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"><div class="bg-white p-8 rounded-lg shadow-lg"><div class="flex items-center mb-4"><img src="https://via.placeholder.com/60" alt="Customer" class="w-12 h-12 rounded-full mr-4"><div><h4 class="font-bold">John Doe</h4><p class="text-sm text-gray-600">CEO, Company Inc.</p></div></div><div class="mb-4"><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i></div><p class="text-gray-700 italic">"VeCarCMS has transformed how we manage our content. The page builder is incredibly intuitive!"</p></div><div class="bg-white p-8 rounded-lg shadow-lg"><div class="flex items-center mb-4"><img src="https://via.placeholder.com/60" alt="Customer" class="w-12 h-12 rounded-full mr-4"><div><h4 class="font-bold">Jane Smith</h4><p class="text-sm text-gray-600">Marketing Director</p></div></div><div class="mb-4"><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i></div><p class="text-gray-700 italic">"The best CMS we\'ve used. Combines WordPress ease with Laravel power."</p></div><div class="bg-white p-8 rounded-lg shadow-lg"><div class="flex items-center mb-4"><img src="https://via.placeholder.com/60" alt="Customer" class="w-12 h-12 rounded-full mr-4"><div><h4 class="font-bold">Mike Johnson</h4><p class="text-sm text-gray-600">Freelance Developer</p></div></div><div class="mb-4"><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i></div><p class="text-gray-700 italic">"Perfect for client projects. They love the intuitive admin panel!"</p></div></div></div></div>',
                'css' => '',
            ]),
            'created_by' => $creator->id ?? null,
            'is_active' => true,
        ]);

        $this->command->info('✅ Page Builder templates created successfully!');
    }
}

