<template>
    <div id="page-builder-wrapper" class="page-builder-container">
        <!-- GrapesJS Editor with Blocks Panel -->
        <div class="page-builder-layout">
            <!-- Left Sidebar - Blocks Panel -->
            <div class="blocks-panel">
                <div class="blocks-header">
                    <h3>Blocks</h3>
                </div>
                <div id="blocks-container" class="blocks-container"></div>
            </div>
            
            <!-- Main Editor -->
            <div class="editor-wrapper">
                <div id="gjs-editor"></div>
            </div>
            
            <!-- Right Sidebar - Settings -->
            <div class="settings-panel">
                <div class="panel-tabs">
                    <button class="panel-tab active" data-panel="styles">
                        <i class="fas fa-paint-brush"></i>
                        <span>Styles</span>
                    </button>
                    <button class="panel-tab" data-panel="traits">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </button>
                    <button class="panel-tab" data-panel="layers">
                        <i class="fas fa-layer-group"></i>
                        <span>Layers</span>
                    </button>
                </div>
                <div id="styles-container" class="panel-content"></div>
                <div id="traits-container" class="panel-content" style="display:none;"></div>
                <div id="layers-container" class="panel-content" style="display:none;"></div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import grapesjs from 'grapesjs';
import 'grapesjs/dist/css/grapes.min.css';
import grapesjsPresetWebpage from 'grapesjs-preset-webpage';
import grapesjsBlocksBasic from 'grapesjs-blocks-basic';
import grapesjsPluginForms from 'grapesjs-plugin-forms';
import { pageBuilderConfig, customBlocks, initCustomComponents } from '../pagebuilder-config.js';
import { bootstrapBlocks } from '../pagebuilder-blocks-bootstrap.js';

