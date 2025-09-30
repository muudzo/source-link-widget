// Content script for the Source Link Widget
// This script runs on every page and can interact with the page content

// Listen for messages from the popup
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.action === 'getPageData') {
        const pageData = {
            title: document.title,
            url: window.location.href,
            description: getMetaDescription(),
            favicon: getFaviconUrl()
        };
        sendResponse(pageData);
    }
});

// Get meta description
function getMetaDescription() {
    const metaDescription = document.querySelector('meta[name="description"]');
    return metaDescription ? metaDescription.content : '';
}

// Get favicon URL
function getFaviconUrl() {
    const favicon = document.querySelector('link[rel="icon"], link[rel="shortcut icon"]');
    if (favicon) {
        return favicon.href;
    }
    
    // Fallback to default favicon
    return `${window.location.origin}/favicon.ico`;
}

// Add a small floating button to the page (optional)
function addFloatingButton() {
    // Only add if not already present
    if (document.getElementById('source-link-widget-btn')) return;
    
    const button = document.createElement('div');
    button.id = 'source-link-widget-btn';
    button.innerHTML = '📌';
    button.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 16px;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    `;
    
    button.addEventListener('click', () => {
        chrome.runtime.sendMessage({ action: 'openPopup' });
    });
    
    button.addEventListener('mouseenter', () => {
        button.style.transform = 'scale(1.1)';
    });
    
    button.addEventListener('mouseleave', () => {
        button.style.transform = 'scale(1)';
    });
    
    document.body.appendChild(button);
}

// Initialize floating button (optional - you can remove this if you don't want it)
// addFloatingButton();
