// Configuration - Update this with your server URL
const API_BASE_URL = 'http://localhost:8000';

// DOM elements
const titleInput = document.getElementById('title');
const descriptionInput = document.getElementById('description');
const categorySelect = document.getElementById('category');
const saveLinkBtn = document.getElementById('saveLink');
const viewLinksBtn = document.getElementById('viewLinks');
const statusDiv = document.getElementById('status');

// Initialize popup
document.addEventListener('DOMContentLoaded', async () => {
    await loadCategories();
    await loadCurrentPageData();
    
    saveLinkBtn.addEventListener('click', saveLink);
    viewLinksBtn.addEventListener('click', openLinksPage);
});

// Load categories from API
async function loadCategories() {
    try {
        const response = await fetch(`${API_BASE_URL}/api/categories`);
        const categories = await response.json();
        
        categorySelect.innerHTML = '<option value="">Select a category</option>';
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading categories:', error);
        showStatus('Error loading categories', 'error');
    }
}

// Load current page data
async function loadCurrentPageData() {
    try {
        const [tab] = await chrome.tabs.query({ active: true, currentWindow: true });
        
        // Get page title and URL
        titleInput.value = tab.title || '';
        
        // Try to get page description from meta tags
        try {
            const results = await chrome.scripting.executeScript({
                target: { tabId: tab.id },
                function: () => {
                    const metaDescription = document.querySelector('meta[name="description"]');
                    return metaDescription ? metaDescription.content : '';
                }
            });
            
            if (results[0]?.result) {
                descriptionInput.value = results[0].result;
            }
        } catch (error) {
            console.log('Could not access page content:', error);
        }
    } catch (error) {
        console.error('Error loading page data:', error);
    }
}

// Save link to server
async function saveLink() {
    if (!titleInput.value.trim()) {
        showStatus('Please enter a title', 'error');
        return;
    }
    
    try {
        const [tab] = await chrome.tabs.query({ active: true, currentWindow: true });
        
        // Get favicon URL
        const faviconUrl = `https://www.google.com/s2/favicons?domain=${new URL(tab.url).hostname}`;
        
        const linkData = {
            title: titleInput.value.trim(),
            url: tab.url,
            description: descriptionInput.value.trim() || null,
            favicon: faviconUrl,
            is_active: true,
            categories: categorySelect.value ? [parseInt(categorySelect.value)] : []
        };
        
        showStatus('Saving link...', 'loading');
        
        const response = await fetch(`${API_BASE_URL}/api/links`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(linkData)
        });
        
        if (response.ok) {
            showStatus('Link saved successfully!', 'success');
            // Clear form
            titleInput.value = '';
            descriptionInput.value = '';
            categorySelect.value = '';
        } else {
            const errorData = await response.json();
            showStatus(`Error: ${errorData.message || 'Failed to save link'}`, 'error');
        }
    } catch (error) {
        console.error('Error saving link:', error);
        showStatus('Error saving link. Check your connection.', 'error');
    }
}

// Open links management page
function openLinksPage() {
    chrome.tabs.create({ url: `${API_BASE_URL}/links` });
}

// Show status message
function showStatus(message, type) {
    statusDiv.textContent = message;
    statusDiv.className = `status ${type}`;
    statusDiv.style.display = 'block';
    
    if (type === 'success') {
        setTimeout(() => {
            statusDiv.style.display = 'none';
        }, 3000);
    }
}