export default {
    name: 'PageBuilder',
    props: {
        initialContent: {
            type: [String, Object, Array],
            default: null
        },
        contentInputName: {
            type: String,
            default: 'page_builder_content'
        },
        contentInputId: {
            type: String,
            default: 'page_builder_content'
        },
        mediaUploadUrl: {
            type: String,
            default: '/admin/media/upload'
        },
        mediaLibraryUrl: {
            type: String,
            default: '/admin/media'
        },
        onSave: {
            type: Function,
            default: (json) => console.log('Save:', json)
        },
        onClose: {
            type: Function,
            default: () => console.log('Close')
        }
    },
    setup(props) {
        const editor = ref(null);
        const jsonContent = ref('');

        const saveContent = () => {
            if (!editor.value) return;
            
            const projectData = editor.value.getProjectData();
            const json = JSON.stringify(projectData);
            
            jsonContent.value = json;
            props.onSave(json);
        };

        onMounted(() => {
            console.log('🚀 PageBuilder component mounted!');
            console.log('Props received:', {
                initialContent: props.initialContent,
                contentType: typeof props.initialContent
            });
            
            initializeEditor();
            
            // Ascolta l'evento save-page-builder
            window.addEventListener('save-page-builder', saveContent);
        });
        
        onUnmounted(() => {
            if (editor.value) {
                editor.value.destroy();
            }
            window.removeEventListener('save-page-builder', saveContent);
        });

        const initializeEditor = () => {
            console.log('🔧 Initializing GrapesJS editor...');
            
            // Distruggi editor esistente se presente
            if (editor.value) {
                editor.value.destroy();
            }
            // Inizializza GrapesJS in modalità full-page
            editor.value = grapesjs.init({
                container: '#gjs-editor',
                height: '100%',
                width: '100%',
                fromElement: false,
                storageManager: {
                    type: 'local',
                    autosave: false,
                    autoload: false,
                },
                plugins: [grapesjsPresetWebpage],
                pluginsOpts: {
                    'gjs-preset-webpage': {
                        modalImportTitle: 'Import Template',
                        modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
                        filestackOpts: null,
                        blocks: ['container', 'text', 'text-sect', 'image', 'video', 'map', 'link', 'row', 'column', 'link-block'],
                        navbarOpts: false,
                        formOpts: false,
                        modalImportTitle: 'Import',
                        modalImportLabel: 'Import',
                        importViewer: 1,
                        text: ['Heading', 'Paragraph', 'Link'],
                        link: ['Link', 'Button', 'Input'],
                        image: ['Image'],
                        video: ['Video'],
                    }
                },
                canvas: {
                    styles: [
                        // Bootstrap 5 and Sigma theme styles
                        '/content/themes/dwntheme/assets/css/libs.bundle.css',
                        '/content/themes/dwntheme/assets/css/theme.bundle.css',
                    ],
                    scripts: [
                        '/content/themes/dwntheme/assets/js/vendor.bundle.js',
                        '/content/themes/dwntheme/assets/js/theme.bundle.js'
                    ]
                },
                blockManager: {
                    appendTo: '#blocks-container',
                },
                layerManager: {
                    appendTo: '#layers-container',
                },
                styleManager: {
                    appendTo: '#styles-container',
                    sectors: [
                        {
                            name: 'Dimension',
                            open: false,
                            buildProps: ['width', 'min-height', 'padding'],
                            properties: [
                                {
                                    id: 'width',
                                    type: 'integer',
                                    name: 'Width',
                                    units: ['px', '%'],
                                    property: 'width',
                                    defaults: 'auto',
                                    min: 0,
                                },
                                {
                                    id: 'flex-width',
                                    type: 'integer',
                                    name: 'Flex Width',
                                    units: ['px', '%'],
                                    property: 'flex-basis',
                                    defaults: 'auto',
                                    min: 0,
                                },
                                {
                                    id: 'min-height',
                                    type: 'integer',
                                    name: 'Min Height',
                                    units: ['px', '%', 'vh'],
                                    property: 'min-height',
                                    defaults: 0,
                                    min: 0,
                                },
                                {
                                    id: 'padding',
                                    type: 'integer',
                                    name: 'Padding',
                                    units: ['px', 'em', '%'],
                                    property: 'padding',
                                    defaults: '0',
                                    min: 0,
                                },
                            ]
                        },
                        {
                            name: 'Typography',
                            open: false,
                            buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align', 'text-decoration', 'text-shadow'],
                        },
                        {
                            name: 'Decorations',
                            open: false,
                            buildProps: ['opacity', 'border-radius', 'border', 'box-shadow', 'background'],
                        },
                        {
                            name: 'Extra',
                            open: false,
                            buildProps: ['transition', 'perspective', 'transform'],
                        },
                    ]
                },
                traitManager: {
                    appendTo: '#traits-container',
                },
            });

            // Setup panel tabs functionality
            setupPanelTabs();

            // Inizializza custom components e blocks
            initCustomComponents(editor.value);
            customBlocks(editor.value);
            bootstrapBlocks(editor.value); // Bootstrap 5 blocks

            // Add header and footer to canvas (Elementor-style)
            addHeaderFooterToCanvas(editor.value);

            // Carica contenuto iniziale se presente
            if (props.initialContent && props.initialContent !== '') {
                try {
                    console.log('Loading initial content:', props.initialContent);
                    const content = typeof props.initialContent === 'string' 
                        ? JSON.parse(props.initialContent) 
                        : props.initialContent;
                    console.log('Parsed content:', content);
                    console.log('Content keys:', Object.keys(content));
                    editor.value.loadProjectData(content);
                    console.log('✅ Content loaded successfully!');
                    
                    // Verify components loaded
                    setTimeout(() => {
                        const wrapper = editor.value.getWrapper();
                        const components = wrapper.components();
                        console.log('Components in canvas:', components.length);
                        console.log('Canvas HTML length:', editor.value.getHtml().length);
                        
                        if (components.length === 0) {
                            console.error('❌ No components rendered in canvas!');
                        } else {
                            console.log('✅ Canvas has', components.length, 'top-level components');
                        }
                    }, 1000);
                } catch (e) {
                    console.error('❌ Error loading initial content:', e);
                    console.error('Content was:', props.initialContent);
                }
            } else {
                console.log('⚠️ No initial content provided');
            }

            // Aggiungi comando per Media Library
            editor.value.Commands.add('open-media-library', {
                run: (editor, sender) => {
                    openMediaLibrary(editor);
                }
            });

            // Aggiungi pulsante Media Library nella toolbar
            editor.value.Panels.addButton('options', {
                id: 'open-media',
                className: 'fa fa-image',
                command: 'open-media-library',
                attributes: { title: 'Media Library' }
            });
        };


        const addHeaderFooterToCanvas = (editorInstance) => {
            // Get wrapper (body)
            const wrapper = editorInstance.getWrapper();
            
            // Add fixed header
            const header = wrapper.append({
                tagName: 'header',
                attributes: { 
                    class: 'page-builder-header',
                    'data-gjs-removable': 'false',
                    'data-gjs-draggable': 'false',
                    'data-gjs-editable': 'false',
                    'data-gjs-selectable': 'false',
                    'data-gjs-highlightable': 'false'
                },
                components: `
                    <div class="bg-white shadow-sm sticky top-0 z-50">
                        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="flex items-center justify-between h-16">
                                <div class="flex-shrink-0">
                                    <a href="/" class="flex items-center space-x-2">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xl font-bold text-gray-900">VeCarCMS</span>
                                    </a>
                                </div>
                                <nav class="hidden md:flex items-center space-x-8">
                                    <a href="/" class="text-gray-700 hover:text-purple-600 font-medium transition">Home</a>
                                    <a href="/blog" class="text-gray-700 hover:text-purple-600 font-medium transition">Blog</a>
                                    <a href="/about" class="text-gray-700 hover:text-purple-600 font-medium transition">About</a>
                                    <a href="/contact" class="text-gray-700 hover:text-purple-600 font-medium transition">Contact</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                `
            })[0];

            // Make header non-editable but visible
            header.set({
                locked: true,
                selectable: false,
                hoverable: false,
                editable: false,
                draggable: false,
                droppable: false,
                removable: false,
                copyable: false,
                resizable: false,
            });

            // Add editable content area
            const contentArea = wrapper.append({
                tagName: 'main',
                attributes: { 
                    class: 'page-builder-content-area',
                    'data-gjs-droppable': 'true'
                },
                components: `
                    <div class="min-h-screen py-12">
                        <div class="container mx-auto px-4">
                            <h2 class="text-3xl font-bold text-center mb-8">Start Building Your Content</h2>
                            <p class="text-center text-gray-600">Drag blocks from the left sidebar to build your page</p>
                        </div>
                    </div>
                `
            })[0];

            // Add fixed footer
            const footer = wrapper.append({
                tagName: 'footer',
                attributes: { 
                    class: 'page-builder-footer',
                    'data-gjs-removable': 'false',
                    'data-gjs-draggable': 'false',
                    'data-gjs-editable': 'false',
                    'data-gjs-selectable': 'false',
                    'data-gjs-highlightable': 'false'
                },
                components: `
                    <div class="bg-gray-900 text-white py-12">
                        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div>
                                    <h3 class="text-lg font-semibold mb-4">VeCarCMS</h3>
                                    <p class="text-gray-400">A powerful WordPress-like CMS built with Laravel</p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                                    <ul class="space-y-2">
                                        <li><a href="/about" class="text-gray-400 hover:text-white">About</a></li>
                                        <li><a href="/blog" class="text-gray-400 hover:text-white">Blog</a></li>
                                        <li><a href="/contact" class="text-gray-400 hover:text-white">Contact</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold mb-4">Legal</h3>
                                    <ul class="space-y-2">
                                        <li><a href="/privacy" class="text-gray-400 hover:text-white">Privacy</a></li>
                                        <li><a href="/terms" class="text-gray-400 hover:text-white">Terms</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400">
                                <p>&copy; 2025 VeCarCMS. All rights reserved.</p>
                            </div>
                        </div>
                    </div>
                `
            })[0];

            // Make footer non-editable but visible
            footer.set({
                locked: true,
                selectable: false,
                hoverable: false,
                editable: false,
                draggable: false,
                droppable: false,
                removable: false,
                copyable: false,
                resizable: false,
            });
        };

        const setupPanelTabs = () => {
            const tabs = document.querySelectorAll('.panel-tab');
            const contents = document.querySelectorAll('.panel-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked tab
                    tab.classList.add('active');

                    // Hide all content panels
                    contents.forEach(c => c.style.display = 'none');

                    // Show corresponding content
                    const panelName = tab.dataset.panel;
                    const targetPanel = document.getElementById(`${panelName}-container`);
                    if (targetPanel) {
                        targetPanel.style.display = 'block';
                    }
                });
            });
        };

        const openMediaLibrary = (editorInstance) => {
            // Trigger evento globale per aprire Media Library
            window.dispatchEvent(new CustomEvent('open-media-modal', {
                detail: {
                    mode: 'single',
                    callback: (media) => {
                        // Inserisci media nel Page Builder
                        const component = editorInstance.addComponent({
                            type: 'image',
                            src: media.url || media.path,
                            alt: media.name || '',
                            style: {
                                width: '100%',
                                height: 'auto',
                            }
                        });
                        editorInstance.select(component);
                    }
                }
            }));
        };

        return {
            editor,
            jsonContent,
        };
    }
};
</script>

