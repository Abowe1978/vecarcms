import { createApp } from 'vue';
import PageBuilder from './components/PageBuilder.vue';

console.log('📄 pagebuilder.js loaded!');

// Funzione per inizializzare il Page Builder
window.initPageBuilder = function(options = {}) {
    console.log('🎬 initPageBuilder called with options:', options);
    const defaultOptions = {
        initialContent: null,
        contentInputName: 'page_builder_content',
        contentInputId: 'page_builder_content',
        mediaUploadUrl: '/admin/media/upload',
        mediaLibraryUrl: '/admin/media',
        onSave: (json) => { console.log('Page Builder saved:', json); },
        onClose: () => { console.log('Page Builder closed'); }
    };

    const config = { ...defaultOptions, ...options };

    // Cerca il container
    const container = document.getElementById('page-builder-container');
    if (!container) {
        console.error('Page Builder container not found!');
        return;
    }

    // Rimuovi istanza Vue esistente se presente
    if (container.__vue_app__) {
        container.__vue_app__.unmount();
    }

    // Crea nuova app Vue
    const app = createApp(PageBuilder, config);
    app.mount(container);
    
    // Salva riferimento all'app
    container.__vue_app__ = app;

    return app;
};

