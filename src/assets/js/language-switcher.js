/**
 * Gestionnaire de changement de langue
 */
document.addEventListener('DOMContentLoaded', function() {
    // Langues disponibles
    const languages = {
        'fr': 'Français',
        'en': 'English',
        'es': 'Español'
    };
    
    // Langue par défaut
    const defaultLanguage = 'fr';
    
    // Récupérer la langue enregistrée ou utiliser la langue par défaut
    const currentLanguage = localStorage.getItem('language') || defaultLanguage;
    
    // Initialiser le sélecteur de langue
    const languageSelector = document.getElementById('language-selector');
    if (languageSelector) {
        // Créer les options pour chaque langue
        for (const [code, name] of Object.entries(languages)) {
            const option = document.createElement('option');
            option.value = code;
            option.textContent = name;
            option.selected = code === currentLanguage;
            languageSelector.appendChild(option);
        }
        
        // Ajouter un écouteur d'événements pour le changement de langue
        languageSelector.addEventListener('change', function() {
            changeLanguage(this.value);
        });
    }
    
    // Charger les traductions au démarrage
    loadTranslations(currentLanguage);
    
    // Fonction pour changer la langue
    window.changeLanguage = function(lang) {
        localStorage.setItem('language', lang);
        loadTranslations(lang);
    };
    
    // Fonction pour charger les traductions
    function loadTranslations(lang) {
        fetch(`/assets/translations/${lang}.json`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(translations => {
                applyTranslations(translations);
            })
            .catch(error => {
                console.error('Erreur lors du chargement des traductions:', error);
            });
    }
    
    // Fonction pour appliquer les traductions
    function applyTranslations(translations) {
        // Sélectionner tous les éléments avec l'attribut data-i18n
        document.querySelectorAll('[data-i18n]').forEach(element => {
            const key = element.getAttribute('data-i18n');
            
            // Si la clé existe dans les traductions
            if (translations[key]) {
                // Si l'élément est un input avec un placeholder
                if (element.hasAttribute('placeholder')) {
                    element.setAttribute('placeholder', translations[key]);
                } 
                // Si l'élément est un bouton ou input avec une valeur
                else if (element.hasAttribute('value') && (element.tagName === 'BUTTON' || element.tagName === 'INPUT')) {
                    element.value = translations[key];
                } 
                // Pour tous les autres éléments, mettre à jour le contenu HTML
                else {
                    element.innerHTML = translations[key];
                }
            }
        });
        
        // Mettre à jour l'attribut lang de la page
        document.documentElement.lang = currentLanguage;
        
        // Déclencher un événement personnalisé pour que d'autres scripts puissent réagir
        document.dispatchEvent(new CustomEvent('languageChanged', { detail: { language: currentLanguage } }));
    }
}); 