<style scoped>
.page-builder-container {
    width: 100%;
    height: 100vh;
    background: #f5f5f5;
    overflow: hidden;
}

.page-builder-layout {
    display: flex;
    height: 100%;
    width: 100%;
}

/* Left Sidebar - Blocks */
.blocks-panel {
    width: 280px;
    background: #fff;
    border-right: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.blocks-header {
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
    background: #fafafa;
}

.blocks-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.blocks-container {
    padding: 15px;
    flex: 1;
    overflow-y: auto;
}

/* Main Editor */
.editor-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

#gjs-editor {
    flex: 1;
    width: 100%;
}

/* Right Sidebar - Settings */
.settings-panel {
    width: 320px;
    background: #fff;
    border-left: 1px solid #ddd;
    display: flex;
    flex-direction: column;
}

.panel-tabs {
    display: flex;
    border-bottom: 1px solid #ddd;
    background: #fafafa;
}

.panel-tab {
    flex: 1;
    padding: 12px 10px;
    border: none;
    background: transparent;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: #666;
    transition: all 0.3s;
}

.panel-tab:hover {
    background: #f0f0f0;
    color: #333;
}

.panel-tab.active {
    background: #fff;
    color: #7c3aed;
    border-bottom: 2px solid #7c3aed;
}

.panel-tab i {
    font-size: 18px;
}

.panel-content {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
}

/* GrapesJS Custom Styles */
:deep(.gjs-one-bg) {
    background-color: #fff;
}

:deep(.gjs-two-color) {
    color: #7c3aed;
}

:deep(.gjs-block) {
    width: 100%;
    min-height: 80px;
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    background: #fafafa;
}

:deep(.gjs-block:hover) {
    border-color: #7c3aed;
    box-shadow: 0 2px 8px rgba(124, 58, 237, 0.2);
}

:deep(.gjs-block__media) {
    font-size: 28px;
    margin-bottom: 8px;
    color: #7c3aed;
}

:deep(.gjs-block-label) {
    font-size: 13px;
    font-weight: 500;
    color: #333;
}

:deep(.gjs-category-title) {
    padding: 10px 15px;
    font-weight: 600;
    color: #333;
    background: #f0f0f0;
    margin: 10px -15px;
    cursor: pointer;
}

/* Header and Footer in Canvas - Non selectable */
:deep(.page-builder-header),
:deep(.page-builder-footer) {
    opacity: 0.7;
    pointer-events: none;
    position: relative;
}

:deep(.page-builder-header::after),
:deep(.page-builder-footer::after) {
    content: 'Fixed Header - Preview Only';
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(124, 58, 237, 0.9);
    color: white;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    z-index: 1000;
}

:deep(.page-builder-footer::after) {
    content: 'Fixed Footer - Preview Only';
    top: auto;
    bottom: 10px;
}

/* Content Area - Editable */
:deep(.page-builder-content-area) {
    background: #fff;
    min-height: 60vh;
}
</style>

